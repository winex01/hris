<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait ExportOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupExportRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/export', [
            'as'        => $routeName.'.export',
            'uses'      => $controller.'@export',
            'operation' => 'export',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupExportDefaults()
    {
        $this->crud->allowAccess('export');

        $this->crud->operation('export', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->enableBulkActions();
            $this->crud->addButtonFromView('bottom', 'export', 'custom_export', 'end');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function export()
    {
        $this->crud->hasAccessOrFail('export');

        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? 'export '.$this->crud->entity_name;

        // load the view
        return view("crud::operations.export", $this->data);
    }
}
