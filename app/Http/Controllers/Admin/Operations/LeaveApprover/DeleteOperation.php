<?php

namespace App\Http\Controllers\Admin\Operations\LeaveApprover;


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
        $item = modelInstance('LeaveApprover')->with('leaveApplications')->findOrFail($id);

        if ($item->leaveApplications->count() >= 1) {
            return;
        }
        
        return $this->crud->delete($id);
    }
}
