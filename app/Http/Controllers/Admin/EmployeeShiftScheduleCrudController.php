<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmployeeShiftScheduleRequest;
use App\Models\EmployeeShiftSchedule;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class EmployeeShiftScheduleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EmployeeShiftScheduleCrudController extends CrudController
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
    use \App\Http\Controllers\Admin\Operations\CalendarOperation;
    
    public function __construct()
    {
        parent::__construct();

        // use this export class instead of BaseExport
        $this->exportClass = '\App\Exports\EmployeeShiftScheduleExport';
    }
    
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\EmployeeShiftSchedule::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/employeeshiftschedule');

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
        $this->showEmployeeNameColumn();

        $daysOfWeek = collect($this->daysOfWeek())->map(function ($item, $key) {
            $item = str_replace('_id', '', $item);  
            return $item;
        })->toArray();

        foreach ($this->daysOfWeek() as $day) {
            $this->showRelationshipColumn($day);
            // modify to fix daysOfWeek table name relationship so it could be sortable by relationship name not by ID
            $this->crud->modifyColumn($day, [
                'orderLogic' => function ($query, $column, $columnDirection) use ($day, $daysOfWeek) {
                    $currentTable = $this->crud->model->getTable();
                    $col = str_replace('_id', '', $day);
                    $relationshipColumn = 'name';

                    if (in_array($col, $daysOfWeek)) {
                        $table = 'shift_schedules';
                    }else {
                        $table = classInstance(convertToClassName($col))->getTable();
                    }

                    return $query->leftJoin($table, $table.'.id', '=', $currentTable.'.'.$col.'_id')
                        ->orderBy($table.'.'.$relationshipColumn, $columnDirection)
                        ->select($currentTable.'.*');
                }
            ]);
        }

        $this->removeGlobalScopeFilter('CurrentEmployeeShiftScheduleScope');
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
        CRUD::setValidation(EmployeeShiftScheduleRequest::class);
        $this->inputs();
        $this->addSelectEmployeeField();
        foreach ($this->daysOfWeek() as $day) {
            $this->addInlineCreateField($day, 'shiftschedules', 'shift_schedules_create');
            $this->crud->modifyField($day, [
                'model'       => 'App\Models\ShiftSchedule',
                'entity'      => relationshipMethodName($day),
                'data_source' => backpack_url($this->crud->route."/fetch/shift-schedule"),
            ]);
        }
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function daysOfWeek()
    {
        return [
            'sunday_id',
            'saturday_id',
            'friday_id',
            'thursday_id',
            'wednesday_id',
            'tuesday_id',
            'monday_id',
        ];
    }

    /**
     * NOTE:: instead of update, i store new items 
     *
     * @return Response
     */
    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        
        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }


    public function setCalendar($id)
    {
        $employeeShifts = EmployeeShiftSchedule::withoutGlobalScope(
            scopeInstance('CurrentEmployeeShiftScheduleScope')
        )->where('employee_id', $id)
        ->orderBy('effectivity_date', 'asc')->get();

        if ($employeeShifts->count() <= 0) {
            return;
        }

        // dd($employeeShifts);
        // TODO:: declare event shifts
        $events = [];
        $events[] = \Calendar::event(null,null,null,null,null,[
            'groupId' => '1',
            'title' => '08:30AM - 5:30PM',
            'start' => null,
            'end' => null,
            'dow' => [ 1,2,3,4,5 ],
            'textColor' => 'white',
            // 'url' => 'javascript:void(0)', // TODO:: redirect to Shift sched. if this is not change sched.
        ]);

        return \Calendar::addEvents($events)
            ->setOptions([
                'header' => [
                    'left' => 'prev,next today',
                    'center' => 'title',
                    'right' => 'month,agendaWeek',
                ],
                'eventLimit' => true,   
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Inline Create Fetch
    |--------------------------------------------------------------------------
    */
    public function fetchShiftSchedule()
    {
        return $this->fetch(\App\Models\ShiftSchedule::class);
    }

    // TODO:: declare event change shift for specific date
    // TODO:: declare event holiday 
    // TODO:: fix error delete/force delete
    // TODO:: caps first letter on calendar buttons
}
