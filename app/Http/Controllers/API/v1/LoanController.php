<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Repositories\LoanRepositoryInterface;
use App\Utility\APIResponse;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    // Dependency
    private $loanRepository;

    private $resource = "loans";

    public function __construct(LoanRepositoryInterface $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    /**
     * Display all loans
     *
     * @return array $loans
     */
    public function index()
    {
        $loans = $this->loanRepository->getAll([], true);
        return APIResponse::transform($this->resource, $loans);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the input
        $loan = $request->validate([
            'amount'            => 'required|integer|min:1',
            'loan_term'         => 'required|integer|min:7',
            'repayment_type'    => 'required|in:WEEKLY,MONTHLY,ANNUALLY'
        ]);

        $loan = $this->loanRepository->create($loan);
        return APIResponse::transform($this->resource, $loan);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loan = $this->loanRepository->getById($id, true);
        return APIResponse::transform($this->resource, $loan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $loanAttributes = $request->validate([
            'status'    => 'required|in:APPROVED,REJECTED'
        ]);

        $loan = $this->loanRepository->updateById($id, $loanAttributes);
        return APIResponse::transform($this->resource, $loan, true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Implement when required
    }
}
