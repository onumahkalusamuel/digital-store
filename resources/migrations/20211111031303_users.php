<?php

use Phoenix\Database\Element\ColumnSettings;
use Phoenix\Migration\AbstractMigration;

class Users extends AbstractMigration
{
    protected function up(): void
    {
        $this->table('users')
            ->addColumn('user_type', 'string', [
                ColumnSettings::SETTING_DEFAULT => 'user',
                ColumnSettings::SETTING_NULL => true
            ])
            ->addColumn('fullname', 'string', [
                ColumnSettings::SETTING_NULL => true,
                ColumnSettings::SETTING_LENGTH => 100
            ])
            ->addColumn('phone', 'string', [
                ColumnSettings::SETTING_NULL => true,
                ColumnSettings::SETTING_LENGTH => 20
            ])
            ->addColumn('email', 'string', [
                ColumnSettings::SETTING_NULL => true,
                ColumnSettings::SETTING_LENGTH => 100
            ])
            ->addColumn('password', 'string', [
                ColumnSettings::SETTING_NULL => true,
                ColumnSettings::SETTING_LENGTH => 60
            ])
            ->addColumn('token', 'string', [
                ColumnSettings::SETTING_NULL => true,
                ColumnSettings::SETTING_LENGTH => 25
            ])
            ->addColumn('balance', 'double', [
                ColumnSettings::SETTING_NULL => true,
                ColumnSettings::SETTING_DEFAULT => 0,
                ColumnSettings::SETTING_DECIMALS => 2,
            ])
            ->addColumn('loyalty_points', 'integer', [
                ColumnSettings::SETTING_NULL => true,
                ColumnSettings::SETTING_DEFAULT => 0
            ])
            ->addColumn('referral_code', 'string', [
                ColumnSettings::SETTING_NULL => true,
                ColumnSettings::SETTING_LENGTH => 20
            ])
            ->addColumn('upline', 'string', [
                ColumnSettings::SETTING_NULL => true,
                ColumnSettings::SETTING_LENGTH => 20
            ])
            ->addColumn('status', 'integer', [
                ColumnSettings::SETTING_DEFAULT => 1,
                ColumnSettings::SETTING_LENGTH => 1
            ])
            ->addColumn('created_at', 'timestamp', [
                ColumnSettings::SETTING_DEFAULT => ColumnSettings::DEFAULT_VALUE_CURRENT_TIMESTAMP
            ])
            ->addColumn('updated_at', 'timestamp', [
                ColumnSettings::SETTING_DEFAULT => ColumnSettings::DEFAULT_VALUE_CURRENT_TIMESTAMP
            ])
            ->create();
    }

    protected function down(): void
    {
        $this->table('users')
            ->drop();
    }
}
