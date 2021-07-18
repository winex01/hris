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
        // TODO:: test and disable/remove permission in auth.user.edit. uncheck clock show
        $this->userPermissions('employee_time_clock');
    }

    protected function setupLoggedClockRoutes($segment, $routeName, $controller) {
        Route::post($segment.'/logged-clock', [
            'as'        => $routeName.'.loggedClock',
            'uses'      => $controller.'@loggedClock',
        ]);
    }

    public function loggedTime()
    {
        $type = request()->type;
        $data = DtrLog::create([
            'employee_id' => request()->empId,
            'dtr_log_type_id' => request()->type
        ]);

        return [
            'text' => trans('lang.dtr_logs_logged_'.$type),
            'clockLoggerButton' => emp()->clockLoggerButton() // TODO:: this
        ];
    }

}
