<?php

namespace App\Service;

// use Google\Client as GoogleClient;
// use Google\Service\AnalyticsData as AnalyticsData;
// use Google\Service\AnalyticsData\RunReportRequest;

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;




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
                    'end_date' => 'today',
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
            ]
        ]);

        $rows = $response->getRows();

        // foreach ($rows as $row) {
        //     $sourceMedium = $row->getDimensionValues()[0]->getValue();
        //     $sessions = $row->getMetricValues()[0]->getValue();
        //     // Przetwarzaj dane, np. wypisując źródło/medium i liczbę sesji
        //     echo "Źródło/Medium: $sourceMedium\n";
        //     echo "Liczba sesji: $sessions\n";
        // }
    }


}