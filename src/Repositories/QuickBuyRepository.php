<?php

namespace App\Repositories;

use App\Base\Domain\Repository;

class QuickBuyRepository extends Repository
{
    protected $connection;
    protected $table = 'quickbuy';
    protected $properties = [
        'id',
        'transaction_id',
        'product',
        'service_provider',
        'details',
        'payment_id',
        'payment_status',
        'transaction_status'
    ];
}
