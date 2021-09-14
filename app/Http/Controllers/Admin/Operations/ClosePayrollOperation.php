<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait ClosePayrollOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupClosePayrollRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/closePayroll', [
            'as'        => $routeName.'.closePayroll',
            'uses'      => $controller.'@closePayroll',
            'operation' => 'closePayroll',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupClosePayrollDefaults()
    {
        $this->crud->allowAccess('closePayroll');

        $this->crud->operation('closePayroll', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButtonFromView('line', 'closePayroll', 'custom_close_payroll', 'beginning');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function closePayroll($id)
    {
        $this->crud->hasAccessOrFail('closePayroll');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        if ($id) {
            return modelInstance('PayrollPeriod')->findOrFail($id)->close()->save();
        }

        return;
    }
}
