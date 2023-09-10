<?php

namespace App\Service;

use Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy;
use Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy\OrderType;
use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;



class GoogleApiDataProvider
{
    private $client;

    public function __construct()
    {

    }

    public function provideData($request, $credentials, $daysAgo) 
    {   
        $startDate = $daysAgo . 'daysAgo';

        $propertyId = "314720165";
        $client = new BetaAnalyticsDataClient(
            ['credentials' => $credentials]
        );
        $response = $client->runReport([
            'property' => 'properties/' . $propertyId,
            'dateRanges' => [
                new DateRange([
                    'start_date' => $startDate,
                    'end_date' => '1daysAgo',
                ]),
            ],
            'dimensions' => [new Dimension(
                [
                    'name' => 'date',
                ]
            ),
            ],
            'metrics' => [new Metric(
                [
                    'name' => 'activeUsers',
                ]
            )
            ],
            'orderBys' => [new OrderBy(
                [
                    'dimension' => new DimensionOrderBy([
                        'dimension_name' => 'date',
                        'order_type' => OrderType::ALPHANUMERIC
                    ]),
                    'desc' => false,
                ]
            )
            ]
            ]);




        $dateUserData = $response->getRows();

        $dates = [];
        $users = [];

        foreach ($dateUserData as $row) {
            $dates[] = $row->getDimensionValues()[0]->getValue();
            $users[] = $row->getMetricValues()[0]->getValue();
        }

        $data = [
            'dates' => $dates,
            'users' => $users
        ];

        return $data;

        
    }


}