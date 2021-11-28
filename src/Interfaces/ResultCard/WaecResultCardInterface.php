<?php

namespace App\Interfaces\ResultCard;

interface WaecResultCardInterface
{
    /**
     * Waec Result Card Interface
     *
     * @return array
     */
    public function buyResultCard(int $user_id = 0): array;
}
