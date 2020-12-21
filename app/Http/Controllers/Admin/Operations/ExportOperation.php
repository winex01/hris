<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Exports\GeneralExport;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

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

        $entries = request()->input('entries');
        
        $returnEntries = [];
        
        // foreach ($entries as $key => $id) {
        //     if ($entry = $this->crud->model::findOrFail($id)) {
        //         $returnEntries[] = $entry->forceDelete();
        //     }
        // }

        // TODO::
        $fileName = auth()->user()->id.'-'.date('Y-m-d-G-i-s').'.xlsx';
        $store = Excel::store(new GeneralExport, $fileName, 'export');
        
        $fileName = 'exports/'.$fileName;
        auth()->user()->exportHistory()->create([
            'file_link' => $fileName,
        ]);

        if ($store) {
            return backpack_url('storage/'.$fileName);
        }   

        return;
    }
}
