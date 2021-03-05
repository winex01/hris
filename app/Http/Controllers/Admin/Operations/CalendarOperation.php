<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\ChangeShiftSchedule;
use App\Models\EmployeeShiftSchedule;
use Calendar;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Route;

trait CalendarOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupCalendarRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/calendar', [
            'as'        => $routeName.'.calendar',
            'uses'      => $controller.'@calendar',
            'operation' => 'calendar',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupCalendarDefaults()
    {
        $this->crud->allowAccess('calendar');

        $this->crud->operation('calendar', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
             $this->crud->addButtonFromView('line', 'calendar', 'custom_calendar', 'beginning');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function calendar($id)
    {
        $this->crud->hasAccessOrFail('calendar');

        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->data['id'] = $id;
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? 'calendar '.$this->crud->entity_name;
        $this->data['calendar'] = $this->setCalendar($id);

        $this->data['employees'] = employeeLists();

        // var is use in crud/inc/custom_printData.blade.php
        $this->data['contentClass'] = 'col-md-12';

        // modals
        $this->data['modalLists'] = $this->modalLists();

        // load the view
        return view("crud::custom_calendar_view", $this->data);
    }

    public function setCalendar($id)
    {
        return Calendar::setOptions(defaultFullCalendarOptions(['selectable' => true]))
            ->addEvents($this->employeeShiftEvents($id))
            ->addEvents($this->changeShiftEvents($id)) 
            ->setCallbacks($this->setCalendarCallbacks($id));
        // TODO:: holiday events
        // TODO:: less priority, multiple click event by holding CTRL or shift functionality
    }

    private function setCalendarCallbacks($id)
    {
        $shiftSchdules = classInstance('ShiftSchedule')->orderBy('name')->select('id', 'name')->get();

        $options = '';
        foreach ($shiftSchdules as $shift) {
            $options .= '<option value="'.$shift->id.'">'.$shift->name.'</option>';
        }

        return [
            'select' => "function(startDate, endDate) {
                var startDate = startDate.format();
                var endDate = endDate.format();

                (async () => {
                    const {value: temp} = await swal.fire({
                        title: 'Change Shift Schedule:',
                        html: 
                        '<select id=\"change-shift-select2\"> class=\"col-md-12\"' +
                          '<option value=\"0\">".trans('lang.select_placeholder')."</option>' +
                          '".$options."' +
                        '</select>',
                        confirmButtonText: 'Save',
                        showCancelButton: true,
                        didOpen: function () {
                            $('#change-shift-select2').select2({
                                width: '70%',
                            });        
                        },
                    })

                    if (temp) {
                        // TODO: here
                        $.ajax({
                            url: '".url(route('changeshiftschedule.changeShift'))."', // 
                            success: function (data) {
                                if (data) {
                                    console.log(data);
                                    new Noty({
                                        type: 'success',
                                        text: '".trans('backpack::crud.update_success')."'
                                    }).show();
                                }
                            }
                        });
                    }
                })()
            }",
        ];
    }

    private function  employeeShiftEvents($id)
    {
        $employeeShifts = EmployeeShiftSchedule::withoutGlobalScope(
            scopeInstance('CurrentEmployeeShiftScheduleScope')
        )->where('employee_id', $id)
        ->orderBy('effectivity_date', 'asc')->get();

        if ($employeeShifts->count() <= 0) {
            return;
        }

        $events = [];
        $i = 1;
        foreach ($employeeShifts as $empShift) {
            $start = $empShift->effectivity_date;

            if ($i != $employeeShifts->count()) {
                $end = subDaysToDate($employeeShifts[($i)]->effectivity_date);
            }else {
                // last loop
                $end = addMonthsToDate(currentDate(), 12); // add 1 year
            }

            $dateRange = CarbonPeriod::create($start, $end);
            foreach ($dateRange as $date) {
                $date = $date->format('Y-m-d');

                $event = $empShift->{daysOfWeek()[getWeekday($date)]};
                if ($event != null) {
                    $events[$date.'_name'] = Calendar::event(null,null,null,null,null,[
                        'title' => '• '.$event->name, // i append space at first to make it order first
                        'start' => $date,
                        'end' => $date,
                        'url' => url(route('shiftschedules.show', $event->id))
                    ]);

                    $color = date('Y-m-d') == $date ? '#fbf7e3' : 'white';
                    //working hours
                    $events[$date.'_wh'] = Calendar::event(null,null,null,null,null,[
                        'title' => "1. Working Hours: \n". str_replace('<br>', "\n", $event->working_hours_as_text),
                        'start' => $date,
                        'end' => $date,
                        'textColor' => 'black',
                        'color' => $color
                    ]);

                    //overtime hours
                    $events[$date.'_oh'] = Calendar::event(null,null,null,null,null,[
                        'title' => "2. Overtime Hours: \n". str_replace('<br>', "\n", $event->overtime_hours_as_text),
                        'start' => $date,
                        'end' => $date,
                        'textColor' => 'black',
                        'color' => $color
                    ]);

                    //dynamic break
                    $events[$date.'_db'] = Calendar::event(null,null,null,null,null,[
                        'title' => '3. Dynamic Break: '. booleanOptions()[$event->dynamic_break],
                        'start' => $date,
                        'end' => $date,
                        'textColor' => 'black',
                        'color' => $color
                    ]);

                    //break credit
                    $events[$date.'_bc'] = Calendar::event(null,null,null,null,null,[
                        'title' => '4. Break Credit: '. $event->dynamic_break_credit,
                        'start' => $date,
                        'end' => $date,
                        'textColor' => 'black',
                        'color' => $color
                    ]);


                    //description
                    if ($event->description != null) {
                        $events[$date.'_desc'] = Calendar::event(null,null,null,null,null,[
                            'title' => '5. '. $event->description,
                            'start' => $date,
                            'end' => $date,
                            'textColor' => 'black',
                            'color' => $color
                        ]);
                    }
                }

            }

            $i++;
        }
        return $events;
    }

     private function changeShiftEvents($id)
    {
        $events = [];
        $changeShiftSchedules = ChangeShiftSchedule::where('employee_id', $id)->get();

        if ($changeShiftSchedules == null) {
            return $events;
        }

        foreach ($changeShiftSchedules as $changeShift) {
            $date = $changeShift->date;
            $event = $changeShift->shiftSchedule;

            $events[$date.'_name'] = Calendar::event(null,null,null,null,null,[
                'title' => '  • '.$event->name, // i append space at first to make it order first
                'start' => $date,
                'end' => $date,
                'url' => url(route('shiftschedules.show', $event->id)),
                'color' => config('hris.legend_success')
            ]);

            $color = date('Y-m-d') == $date ? '#fbf7e3' : 'white';
            //working hours
            $events[$date.'_wh'] = Calendar::event(null,null,null,null,null,[
                'title' => " 1. Working Hours: \n". str_replace('<br>', "\n", $event->working_hours_as_text),
                'start' => $date,
                'end' => $date,
                'textColor' => 'black',
                'color' => $color
            ]);

            //overtime hours
            $events[$date.'_oh'] = Calendar::event(null,null,null,null,null,[
                'title' => " 2. Overtime Hours: \n". str_replace('<br>', "\n", $event->overtime_hours_as_text),
                'start' => $date,
                'end' => $date,
                'textColor' => 'black',
                'color' => $color
            ]);

            //dynamic break
            $events[$date.'_db'] = Calendar::event(null,null,null,null,null,[
                'title' => ' 3. Dynamic Break: '. booleanOptions()[$event->dynamic_break],
                'start' => $date,
                'end' => $date,
                'textColor' => 'black',
                'color' => $color
            ]);

            //break credit
            $events[$date.'_bc'] = Calendar::event(null,null,null,null,null,[
                'title' => ' 4. Break Credit: '. $event->dynamic_break_credit,
                'start' => $date,
                'end' => $date,
                'textColor' => 'black',
                'color' => $color
            ]);

            //description
            if ($event->description != null) {
                $events[$date.'_desc'] = Calendar::event(null,null,null,null,null,[
                    'title' => ' 5. '. $event->description,
                    'start' => $date,
                    'end' => $date,
                    'textColor' => 'black',
                    'color' => $color
                ]);
            }
        }
        return $events;
    }

    private function modalLists()
    {
        return [];
    }
}
