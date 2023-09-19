<?php

namespace App\Service;

use Faker\Factory;

class FakeChartDataProvider 
{
    static private $faker;

    static function provideFakeChartData(int $daysAgo) 
    {
        self::$faker = Factory::create();

        $dateUserData = [
            'dates' => [],
            'users' => []
        ];
        $cityUserData = [
            'cities' => [],
            'users' => []
        ];
        $pageViewData = [
            'pages' => [
                "/",
                "/oferta/jaszczurki/",
                "/oferta/weze/",
                "/oferta/",
                "/oferta/inne-zwierzeta/",
                "/oferta/pajeczaki/",
                "/oferta/pajeczaki/skakun-krolewski-Phidippus-regius",
                "/oferta/plazy/",
            ],
            'views' => []
        ];

        for ($i = 0; $i < $daysAgo; $i++) {

            $date = $daysAgo - $i;

            $dateUserData['dates'][$i] = date('Ymd', strtotime("-" . $date . "days")); 

            $dateUserData['users'][$i] = rand(60,180);

        }



        for ($i = 0; $i < 10; $i++) {

            $cityUserData['cities'][$i] = self::$faker->city();

            $cityUserData['users'][$i] = $daysAgo * rand(5, 40);

        }

        for ($i = 0; $i < 8; $i++) {

            $pageViewData['views'][$i] = $daysAgo * rand(5, 40);

        }

        return $data = [
            "dateUserData" => $dateUserData, 
            "cityUserData" => $cityUserData, 
            "pageViewData" => $pageViewData
        ];
    }
}