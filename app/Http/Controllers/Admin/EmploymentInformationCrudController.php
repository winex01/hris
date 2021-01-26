<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmploymentInformationCreateRequest;
use App\Http\Requests\EmploymentInformationUpdateRequest;
use App\Models\EmploymentInfoField;
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
    use \Backpack\ReviseOperation\ReviseOperation;
    use \App\Http\Controllers\Admin\Operations\ExportOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

    public $inputFields;
    public $pageLength;

    public function __construct()
    {
        parent::__construct();
        $this->inputFields = EmploymentInfoField::pluck('field_type', 'name')->toArray();
        $this->pageLength = EmploymentInfoField::count();
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

        $this->filters();

        // data table default page length
        $this->crud->setPageLengthMenu([[$this->pageLength, 50, 100,-1],[$this->pageLength, 50, 100,"backpack::crud.all"]]);
        $this->crud->setDefaultPageLength($this->pageLength);

        $this->crud->addColumn('employee_id');
        $this->crud->addColumn(['name' => 'field_name','orderable' => false]);
        $this->crud->addColumn(['name' => 'field_value','orderable' => false]);
        $this->crud->addColumn(['name' => 'effectivity_date','orderable' => false]);
        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => 'Date Change',
            'orderable' => false
        ]);
        
        $this->showEmployeeNameColumnUnsortable();
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
        foreach ($this->inputFields as $fieldName => $type) {
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
        if (array_key_exists($field, $this->inputFields)) {
            $this->addSelectField($field);
            $this->crud->modifyField($field, [
                'default' => ($fieldValue) ? $fieldValue->id : null,
            ]);
        }else {
            $this->crud->addField([
                'name'  => 'new_field_value',
                'label' => convertColumnToHumanReadable(strtolower($field)),
                'type'  => 'number',
                'value' => $fieldValue
            ]);
            $this->currencyField('new_field_value');
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

        $fieldValue = $request->{$request->field_name};
        $fieldValue = array_key_exists($request->field_name, $this->inputFields) ? json_encode(['id' => $fieldValue]) : $fieldValue;

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

        foreach ($this->inputFields as $field => $type) {
            if ($type == 1) {
                // if select box
                $this->addSelectField($field);
            }else {
                // if input box
                $this->crud->addField([
                    'name'  => $field,
                    'label' => convertColumnToHumanReadable(strtolower($field)),
                    'type'  => 'number',
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
                'placeholder'   => '-',
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
        $this->crud->addFilter([
            'name'  => 'date_range_filter_effectivity_date',
            'type'  => 'date_range',
            'label' => 'Effectivity Date',
        ],
        false,
        function ($value) { // if the filter is active, apply these constraints
            $dates = json_decode($value);
            $this->crud->query->whereBetween('effectivity_date', [$dates->from, $dates->to]);
        });

        // date change date range filter
        $this->crud->addFilter([
            'name'  => 'date_range_filter_created_at',
            'type'  => 'date_range',
            'label' => 'Date Change',
        ],
        false,
        function ($value) { // if the filter is active, apply these constraints
            $dates = json_decode($value);
            debug($value);
            $this->crud->query->whereBetween('created_at', [$dates->from, $dates->to]);
        });
    
        // display all
        $this->crud->addFilter([
            'type'  => 'simple',
            'name'  => 'remove_scope_CurrentEmploymentInfoScope',
            'label' => 'Employment History'
        ], 
        false, 
        function() { // if the filter is active
            $this->crud->query->withoutGlobalScope(CurrentEmploymentInfoScope::class);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Fetch Inline Create Data
    | NOTE:: I intentionaly ucfirst all function after the word fetch to match entity from 
    | crud bec. if i name the function like this fetchPayBasis it would produce 
    | fetch/pay-basis, since i dont want to alter too much in custom_inline_create.blade.php
    | to fix it, i use lowercase to transform route fetch, ex. fetchPaybasis = fetch/paybasis
    | which match to entity crud of pay basis. 
    |--------------------------------------------------------------------------
    */
    public function fetchCompany()
    {
        return $this->fetch(\App\Models\Company::class);
    }

    public function fetchLocation()
    {
        return $this->fetch(\App\Models\Location::class);
    }

    public function fetchDepartment()
    {
        return $this->fetch(\App\Models\Department::class);
    }

    public function fetchDivision()
    {
        return $this->fetch(\App\Models\Division::class);
    }

    public function fetchSection()
    {
        return $this->fetch(\App\Models\Section::class);
    }

    public function fetchPosition()
    {
        return $this->fetch(\App\Models\Position::class);
    }

    public function fetchLevel()
    {
        return $this->fetch(\App\Models\Level::class);
    }

    public function fetchRank()
    {
        return $this->fetch(\App\Models\Rank::class);
    }

    public function fetchDaysperyear()
    {
        return $this->fetch([
            'model' => \App\Models\DaysPerYear::class,
            'searchable_attributes' => ['days_per_year', 'days_per_week', 'hours_per_day']
        ]);
    }

    public function fetchPaybasis()
    {
        return $this->fetch(\App\Models\PayBasis::class);
    }

    public function fetchPaymentmethod()
    {
        return $this->fetch(\App\Models\PaymentMethod::class);
    }

    public function fetchEmploymentstatus()
    {
        return $this->fetch(\App\Models\EmploymentStatus::class);
    }

    public function fetchJobstatus()
    {
        return $this->fetch(\App\Models\JobStatus::class);
    }

    public function fetchGrouping()
    {
        return $this->fetch(\App\Models\Grouping::class);
    }
}
