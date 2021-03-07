<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ChangeShiftScheduleCreateRequest;
use App\Http\Requests\ChangeShiftScheduleUpdateRequest;
use App\Models\ChangeShiftSchedule;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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

        // TODO:: loop date from start to enddate
        // TODO:: check if that employee with that date if has data,
        // TODO:: if has data then update otherwise create
        // TODO:: if changeshift select is = 0 then delete it

        return [
            'empId' => $empId,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'shiftSchedId' => $shiftSchedId,
        ];
    }
}
