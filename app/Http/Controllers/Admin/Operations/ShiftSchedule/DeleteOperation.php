<?php

namespace App\Http\Controllers\Admin\Operations\ShiftSchedule;

trait DeleteOperation
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as parentDestroy; }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return string
     */
    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // check if has relationship, if yes dont allow to delete
        $empShift = modelInstance('EmployeeShiftSchedule')->whereShiftScheduleId($id)->exists();
        $changeShift = modelInstance('ChangeShiftSchedule')->whereShiftScheduleId($id)->exists();

        if ($empShift || $changeShift) {
            return;
        }

        return $this->crud->delete($id);
    }
}
