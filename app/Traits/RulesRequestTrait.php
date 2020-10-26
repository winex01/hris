<?php 

namespace App\Traits;

use Illuminate\Validation\Rule;

/**
 * summary
 */
trait RulesRequestTrait
{
    /**
     * summary
     */
    public function uniqueRules($table, $requestInput = 'id')
    {
        return Rule::unique($table)->ignore(
        	request($requestInput)
        );
    }
}