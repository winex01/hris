<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmploymentInformationRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\EmploymentInformation::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/employmentinformation');

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
        $this->crud->addColumn('employee_id');
        $this->crud->addColumn('field_name');
        $this->crud->addColumn('field_value');
        $this->crud->addColumn('effectivity_date');
        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => 'Date Change',
        ]);
        
        $this->showEmployeeNameColumn();
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
        CRUD::setValidation(EmploymentInformationRequest::class);
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
        foreach ($this->selectFields() as $fieldName) {
            $fieldValue = $request->{$fieldName};
            $fieldValue = ($fieldValue != null) ? json_encode(['id' => $fieldValue]) : null;

            $dataToStore[] = [
                'employee_id'      => $employeeId,
                'field_name'       => $fieldName,
                'field_value'      => $fieldValue,
                'effectivity_date' => $effectivityDate,
            ];
        }

        foreach ($this->inputFields() as $fieldName) {
            $fieldValue = $request->{$fieldName};

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
        // CRUD::setValidation(EmploymentInformationRequest::class);
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $data = \App\Models\EmploymentInformation::findOrFail($id);
        $fieldValue = json_decode($data->field_value_json);

        $this->crud->addField([
            'name' => 'employee_disabled',
            'label' => 'Employee',
            'value' => $data->employee->full_name_with_badge,
            'attributes' => [
               'disabled'=> 'disabled',
             ], 
        ]);

        $field = convertToClassName(strtolower($data->field_name));

        if (in_array($field, $this->selectFields())) {
            $this->addSelectField($field);

            if ($fieldValue) {
                $this->crud->modifyField(relationshipMethodName($field), [
                    'default' => $fieldValue->id
                ]);
            }
        }else {
            $this->crud->addField([
                'name'  => $field,
                'label' => convertColumnToHumanReadable($field),
                'type'  => 'number',
                'value' => $fieldValue
            ]);
            $this->currencyField($field);
        }
        
        // TODO:: validaiton

    }

    // TODO:: here naku
    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        dd(
            $this->crud->getStrippedSaveRequest()
        );

        // update the row in the db
        $item = $this->crud->update($request->get($this->crud->model->getKeyName()),
                            $this->crud->getStrippedSaveRequest());
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

        foreach ($this->selectFields() as $field) {
            $this->addSelectField($field);
        }      

        foreach ($this->inputFields() as $col) {
            $this->crud->addField([
                'name'  => $col,
                'label' => convertColumnToHumanReadable(strtolower($col)),
                'type'  => 'number',
            ])->beforeField('DAYS_PER_YEAR');
            $this->currencyField($col);
        }

        $col = 'effectivity_date';
        $this->crud->addField([
            'name'  => $col,
            'label' => convertColumnToHumanReadable($col),
        ]);

    }

    public function inputFields()
    {
        return [
            'BASIC_RATE',
            'BASIC_ADJUSTMENT',
        ];
    }

    // TODO:: tbd create crud and reorder
    public function selectFields()
    {   
        // class name
        return [
            'COMPANY', 
            'LOCATION', 
            'DEPARTMENT', 
            'DIVISION', 
            'SECTION', 
            'POSITION', 
            'LEVEL', 
            'RANK', 
            'DAYS_PER_YEAR', 
            'PAY_BASIS', 
            'PAYMENT_METHOD', 
            'EMPLOYMENT_STATUS', 
            'JOB_STATUS', 
            'GROUPING', 
        ];
    }

    private function fetchSelect2Lists()
    {
        $data = [];
        foreach ($this->selectFields() as $field) {
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
    // TODO:: change button label TBD
    // TODO:: inline create
    // TODO:: request validation
}
