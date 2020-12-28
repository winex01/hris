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
        Route::post($segment.'/export', [
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

        // 
        $data = $this->exportColumnCheckboxes();
        $this->crud->macro('dbColumns', function() use ($data) {
            return $data;
        });

        $data = $this->checkOnlyCheckbox();
        $this->crud->macro('checkOnlyCheckbox', function() use ($data) {
            return $data;
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

        // return request()->all();

        // TODO:: allow supports PDF
        // TODO:: xls type

        $data = [
            'entries'       => request()->input('entries'),
            'model'         => request()->input('model'),
            'exportColumns' => request()->input('exportColumns'),
            'fileName'      => date('Y-m-d-G-i-s').'-'.auth()->user()->id.'.xlsx',
            'disk'          => 'export',
            'writerType'    => null,
        ];

        $store = $this->exportClass($data);

        $fileName = 'exports/'.$data['fileName'];
        auth()->user()->exportHistory()->create([
            'file_link' => $fileName,
        ]);

        if ($store) {
            return backpack_url('storage/'.$fileName);
        }   

        return;
    }

     // override this in crud controller if you want to modify what column shows in column dropdown with checkbox
    public function exportColumnCheckboxes()
    {
        return [
            // 
        ];
    }

    // declare if you want to idenfy which checkbox is check on default
    public function checkOnlyCheckbox()
    {
        return [
            // 
        ];
    }

    // override this in crud controller if you want to change export class
    public function exportClass($data)
    {
        return \Maatwebsite\Excel\Facades\Excel::store(
            new \App\Exports\GeneralExport($data['model'], $data['entries'], $data['exportColumns']), 
            $data['fileName'], 
            $data['disk'],
            $data['writerType']
        ); 
    }
}
