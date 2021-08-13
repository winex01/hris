<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchPaymentmethodTrait
{
    public function fetchPaymentmethod()
    {
        return $this->fetch(\App\Models\PaymentMethod::class);
    }
    
}