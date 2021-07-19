<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmployeeTimeClockRequest;
use App\Models\DtrLog;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;

/**
 * Class EmployeeTimeClockCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EmployeeTimeClockCrudController extends CrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        // add permission
        if (emp()->timeClock()['show']) {
            $this->crud->allowAccess('loggedTime');
        }
    }

    protected function setupLoggedClockRoutes($segment, $routeName, $controller) {
        Route::post($segment.'/loggedTime', [
            'as'        => $routeName.'.loggedTime',
            'uses'      => $controller.'@loggedTime',
        ]);
    }

    // TODO::
    public function loggedTime()
    {   
        $msg = null;
        $error = false;

        // refer to method setup above
        if ($this->crud->hasAccess('loggedTime')) {
            $type = request()->type;
            $data = DtrLog::create([
                'employee_id' => request()->empId,
                'dtr_log_type_id' => request()->type
            ]);

            $msg = trans('lang.clock_success_'.$type);
        }else {
            $msg = trans('lang.clock_invalid_logged');
            $error = true;
        }

        return [
            'msg'   => $msg,
            'error' => $error,
            'timeClock' => emp()->timeClock()
        ];
    }

}
