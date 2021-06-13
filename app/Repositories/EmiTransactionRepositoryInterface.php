<?php

namespace App\Repositories;

interface EmiTransactionRepositoryInterface
{
    /**
     * Create a EmiTransaction Model
     *
     * @param string $loanId
     * @return \App\Models\EmiTransaction
     */
    public function create($loanId);

    /**
     * Get All EmiTransactions for loanId
     *
     * @param string $loanId
     * @return mixed
     */
    public function getAll($loanId);

    /**
     * Get EmiTransaction based on $id
     *
     * @param string $id
     * @param array $with
     * @return \App\Models\EmiTransaction
     */
    public function getById($id, array $with);

    /**
     * Update EmiTransaction for the given $id
     *
     * @param string $id
     * @param array $emiTransactionAttributes
     * @return mixed
     */
    public function updateById($id, array $emiTransactionAttributes);
}
