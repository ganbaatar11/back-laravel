<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaidEntryFee extends Model
{
    //
    protected $table = 'paid_entry_fees';
    public $primaryKey  = 'pef_id';
    protected $hidden = ['created_at','updated_at'];
}
