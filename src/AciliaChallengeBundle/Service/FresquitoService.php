<?php

namespace AciliaChallengeBundle\Service;

use AciliaChallengeBundle\Library\FresquitoServiceInterface;

class FresquitoService implements FresquitoServiceInterface
{
    protected $apiUrl;

    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    public function getResults()
    {
        $results = $this->readJSON();

        $list_cities_measurements = $this->parseResults($results);

        $list_cities_with_averages = $this->calculateAverages($list_cities_measurements);

        $frozen_cities = $this->sortByCold($list_cities_with_averages);

        $frozen_cities = array_slice($frozen_cities, 0,3);
        $frozen_cities_formatted = [];
        array_walk($frozen_cities, function ($cityAvg, $cityName) use (&$frozen_cities_formatted) {
            $frozen_cities_formatted[] = [0, $cityName, $cityAvg];
        });

        return $frozen_cities_formatted;
    }

    public function readJSON()
    {
        $lfm = file_get_contents(__DIR__ .'/../Resources/data/aemet-data.json');
        $list_city_measurements = json_decode($lfm, true);

        return $list_city_measurements;
    }

    public function parseResults($results)
    {
        $cities = [];

        foreach ($results as $result) {
            if (!isset($result['tmed'])) {
                continue;
            }
            if (!isset($cities[$result['nombre']])) {
                $cities[$result['nombre']] = [];
            }
            $cities[$result['nombre']][] = floatval(str_replace(',', '.', str_replace('.', '', $result['tmed'])));
        }

        return $cities;
    }

    public function calculateAverages($cities)
    {
        foreach ($cities as &$city_measurements) {
            $number_measurements = count($city_measurements);
            $sum = array_reduce($city_measurements, function ($total, $measurement) {
                return $total + $measurement;
            }, 0);
            $city_measurements = $sum / $number_measurements;
        }

        return $cities;
    }

    public function sortByCold($cities)
    {
        arsort($cities);

        return array_reverse($cities);
    }
}