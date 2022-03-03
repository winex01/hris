<?php

namespace App\Http\Controllers\Admin\Operations\LeaveApplication;

use Illuminate\Support\Facades\Route;

trait BulkDeleteOperation
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation { bulkDelete as parentBulkDelete; }

    /**
     * Delete multiple entries in one go.
     * Prohibit user from deleting items if status != pending
     * 
     * @return string
     */
    public function bulkDelete()
    {
        $this->crud->hasAccessOrFail('bulkDelete');

        $entries = request()->input('entries', []);
        $deletedEntries = [];

        foreach ($entries as $key => $id) {
            if ($entry = $this->crud->model->find($id)) {
                if ($entry->status == 0) { // allow only delete if status is pending
                    $deletedEntries[] = $entry->delete();
                }else {
                    $deletedEntries = false;
                }
            }
        }

        return $deletedEntries;
    }

}
