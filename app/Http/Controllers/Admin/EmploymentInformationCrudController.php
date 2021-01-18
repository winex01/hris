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
        $this->showColumns();
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
        $this->fieldInputs();
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
        $this->fieldInputs();
    }  

    private function fieldInputs()
    {   
        $col = 'employee_id';
        $this->crud->addField([
            'name' => $col, 
            'label' => convertColumnToHumanReadable($col)
        ]);
        $this->addSelectEmployeeField($col);

        foreach ($this->availableFields() as $field => $temp) {
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

        foreach ([
            'basic_rate',
            'basic_adjustment',
        ] as $col) {
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

        // TODO:: ecola / cola tbd
    }

    private function availableFields()
    {
        return [
            'Company'          => \App\Models\Company::orderBy('name')->get(),
            'Location'         => \App\Models\Location::orderBy('name')->get(),
            'Department'       => \App\Models\Department::orderBy('name')->get(),
            'Division'         => \App\Models\Division::orderBy('name')->get(),
            'Section'          => \App\Models\Section::orderBy('name')->get(),
            'Position'         => \App\Models\Position::orderBy('name')->get(),
            'Level'            => \App\Models\Level::orderBy('name')->get(),
            'Rank'             => \App\Models\Rank::orderBy('name')->get(),
            'DaysPerYear'      => \App\Models\DaysPerYear::orderBy('days_per_year')
                                    ->orderBy('days_per_week')
                                    ->orderBy('hours_per_day')
                                    ->get(),
            'PayBasis'         => \App\Models\PayBasis::orderBy('name')->get(),
            'PaymentMethod'    => \App\Models\PaymentMethod::orderBy('name')->get(),
            'EmploymentStatus' => \App\Models\EmploymentStatus::orderBy('name')->get(),
            'JobStatus'        => \App\Models\JobStatus::orderBy('name')->get(),
            'Grouping'         => \App\Models\Grouping::orderBy('name')->get(),
        ];
    }

    private function fetchSelect2Lists()
    {
        $data = [];
        foreach ($this->availableFields() as $field => $lists) {
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
