<?php

namespace App\Service;

use Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy;
use Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy\OrderType;
use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;
use Google\Analytics\Data\V1beta\OrderBy\MetricOrderBy;
use Google\Analytics\Data\V1beta\OrderBy\MetricOrderBy\OrderType as Order;
use Symfony\Component\HttpFoundation\Request;

class GoogleApiDataProvider
{
    private $client;
    private $request;
    private $propertyId;
    private $credentials;
    private $daysAgo;

    public function __construct(Request $request, string $credentials, int $daysAgo)
    {
        $this->request = $request;
        $this->credentials = $credentials;
        $this->daysAgo = $daysAgo;
        $this->client = new BetaAnalyticsDataClient(
            ['credentials' => $credentials]
        );
        $this->propertyId = "314720165";
    }

    public function provideData(?bool $fakeDataFlag = false) 
    {  
        if ($fakeDataFlag) {
            $data = FakeChartDataProvider::provideFakeChartData($this->daysAgo);
            return $data;
        }

        $data['dateUserData'] = $this->fetchForDateUserData();
        $data['cityUserData'] = $this->fetchForCityUserData();
        $data['pageViewData'] = $this->fetchForPageViewData();
        
        return $data;
    }

    private function fetchForDateUserData(): array
    {
        $response = $this->client->runReport([
            'property' => 'properties/' . $this->propertyId,
            'dateRanges' => [
                new DateRange([
                    'start_date' => $this->daysAgo . 'daysAgo',
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

        return $data = [
            'dates' => $dates,
            'users' => $users
        ];

    }

    private function fetchForCityUserData(): array
    {
        $response = $this->client->runReport([
            'property' => 'properties/' . $this->propertyId,
            'dateRanges' => [
                new DateRange([
                    'start_date' => $this->daysAgo . 'daysAgo',
                    'end_date' => '1daysAgo',
                ]),
            ],
            'dimensions' => [new Dimension(
                [
                    'name' => 'city',
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
                    'metric' => new MetricOrderBy([
                        'metric_name' => 'activeUsers',
                    ]),
                    'desc' => true,
                ],
            )
            ],
            'limit' => 10
            ]);

        $cityUserData = $response->getRows();

        $cities = [];
        $users = [];

        foreach ($cityUserData as $row) {
            $cities[] = $row->getDimensionValues()[0]->getValue();
            $users[] = $row->getMetricValues()[0]->getValue();
        }

        return $data = [
            'cities' => $cities,
            'users' => $users
        ];
    }

    private function fetchForPageViewData(): array
    {
        $response = $this->client->runReport([
            'property' => 'properties/' . $this->propertyId,
            'dateRanges' => [
                new DateRange([
                    'start_date' => $this->daysAgo . 'daysAgo',
                    'end_date' => '1daysAgo',
                ]),
            ],
            'dimensions' => [new Dimension(
                [
                    'name' => 'pagePath',
                ]
            ),
            ],
            'metrics' => [new Metric(
                [
                    'name' => 'screenPageViews',
                ]
            )
            ],
            'orderBys' => [new OrderBy(
                [
                    'metric' => new MetricOrderBy([
                        'metric_name' => 'screenPageViews',
                    ]),
                    'desc' => true,
                ],
            )
            ],
            'limit' => 8
            ]);

        $cityUserData = $response->getRows();

        $pages = [];
        $views = [];

        foreach ($cityUserData as $row) {
            $pages[] = $row->getDimensionValues()[0]->getValue();
            $views[] = $row->getMetricValues()[0]->getValue();
        }

        return $data = [
            'pages' => $pages,
            'views' => $views
        ];
    }

}