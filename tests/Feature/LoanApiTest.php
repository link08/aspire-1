<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\ModelStructures;

class LoanApiTest extends TestCase
{
    private $testUserEmail = 'test@test.com';
    private $testUserPassword = 'pass';
    private $headers;

    /**
     * Setup headers befor testing any API call
     *
     * @return void
     */
    public function setUp(): void {
        parent::setUp();

        $formData = [
            'email' => $this->testUserEmail,
            'password' => $this->testUserPassword
        ];
        $response = $this->postJson(route('user.login'), $formData);
        $content = $response->getOriginalContent();

        if($response->getStatusCode() == 200) {
            $this->headers = [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$content['accessToken']
            ];
        }
    }

    /**
     * Test for loan creation API
     *
     * @return void
     */
    public function test_can_create_loan()
    {
        $formData = [
            'amount' => 25000,
            'loan_term' => 30,
            'repayment_type' => 'WEEKLY'
        ];

        $response = $this->withHeaders($this->headers)
            ->postJson(route('loans.store'), $formData);

        $content = $response->getOriginalContent();
        if($response->getStatusCode() == 200) {
            $loanId = $content['loans'][0]['id'];
        }
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'loans' => ['*' => ModelStructures::getStructureFor('Loan')]
        ]);

        // Test for loan fetch and loan status update API
        $this->can_fetch_a_loan($loanId);
        $this->can_update_loan_status($loanId);
    }

    /**
     * Test for all loans fetch API
     *
     * @return void
     */
    public function test_can_fetch_all_loans()
    {
        $response = $this->withHeaders($this->headers)
            ->getJson(route('loans.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'loans' => ['*' => ModelStructures::getStructureFor('Loan')]
        ]);
    }

    /**
     * Test for single loan fetch API
     * Called only via ather test methods
     *
     * @param  string   $loanId
     * @return void
     */
    public function can_fetch_a_loan($loanId)
    {
        $params = [
            'loan' => $loanId
        ];

        $response = $this->withHeaders($this->headers)
            ->getJson(route('loans.show', $params));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'loans' => ['*' => ModelStructures::getStructureFor('Loan')]
        ]);
    }

    /**
     * Test for loan status update API
     * Called only via ather test methods
     *
     * @param  string   $loanId
     * @return void
     */
    public function can_update_loan_status($loanId)
    {
        $params = [
            'loan' => $loanId
        ];

        $formData = [
            'status' => 'REJECTED'
        ];

        $response = $this->withHeaders($this->headers)
            ->putJson(route('loans.update', $params), $formData);

        $response->assertStatus(200);
        $response->assertJsonStructure(['status']);
    }
}
