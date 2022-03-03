<?php

namespace App\Http\Controllers\Admin\Operations\LeaveApplication;

use App\Models\LeaveCredit;
use App\Models\LeaveApplication;

trait StatusOperation
{
    use \App\Http\Controllers\Admin\Operations\StatusOperation { status as parentStatus; }

    /**
     * Show the view for performing the operation.
     * 
     * override from StatusOperation
     *
     * @return Response
     */
    public function status($id)
    {
        $this->crud->hasAccessOrFail('status');
        
        $id = $this->crud->getCurrentEntryId() ?? $id; // leaveAppId
        $newLeaveAppStatus = request()->status;

        // VALIDATE:: only accept this 3 values
        if (!in_array($newLeaveAppStatus, [0,1,2])) { // pending, approved, denied
            return;
        }

        // debug(request()->all());

        $leaveApp = modelInstance('LeaveApplication')->findOrFail($id);
        $leaveCredit = modelInstance('LeaveCredit')
                ->where('employee_id', $leaveApp->employee_id)
                ->where('leave_type_id', $leaveApp->leave_type_id)
                ->firstOrFail();

        $newLeaveCredit = $this->validateIfHasLeaveCredit($leaveApp, $leaveCredit);

        // VALIDATE:: duplicate leave approved on this date
        $duplicate = modelInstance('LeaveApplication')
                    ->where('employee_id', $leaveApp->employee_id)
                    ->where('date', $leaveApp->date)
                    ->approved()
                    ->first();
        
        if ($newLeaveAppStatus == 1 && $duplicate) { // duplicate entry for same date 
            $result['validationFail'] = true;
            $result['validationMsgText'] = trans('lang.leave_applications_employee_unique'); 
            return $result;
        }

        // VALIDATE:: if employee leave credit is less than 0
        if ($newLeaveCredit < 0) {
            $result['validationFail'] = true;
            $result['validationMsgText'] = trans('lang.leave_applications_leave_credits_required'); 
            return $result;
        }

        //validation success
        $leaveApp->status = $newLeaveAppStatus;
        $leaveApp->save();

        // if the same leave credit, meaning the employee didn't change it's status from prev. value
        // this is only to make sure if ever the frontent button hide/button is breach
        if ($newLeaveCredit != $leaveCredit->leave_credit) {
            $leaveCredit->leave_credit = $newLeaveCredit;
            $leaveCredit->save();
        }

        return true;
    }

    private function validateIfHasLeaveCredit(LeaveApplication $leaveApp, LeaveCredit $leaveCredit)
    {
        $currentLeaveAppStatus = $leaveApp->status;
        $newLeaveCredit = $leaveCredit->leave_credit;
        $newLeaveAppStatus = request()->status;

        if ($newLeaveAppStatus == 1) { // approved
            if ($currentLeaveAppStatus != 1) { // if currentLeaveAppStatus is not approved
                // then deduct employee leave credit regardless of currentLeaveAppStatus value
                $newLeaveCredit = $leaveCredit->leave_credit - $leaveApp->credit_unit;
            }                    
        }elseif ($newLeaveAppStatus == 2) { // denied
            if ($currentLeaveAppStatus == 1) { // if currentLeaveAppStatus is approved
                // then add employee leave credit
                $newLeaveCredit = $leaveCredit->leave_credit + $leaveApp->credit_unit;
            }
        }else {
            // do nothing :)
        }

        return $newLeaveCredit;
    }
}
