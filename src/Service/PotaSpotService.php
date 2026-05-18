<?php

namespace App\Service;

use App\Enum\State;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PotaSpotService
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    public function getCurrentSpots(): array
    {
        $response = $this->httpClient->request(
            'GET',
            'https://api.pota.app/spot/'
        );

        $rawSpots = $response->toArray();

        // Filter out non-US spots
        $usSpots = array_filter($rawSpots, function ($spot) {
            return str_starts_with($spot['locationDesc'], 'US-');
        });

        // Filter out only the latest spots for each activator
        $latestSpots = [];

        foreach ($usSpots as $spot) {
            $activator = $spot['activator'];

            if (
                !isset($latestSpots[$activator]) ||
                $spot['spotTime'] > $latestSpots[$activator]['spotTime']
            ) {
                $latestSpots[$activator] = $spot;
            }
        }

        $usSpots = array_values($latestSpots);

        return array_map(function ($spot) {
            return [
                'activator' => $spot['activator'],
                'frequency' => $spot['frequency'],
                'mode' => $spot['mode'],
                'park' => $spot['reference'],
                'parkName' => $spot['name'],
                'state' => State::from(
                    str_replace('US-', '', $spot['locationDesc'])
                ),
                'spotTime' => new \DateTimeImmutable($spot['spotTime']),
            ];
        }, $usSpots);
    }
}
