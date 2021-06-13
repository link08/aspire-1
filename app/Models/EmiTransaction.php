<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmiTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'emi',
        'transaction_id',
        'payment_mode',
        'status',
    ];

    // Update timestamps
    public $timestamps = true;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'        => 'integer',
        'user_id'   => 'integer',
        'loan_id'   => 'integer',
        'emi'       => 'float',
    ];

    // Define EmiTransaction's relationship with user
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    // Define EmiTransaction's relationship with loan
    public function loan()
    {
        return $this->belongsTo('App\Models\Loan', 'loan_id');
    }
}
