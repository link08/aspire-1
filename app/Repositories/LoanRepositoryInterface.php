<?php

namespace App\Repositories;

interface LoanRepositoryInterface
{
    /**
     * Create a Loan Model
     *
     * @param array $loanAttributes
     * @return \App\Models\Loan
     */
    public function create(array $loanAttributes);

    /**
     * Get All Loans
     *
     * @param array $with
     * @return mixed
     */
    public function getAll(array $with);

    /**
     * Get Loan based on $id
     *
     * @param string $id
     * @param array $with
     * @return \App\Models\Loan
     */
    public function getById($id, array $with);

    /**
     * Update loan for the given $id
     *
     * @param string $id
     * @param array $loanAttributes
     * @return mixed
     */
    public function updateById($id, array $loanAttributes);
}
