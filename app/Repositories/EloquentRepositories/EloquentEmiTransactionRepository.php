<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\EmiTransaction;
use App\Repositories\EmiTransactionRepositoryInterface;
use App\Services\EmiService\EmiGenerationService;
use App\Services\EmiService\EmiTransactionService;
use Auth;

class EloquentEmiTransactionRepository implements EmiTransactionRepositoryInterface
{
    // Dependencies
    private $emiGenerationService;
    private $emiTransactionService;

    public function __construct(
        EmiGenerationService $emiGenerationService,
        EmiTransactionService $emiTransactionService
    ) {
        $this->emiGenerationService = $emiGenerationService;
        $this->emiTransactionService = $emiTransactionService;
    }

    /**
     * Create & save a EmiTransaction Model
     *
     * @return null
     */
    public function create($loanId)
    {
        // If EMI is already generate, return it
        $queryOptions = [
            'where' => ['status' => 'GENERATED']
        ];
        $emiTransaction = $this->getAll($loanId, $queryOptions);

        if(count($emiTransaction)) {
            return $emiTransaction;
        }

        // Generate EmiTransaction and save it
        $emiTransaction = $this->emiGenerationService->generateEmiForLoan($loanId);

        return $emiTransaction;
    }

    /**
     * Get All EmiTransaction
     *
     * @param string $loanId
     * @param array  $filters
     * @param bool   $toArray
     * @return mixed
     */
    public function getAll($loanId, $options = [], $toArray = false)
    {
        $emiTransaction = EmiTransaction::where('loan_id', $loanId)
            ->where('user_id', Auth::user()->id)
            ->where(function($query) use($options) {
            if(isset($options['where'])) {
                foreach ($options['where'] as $key => $value) {
                    $query->where($key, '=', $value);
                }
            }

            if(isset($options['orWhere'])) {
                foreach ($options['orWhere'] as $key => $value) {
                    $query->orWhere($key, '=', $value);
                }
            }
        })->get();

        if($toArray) {
            $emiTransaction = $emiTransaction->toArray();
        }

        return $emiTransaction;
    }

    /**
     * Get EmiTransaction based on $id
     *
     * @param string $id
     * @return \App\Models\EmiTransaction
     */
    public function getById($id, $with=[])
    {
        $emiTransaction = EmiTransaction::where('user_id', Auth::user()->id)->find($id);

        return $emiTransaction;
    }

    /**
     * Update EmiTransaction for the given $id
     *
     * @param string $id
     * @return mixed
     */
    public function updateById($id, $emiTransactionAttributes)
    {
        // Implement when required
        // Fire webhook events for loan updates
    }

    /**
     * EMI Payment method
     *
     * @param  string  $emiTransactionId
     * @param  string  $loanId
     * @return \Illuminate\Http\Response
     */
    public function payment($emiTransactionId, $loanId)
    {
        $queryOptions = [
            'where' => [
                'loan_id'   => $loanId,
                'id'        => $emiTransactionId
            ]
        ];
        $emiTransaction = $this->getAll($loanId, $queryOptions);

        if(count($emiTransaction) && $emiTransaction[0]->status == "GENERATED") {
            // Perform payment transaction for this EMI
            $this->emiTransactionService->makeSecurePayment($emiTransaction[0]);

            // Get update model of EMI from DB
            $emiTransaction = $this->getById($emiTransactionId);
        }

        return $emiTransaction;
    }
}
