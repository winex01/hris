<?php

namespace App\Http\Controllers\Admin\Operations\LeaveApplication;

use Illuminate\Support\Facades\Route;

trait EmployeeOnChangeOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupEmployeeOnChangeRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/employeeOnChange', [
            'as'        => $routeName.'.employeeOnChange',
            'uses'      => $controller.'@employeeOnChange',
            'operation' => 'employeeOnChange',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupEmployeeOnChangeDefaults()
    {
        $this->crud->allowAccess('employeeOnChange');

        $this->crud->operation('employeeOnChange', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function employeeOnChange()
    {
        $this->crud->hasAccessOrFail('employeeOnChange');

        if (!$employee_id = request()->employee_id) {
            return;
        }

        $item =  modelInstance('LeaveApprover')
            ->where('employee_id', $employee_id)
            ->first();
        
        if ($item) {
            if (!empty($item->approvers)) {
                $item->approvers_name = jsonToArrayImplode($item->approvers, 'employee_name');

                return $item;
            }
        }

        return;
    }

    
}
