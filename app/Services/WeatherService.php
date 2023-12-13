<?php

namespace App\Services;

use GuzzleHttp\Client;

class WeatherService
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getCurrentWeather($city)
    {
        $client = new Client();
        $response = $client->get("http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$this->apiKey}");

        return json_decode($response->getBody()->getContents(), true);
    }
}
