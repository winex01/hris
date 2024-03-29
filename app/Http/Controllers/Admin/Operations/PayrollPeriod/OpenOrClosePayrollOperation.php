<?php

namespace App\Http\Controllers\Admin\Operations\PayrollPeriod;

use Illuminate\Support\Facades\Route;

trait OpenOrClosePayrollOperation
{
    private $openOrClosePayrollButton = 'payroll_periods.custom_open_or_close_payroll';
    
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
        $this->crud->allowAccess(['openPayroll', 'closePayroll']);
        
        $this->crud->operation('openOrClosePayroll', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButtonFromView('line', 'openOrClosePayroll', $this->openOrClosePayrollButton, 'beginning');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function openOrClosePayroll($id)
    {
        $this->crud->allowAccess(['openPayroll', 'closePayroll']);
        
        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        if ($id) {
            if (request()->status == 0) {
                return $this->crud->model::findOrFail($id)->open()->save();
            }else {
                return $this->crud->model::findOrFail($id)->close()->save();
            }
        }

        return;
    }
}
