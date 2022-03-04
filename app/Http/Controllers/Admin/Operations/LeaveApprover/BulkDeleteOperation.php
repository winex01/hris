<?php

namespace App\Http\Controllers\Admin\Operations\LeaveApprover;

trait BulkDeleteOperation
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation { bulkDelete as parentBulkDelete; }

    /**
     * Delete multiple entries in one go.
     *
     * @return string
     */
    public function bulkDelete()
    {
        $this->crud->hasAccessOrFail('bulkDelete');

        $entries = request()->input('entries', []);
        $deletedEntries = [];

        $someError = false;
        foreach ($entries as $key => $id) {
            if ($entry = $this->crud->model->find($id)) {
                // check if has relationship, if yes dont allow to delete
                $item = modelInstance('LeaveApprover')->with('leaveApplications')->findOrFail($id);
                
                if ($item->leaveApplications->count() >= 1) {
                    $someError = true;
                }else {
                    $deletedEntries[] = $entry->delete();
                }
                
            }
        }

        
        if ($someError) {
            return;
        }

        return $deletedEntries;
    }
}
