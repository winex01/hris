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
        $this->inputs();
    }

    // TODO:: store 1 by 1
    public function store()
    {
        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        $employeeId = $request->employee_id;
        $effectivityDate = $request->effectivity_date;
        
        $dataToStore = [];
        foreach ($this->selectFields() as $fieldName) {
            $fieldValue = $request->{relationshipMethodName($fieldName)};

            $dataToStore[] = [
                'employee_id'      => $employeeId,
                'field_name'       => $fieldName,
                'field_value'      => $fieldValue,
                'effectivity_date' => $effectivityDate,
            ];
        }

        foreach ($this->inputFields() as $fieldName) {
            // $fieldValue = ['value' => $request->{$fieldName}];
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

        // dd($dataToStore);
        // dd($this->crud->getStrippedSaveRequest());

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
        CRUD::setValidation(EmploymentInformationRequest::class);
        $this->inputs();
    }  

    private function inputs()
    {   
        $col = 'employee_id';
        $this->crud->addField([
            'name' => $col, 
            'label' => convertColumnToHumanReadable($col)
        ]);
        $this->addSelectEmployeeField($col);

        foreach ($this->selectFields() as $field) {
            $hint = trans('lang.employment_informations_hint_'.\Str::snake($field));
            $this->crud->addField([
                'name'        => relationshipMethodName($field),
                'label'       => convertColumnToHumanReadable($field),
                'type'        => 'select2_from_array',
                'options'     => $this->fetchSelect2Lists()[$field],
                // 'allows_null' => true,
                'hint'        => $hint,
            ]);
        }      

        foreach ($this->inputFields() as $col) {
            $this->crud->addField([
                'name'  => $col,
                'label' => convertColumnToHumanReadable($col),
                'type'  => 'number',
            ])->beforeField('daysPerYear');
        }

        $col = 'effectivity_date';
        $this->crud->addField([
            'name'  => $col,
            'label' => convertColumnToHumanReadable($col),
        ]);

        $this->currencyField('basic_rate');
        $this->currencyField('basic_adjustment');
    }

    private function inputFields()
    {
        return [
            'basic_rate',
            'basic_adjustment',
        ];
    }

    private function selectFields()
    {
        return [
            'Company', 
            'Location', 
            'Department', 
            'Division', 
            'Section', 
            'Position', 
            'Level', 
            'Rank', 
            'DaysPerYear', 
            'PayBasis', 
            'PaymentMethod', 
            'EmploymentStatus', 
            'JobStatus', 
            'Grouping', 
        ];
    }

    private function fetchSelect2Lists()
    {
        $data = [];
        foreach ($this->selectFields() as $field) {
            switch ($field) {
                case 'DaysPerYear':
                    $lists = classInstance($field)->orderBy('days_per_year')
                                ->orderBy('days_per_week')
                                ->orderBy('hours_per_day')
                                ->get();
                    break;
                
                default:
                    $lists = classInstance($field)->orderBy('name')->get();
                    break;
            }

            if (!$lists->isEmpty()) {
                foreach ($lists as $t) {
                    if ($field == 'DaysPerYear') {
                        $desc = $t->days_per_year.' / '.$t->days_per_week.' / '.$t->hours_per_day;
                    }else {
                        $desc = $t->name;
                    }
                    $data[$field][$t->toJson()] = $desc;
                }
            }else {
                $data[$field] = [];
            }
        }

        return $data;
    }


    // TODO:: change button label TBD
    // TODO:: add column date_change = creatd_at
    // TODO:: inline create
    // TODO:: request validation
    // TODO:: function for field_names array
}
