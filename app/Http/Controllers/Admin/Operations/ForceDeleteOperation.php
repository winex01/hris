<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait ForceDeleteOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupForceDeleteRoutes($segment, $routeName, $controller)
    {
        Route::delete($segment.'/{id}/forceDelete', [
          'as'        => $routeName.'.forceDelete',
          'uses'      => $controller.'@forceDelete',
          'operation' => 'forceDelete',
        ]);

        // bulk
        Route::post($segment.'/forceBulkDelete', [
            'as'        => $routeName.'.forceBulkDelete',
            'uses'      => $controller.'@forceBulkDelete',
            'operation' => 'forceBulkDelete',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupForceDeleteDefaults()
    {
        $this->crud->allowAccess('forceDelete');

        $this->crud->operation('forceDelete', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation(['list', 'show'], function () {
            $this->crud->addButtonFromView('line', 'forceDelete', 'custom_force_delete', 'end');
        });

        //bulk
        $this->crud->allowAccess('forceBulkDelete');

        $this->crud->operation('forceBulkDelete', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->enableBulkActions();
            $this->crud->addButtonFromView('bottom', 'forceBulkDelete', 'custom_force_bulk_delete', 'end');
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return string
     */
    public function forceDelete($id)
    {
        $this->crud->hasAccessOrFail('forceDelete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        return $this->crud->model::findOrFail($id)->forceDelete();
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function forceBulkDelete()
    {
        $this->crud->hasAccessOrFail('forceBulkDelete');

        $entries = request()->input('entries');
        $deletedEntries = [];
        debug('fuck winnex');
        foreach ($entries as $key => $id) {
            if ($entry = $this->crud->model::findOrFail($id)) {
                $deletedEntries[] = $entry->forceDelete();
            }
        }

        return $deletedEntries;
    }
}
