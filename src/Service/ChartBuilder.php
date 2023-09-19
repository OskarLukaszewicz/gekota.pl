<?php

namespace App\Service;

use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartBuilder
{
    static function generateChartsForDashboard(ChartBuilderInterface $chartBuilder, array $data): array
    {
        $charts['dateUserChart'] = self::generateDateUserChart($chartBuilder, $data);
        $charts['cityUserChart'] = self::generateCityUserChart($chartBuilder, $data);
        $charts['pageViewChart'] = self::generatePageViewChart($chartBuilder, $data);

        return $charts;
    }

    static function generateDateUserChart(ChartBuilderInterface $chartBuilder, $data) 
    {
        $dateUserChart = $chartBuilder->createChart(Chart::TYPE_LINE);

        foreach ($data['dateUserData']['dates'] as $date) {
            $month = substr($date, 4, 2); // Wyodrębnij miesiąc (następne 2 znaki)
            $day = substr($date, 6, 2); // Wyodrębnij dzień (ostatnie 2 znaki)
            
            $formattedDate = $day . '.' . $month; // Sklej nowy format 'mm.dd'
            
            $formattedDates[] = $formattedDate;
        }

        $dateUserChart->setData([
            'labels' => $formattedDates,
            'datasets' => [
                [
                    'label' => 'Użytkownicy',
                    'backgroundColor' => 'rgb(232, 225, 217)',
                    'borderColor' => 'rgb(62, 124, 23)',
                    'data' => $data['dateUserData']['users'],
                    'tension' => 0.4,
                ],
            ],
        ]);

        $dateUserChart->setOptions([
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 200,
                ],
            ],
            'plugins' => [
                'title' => [
                    'display' => true,
                    'text' => 'Ruch na stronie'
                ]
            ]
        ]);

        return $dateUserChart;

    }

    static function generateCityUserChart(ChartBuilderInterface $chartBuilder, $data) 
    {
        $cityUserChart = $chartBuilder->createChart(Chart::TYPE_BAR);

        $cityUserChart->setData([
            'labels' => $data['cityUserData']['cities'],
            'datasets' => [
                [
                    'label' => 'Użytkownicy',
                    'backgroundColor' => [
                        'rgba(62, 124, 23, 0.8)',
                        'rgba(232, 225, 217, 0.8)',
                        'rgba(18, 92, 19,0.8)',
                        'rgba(244, 164, 66, 0.8)',
                ],
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $data['cityUserData']['users'],
                ],
            ],
        ]);

        $cityUserChart->setOptions([
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 200,
                ],
            ],
        ]);

        return $cityUserChart;
    }

    static function generatePageViewChart(ChartBuilderInterface $chartBuilder, $data) 
    {

        foreach ($data['pageViewData']['pages'] as &$path) {
            if ($path[strlen($path)-1] === "/"){$path = substr($path, 0, -1);}
            $parts = explode('/', $path);
            $path = end($parts);
            strlen($path) > 10 && $path = substr($path, 0, 14) . "...";
            $path == "" && $path = "index" ;
        }

        $pageViewChart = $chartBuilder->createChart(Chart::TYPE_BAR);

        $pageViewChart->setData([
            'labels' => $data['pageViewData']['pages'],
            'datasets' => [
                [
                    'label' => 'Wyświetlenia',
                    'backgroundColor' => [
                        'rgba(62, 124, 23, 0.8)',
                        'rgba(232, 225, 217, 0.8)',
                        'rgba(18, 92, 19,0.8)',
                        'rgba(244, 164, 66, 0.8)',
                    ],
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $data['pageViewData']['views'],
                    'tension' => 0.6,
                ],
            ],
        ]);

        $pageViewChart->setOptions([
            'indexAxis' => 'y',
            'maintainAspectRatio' => false,
        ]);

        return $pageViewChart;
    }

}