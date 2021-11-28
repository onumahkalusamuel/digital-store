<?php

namespace App\Repositories;

use App\Base\Domain\Repository;

class ProductsRepository extends Repository
{
    protected $connection;
    protected $table = 'products';
    protected $properties = [
        'id',
        'category',
        'type',
        'title',
        'description',
        'price',
        'stock',
        'content',
        'balance',
        'loyalty_points',
        'status'
    ];
}
