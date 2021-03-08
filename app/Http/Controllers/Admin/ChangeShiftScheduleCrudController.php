<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ChangeShiftScheduleCreateRequest;
use App\Http\Requests\ChangeShiftScheduleUpdateRequest;
use App\Models\ChangeShiftSchedule;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
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
        CRUD::setValidation(ChangeShiftScheduleCreateRequest::class);
        $this->customInputs();
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(ChangeShiftScheduleUpdateRequest::class);
        $this->customInputs();
    }

    private function customInputs()
    {
        $this->inputs();
        $this->addSelectEmployeeField();
        $this->addInlineCreateField('shift_schedule_id', 'shiftschedules', 'shift_schedules_create');
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

    /*
    |--------------------------------------------------------------------------
    | Use in calendar operation
    |--------------------------------------------------------------------------
    */
    protected function setupChangeShiftScheduleRoutes($segment, $routeName, $controller) {
        \Route::post($segment.'/change-shift', [
            'as'        => $routeName.'.changeShift',
            'uses'      => $controller.'@changeShift',
        ]);
    }

    public function changeShift()
    {
        $this->crud->hasAccessOrFail('calendar');

        $empId = request('empId');
        $startDate = request('startDate');
        $endDate = subDaysToDate(request('endDate'));
        $shiftSchedId = request('shiftSchedId');
        
        $dateChanges = [];
        $events = [];
        // loop date from start to enddate
        $dateRange = CarbonPeriod::create($startDate, $endDate);
        foreach ($dateRange as $date) {
            $date = $date->format('Y-m-d');
            $calendarId = $date.'-change-shift';
            $dateChanges[] = $calendarId;

            // if changeshift select2 null then delete it to remove change shift sched
            if ($shiftSchedId == null) {
                ChangeShiftSchedule::where('employee_id', $empId)->where('date', $date)->delete();
            }else {
                // update or create
                $changeShift = ChangeShiftSchedule::updateOrCreate(
                    ['employee_id' => $empId, 'date' => $date], // where
                    ['shift_schedule_id' => $shiftSchedId] // update or create this value
                );

                $event = $changeShift->shiftSchedule;

                // append 2 space for every title to indicate change shift from calendar
                $events[] = [
                    'id' => $calendarId, 
                    'title' => '  • '.$event->name,
                    'start' => $date,
                    'end' => $date,
                    'url' => url(route('shiftschedules.show', $event->id)),
                    'color' => config('hris.legend_success')
                ];

                //working hours
                $events[] = [
                    'id' => $calendarId, 
                    'title' => "  1. Working Hours: \n". str_replace('<br>', "\n", $event->working_hours_as_text), // append 1 space
                    'start' => $date,
                    'end' => $date,
                    'textColor' => 'black',
                    'color' => $this->eventBgColor($date)
                ];

                //overtime hours
                $events[] = [
                    'id' => $calendarId, 
                    'title' => "  2. Overtime Hours: \n". str_replace('<br>', "\n", $event->overtime_hours_as_text),
                    'start' => $date,
                    'end' => $date,
                    'textColor' => 'black',
                    'color' => $this->eventBgColor($date)
                ];

                //dynamic break
                $events[] = [
                    'id' => $calendarId, 
                    'title' => '  3. Dynamic Break: '. booleanOptions()[$event->dynamic_break],
                    'start' => $date,
                    'end' => $date,
                    'textColor' => 'black',
                    'color' => $this->eventBgColor($date)
                ];

                // break credit
                $events[] = [
                    'id' => $calendarId, 
                    'title' => '  4. Break Credit: '. $event->dynamic_break_credit,
                    'start' => $date,
                    'end' => $date,
                    'textColor' => 'black',
                    'color' => $this->eventBgColor($date)
                ];

                //description
                if ($event->description != null) {
                    $events[] = [
                        'id' => $calendarId, 
                        'title' => '  5. '. $event->description,
                        'start' => $date,
                        'end' => $date,
                        'textColor' => 'black',
                        'color' => $this->eventBgColor($date)
                    ];
                }
            }

        }

        // TODO:: add new event and see https://stackoverflow.com/questions/52889433/laravel-fullcalendar-refresh-events-from-database-without-reloading-the-page
        return compact('events', 'dateChanges');
    }
}
