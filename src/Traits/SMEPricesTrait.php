<?php

namespace App\Traits;

trait SMEPricesTrait
{
    public function MtnSmePrices(): array
    {
        return [
            ['id' => 1, 'data' => '500MB', 'units' => '500', 'amount' => '190'],
            ['id' => 2, 'data' => '1GB', 'units' => '1000', 'amount' => '370'],
            ['id' => 3, 'data' => '2GB', 'units' => '2000', 'amount' => '730'],
            ['id' => 4, 'data' => '3GB', 'units' => '3000', 'amount' => '970'],
            ['id' => 5, 'data' => '5GB', 'units' => '5000', 'amount' => '1550'],
            ['id' => 6, 'data' => '10GB', 'units' => '10000', 'amount' => '3050'],
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
