<?php

namespace App\Http\Controllers\Admin\Operations\ShiftSchedule;


trait ForceBulkDeleteOperation
{
    use \App\Http\Controllers\Admin\Operations\ForceBulkDeleteOperation { forceBulkDelete as parentForceBulkDelete; }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function forceBulkDelete()
    {
        $this->crud->hasAccessOrFail('forceBulkDelete');

        $entries = request()->input('entries');
        $deletedEntries = [];

        $someError = false;
        foreach ($entries as $key => $id) {
            if ($entry = $this->crud->model::findOrFail($id)) {
                 // check if has relationship, if yes dont allow to delete
                 $empShift = modelInstance('EmployeeShiftSchedule')->whereShiftScheduleId($id)->exists();
                 $changeShift = modelInstance('ChangeShiftSchedule')->whereShiftScheduleId($id)->exists();
         
                 if ($empShift || $changeShift) {
                     $someError = true;
                 }else {
                     $deletedEntries[] = $entry->forceDelete();
                 }
            }
        }

        if ($someError) {
            return;
        }

        return $deletedEntries;
    }
}
