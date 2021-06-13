<?php

namespace App\Services\EmiService;

use App\Repositories\LoanRepositoryInterface;
use App\Models\EmiTransaction;
use DB;

class EmiTransactionService
{
	public function __construct(LoanRepositoryInterface $loanRepository)
	{
        $this->loanRepository = $loanRepository;
    }

    /**
     * Pay EMI installment for given EmiTransaction
     *
     * @param  \App\Models\EmiTransaction $emiTransaction
     * @return \App\Models\EmiTransaction
     */
	public function makeSecurePayment($emiTransaction) {
		// Custom logic of verification here
		// Custom logic of payment transaction here

		DB::transaction(function() use ($emiTransaction) {

			// Verification transactions here
			// Make payment transactions here

			$loan = $this->loanRepository->getById($emiTransaction->loan_id);
			$loan->amount_paid += $emiTransaction->emi;

			// If all EMI's are paied, then close this loan
			$amountWithInterest = $loan->amount + ($loan->amount * $loan->interest_rate / 100);

			if($loan->amount_paid >= $amountWithInterest) {
				$loan->status = "CLOSED";
			}

			$loan->save();

			$emiTransaction->status = "TRANSACTION_SUCCESS";
			$emiTransaction->transaction_id = uniqid();
			$emiTransaction->save();
		});
	}
}