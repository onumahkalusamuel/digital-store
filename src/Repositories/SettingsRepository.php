<?php

namespace App\Repositories;

use App\Base\Domain\Repository;
use Illuminate\Database\Connection;

/**
 * Repository.
 */
class SettingsRepository extends Repository
{
    /**
     * @var PDO The database connection
     */
    protected $connection;
    protected $table = 'settings';
    protected $properties = [
        'setting',
        'value'
    ];
    private $settings;

    protected $CHILD_DEFAULTS = [
        'order_by' => 'setting'
    ];
    /**
     * Constructor.
     *
     * @param PDO $connection The database connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->setUp();
    }

    private function setUp()
    {
        $settings = $this->readAll([]);

        foreach ($settings as $row) {
            $this->settings[$row->setting] = $row->value;
        }
    }

    public function __get($setting)
    {
        return $this->settings[$setting];
    }

    public function __set($setting, $value)
    {
        $this->settings[$setting] = $value;
        return (bool) $this->update([
            'setting' => $setting,
            'value' => $value
        ]);
    }

    public function updateSettings($data)
    {
        // update normal records
        foreach ($data as $key => $value) $this->$key = $value;
        // commit the changes
        return true;
    }

    public function update(array $props): bool
    {
        ['setting' => $setting, 'value' => $value] = $props;

        return $this->connection->table($this->table)
            ->where(['setting' => $setting])
            ->update(['value' => $value]);
    }
}
