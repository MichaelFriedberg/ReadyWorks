<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ComputerController extends Controller
{

    /**
     * @return array|mixed
     */
    public function getTopTenComputerModels() {
        $computerModel = new Computer();
        return $computerModel->getTopTenComputerModels();
    }

    /**
     * @return array|mixed
     */
    public function getComputersByOperatingSystem() {
        $computerModel = new Computer();
        return $computerModel->getByOperatingSystem();
    }

    /**
     * @return array|mixed
     */
    public function getComputersByLocation() {
        $computerModel = new Computer();
        return $computerModel->getByLocation();
    }

    /**
     * @return mixed
     *
     */
    public function dataTable(Request $request) {
        $result = [];
        $params = [];
        $draw = $request->draw;
        $length = $request->length;
        if (isset($request->search['value'])) {
            $params['search'] = $request->search['value'];
        }
        if ($request->columns) {
            $searchColumnValue = $this->getSearchForColumn($request->columns);
            if ($searchColumnValue) {
                $params['search_column'] = $searchColumnValue[0];
                $params['search_term'] = $searchColumnValue[1];
            }
        }
        $params['limit'] = $length;
        $page = $draw - 1;
        $params['offset'] = $params['limit'] * $page;
        $computerModel = new Computer();
        $data =  $computerModel->getTableData($params);
        $count = $computerModel->getCount();
        $result['data'] = $data['data'] ?? [];
        $result["recordsFiltered"] = $data['total'] ?? $count;
        $result['recordsTotal'] = $count;
        $result['draw'] = $draw;
        return $result;
    }

    /**
     * @param array $columns
     * @return array|bool[]
     */
    private function getSearchForColumn(array $columns) {
        foreach (Computer::SELECT_COLUMNS as $ind => $column) {
                 if ($searchTerm = $columns[$ind]['search']['value'] ?? false) {
                     return [$column , $searchTerm];
                 }
        }
        return false;
    }
}
