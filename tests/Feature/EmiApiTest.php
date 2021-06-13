<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\ModelStructures;

class EmiApiTest extends TestCase
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
     * Test for EMI generation API
     *
     * @return void
     */
    public function test_can_generate_emi()
    {
        // Make a loan and approve to create EMI, and get its id
        $loanId = $this->approveLoan($this->createNewLoan());
        $params = [
            'loanId'  => $loanId
        ];

        $response = $this->withHeaders($this->headers)
            ->postJson(route('emis.store', $params));

        $content = $response->getOriginalContent();

        if($response->getStatusCode() == 200) {
            $emiId = $content['emis'][0]['id'];
        }

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'emis' => ['*' => ModelStructures::getStructureFor('EmiTransaction')]
        ]);

        // Test for other EMI API's for this fetched emiId & loanId
        $this->can_fetch_all_emis($loanId);
        $this->can_fetch_an_emi($loanId, $emiId);
        $this->can_make_emi_payment($loanId, $emiId);
    }

    /**
     * Test to fetching all EMI's for a loan
     * Called only via ather test methods
     *
     * @param  string   $loanId
     * @return void
     */
    public function can_fetch_all_emis($loanId)
    {
        $params = [
            'loanId'  => $loanId
        ];

        $response = $this->withHeaders($this->headers)
            ->getJson(route('emis.index', $params));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'emis' => ['*' => ModelStructures::getStructureFor('EmiTransaction')]
        ]);
    }

    /**
     * Test to fetch single emi for a loan
     * Called only via ather test methods
     *
     * @param  string   $loanId
     * @param  string   $emiId
     * @return void
     */
    public function can_fetch_an_emi($loanId, $emiId)
    {
        $params = [
            'loanId'  => $loanId,
            'emi'   => $emiId
        ];

        $response = $this->withHeaders($this->headers)
            ->getJson(route('emis.show', $params));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'emis' => ['*' => ModelStructures::getStructureFor('EmiTransaction')]
        ]);
    }

    /**
     * Test for EMI payment
     * Called only via ather test methods
     *
     * @param  string   $loanId
     * @param  string   $emiId
     * @return void
     */
    public function can_make_emi_payment($loanId, $emiId)
    {
        $params = [
            'loanId'  => $loanId,
            'id'   => $emiId
        ];

        $response = $this->withHeaders($this->headers)
            ->postJson(route('emis.payment', $params));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'emis' => ['*' => ModelStructures::getStructureFor('EmiTransaction')]
        ]);
    }

    /**
     * Creates a new loan to test
     * Called only via ather test methods
     *
     * @return string $loanId
     */
    public function createNewLoan()
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
            return $content['loans'][0]['id'];
        }
    }

    /**
     * Approves loan for given loanId
     * Called only via ather test methods
     *
     * @param  string   $loanId
     * @return void
     */
    public function approveLoan($loanId)
    {
        $params = [
            'loan' => $loanId
        ];

        $formData = [
            'status' => 'APPROVED'
        ];

        $this->withHeaders($this->headers)
            ->putJson(route('loans.update', $params), $formData);

        return $loanId;
    }
}
