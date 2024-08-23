<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class OnlyDuplicate implements ValidationRule
{
    protected $table;
    protected $column;

    public function __construct($table, $column){
        $this->table = $table;
        $this->column = $column;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = DB::table($this->table)->where($this->column, $value)->exists();

        if(!$exists){
            $fail("{$this->table}->{$this->column} not exists");
        }
    }
}
