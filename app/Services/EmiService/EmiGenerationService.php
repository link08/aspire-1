<?php

namespace App\Services\EmiService;

use App\Repositories\LoanRepositoryInterface;
use App\Models\EmiTransaction;
use Auth;

class EmiGenerationService
{
	// Dependencies
    private $loanRepository;

    // Repayment type vs days map
    private $days = [
    	'WEEKLY'	=> 7,
    	'MONTHLY'	=> 30,
    	'YEARLY'	=> 365
    ];

	public function __construct(LoanRepositoryInterface $loanRepository)
	{
        $this->loanRepository = $loanRepository;
    }

    /**
     * Generates EMI installment for given loanId
     *
     * @param  string   $loanId
     * @return \App\Models\EmiTransaction
     */
	public function generateEmiForLoan($loanId)
	{
		$loan = $this->loanRepository->getById($loanId, false);

		// Proceed only if loan is not settled/closed
		if($this->isLoanClosed($loan)) {
			return 0;	// Indicating loan is in closed status
		}

		// Proceed only if loan is in approved status
		if(! $this->isLoanApproved($loan)) {
			return 1;	// Indicating loan is not in approved status
		}

		// Generate EmiTransaction modal
		$emiTransaction = new EmiTransaction;
		$emiTransaction->emi = $this->calculateEmi($loan);
		$emiTransaction->status = "GENERATED";  // Create EMI in GENERATED status
		$emiTransaction->user_id = Auth::user()->id;
		$emiTransaction->loan_id = $loan->id;
		$emiTransaction->transaction_id = null;
		$emiTransaction->payment_mode = 'ONLINE';
		$emiTransaction->save();
		return $emiTransaction->toArray();
	}

	/**
     * Checks if loan is in closed status
     *
     * @param  App\Model\Loan $loan
     * @return boolean
     */
	private function isLoanClosed($loan)
	{
		if($loan->status == "CLOSED") {
			return true;
		}

		return false;
	}

	/**
     * Checks if loan is in approved status
     *
     * @param  App\Model\Loan $loan
     * @return boolean
     */
	private function isLoanApproved($loan)
	{
		if($loan->status == "APPROVED") {
			return true;
		}

		return false;
	}

	/**
     * Calculates EMI installment for given loan
     *
     * @param  App\Model\Loan $loan
     * @return double 	$emi
     */
	private function calculateEmi($loan)
	{
		// Customized formulae for calculating yearly EMI
		$amountWithInterest = $loan->amount + ($loan->amount * $loan->interest_rate / 100);
		$amountPending = $amountWithInterest - $loan->amount_paid;
		$emi = $amountWithInterest / ($loan->loan_term / $this->days[$loan->repayment_type]);
		if($emi > $amountWithInterest - $loan->amount_paid) {
			$emi = $amountWithInterest - $loan->amount_paid;
		}

		return $emi;
	}
}