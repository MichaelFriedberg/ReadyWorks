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

    const SQL_TOP_TEN_COMPUTERS = <<<SQL
SELECT
computer_model,
total
FROM (SELECT computer_model,
COUNT(*) total,
rank() over(order by count(*) desc) as rnk
FROM computers
GROUP BY computer_model
ORDER BY total DESC) as total_models
where rnk <= 10
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
%s
SELECT `name`,`migration_status`,`user_name`,`location`,`computer_type`,`computer_model`,
       `operating_system`,`windows_10_version`,`memory_gb`,`disk_size_gb`,`free_space_gb`,
       `serial`,`business_unit`,`department`,`replacement_ordered`,`static_ip`,`state`,
       `central_build_site`,`last_logon_user`,`vetted`
FROM computers
%s
;
SQL;

    const SQL_DECLARE = <<<SQL
DECLARE @search AS VARCHAR(100)= :search;
SQL;

    const SQL_TABLE_SEARCH = <<<SQL
WHERE
`name` LIKE @search OR
`migration_status` LIKE @search OR
`user_name` LIKE @search OR
`location` LIKE @search OR
`computer_type` LIKE @search OR
`computer_model` LIKE @search OR
`operating_system` LIKE @search OR
`windows_10_version` LIKE @search OR
`memory_gb` LIKE @search OR
`disk_size_gb` LIKE @search OR
`free_space_gb` LIKE @search OR
`serial` LIKE @search OR
`business_unit` LIKE @search OR
`department` LIKE @search OR
`replacement_ordered` LIKE @search OR
`static_ip` LIKE @search OR
`state` LIKE @search OR
`central_build_site` LIKE @search OR
`last_logon_user` LIKE @search OR
`vetted` LIKE @search
SQL;

    const SQL_TABLE_COUNT = <<<SQL
SELECT count(*) as total FROM computers;
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

    public function getByLocation() {
        $data = $this->getRecord(
            self::SQL_COMPUTER_BY_LOCATION,
            self::CACHE_KEY_LOCATION
        );

        return $this->formatData($data, 'location');
    }

    public function getCount() {
        $data = $this->getRecord(self::SQL_TABLE_COUNT);
        return $data[0]->total;
    }

    public function getTableData(array $params) {
        $result = [];
        if (isset($params['search'])) {
            $sql = sprintf(self::SQL_TABLE, self::SQL_DECLARE,self::SQL_TABLE_SEARCH);
        }
        else {
            $sql = sprintf(self::SQL_TABLE, '','');
        }
        $data = $this->getRecord($sql, '', $params);
        foreach ($data as $datum) {
            $result[] = array(
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
     * @param string $cacheKey
     *
     * @return array|mixed
     */
    private function getRecord(string $query, string $cacheKey = '', $params = []) {
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
}
