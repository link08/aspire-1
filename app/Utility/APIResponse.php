<?php

namespace App\Utility;

/**
 * Class Transform
 * 
 * Has transfrom/format methods for v1 APIs
 */
class APIResponse
{
    // Error maps
    private static $resourceValueMap = [
        'loans' => [
            0 => [
                'responseCode' => 422,
                'data' => [
                    'status' => 'Error',
                    'message'=> 'Cannot update loan'
                ]
            ],
            1 => [
                'responseCode' => 200,
                'data' => [
                    'status' => 'Success',
                    'message'=> 'Loan updated'
                ]
            ]
        ],

        'emis' => [
            0 => [
                'responseCode' => 422,
                'data' => [
                    'status' => 'Error',
                    'message'=> 'Cannot generate EMI',
                    'errors' => ['loan_status'   => ['Loan already closed/settled']]
                ]
            ],
            1 => [
                'responseCode' => 422,
                'data' => [
                    'status' => 'Error',
                    'message'=> 'Cannot generate EMI',
                    'errors' => ['loan_status'   => ['Loan is not in approved status']]
                ]
            ]
        ]
    ];

    /**
     * Transforms the API responses
     *
     * @param  string   $resource
     * @param  array    $resourceData
     * @param  boolean  $mapWithValue
     * @return array    $response
     */
    public static function transform($resource, $resourceData, $mapWithValue = false) {
        if($mapWithValue && is_int($resourceData)) {
            return response(
                self::$resourceValueMap[$resource][$resourceData]['data'],
                self::$resourceValueMap[$resource][$resourceData]['responseCode'],
            );
        }

        $response = [
            'status'    => 'Success',
            $resource   => $resourceData ?? []
        ];
        $response = json_decode(json_encode($response), true);

        // Just a format validation
        if(! isset($response[$resource][0]) && !empty($response[$resource])) {
            $response[$resource] = [$response[$resource]];
        }

        return $response;
    }
}