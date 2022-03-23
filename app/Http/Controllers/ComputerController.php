<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use Illuminate\Http\Request;

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

    public function dataTable() {
        return Computer::paginate(15);
    }
}
