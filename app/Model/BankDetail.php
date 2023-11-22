<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class BankDetail extends Model
{
    protected $fillable = [
        'user_id',
        'account_number',
        'account_holder',
        'bank_name',
        'ifsc_code',
        'upi_id',
        'upi_number',
    ];
}
?>
