<?php

namespace App\Traits;

trait SMEPricesTrait
{
    public function MtnSmePrices(): array
    {
        return [
            '500' => ['data' => '500MB', 'units' => '500', 'amount' => '190'],
            '1000' => ['data' => '1GB', 'units' => '1000', 'amount' => '370'],
            '2000' => ['data' => '2GB', 'units' => '2000', 'amount' => '730'],
            '3000' => ['data' => '3GB', 'units' => '3000', 'amount' => '970'],
            '5000' => ['data' => '5GB', 'units' => '5000', 'amount' => '1550'],
            '10000' => ['data' => '10GB', 'units' => '10000', 'amount' => '3050'],
        ];
    }
    public function AirtelSmePrices(): array
    {
        return [];
    }
    public function GloSmePrices(): array
    {
        return [];
    }
    public function NineMobileSmePrices(): array
    {
        return [];
    }
}
