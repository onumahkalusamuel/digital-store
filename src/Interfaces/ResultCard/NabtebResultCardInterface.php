<?php

namespace App\Interfaces\ResultCard;

interface NabtebResultCardInterface
{
    /**
     * Nabteb Result Card Interface
     *
     * @return array
     */
    public function buyResultCard(int $user_id = 0): array;
}
