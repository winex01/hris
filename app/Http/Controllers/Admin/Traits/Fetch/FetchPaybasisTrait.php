<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchPaybasisTrait
{
    /*
    |--------------------------------------------------------------------------
    | Fetch Inline Create Data
    | NOTE:: I intentionaly ucfirst all function after the word fetch to match entity from 
    | crud bec. if i name the function like this fetchPayBasis it would produce 
    | fetch/pay-basis, since i dont want to alter too much in custom_inline_create.blade.php
    | to fix it, i use lowercase to transform route fetch, ex. fetchPaybasis = fetch/paybasis
    | which match to entity crud of pay basis. 
    |--------------------------------------------------------------------------
    */
    public function fetchPaybasis()
    {
        return $this->fetch(\App\Models\PayBasis::class);
    }
    
}