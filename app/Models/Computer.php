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
rank() over(order by count(*) desc) as rank
FROM computers
GROUP BY computer_model
ORDER BY total DESC) as total_models
where rank <= 10
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

    /**
     * @param string $query
     * @param string $cacheKey
     *
     * @return array|mixed
     */
    private function getRecord(string $query, string $cacheKey) {
        $record = Cache::get($cacheKey);
        if ($record) {
            return $record;
        }
        $result = DB::select($query);
        Cache::put($cacheKey, $result, self::CACHE_TTL);
        return $result;
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
