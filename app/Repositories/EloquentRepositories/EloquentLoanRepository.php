<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\Loan;
use App\Repositories\LoanRepositoryInterface;
use Auth;

class EloquentLoanRepository implements LoanRepositoryInterface
{
    /**
     * Create a Loan Model
     *
     * @param array $loanAttributes
     * @return null
     */
    public function create($loanAttributes)
    {
        // Create a new loan model and save it
        $loan = new Loan;
        $loan->amount           = $loanAttributes['amount'];
        $loan->amount_paid      = 0;
        $loan->loan_term        = $loanAttributes['loan_term'];
        $loan->repayment_type   = $loanAttributes['repayment_type'];
        $loan->interest_rate    = config('app.loan_interest_rate');
        $loan->status           = 'PENDING';
        $loan->user()->associate(Auth::user());

        if($loan->save()) {
            return $loan->toArray();
        }
    }

    /**
     * Get All Loans for authorized user
     *
     * @param array $with
     * @param bool   $toArray
     * @return mixed
     */
    public function getAll($with = [], $toArray = false)
    {
        $loans = Loan::where('user_id', Auth::user()->id)->get();
        if($toArray) {
            $loans = $loans->toArray();
        }

        return $loans;
    }

    /**
     * Get Loan based on $id
     *
     * @param string $id
     * @param bool   $toArray
     * @return \App\Models\Loan
     */
    public function getById($id, $with=[], $toArray = false)
    {
        $loan = Loan::where('user_id', Auth::user()->id)->find($id);
        if($toArray) {
            $loan = $loan->toArray();
        }

        return $loan;
    }

    /**
     * Update loan for the given $id
     *
     * @param string $id
     * @return mixed
     */
    public function updateById($id, $loanAttributes)
    {
        return Loan::where('id', $id)->update($loanAttributes);
        // Fire webhook events for loan updates
    }
}
