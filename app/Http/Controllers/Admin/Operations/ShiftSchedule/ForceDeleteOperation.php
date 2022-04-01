<?php

namespace App\Http\Controllers\Admin\Operations\ShiftSchedule;


trait ForceDeleteOperation
{
    use \App\Http\Controllers\Admin\Operations\ForceDeleteOperation { forceDelete as parentForceDelete; }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return string
     */
    public function forceDelete($id)
    {
        $this->crud->hasAccessOrFail('forceDelete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // check if has relationship, if yes dont allow to delete
        $empShift = modelInstance('EmployeeShiftSchedule')->whereShiftScheduleId($id)->exists();
        $changeShift = modelInstance('ChangeShiftSchedule')->whereShiftScheduleId($id)->exists();

        if ($empShift || $changeShift) {
            return;
        }

        return $this->crud->model::findOrFail($id)->forceDelete();
    }
}
