<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AusPostService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.auspost.url');
        $this->apiKey = config('services.auspost.key');
    }

    public function getRates($fromPostcode, $toPostcode, $weight)
    {
        $response = Http::withHeaders([
            'AUTH-KEY' => $this->apiKey,
        ])->get("{$this->baseUrl}/postage/parcel/domestic/service", [
            'from_postcode' => $fromPostcode,
            'to_postcode' => $toPostcode,
            'length' => 10,
            'width' => 10,
            'height' => 10,
            'weight' => $weight
        ]);

        return $response->json();
    }
}
