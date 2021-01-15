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
    use \App\Http\Controllers\Admin\Traits\FetchModelTrait;
    use \App\Http\Controllers\Admin\Traits\FilterTrait;

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
        foreach (getTableColumns('employees') as $col) {
            $this->crud->addColumn([
                'name'  => $col,
                'label' => convertColumnToHumanReadable($col),
                'type' => (stringContains($col, 'email')) ? 'email' : null,
            ]);
        }

        foreach ($this->columnWithRelationship() as $col) {
            $method = relationshipMethodName($col);
            $this->crud->modifyColumn($col, [
               'label'    => convertColumnToHumanReadable($col),
               'type'     => 'closure',
               'function' => function($entry) use ($method) {
                    return $entry->{$method}->name;
                },
                'searchLogic' => function ($query, $column, $searchTerm) use ($method) {
                    $query->orWhereHas($method, function ($q) use ($column, $searchTerm) {
                        $q->where('name', 'like', '%'.$searchTerm.'%');
                    });
                }
            ]);
        }

        // photo
        $this->crud->modifyColumn('photo', [
            'type'   => 'image',
            'prefix' => 'storage/',
            'height' => '30px',
            'width'  => '30px',
        ]);

        $this->appSettingsFilter('gender');
        $this->appSettingsFilter('civilStatus');
        // $this->appSettingsFilter('citizenship');
        // $this->appSettingsFilter('religion');
        // $this->appSettingsFilter('bloodType');
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
                'label'       => convertColumnToHumanReadable($col),
                'type'        => $this->fieldTypes()[$dataType],
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

        // gender 
        $this->crud->addField([
            'name'          => 'gender', 
            'label'         => trans('lang.gender_id'),
            'type'          => 'relationship',
            'allows_null'   => false, 
            'default'       => 1,
            'ajax'          => false,
            'inline_create' => hasAuthority('genders_create') ?: null,
        ])->beforeField('birth_date');

        // civil status
        $this->crud->addField([
            'name'          => 'civilStatus',
            'label'         => trans('lang.civil_status_id'),
            'type'          => "relationship",
            'ajax'          => false,
            'allows_null'   => false, 
            'default'       => 1,
            'inline_create' => hasAuthority('civil_statuses_create') ? ['entity' => 'civilstatus'] : null
        ])->beforeField('birth_date');

        // citizenship 
        $this->crud->addField([
            'name'          => 'citizenship', 
            'label'         => trans('lang.citizenship_id'),
            'type'          => 'relationship',
            'ajax'          => false,
            'allows_null'   => false, 
            'default'       => 1,
            'inline_create' => hasAuthority('citizenships_create') ?: null,
        ])->beforeField('birth_date');

        // religion 
        $this->crud->addField([
            'name'          => 'religion', 
            'label'         => trans('lang.religion_id'),
            'type'          => 'relationship',
            'ajax'          => false,
            'allows_null'   => false, 
            'default'       => 1,
            'inline_create' => hasAuthority('religions_create') ?: null,
        ])->beforeField('birth_date');

        // blood type
        $this->crud->addField([
            'name'          => 'bloodType', // the method on your model that defines the relationship
            'label'         => trans('lang.blood_type_id'),
            'type'          => "relationship",
            'ajax'          => false,
            'allows_null'   => false, 
            'default'       => 1,
            'inline_create' => hasAuthority('blood_types_create') ? ['entity' => 'bloodtype'] : null
        ])->beforeField('birth_date');
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