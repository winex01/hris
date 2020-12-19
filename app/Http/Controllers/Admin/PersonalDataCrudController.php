<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PersonalDataRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PersonalDataCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PersonalDataCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\ReviseOperation\ReviseOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\PersonalData::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/personaldata');
        CRUD::setEntityNameStrings(
            __('lang.personal_data'), 
            __('lang.personal_data'), 
        );

        $this->userPermissions();

        // NOTE:: add this soo modal details would not open when click employee column
        // but rather into checkbox column
        $this->crud->enableBulkActions();
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // TODO:: add mini photo/image in table list column
        $this->showColumns();
        $this->showEmployeeNameColumn(); 

        $modifyColumns = [
            'gender_id',
            'civil_status_id',
            'citizenship_id',
            'religion_id',
            'blood_type_id',
        ];

        $this->crud->removeColumns($modifyColumns);

        foreach ($modifyColumns as $column) {
            $name = str_replace('_id', '', $column);
            $label = ucwords(str_replace('_', ' ', $name));
            $name = \Str::camel($name);

            $this->crud->addColumn([
                'name' => $name,
                'labe' => $name,
                'type' => 'relationship',
            ])->beforeColumn('date_applied');
        }

    }

    protected function setupShowOperation()
    {
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $personalData = \App\Models\PersonalData::findOrFail($id);
        $image = $personalData->employee->img_url;

        $this->imageRow('img', $image);
        $this->setupListOperation();

        $modifyColumns = [
            'gender_id',
            'civil_status_id',
            'citizenship_id',
            'religion_id',
            'blood_type_id',
        ];

        foreach ($modifyColumns as $column) {
            $method = str_replace('_id', '', $column);
            $label = ucwords(str_replace('_', ' ', $method));
            $method = \Str::camel($method);

            $this->crud->modifyColumn($column, [
               'label'    => $label,
               'type'     => 'closure',
               'function' => function($entry) use ($method) {
                    if ($entry->{$method}) {
                        return $entry->{$method}->name;
                    }

                    return;
                },
            ]);
        }// end foreach

    }

}
