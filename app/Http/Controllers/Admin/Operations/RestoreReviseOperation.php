<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait RestoreReviseOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupRestoreReviseRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/restorerevise', [
            'as'        => $routeName.'.restorerevise',
            'uses'      => $controller.'@restorerevise',
            'operation' => 'restoreRevise',
        ]);

    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupRestoreReviseDefaults()
    {
        $this->crud->allowAccess('restoreRevise');

        $this->crud->operation('restoreRevise', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation(['list', 'show'], function () {
            $this->crud->addButtonFromView('line', 'restoreRevise',  'custom_restore_button', 'end');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function restoreRevise($id)
    {
        $this->crud->hasAccessOrFail('restoreRevise');

        $id = $this->crud->getCurrentEntryId() ?? $id;
        
        if (! $id) {
            abort(500, 'Can\'t restore revision without revision_id');
        } else {
            $revision = \Venturecraft\Revisionable\Revision::findOrFail($id);

            $entry = $this->classInstance($revision->revisionable_type)
                    ->withTrashed()->findOrFail($revision->revisionable_id);

            // Update the revisioned field with the old value
            return $entry->update([$revision->key => $revision->old_value]);
        }

        return;
    }


    public function oldrestoreRevise($id)
    {
        $this->crud->hasAccessOrFail('restoreRevise');

        $id = $this->crud->getCurrentEntryId() ?? $id;

        $revision = \Venturecraft\Revisionable\Revision::findOrFail($id);

        $entry = $this->classInstance($revision->revisionable_type)
                ->withTrashed()->findOrFail($revision->revisionable_id);

        // Update the revisioned field with the old value
        $entry->update([$revision->key => $revision->old_value]);

        // show a success message
        \Alert::success(trans('revise-operation::revise.revision_restored'))->flash();

        return redirect()->back();
       
    }
}
