<?php

namespace App\Http\Controllers\Admin;

use App\Models\DtrLog;
use App\Services\EmployeeTimeClockService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
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
        // permission
        if (auth()->user()->can('employee_time_clock_show')) {
            $this->crud->allowAccess('loggedTime');
        }
    }

    protected function setupLoggedClockRoutes($segment, $routeName, $controller) {
        Route::post($segment.'/loggedTime', [
            'as'        => $routeName.'.loggedTime',
            'uses'      => $controller.'@loggedTime',
        ]);

        Route::post($segment.'/show', [
            'as'        => $routeName.'.show',
            'uses'      => $controller.'@show',
        ]);
    }

    public function loggedTime()
    {   
        $msg = null;
        $error = false;

        if ($this->crud->hasAccess('loggedTime')) { // refer to method setup above
            $type = request()->type;
            $data = DtrLog::create([
                'employee_id' => emp()->id,
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
            'timeClock' => $this->timeClock()
        ];
    }

    public function show()
    {
        if (emp()) {
            return $this->timeClock();
        }

        return;
    }

    public function timeClock()
    {
        $clock =  new EmployeeTimeClockService();

        return $clock->timeClock();
    }
}