<?php

use Phoenix\Database\Element\ColumnSettings;
use Phoenix\Migration\AbstractMigration;

class Traillog extends AbstractMigration
{
    protected function up(): void
    {
        $this->table('traillog')
            ->addColumn('user_id', 'integer', [
                ColumnSettings::SETTING_LENGTH => 11,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('log_type', 'string', [
                ColumnSettings::SETTING_DEFAULT => "",
                ColumnSettings::SETTING_LENGTH => 191,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('destination', 'string', [
                ColumnSettings::SETTING_DEFAULT => "",
                ColumnSettings::SETTING_LENGTH => 191,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('amount', 'double', [
                ColumnSettings::SETTING_DEFAULT => 0,
                ColumnSettings::SETTING_LENGTH => 10,
                ColumnSettings::SETTING_DECIMALS => 2,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('description', 'text', [
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('details', 'text', [
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('status', 'enum', [
                ColumnSettings::SETTING_DEFAULT => 'processing',
                ColumnSettings::SETTING_VALUES => array('scheduled', 'processing', 'completed', 'failed'),
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('balance_before', 'double', [
                ColumnSettings::SETTING_DEFAULT => 0,
                ColumnSettings::SETTING_LENGTH => 10,
                ColumnSettings::SETTING_DECIMALS => 2,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('balance_after', 'double', [
                ColumnSettings::SETTING_DEFAULT => 0,
                ColumnSettings::SETTING_LENGTH => 10,
                ColumnSettings::SETTING_DECIMALS => 2,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('loyalty_points', 'integer', [
                ColumnSettings::SETTING_DEFAULT => 0,
                ColumnSettings::SETTING_LENGTH => 11,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('platform_id', 'string', [
                ColumnSettings::SETTING_DEFAULT => "",
                ColumnSettings::SETTING_LENGTH => 50,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('created_at', 'timestamp', [
                ColumnSettings::SETTING_DEFAULT => ColumnSettings::DEFAULT_VALUE_CURRENT_TIMESTAMP
            ])
            ->create();
    }

    protected function down(): void
    {
        $this->table('traillog')
            ->drop();
    }
}
