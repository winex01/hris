<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait SelectOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupSelectRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/select', [
            'as'        => $routeName.'.select',
            'uses'      => $controller.'@select',
            'operation' => 'select',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupSelectDefaults()
    {
        $this->crud->allowAccess('select');

        $this->crud->operation('select', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation(['list', 'show'], function () {
            $this->crud->addButtonFromView('line', 'select', 'custom_select', 'beginning');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function select($id)
    {
        $this->crud->hasAccessOrFail('select');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        if ($id) {
            // TODO:: fix revision not triggering
            classInstance($this->setModelSelectOperation())->where('id', '!=', $id)->update(['selected' => 0]);    
            return classInstance($this->setModelSelectOperation())->where('id', '=', $id)->update(['selected' => 1]);    
        }

        return;
    }

    // override this in your crud controller
    public function setModelSelectOperation()
    {
        return 'ModelClassNameHere';
    }
}