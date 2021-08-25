<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmploymentInformationCreateRequest;
use App\Http\Requests\EmploymentInformationUpdateRequest;
use App\Models\EmploymentInformation;
use App\Scopes\CurrentEmploymentInfoScope;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;

/**
 * Class EmploymentInformationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EmploymentInformationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \Backpack\ReviseOperation\ReviseOperation;
    use \App\Http\Controllers\Admin\Operations\ExportOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;
    use \App\Http\Controllers\Admin\Traits\FilterTrait;
    use \App\Http\Controllers\Admin\Traits\FetchPaybasisTrait;
    use \App\Http\Controllers\Admin\Traits\FetchCompanyTrait;
    use \App\Http\Controllers\Admin\Traits\FetchLocationTrait;
    use \App\Http\Controllers\Admin\Traits\FetchDepartmentTrait;
    use \App\Http\Controllers\Admin\Traits\FetchDivisionTrait;
    use \App\Http\Controllers\Admin\Traits\FetchSectionTrait;
    use \App\Http\Controllers\Admin\Traits\FetchPositionTrait;
    use \App\Http\Controllers\Admin\Traits\FetchLevelTrait;
    use \App\Http\Controllers\Admin\Traits\FetchRankTrait;
    use \App\Http\Controllers\Admin\Traits\FetchDaysperyearTrait;
    use \App\Http\Controllers\Admin\Traits\FetchPaymentmethodTrait;
    use \App\Http\Controllers\Admin\Traits\FetchEmploymentstatusTrait;
    use \App\Http\Controllers\Admin\Traits\FetchJobstatusTrait;
    use \App\Http\Controllers\Admin\Traits\FetchGroupingTrait;
    use \App\Http\Controllers\Admin\Traits\FetchTeamTrait;

    public function __construct()
    {
        parent::__construct();
        $this->exportClass = '\App\Exports\EmploymentInformationExport';
    }

    public function inputFields()
    {
        return classInstance('EmploymentInfoField')->pluck('field_type', 'name')->toArray();
    }

    public function pageLength()
    {
        return classInstance('EmploymentInfoField')->count();
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(EmploymentInformation::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/employmentinformation');

        $this->userPermissions();

        $this->crud->entity_name = '/ Edit All Info.';
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // add on queries
        $this->crud->orderBy('employee_id');
        $this->crud->addClause('orderByField');
        $this->crud->orderBy('created_at');

        $this->filters();

        // data table default page length
        $this->crud->setPageLengthMenu([[$this->pageLength(), 50, 100,-1],[$this->pageLength(), 50, 100,"backpack::crud.all"]]);
        $this->crud->setDefaultPageLength($this->pageLength());

        $this->crud->addColumn('employee_id');
        $this->crud->addColumn(['name' => 'field_name', 'label' => 'Field Name', 'orderable' => false]);
        $this->crud->addColumn(['name' => 'field_value', 'label' => 'Field Value', 'orderable' => false]);
        $this->crud->addColumn(['name' => 'effectivity_date', 'label' => 'Effectivity Date']);
        $this->crud->addColumn(['name' => 'date_change','label' => 'Date Change']);
        $this->showEmployeeNameColumn();

        // override employee column order, must also be orderByField
        $currentTable = $this->crud->model->getTable();
        $this->crud->modifyColumn('employee_id', [
            'orderLogic' => function ($query, $column, $columnDirection) use ($currentTable) {
                $query->leftJoin('employees', 'employees.id', '=', $currentTable.'.employee_id')
                        ->orderBy('employees.last_name', $columnDirection)
                        ->orderBy('employees.first_name', $columnDirection)
                        ->orderBy('employees.middle_name', $columnDirection)
                        ->orderBy('employees.badge_id', $columnDirection)
                        ->select($currentTable.'.*');
                return $query->orderByField();
            },
        ]);
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false); 
        $this->setupListOperation();
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(EmploymentInformationCreateRequest::class);
        $this->customInputs();
    }

    public function store()
    {
        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        $employeeId = $request->employee_id;
        $effectivityDate = $request->effectivity_date;
        
        $dataToStore = [];
        foreach ($this->inputFields() as $fieldName => $type) {
            $fieldValue = $request->{$fieldName};

            // if select box
            if ($type == 1) {
                $fieldValue = ($fieldValue != null) ? json_encode(['id' => $fieldValue]) : null;
            }

            $dataToStore[] = [
                'employee_id'      => $employeeId,
                'field_name'       => $fieldName,
                'field_value'      => $fieldValue,
                'effectivity_date' => $effectivityDate,
            ];
        }

        foreach ($dataToStore as $data) {
            // insert item in the db
            $item = $this->crud->create($data);
            $this->data['entry'] = $this->crud->entry = $item;
        }

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(EmploymentInformationUpdateRequest::class);

        $id = $this->crud->getCurrentEntryId() ?? $id;
        $data = EmploymentInformation::findOrFail($id);
        $fieldValue = json_decode($data->field_value_json);

        $this->crud->addField([
            'name'       => 'employee_disabled',
            'label'      => 'Employee',
            'value'      => $data->employee->full_name_with_badge,
            'attributes' => [
            'disabled'   => 'disabled',
             ], 
        ]);

        $this->crud->addField([
            'name'  => 'employee_id',
            'value' => $data->employee_id,
            'type'  => 'hidden'
        ]);

        $this->crud->addField([
            'name'  => 'field_name',
            'value' => $data->field_name,
            'type'  => 'hidden'
        ]);

        $field = $data->field_name;
        if (array_key_exists($field, $this->inputFields()) && $this->inputFields()[$field] == 1) {
            $this->addSelectField($field);
            $this->crud->modifyField($field, [
                'default' => ($fieldValue) ? $fieldValue->id : null,
            ]);
        }else {
            $this->crud->addField([
                'name'  => $field,
                'label' => convertColumnToHumanReadable(strtolower($field)),
                'value' => $fieldValue
            ]);

            $this->currencyField($field);
        }
        
        $col = 'effectivity_date';
        $this->crud->addField([
            'name'  => $col,
            'label' => convertColumnToHumanReadable($col),
        ]);
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        $fieldName = $request->field_name;
        $fieldValue = $request->{$fieldName};

        if (array_key_exists($fieldName, $this->inputFields()) && $this->inputFields()[$fieldName] == 1) {
            $fieldValue = json_encode(['id' => $fieldValue]);
        }

        $data = [
            'employee_id'      => $request->employee_id,
            'field_name'       => $request->field_name,
            'effectivity_date' => $request->effectivity_date,
            'field_value'      => $fieldValue, 
        ];

        // this update is equal to insert item in the db
        $item = $this->crud->create($data);
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }  

    private function customInputs()
    {   
        $col = 'employee_id';
        $this->crud->addField([
            'name' => $col, 
            'label' => convertColumnToHumanReadable($col)
        ]);
        $this->addSelectEmployeeField($col);

        foreach ($this->inputFields() as $field => $type) {
            if ($type == 1) {
                // if select box
                $this->addSelectField($field);
            }else {
                // if input box
                $this->crud->addField([
                    'name'  => $field,
                    'label' => convertColumnToHumanReadable(strtolower($field)),
                ]);

                $this->currencyField($field);
            }
        }      

        $col = 'effectivity_date';
        $this->crud->addField([
            'name'  => $col,
            'label' => convertColumnToHumanReadable($col),
        ]);
    }

    private function addSelectField($field)
    {
        $hint = trans('lang.employment_informations_hint_'.\Str::snake(strtolower($field)));
        
        $permission = \Str::plural(strtolower($field)).'_create';
        if (hasAuthority($permission)) {
            $entity = strtolower(str_replace('_', '', $field));

            $this->crud->addField([
                'name'          => $field,
                'label'         => convertColumnToHumanReadable(strtolower($field)),
                'type'          => 'custom_inline_create',
                'hint'          => $hint,
                'attribute'     => ($field == 'DAYS_PER_YEAR') ? 'identifiableAttribute' : 'name',
                'model'         => 'App\Models\\'.convertToClassName(strtolower($field)),
                'ajax'          => false,
                'allows_null'   => true,
                'placeholder'   => trans('lang.select_placeholder'),
                'inline_create' => [ // specify the entity in singular
                    'entity'       => $entity, // the entity in singular
                    // OPTIONALS
                    'force_select' => true, // should the inline-created entry be immediately selected?
                    'modal_class'  => 'modal-dialog modal-md', // use modal-sm, modal-lg to change width
                    'modal_route'  => route($entity.'-inline-create'), // InlineCreate::getInlineCreateModal()
                    'create_route' => route($entity.'-inline-create-save'), // InlineCreate::storeInlineCreate()
                ],
            ]);
        }else {
            if ($field == 'DAYS_PER_YEAR') {
                $temp = classInstance(strtolower($field))->orderBy('days_per_year')
                            ->orderBy('days_per_week')
                            ->orderBy('hours_per_day')
                            ->get();
                foreach ($temp as $t) {
                    $options[$t->id] = $t->days_per_year.' / '.$t->days_per_week.' / '.$t->hours_per_day;
                }
            }else {
                $options = classInstance(strtolower($field))->pluck('name', 'id');
            }

            $this->crud->addField([
                'name'          => $field,
                'label'         => convertColumnToHumanReadable(strtolower($field)),
                'type'          => 'select2_from_array',
                'allows_null'   => true,
                'hint'          => $hint,
                'options'       => $options,
            ]);
        }
    }

    private function filters()
    {
        $field = 'field_name';
        $this->crud->addFilter([
            'name'  => $field,
            'type'  => 'select2',
            'label' => convertColumnToHumanReadable($field)
        ], 
        classInstance('EmploymentInfoField')::orderBy('lft', 'ASC')->pluck('name', 'name')->toArray(),
        function($value) { // if the filter is active
            $this->crud->addClause('where', 'field_name', $value);
        });

        // effectivity date range filter
        $this->dateRangeFilter('effectivity_date', 'Effectivity Date');

        // date change date range filter
        $this->dateRangeFilter('created_at', 'Date Change');

        // display history 
        $this->removeGlobalScopeFilter('CurrentEmploymentInfoScope');

    }

}
