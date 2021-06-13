<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Repositories\EmiTransactionRepositoryInterface;
use App\Utility\APIResponse;
use Illuminate\Http\Request;

class EmiTransactionController extends Controller
{
    // Dependency
    private $emiTransactionRepository;

    private $resource = "emis";

    public function __construct(EmiTransactionRepositoryInterface $emiTransactionRepository)
    {
        $this->emiTransactionRepository = $emiTransactionRepository;
    }

    /**
     * Get all EMI's for given loanId
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $emis = $this->emiTransactionRepository->getAll($request->loanId);
        return APIResponse::transform($this->resource, $emis);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $emis = $this->emiTransactionRepository->create($request->loanId);
        return APIResponse::transform($this->resource, $emis, true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $loanId, $id)
    {
        $emi = $this->emiTransactionRepository->getById($id);
        return APIResponse::transform($this->resource, $emi);
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
        // Implement when required
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

    /**
     * EMI Payment method
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function payment($loanId, $emiTransactionId)
    {
        $emi = $this->emiTransactionRepository->payment($emiTransactionId, $loanId);
        return APIResponse::transform($this->resource, $emi);
    }
}
