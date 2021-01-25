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

        $this->crud->addFilter([
          'type'  => 'simple',
          'name'  => 'remove_scope_CurrentEmploymentInfoScope',
          'label' => 'Employment History'
        ], 
        false, 
        function() { // if the filter is active
            $this->crud->query->withoutGlobalScope(CurrentEmploymentInfoScope::class);
        } );

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
            $hint = trans('lang.employment_informations_hint_'.\Str::snake(strtolower($field)));
            $this->crud->addField([
                'name'        => 'new_field_value',
                'label'       => convertColumnToHumanReadable(strtolower($field)),
                'type'        => 'select2_from_array',
                'options'     => $this->fetchSelect2Lists()[$field],
                'hint'        => $hint,
                'default'     => ($fieldValue) ? $fieldValue->id : null,
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

        $fieldValue = $request->new_field_value;
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

        // TODO:: here
        $this->crud->modifyField('COMPANY', [
            'type' => 'custom_inline_create',
            'ajax' => true,
            'inline_create' => [ // specify the entity in singular
                'entity' => 'company', // the entity in singular
                // OPTIONALS
                'force_select' => true, // should the inline-created entry be immediately selected?
                'modal_class' => 'modal-dialog modal-md', // use modal-sm, modal-lg to change width
                'modal_route' => route('company-inline-create'), // InlineCreate::getInlineCreateModal()
                'create_route' =>  route('company-inline-create-save'), // InlineCreate::storeInlineCreate()
            ]
        ]);
    }

    private function fetchSelect2Lists()
    {
        $data = [];
        foreach ($this->inputFields as $field => $type) {
            if ($type == 0) { // 0 = input box
                continue;
            }

            $class = convertToClassName(strtolower($field));
            switch ($field) {
                case 'DAYS_PER_YEAR':
                    $temp = classInstance($class)->orderBy('days_per_year')
                        ->orderBy('days_per_week')
                        ->orderBy('hours_per_day')
                        ->get();

                    $lists = [];
                    foreach ($temp as $t) {
                        $lists[$t->id] = $t->days_per_year.' / '.$t->days_per_week.' / '.$t->hours_per_day;
                    }
                    break;
                
                default:
                    $lists = classInstance($class)->orderBy('name')->pluck('name', 'id')->toArray();
                    break;
            }

            $data[$field] = $lists;
        }

        return $data;
    }

    private function addSelectField($field)
    {
        $hint = trans('lang.employment_informations_hint_'.\Str::snake(strtolower($field)));
        $this->crud->addField([
            'name'        => $field,
            'label'       => convertColumnToHumanReadable(strtolower($field)),
            'type'        => 'select2_from_array',
            'options'     => $this->fetchSelect2Lists()[$field],
            // 'allows_null' => true,
            'hint'        => $hint,
        ]);
    }

    // TODO:: inline create
}
