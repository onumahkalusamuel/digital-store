<?php

namespace App\Base\Domain;

use App\Traits\GeneralTrait;
use Illuminate\Database\Connection;

class Repository
{
    use GeneralTrait;

    protected $connection;

    /**
     * table
     *
     * @var string
     */
    protected $table = '';

    /**
     * properties
     *
     * @var array
     */
    protected $properties = [];

    protected const DEFAULTS = [
        'id' => '',
        'select' => '*',
        'data' => [],
        'filters' => [],
        'params' => [],
        'order_by' => 'id',
        'order' => 'ASC',
        'group_by' => '',
        'select_raw' => ''
    ];

    // defaults from child
    protected $CHILD_DEFAULTS = [];

    /**
     * Constructor.
     *
     * @param Illuminate\Database\Connection $connection The database connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    public function commit(): void
    {
        $this->connection->commit();
    }

    public function rollback(): void
    {
        $this->connection->rollback();
    }

    /**
     * readSingle
     *
     * @param $props
     * @return object
     */
    public function readSingle(array $props): object
    {
        [
            'id' => $id,
            'select' => $select
        ] = $props + $this->CHILD_DEFAULTS + self::DEFAULTS;

        $__ = $this->connection->table($this->table);

        if (!empty($select)) $__->select($select);

        return (object) $__->find($id);
    }

    /**
     * readPaging
     *
     * @param $props
     * @return array
     */
    public function readPaging(array $props): array
    {
        [
            'params' => $params,
            'filters' => $filters,
            'select' => $select,
            'order_by' => $order_by,
            'order' => $order
        ] = $props + $this->CHILD_DEFAULTS + self::DEFAULTS;

        $return = ['data' => [], 'total_rows' => 0];

        if (!empty($filters['rpp'])) {
            $filters['page'] = $filters['page'] ?? 1;
            $filters['offset'] = ($filters['page'] - 1) * $filters['rpp'];
        } else {
            $filters['rpp'] = 0;
            $filters['page'] = 1;
            $filters['offset'] = 0;
        }

        $__ = $this->connection
            ->table($this->table);

        if (!empty($select)) $__->select($select);

        // where like
        if (!empty($params['like'])) {

            $__->where(function ($q) use ($params) {

                $x = 0;

                foreach ($params['like'] as $key => $value) {

                    if ($x == 0) $q->where($key, 'LIKE', '%' . $value . '%');
                    else $q->orWhere($key, 'LIKE', '%' . $value . '%');

                    $x++;
                }
            });
        }

        // fix for date ranges - from
        if (!empty($params['where']['from'])) {
            $__->where('created_at', '>=', $params['where']['from']);
        }

        // fix for date ranges - to
        if (!empty($params['where']['to'])) {
            $__->where('created_at', '<=', $params['where']['to']);
        }
        // unset from and to
        unset($params['where']['from']);
        unset($params['where']['to']);

        // where direct - the rest
        if (!empty($params['where'])) {
            $__->where($params['where']);
        }

        // get the count first
        $return['total_rows'] = $__->get()->count();

        // then continue
        // order
        $order_by = $filters['sort_by'] && in_array($filters['sort_by'], $this->properties)
            ? $filters['sort_by']
            : $order_by;
        $order = $filters['desc'] ? 'DESC' : $order;

        $__->orderBy($order_by, $order);

        // records per page
        if (!empty($filters['rpp'])) {
            // offset
            $__->skip($filters['offset']);
            $__->take($filters['rpp']);
        }

        $return['data'] = (array)$__->get()->all();

        // finally return
        return $return;
    }

