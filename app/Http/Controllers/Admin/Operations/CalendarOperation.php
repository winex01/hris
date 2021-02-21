<?php

namespace App\Http\Controllers\Admin\Operations;

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

        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? 'calendar '.$this->crud->entity_name;

        $event = \Calendar::event(
            "Valentine's Day", //event title
            true, //full day event?
            '2021-02-14', //start time, must be a DateTime object or valid DateTime format (http://bit.ly/1z7QWbg)
            '2021-02-14', //end time, must be a DateTime object or valid DateTime format (http://bit.ly/1z7QWbg),
            1, //optional event ID
            [
                'url' => 'http://full-calendar.io'
            ]
        );

        $calendar = \Calendar::addEvent($event);

        $this->data['calendar'] = $calendar;

        // load the view
        return view("crud::custom_calendar_view", $this->data);
    }
}
