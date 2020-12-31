<?php

namespace App\Http\Controllers\Admin;

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
    use \App\Http\Controllers\Admin\Operations\ExportOperation;
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
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->showColumns();
        $this->showEmployeeNameColumn(); 

        $this->crud->removeColumns($this->removeColumns());

        foreach ($this->removeColumns() as $column) {
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
        $this->crud->set('show.setFromDb', false);

        $id = $this->crud->getCurrentEntryId() ?? $id;
        $personalData = \App\Models\PersonalData::findOrFail($id);
        $image = $personalData->employee->img_url;

        $this->imageRow('img', $image);
        $this->setupListOperation();

    }

    private function removeColumns()
    {
        return [
            'gender_id',
            'civil_status_id',
            'citizenship_id',
            'religion_id',
            'blood_type_id',
        ];
    }

}
