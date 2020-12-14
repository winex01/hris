<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmployeeCreateRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Employee;
use App\Models\PersonalData;
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
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; edit as traitEdit; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation { show as traitShow; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation { bulkDelete as traitBulkDelete; }
    use \Backpack\ReviseOperation\ReviseOperation;
    use \App\Http\Controllers\Admin\Operations\ForceDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\ForceBulkDeleteOperation;
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
            \Str::singular(__('lang.employee')), 
            \Str::plural(__('lang.employee')), 
        );

        $this->userPermissions('employee');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // columns
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
        
        $this->inputs();
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
        
        $this->inputs();
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);

        $id = $this->crud->getCurrentEntryId() ?? $id;

        // personal data tab
        $emp = Employee::findOrFail($id);
        $personalData = PersonalData::where('employee_id', $id)->info()->first();

        $this->imageRow('img', $emp->img_url, ['tab' => 'personal_data']);
        $this->dataPreview([
            $emp,
            $personalData,
        ], 'personal_data');

        foreach ([
            'gender', 
            'civilStatus',
            'citizenship',
            'religion',
            'bloodType',
        ] as $modelAttr) {
            if ($personalData->{$modelAttr}) {
                $this->modifyDataRow(\Str::snake($modelAttr), $personalData->{$modelAttr}->name);
            }
        }
        // end personal data tab

        // family data
        foreach ($this->familyDataTabs() as $familyData) {
            $labelPrefix = __('lang.'.$familyData);
            $labelPrefix = str_replace('Info', '', $labelPrefix);
            $labelPrefix = str_replace('Emergency Contact', 'Contact\'s', $labelPrefix);

            $method = $this->convertMethodName($familyData);

            foreach (getTableColumns('persons', ['relation']) as $modelAttr) {
                $this->dataRow(
                    $familyData.$modelAttr, 
                    $emp->{$method}()->{$modelAttr}, 
                    [
                        'tab'   => $familyData,
                        'label' => $labelPrefix.' '.__('lang.'.$modelAttr),
                    ]
                );
            }
        }

        // dd($this->crud->columns());
    }

    public function store()
    {
        $response = $this->traitStore();

        $inputs = $this->crud->getStrippedSaveRequest();

        // find or insert employee
        $employee = Employee::firstOrCreate(
            $this->formInputs(
                $inputs, 
                'employees'
            )
        );

        // insert employee img
        $employee->img = $inputs['img'];

        // insert personal data
        $employee->personalData()->create(
            $this->formInputs(
                $inputs,
                'personal_datas' 
            )
        );

        // insert family data
       $this->storeOrUpdateFamilyData($employee, $inputs);
        
        return $response;
    }

    public function edit($id)
    {
        $response = $this->traitEdit($id);

        $id = $this->crud->getCurrentEntryId() ?? $id;
        $fields = $this->crud->getUpdateFields();

        $personalData = PersonalData::firstOrCreate(['employee_id' => $id]);
        
        // fill up personal data
        foreach (getTableColumns('personal_datas', ['employee_id']) as $modelAttr){
            $fields[$modelAttr]['value'] = $personalData->{$modelAttr};
        }

        // fill up family data
        foreach ($this->familyDataTabs() as $familyData) {
            $method = $this->convertMethodName($familyData);

            if ($personalData->employee->{$method}()) {

                foreach (getTableColumns('persons', ['relation']) as $modelAttr){
                    $fields[$familyData.'_'.$modelAttr]['value'] = $personalData->employee->{$method}()->{$modelAttr};
                }       
            }
        }

        // img
        $emp = $personalData->employee;
        if ($emp->image) {
            $fields['img']['value'] = $emp->img_url;
        }

        // override
        $this->crud->setOperationSetting('fields', $fields);

        return $response;
    }

    public function update()
    {
        $response = $this->traitUpdate();

        $inputs = $this->crud->getStrippedSaveRequest();

        $employee = Employee::findOrFail(request()->id); 

        // update employee
        $employee->update(
            $this->formInputs(
                $inputs, 
                $employee->getTable()
            )
        );

        // update employee img
        $employee->img = $inputs['img'];

        // update personal data
        // personalData call as property
        // not personalData() as method to run event revise
        $employee->personalData->update(
            $this->formInputs(
                $inputs, 
                $employee->personalData->getTable()
            )
        );

        // update family data
       $this->storeOrUpdateFamilyData($employee, $inputs);

        return $response;
    }

    public function show($id)
    {
        $content = $this->traitShow($id);

        // return $content;
        return view('crud::custom_show_with_tab', $this->data);
    }

    private function storeOrUpdateFamilyData($employee, $inputs)
    {
         // insert/update family data
        foreach ($this->familyDataTabs() as $familyData) {
            $method = $this->convertMethodName($familyData);

            $employee->{$method}(
                $this->formInputsRemovePrefix(
                    $inputs,
                    'persons', 
                    $familyData.'_', 
                )
            );
        }

    }

    public function inputs()
    {
        // dropdown select lists
        $selectList = $this->selectList([
            'gender_id',
            'civil_status_id',
            'citizenship_id',
            'religion_id',
            'blood_type_id',
        ]);

        // personal data tab
        $tabName = __('lang.personal_data');
        $this->crud->addField($this->imageField('img', $tabName));

        foreach (getTableColumnsWithDataType('employees') as $column => $dataType) {
            $this->crud->addField(
                $this->{$dataType.'Field'}($column, $tabName, [
                    'attributes' => [
                        'placeholder' => ($column == 'badge_id') ? 'Employee ID' : ''
                    ], 
                ]),
            );
        }

        foreach (getTableColumnsWithDataType('personal_datas') as $column => $dataType) {
            if ($column == 'employee_id') {
                continue;
            }

            if ($dataType == 'bigint') {
                $this->crud->addField(
                    $this->select2FromArray($column, $tabName, [
                        'options' => $selectList[$column]
                    ])
                );

                continue;
            }
            
            $this->crud->addField(
                $this->{$dataType.'Field'}($column, $tabName),
            );
        }

        // family data tab
        $familyDatas = $this->familyDataTabs();
        foreach ($familyDatas as $familyData) {
            $labelPrefix = __('lang.'.$familyData);
            $labelPrefix = str_replace('Info', '', $labelPrefix);
            $labelPrefix = str_replace('Emergency Contact', 'Contact\'s', $labelPrefix);

            foreach (getTableColumnsWithDataType('persons') as $column => $dataType) {
                if ($column == 'relation') {
                    continue;
                }

                $this->crud->addField(
                    $this->{$dataType.'Field'}(
                        $familyData.'_'.$column, // name
                        __('lang.'.$familyData), // tab
                        [ // others
                            'label' => $labelPrefix.' '. __('lang.'.$column)
                        ]
                    )
                );
            }
        }
        
    }

    public function convertMethodName($familyData)
    {
        $method = str_replace('_info', '', $familyData);
        $method = \Str::singular($method);
        $method = \Str::camel($method);

        return $method;
    }

    public function familyDataTabs()
    {
        return [
            'emergency_contact',  
            'fathers_info',  
            'mothers_info',  
            'spouse_info',  
        ];   
    }

}
