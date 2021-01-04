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

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Employee::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/employee');
        CRUD::setEntityNameStrings(
            \Str::singular(trans('lang.employee')), 
            \Str::plural(trans('lang.employee')), 
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
        foreach (getTableColumns('employees', $this->columnWithRelationship()) as $col) {
            $this->crud->addColumn([
                'name'  => $col,
                'label' => ucwords(str_replace('_', ' ', $col)),
                'type' => (stringContains($col, 'email')) ? 'email' : null,
            ]);
        }

        foreach ($this->columnWithRelationship() as $col) {
            $this->crud->addColumn([
                'name' => relationshipMethodName($col),
                'label' => convertColumnToHumanReadable($col),
                'type' => 'relationship',
            ])->beforeColumn('birth_date');
        }

        // photo
        $this->crud->modifyColumn('photo', [
            'type'   => 'image',
            'prefix' => 'storage/',
            'height' => '30px',
            'width'  => '30px',
        ]);
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false); // remove fk column such as: gender_id
        $this->setupListOperation();

        // photo
        $this->crud->modifyColumn('photo', [
            'type'   => 'image',
            'prefix' => 'storage/',
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
        foreach (getTableColumnsWithDataType('employees', 
            $this->columnWithRelationship() //dont include
        ) as $col => $dataType) {
            $this->crud->addField([
                'name'        => $col,
                'label'       => ucwords(str_replace('_', ' ', $col)),
                'type'        => $this->fieldTypes()[$dataType],
            ]);
        }// end foreach

        // photo
        $this->crud->modifyField('photo', [
            'type'   => 'image',
            'prefix' => 'storage/',
            'crop' => true, 
            'aspect_ratio' => 1, 
        ]);

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

        // gender 
        $this->crud->addField([
            'name'          => 'gender', 
            'label'         => trans('lang.gender'),
            'type'          => 'relationship',
            'allows_null'   => false, 
            'default'       => 1,
            'ajax'          => false,
            'inline_create' => hasAuthority('genders_create') ?: null,
        ])->beforeField('birth_date');

        // civil status
        $this->crud->addField([
            'name'          => 'civilStatus',
            'label'         => trans('lang.civil_status'),
            'type'          => "relationship",
            'ajax'          => false,
            'allows_null'   => false, 
            'default'       => 1,
            'inline_create' => hasAuthority('civil_statuses_create') ? ['entity' => 'civilstatus'] : null
        ])->beforeField('birth_date');

        // citizenship 
        $this->crud->addField([
            'name'          => 'citizenship', 
            'label'         => trans('lang.citizenship'),
            'type'          => 'relationship',
            'ajax'          => false,
            'allows_null'   => false, 
            'default'       => 1,
            'inline_create' => hasAuthority('citizenships_create') ?: null,
        ])->beforeField('birth_date');

        // religion 
        $this->crud->addField([
            'name'          => 'religion', 
            'label'         => trans('lang.religion'),
            'type'          => 'relationship',
            'ajax'          => false,
            'allows_null'   => false, 
            'default'       => 1,
            'inline_create' => hasAuthority('religions_create') ?: null,
        ])->beforeField('birth_date');

        // blood type
        $this->crud->addField([
            'name'          => 'bloodType', // the method on your model that defines the relationship
            'label'         => trans('lang.blood_type'),
            'type'          => "relationship",
            'ajax'          => false,
            'allows_null'   => false, 
            'default'       => 1,
            'inline_create' => hasAuthority('blood_types_create') ? ['entity' => 'bloodtype'] : null
        ])->beforeField('birth_date');
    }

    public function fetchGender()
    {
        return $this->fetch(\App\Models\Gender::class);
    }

    public function fetchCivilStatus()
    {
        return $this->fetch(\App\Models\CivilStatus::class);
    }

    public function fetchCitizenship()
    {
        return $this->fetch(\App\Models\Citizenship::class);
    }

    public function fetchReligion()
    {
        return $this->fetch(\App\Models\Religion::class);
    }

    public function fetchBloodType()
    {
        return $this->fetch(\App\Models\BloodType::class);
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