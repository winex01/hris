<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait EmployeeFieldOnChangeOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupEmployeeFieldOnChangeRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/employeeFieldOnChange', [
            'as'        => $routeName.'.employeeFieldOnChange',
            'uses'      => $controller.'@employeeFieldOnChange',
            'operation' => 'employeeFieldOnChange',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupEmployeeFieldOnChangeDefaults()
    {
        $this->crud->allowAccess('employeeFieldOnChange');

        $this->crud->operation('employeeFieldOnChange', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

    }
    
    // NOTE:: select on change event located at
    private function employeeFieldOnChangeOperationType()
    {
        return [
            'type' => 'leave_applications.custom_employee_on_change'
        ]; 
    }

    /**
     * Show the view for performing the operation.
     * Override this in crud controller, to change business logic
     * 
     * @return Response
     */
    public function employeeFieldOnChange()
    {
        $this->crud->hasAccessOrFail('employeeFieldOnChange');

        $id = request()->id;

        // TODO:: approvers        
        $items = classInstance('LeaveApprover')
                    ->where('employee_id', $id)
                    ->orderBy('level', 'asc')
                    ->pluck('approver_id')
                    ->toArray();

        return $items;
    }
    
}
