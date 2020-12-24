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

        // initilized dbColumns
        $dbColumns = $this->exportDbColumns();
        $this->crud->macro('dbColumns', function() use ($dbColumns) {
            return $dbColumns;
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

        $entries = request()->input('entries');
        $model = request()->input('model');
        $exportColumns = request()->input('exportColumns');

        $fileName = date('Y-m-d-G-i-s').'-'.auth()->user()->id.'.xlsx';
        $store = $this->exportClass($model, $entries, $exportColumns, $fileName);

        // TODO:: allow supports PDF
        // public function store(
        //     $export, 
        //     string $filePath, 
        //     string $diskName = null, 
        //     string $writerType = null, 
        //     $diskOptions = []
        // )
        
        $fileName = 'exports/'.$fileName;
        auth()->user()->exportHistory()->create([
            'file_link' => $fileName,
        ]);

        if ($store) {
            return backpack_url('storage/'.$fileName);
        }   

        return;
    }

     // override this in crud controller if you want to modify what column shows in column dropdow with checkbox
    public function exportDbColumns()
    {
        return [
            // 
        ];
    }

    // override this in crud controller if you want to change export class
    public function exportClass($model, $entries, $exportColumns, $fileName)
    {
        return \Maatwebsite\Excel\Facades\Excel::store(
            new \App\Exports\GeneralExport($model, $entries, $exportColumns), 
            $fileName, 
            'export',
        ); 
    }
}
