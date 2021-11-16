<?php

namespace App\Repositories;

use App\Base\Domain\Repository;

class PaymentsRepository extends Repository
{
    protected $connection;
    protected $table = 'payments';
    protected $properties = [
        'id',
        'type',
        'data',
        'status',
        'error'
    ];
}
