<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Computer extends Model
{
    use HasFactory;

    const CACHE_KEY_TOP_TEN = 'top_ten';
    const CACHE_KEY_OS = 'operating_sys';
    const CACHE_KEY_LOCATION = 'location';
    const CACHE_TTL = 86400;

    const SEARCH_EXACT = ['computer_type', 'department'];

    // Keep order the same as in TableComponent.vue
    const SELECT_COLUMNS = array(
        'name',
        'migration_status',
        'user_name',
        'location',
        'computer_type',
        'computer_model',
        'operating_system',
        'windows_10_version',
        'memory_gb',
        'disk_size_gb',
        'free_space_gb',
        'serial',
        'business_unit',
        'department',
        'replacement_ordered',
        'static_ip',
        'state',
        'central_build_site',
        'last_logon_user',
        'vetted'
    );

    const SQL_SELECT_DISTINCT_DEPARTMENT = <<<SQL
SELECT DISTINCT department from computers;
SQL;

    const SQL_SELECT_DISTINCT_TYPE = <<<SQL
SELECT DISTINCT computer_type from computers;
SQL;

    const SQL_SEARCH_LIKE_COMPUTER_MODEL = <<<SQL
WHERE :search_column LIKE :search_term
SQL;

    const SQL_SEARCH_EXACT_COMPUTER_MODEL = <<<SQL
 WHERE :search_column = :search_term
SQL;

    const SQL_TOP_TEN_COMPUTERS = <<<SQL
SELECT
computer_model,
total
FROM (SELECT computer_model,
COUNT(*) total,
rank() over(ORDER BY COUNT(*) DESC) as rnk
FROM computers
GROUP BY computer_model
ORDER BY total DESC) AS total_models
WHERE rnk <= 10
SQL;

    const SQL_COMPUTER_BY_OPERATING_SYSTEM = <<<SQL
SELECT operating_system,COUNT(*) total
FROM computers
GROUP BY operating_system
ORDER BY total DESC;
SQL;

    const SQL_COMPUTER_BY_LOCATION = <<<SQL
SELECT location,COUNT(*) total
FROM computers
GROUP BY location
ORDER BY total DESC;
SQL;

    const SQL_TABLE = <<<SQL
SELECT `name`,`migration_status`,`user_name`,`location`,`computer_type`,`computer_model`,
       `operating_system`,`windows_10_version`,`memory_gb`,`disk_size_gb`,`free_space_gb`,
       `serial`,`business_unit`,`department`,`replacement_ordered`,`static_ip`,`state`,
       `central_build_site`,`last_logon_user`,`vetted`, COUNT(*) over() total_count
FROM computers
%s
%s
;
SQL;

    const SQL_LIMIT_WITH_OFFSET = <<<SQL
LIMIT :offset,:limit
SQL;

    const SQL_TABLE_SEARCH = <<<SQL
WHERE
`name` LIKE %s OR
`migration_status` LIKE %s OR
`user_name` LIKE %s OR
`location` LIKE %s OR
`computer_type` LIKE %s OR
`computer_model` LIKE %s OR
`operating_system` LIKE %s OR
`windows_10_version` LIKE %s OR
`memory_gb` LIKE %s OR
`disk_size_gb` LIKE %s OR
`free_space_gb` LIKE %s OR
`serial` LIKE %s OR
`business_unit` LIKE %s OR
`department` LIKE %s OR
`replacement_ordered` LIKE %s OR
`static_ip` LIKE %s OR
`state` LIKE %s OR
`central_build_site` LIKE %s OR
`last_logon_user` LIKE %s OR
`vetted` LIKE %s
SQL;

    const SQL_TABLE_COUNT = <<<SQL
SELECT count(*) AS total FROM computers;
SQL;

    /**
     * @return array|mixed
     */
    public function getTopTenComputerModels() {
        $data = $this->getRecord(
            self::SQL_TOP_TEN_COMPUTERS,
            self::CACHE_KEY_TOP_TEN
        );

        return $this->formatData($data, 'computer_model');
    }

    /**
     * @return array|mixed
     */
    public function getByOperatingSystem() {
        $data = $this->getRecord(
            self::SQL_COMPUTER_BY_OPERATING_SYSTEM,
            self::CACHE_KEY_OS
        );

        return $this->formatData($data, 'operating_system');
    }

    /**
     * @return array|array[]
     */
    public function getByLocation():array {
        $data = $this->getRecord(
            self::SQL_COMPUTER_BY_LOCATION,
            self::CACHE_KEY_LOCATION
        );

        return $this->formatData($data, 'location');
    }

    /**
     * @return int
     */
    public function getCount():int {
        $data = $this->getRecord(self::SQL_TABLE_COUNT);
        return (int) $data[0]->total;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getTableData(array $params):array {
        $result = [];
        $setTotal = false;
        if (isset($params['search'])) {
            $bindings = $this->buildBindings($params['search']);
            $whereQuery = $this->addNamedBindsToQuery($bindings);
            // unset we wont use these names for bindings
            unset($params['search']);
            $params = array_merge($bindings, $params);
            $sql = sprintf(self::SQL_TABLE, $whereQuery, self::SQL_LIMIT_WITH_OFFSET);
        }
        elseif (isset($params['search_column'])) {
            $columnName = $params['search_column'];
            if (in_array($columnName, self::SEARCH_EXACT)) {
                $sql = sprintf(self::SQL_TABLE, self::SQL_SEARCH_EXACT_COMPUTER_MODEL, self::SQL_LIMIT_WITH_OFFSET);
            }
            else {
                $sql = sprintf(self::SQL_TABLE, self::SQL_SEARCH_LIKE_COMPUTER_MODEL, self::SQL_LIMIT_WITH_OFFSET);
                $params['search_term'] = '%' . $params['search_term'] . '%';
            }
        }
        else {
            $sql = sprintf(self::SQL_TABLE,'',self::SQL_LIMIT_WITH_OFFSET);
        }
        $cacheKey = $this->buildCacheKey($params);
        $data = $this->getRecord($sql, $cacheKey, $params);
        foreach ($data as $datum) {
            if (!$setTotal) {
                $result['total'] = $datum->total_count;
                $setTotal = true;
            }
            $result['data'][] = array(
                $datum->name,
                $datum->migration_status,
                $datum->user_name,
                $datum->location,
                $datum->computer_type,
                $datum->computer_model,
                $datum->operating_system,
                $datum->windows_10_version,
                $datum->memory_gb,
                $datum->disk_size_gb,
                $datum->free_space_gb,
                $datum->serial,
                $datum->business_unit,
                $datum->department,
                $datum->replacement_ordered,
                $datum->static_ip,
                $datum->state,
                $datum->central_build_site,
                $datum->last_logon_user,
                $datum->vetted
            );
        }
        return $result;
    }

    /**
     * @param string $query
     * @param string|null $cacheKey
     *
     * @return array|mixed
     */
    private function getRecord(string $query, $cacheKey = null, $params = []) {
        if ($cacheKey) {
            $record = Cache::get($cacheKey);
            if ($record) {
                return $record;
            }
            $result = DB::select($query, $params);
            Cache::put($cacheKey, $result, self::CACHE_TTL);
            return $result;
        }
        return DB::select($query, $params);
    }

    /**
     * @param $data
     * @return array|array[]
     */
    private function formatData($data, $colName):array {
        $result = ['labels' => [], 'totals' => []];
        foreach ($data as $datum) {
            $total = $datum->total;
            $result['labels'][] = $datum->$colName . " (${total})";
            $result['totals'][] = $total;
        }
        return $result;
    }

    /**
     * @param string $searchTerm
     * @return array
     */
    private function buildBindings(string $searchTerm): array {
        $bindings = [];
        $total = count(self::SELECT_COLUMNS);
        for ($i = 0; $i < $total; $i++) {
            $bindings["search${i}"] = "%${searchTerm}%";
        }
        return $bindings;
    }

    /**
     * @param array $bindings
     * @return string
     */
    private function addNamedBindsToQuery(array $bindings): string {
        $namedBindings = [];
        $keys = array_keys($bindings);
        foreach ($keys as $key) {
            // this form of interpolation require if : is prefixed
            $namedBindings[] = ":$key";
        }
        return sprintf(self::SQL_TABLE_SEARCH, ...$namedBindings);
    }

    private function buildCacheKey($params) {
        if ($searchTerm = $params['search0'] ?? false) {
            return "cache_key_search_${searchTerm}";
        }

        if (isset($params['search_column'])) {
            $columnName = $params['search_column'];
            $searchTerm = $params['search_term'];
            return "cache_key_column_search_${columnName}_${searchTerm}";
        }

        $offset = $params['offset'];
        return "cache_key_offset_${offset}";
    }
}
