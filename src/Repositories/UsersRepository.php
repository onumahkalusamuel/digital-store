<?php

namespace App\Repositories;

use App\Base\Domain\Repository;
use Illuminate\Database\Connection;

/**
 * Repository.
 */
class UsersRepository extends Repository
{
    /**
     * @var PDO The database connection
     */
    protected $connection;

    protected $table = 'users';

    protected $properties = [
        'id',
        'fullname',
        'user_type',
        'phone',
        'email',
        'password',
        'token',
        'balance',
        'loyalty_points',
        'referral_code',
        'upline',
        'status',
        'created_at',
        'updated_at'
    ];
    /**
     * Constructor.
     *
     * @param PDO $connection The database connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function emailInUse(string $email): bool
    {
        return (bool) $this->connection->table($this->table)->where(['email' => $email])->count();
    }

    public function phoneInUse(string $phone): bool
    {
        return (bool) $this->connection->table($this->table)->where(['phone' => $phone])->count();
    }
}
