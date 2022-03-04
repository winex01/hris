<?php

namespace App\Http\Controllers\Admin\Operations\LeaveApplication;

trait ForceBulkDeleteOperation
{
    use \App\Http\Controllers\Admin\Operations\ForceBulkDeleteOperation { forceBulkDelete as parentForceBulkDelete; }

    public function forceBulkDelete()
    {
        $this->crud->hasAccessOrFail('forceBulkDelete');

        $entries = request()->input('entries');
        $returnEntries = [];

        foreach ($entries as $key => $id) {
            if ($entry = $this->crud->model::findOrFail($id)) {
                if ($entry->status == 0) { // allow only delete if status is pending
                    $returnEntries[] = $entry->forceDelete();
                }else {
                    $returnEntries = false;
                }
            }
        }

        return $returnEntries;
    }
}
