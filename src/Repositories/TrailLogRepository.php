<?php

namespace App\Repositories;

use App\Base\Domain\Repository;

/**
 * Repository.
 */
class TrailLogRepository extends Repository
{
    /**
     * @var PDO The database connection
     */
    protected $connection;
    protected $table = 'traillog';
    protected $properties = [
        'id',
        'user_id',
        'log_type',
        'details',
        'price',
        'description',
        'more_details',
        'status',
        'created_at'
    ];

    public function readPaging(array $props): array
    {
        $return = ['data' => [], 'total_rows' => 0];

        [
            'params' => $params,
            'filters' => $filters,
            'select' => $select,
            'order_by' => $order_by,
            'order' => $order
        ] = $props + self::DEFAULTS;

        ['where' => $where] = $params;

        $__ = $this->connection->table($this->table);

        if (!empty($select)) $__->select($select);

        if (!empty($where['from'])) {
            $__->where('created_at', '>=', $where['from']);
        }

        if (!empty($where['to'])) {
            $__->where('created_at', '<=', $where['to']);
        }

        if (!empty($where['log_type']) && $where['log_type'] !== "all") {
            $__->where('log_type', $where['log_type']);
        }

        if (!empty($where['user_id'])) {
            $__->where('user_id', $where['user_id']);
        }

        // get count
        $return['total_rows'] = $__->get()->count();

        // order
        $__->orderBy($order_by, $order);

        // records per page
        if (!empty($filters['rpp'])) {
            // offset 
            $__->skip($filters['offset']);
            $__->take($filters['rpp']);
        }

        $result = (array)$__->get()->all();

        $result = array_map(function ($value) {
            return (array)$value;
        }, $result);

        $return['data'] = $result;

        // finally return
        return $return;
    }
}
