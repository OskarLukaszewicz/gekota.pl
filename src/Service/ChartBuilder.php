<?php

namespace App\Service;

use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartBuilder
{
    static function generateChartsForDashboard(ChartBuilderInterface $chartBuilder, array $data): Chart
    {
        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);

        foreach ($data['dates'] as $date) {
            $month = substr($date, 4, 2); // Wyodrębnij miesiąc (następne 2 znaki)
            $day = substr($date, 6, 2); // Wyodrębnij dzień (ostatnie 2 znaki)
            
            $formattedDate = $month . '.' . $day; // Sklej nowy format 'mm.dd'
            
            $formattedDates[] = $formattedDate;
        }

        $chart->setData([
            'labels' => $formattedDates,
            'datasets' => [
                [
                    'label' => 'Użytkownicy',
                    'backgroundColor' => 'rgba(133, 184, 30, 0.8)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $data['users'],
                    'tension' => 0.6,
                ],
            ],
        ]);

        $chart->setOptions([
            'maintainAspectRatio' => false,
        ]);

        return $chart;
    }

}