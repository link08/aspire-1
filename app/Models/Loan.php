<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'amount_paid',
        'loan_term',
        'interest_rate',
        'repayment_type',
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
        'user_id'       => 'integer',
        'amount'        => 'integer',
        'amount_paid'   => 'integer',
        'loan_term'     => 'integer',
        'interest_rate' => 'float',
    ];

    // Define loan's relationship with user
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    // Define loan's relationship with EmiTransaction
    public function emiTransactions()
    {
        return $this->hasMany('App\Model\EmiTransaction', 'loan_id');
    }

}
