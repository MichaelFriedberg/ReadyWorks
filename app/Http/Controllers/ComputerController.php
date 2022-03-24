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
        if (isset($request->search['value'])) {
            $params['search'] = $request->search['value'];
        }
//        $params['limit'] = $request->length;
        $page = $request->draw - 1;
//        $params['offset'] = $params['limit'] * $page;
        $computerModel = new Computer();
        $data =  $computerModel->getTableData($params);
        $count = $computerModel->getCount();
        $result['data'] = $data;
        $result["recordsFiltered"] = $count;
        $result['recordsTotal'] = $count;
        $result['draw'] = $draw;
        return $result;
    }

    /**
     * @return mixed
     *
     */
    public function dataTable2() {
        $computers = Computer::all(self::COLUMNS);
        return DataTables::of($computers)->make();
    }
}
