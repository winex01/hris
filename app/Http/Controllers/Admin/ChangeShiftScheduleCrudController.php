<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\EmployeeShiftScheduleCrudController;
use App\Http\Requests\ChangeShiftScheduleRequest;
use App\Models\ChangeShiftSchedule;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Str;
use Carbon\CarbonPeriod;
use Calendar;

/**
 * Class ChangeShiftScheduleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ChangeShiftScheduleCrudController extends CrudController
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
    // use \App\Http\Controllers\Admin\Traits\FilterTrait;
    use \App\Http\Controllers\Admin\Operations\CalendarOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\ChangeShiftSchedule::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/changeshiftschedule');

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
        $this->showRelationshipColumn('shift_schedule_id');

        $this->crud->modifyColumn('shift_schedule_id', [
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return url('shiftschedules/'.$entry->shift_schedule_id.'/show');
                },
                'class' => trans('lang.link_color')
            ],
        ]);
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false); // remove fk column such as: gender_id
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
        CRUD::setValidation(ChangeShiftScheduleRequest::class);
        $this->inputs();
        $this->addSelectEmployeeField();
        $this->addInlineCreateField('shift_schedule_id', 'shiftschedules', 'shift_schedules_create');
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

    public function setCalendar($id)
    {
        $calendar = new EmployeeShiftScheduleCrudController();
        $calendar = $calendar->setCalendar($id);

        $changeShift = ChangeShiftSchedule::where('employee_id', $id)->latest()->firstOrFail();
        $date = $changeShift->date;
        $event = $changeShift->shiftSchedule;

        $events[$date.'_name'] = Calendar::event(null,null,null,null,null,[
            'title' => '  A.'.$event->name, // i append space at first to make it order first
            'start' => $date,
            'end' => $date,
            'url' => url(route('shiftschedules.show', $event->id)),
            'color' => 'green'
        ]);

        //working hours
        $events[$date.'_wh'] = Calendar::event(null,null,null,null,null,[
            'title' => " B. Working Hours: \n". str_replace('<br>', "\n", $event->working_hours_as_text),
            'start' => $date,
            'end' => $date,
            'textColor' => 'black',
            'color' => 'white',
        ]);

        //overtime hours
        $title  = $event->overtime_hours_as_text == null ? 'Auto' : $event->overtime_hours_as_text;
        $events[$date.'_oh'] = Calendar::event(null,null,null,null,null,[
            'title' => " C. Overtime Hours: \n". str_replace('<br>', "\n", $title),
            'start' => $date,
            'end' => $date,
            'textColor' => 'black',
            'color' => 'white',
        ]);

        //dynamic break
        $events[$date.'_db'] = Calendar::event(null,null,null,null,null,[
            'title' => ' D. Dynamic Break: '. booleanOptions()[$event->dynamic_break],
            'start' => $date,
            'end' => $date,
            'textColor' => 'black',
            'color' => 'white',
        ]);

        //break credit
        $events[$date.'_db'] = Calendar::event(null,null,null,null,null,[
            'title' => ' E. Break Credit: '. $event->dynamic_break_credit,
            'start' => $date,
            'end' => $date,
            'textColor' => 'black',
            'color' => 'white',
        ]);

        //description
        if ($event->description != null) {
            $events[$date.'_desc'] = Calendar::event(null,null,null,null,null,[
                'title' => ' F. '. $event->description,
                'start' => $date,
                'end' => $date,
                'textColor' => 'black',
                'color' => 'white',
            ]);
        }
        
        $calendar->addEvents($events);

        return $calendar;
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
    // TODO:: fix event calendar order/arrangement of event
    // TODO:: add own calendar
    // TODO:: link date column value to own calendaar
}