    /**
     * readAll
     *
     * @param $props
     * @return array
     */
    public function readAll(array $props): array
    {

        [
            'params' => $params,
            'select' => $select,
            'order_by' => $order_by,
            'order' => $order,
            'group_by' => $group_by,
            'select_raw' => $select_raw
        ] = $props + $this->CHILD_DEFAULTS + self::DEFAULTS;

        $__ = $this->connection->table($this->table);

        // select
        if (!empty($select)) $__->select($select);

        // select raw
        if (!empty($select_raw)) $__->selectRaw(implode(',', $select_raw));

        // where like
        if (!empty($params['like'])) {

            $__->where(function ($q) use ($params) {

                $x = 0;

                foreach ($params['like'] as $key => $value) {
                    // check for multiple entries in one, separated by pipe (|)
                    $values = explode("|", $value);
                    foreach ($values as $v) {
                        if ($x == 0) $q->where($key, 'LIKE', '%' . $v . '%');
                        else $q->orWhere($key, 'LIKE', '%' . $v . '%');
                        $x++;
                    }
                }
            });
        }

        // where direct
        if (!empty($params['where'])) {
            $__->where($params['where']);
        }

        //grouping
        if (!empty($group_by)) $__->groupBy($group_by);

        // ordering
        $__->orderBy($order_by, $order);

        return (array)$__->get()->all();
    }

    /**
     * readAll
     *
     * @param array $props
     * @return array
     */
    public function totalRows(array $props = []): int
    {
        [
            'params' => $params
        ] = $props + $this->CHILD_DEFAULTS + self::DEFAULTS;

        $__ = $this->connection->table($this->table);

        // where like
        if (!empty($params['like'])) {

            $__->where(function ($q) use ($params) {

                $x = 0;

                foreach ($params['like'] as $key => $value) {
                    // check for multiple entries in one, separated by pipe (|)
                    $values = explode("|", $value);
                    foreach ($values as $v) {
                        if ($x == 0) $q->where($key, 'LIKE', '%' . $v . '%');
                        else $q->orWhere($key, 'LIKE', '%' . $v . '%');
                        $x++;
                    }
                }
            });
        }

        // where direct
        if (!empty($params['where'])) {
            $__->where($params['where']);
        }

        // finally return
        return (int) $__->get()->count();
    }

    /**
     * find
     *
     * @param array $props
     * @return object
     */
    public function find(array $props): object
    {
        [
            'params' => $params,
            'select' => $select
        ] = $props + $this->CHILD_DEFAULTS + self::DEFAULTS;

        foreach ($params as $key => $value) {
            if (!in_array($key, $this->properties)) {
                unset($params[$key]);
            }
        }
        $__ = $this->connection->table($this->table);

        // select
        if (!empty($select)) $__->select($select);

        // where
        $__->where($params);

        $return = (object) $__->get()->first();

        return $return;
    }

    /**
     * create
     *
     * @param array $props
     * @return int
     */
    public function create(array $props): string
    {

        [
            'data' => $data,
            'id' => $id_override
        ] = $props + $this->CHILD_DEFAULTS + self::DEFAULTS;

        // is there a title and slug?
        if (!empty($data['title'])) $data['slug'] = $this->slug($data['title']);

        $row = [];
        foreach ($data as $key => $value)
            if (in_array($key, $this->properties) && !in_array($key, ['id']))
                $row[$key] = $value;

        // override default id
        if ($id_override) $row['id'] = $id_override;

        try {
            return $this->connection->table($this->table)->insertGetId($row);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * delete
     *
     * @param array $props
     * @return bool
     */
    public function delete(array $props): bool
    {
        [
            'id' => $id,
            'params' => $params
        ] = $props + $this->CHILD_DEFAULTS + self::DEFAULTS;

        $__ = $this->connection->table($this->table);

        if (!empty($params)) $__->where($params);
        if (!empty($id))    $__->where(['id' => $id]);

        return $__->delete();
    }

    /**
     * update
     *
     * @param array $props
     * @return bool
     */
    public function update(array $props): bool
    {

        [
            'data' => $data,
            'id' => $id,
            'params' => $params
        ] = $props + $this->CHILD_DEFAULTS + self::DEFAULTS;

        // at least one must be provided
        if (empty($id) && empty($params)) return false;

        // is there a title and slug?
        if (!empty($data['title'])) $data['slug'] = $this->slug($data['title']);

        // prepare the update data
        $row = [];
        foreach ($data as $key => $value)
            if (in_array($key, $this->properties) && !in_array($key, ['id']))
                $row[$key] = $value;

        if (empty($row)) return false;

        $__ = $this->connection->table($this->table);
        // attach id
        if (!empty($id)) $__->where(['id' => $id]);
        // attach other params
        if (!empty($params)) $__->where($params);

        // execute update query on $row
        $__->update($row);

        return true;
    }
}
