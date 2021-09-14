<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait OpenOrClosePayrollOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupOpenOrClosePayrollRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/openOrClosePayroll', [
            'as'        => $routeName.'.openOrClosePayroll',
            'uses'      => $controller.'@openOrClosePayroll',
            'operation' => 'openOrClosePayroll',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupOpenOrClosePayrollDefaults()
    {
        $this->crud->allowAccess('openOrClosePayroll'); // TODO:: fix this permission
        
        $this->crud->operation('openOrClosePayroll', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButtonFromView('line', 'openOrClosePayroll', 'custom_open_or_close_payroll', 'beginning');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function openOrClosePayroll()
    {
        $this->crud->hasAccessOrFail('openOrClosePayroll');
        
        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        if ($id) {
            $status = request()->status;

            if ($status == 0) {
                $status = 1;
            }else {
                $status = 0;
            }

            return modelInstance('PayrollPeriod')->where('id', $id)->update(['status' => $status]);
        }

        return;
    }
}
