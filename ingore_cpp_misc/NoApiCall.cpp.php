<?php

namespace GetMarks;

class NoApiCall
{
    private $origin;
    private $destination;
    private $departureDate;
    private $arrivalDate;
    private $key;
    private $host;
    private $endpointApi;



    public function __construct($key, $host, $endpointApi)
    {
        $this->key = $key;
        $this->host = $host;
        $this->endpointApi = $endpointApi;
    }

    // Getter for $origin
    public function getOrigin()
    {
        return $this->origin;
    }

    // Setter for $origin
    public function setOrigin($origin)
    {
        $this->origin = $origin;
    }

    // Getter for $destination
    public function getDestination()
    {
        return $this->destination;
    }

    // Setter for $destination
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    // Getter for $departureDate
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    // Setter for $departureDate
    public function setDepartureDate($departureDate)
    {
        $this->departureDate = $departureDate;
    }

    // Getter for $arrivalDate
    public function getArrivalDate()
    {
        return $this->arrivalDate;
    }

    // Setter for $arrivalDate
    public function setArrivalDate($arrivalDate)
    {
        $this->arrivalDate = $arrivalDate;
    }



    public function executeApi()
    {
        //$host = $this->host;
        //$origin = $this->origin;
        //$destination = $this->destination;
        //$departureDate = $this->departureDate;
        //$url = "https://$host/TimeTable/$origin/$destination/$departureDate/";
        if ($this->endpointApi == 'rapidapi') {
            $url = "https://{$this->host}/TimeTable/{$this->origin}/{$this->destination}/{$this->departureDate}/";
            $headers = [
                'X-RapidAPI-Key: ' . $this->key,
                'X-RapidAPI-Host: ' . $this->host,
            ];
        } else {
            // ANY OTHER API FOR THE FUTURE
        }


        // Initialize cURL session
        $curl = curl_init();

        // Set cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        return curl_exec($curl);
    }
}
