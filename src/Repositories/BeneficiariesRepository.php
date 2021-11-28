<?php

namespace App\Repositories;

use App\Base\Domain\Repository;

class BeneficiariesRepository extends Repository
{
    protected $connection;
    protected $table = 'beneficiaries';
    protected $properties = [
        'id',
        'name',
        'phone',
        'phone_network',
        'email',
        'account_number',
        'account_name',
        'bank_name'
    ];
}
