<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmployeeCreateRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class EmployeeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EmployeeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \Backpack\ReviseOperation\ReviseOperation;
    use \App\Http\Controllers\Admin\Operations\ForceDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\ForceBulkDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\ExportOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;
    use \App\Http\Controllers\Admin\Traits\FilterTrait;
    use \App\Http\Controllers\Admin\Traits\Fetch\FetchGenderTrait;
    use \App\Http\Controllers\Admin\Traits\Fetch\FetchCivilStatusTrait;
    use \App\Http\Controllers\Admin\Traits\Fetch\FetchCitizenshipTrait;
    use \App\Http\Controllers\Admin\Traits\Fetch\FetchReligionTrait;
    use \App\Http\Controllers\Admin\Traits\Fetch\FetchBloodTypeTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Employee::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/employee');

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
        foreach (getTableColumnsWithDataType('employees') as $col => $dataType) {
            $type = (stringContains($col, 'email')) ? 'email' : null;

            if ($dataType == 'date') {
                $type = config('appsettings.date_format_field');
            }

            $this->crud->addColumn([
                'name'  => $col,
                'label' => convertColumnToHumanReadable($col),
                'type' => $type,
            ]);
        }

        foreach ($this->columnWithRelationship() as $col) {
            $this->showRelationshipColumn($col);
        }

        // append badge with id
        $this->crud->modifyColumn('badge_id', [
            'label' => 'Badge ID'
        ]);

        // photo
        $this->crud->modifyColumn('photo', [
            'type'   => 'image',
            'height' => '30px',
            'width'  => '30px',
            'orderable' => false,
        ]);

        $this->select2Filter('gender');
        $this->select2Filter('civilStatus');
        // $this->select2Filter('citizenship');
        // $this->select2Filter('religion');
        // $this->select2Filter('bloodType');
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false); // remove fk column such as: gender_id
        $this->setupListOperation();

        // photo
        $this->crud->modifyColumn('photo', [
            'height' => '200px',
            'width'  => '200px',
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(EmployeeCreateRequest::class);
        $this->inputFields();
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(EmployeeUpdateRequest::class);
        $this->inputFields();
    }

    private function inputFields()
    {
        foreach (getTableColumnsWithDataType('employees') as $col => $dataType) {
            $this->crud->addField([
                'name'        => $col,
                'label'       => convertColumnToHumanReadable($col),
                'type'        => ($dataType == 'date') ? $this->dateFieldType() : $this->fieldTypes()[$dataType],
            ]);
        }// end foreach

        // photo
        $this->crud->modifyField('photo', [
            'label'        => trans('lang.photo'),
            'type'         => 'image',
            'crop'         => true,
            'aspect_ratio' => 1,
        ]);

        // badge_id
        $this->crud->modifyField('badge_id', [
            'attributes'  => [
                'placeholder' => trans('lang.enter_employee_id')
            ]
        ]);

        $this->addInlineCreateField('gender_id');
        $this->addInlineCreateField('civil_status_id');
        $this->addInlineCreateField('citizenship_id');
        $this->addInlineCreateField('religion_id');
        $this->addInlineCreateField('blood_type_id');
    }

    private function columnWithRelationship()
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
// TOOD:: add trash and employee filter