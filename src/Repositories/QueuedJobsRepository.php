<?php

namespace App\Repositories;

use App\Base\Domain\Repository;

class QueuedJobsRepository extends Repository
{
    protected $connection;
    protected $table = 'queued_jobs';
    protected $properties = [
        'id',
        'type',
        'data',
        'status',
        'error'
    ];
}
