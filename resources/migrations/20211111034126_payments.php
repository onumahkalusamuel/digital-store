<?php

use Phoenix\Database\Element\ColumnSettings;
use Phoenix\Migration\AbstractMigration;

class Payments extends AbstractMigration
{
    protected function up(): void
    {
        $this->table('payments')
            ->addColumn('type', 'string', [
                ColumnSettings::SETTING_LENGTH => 20,
                ColumnSettings::SETTING_DEFAULT => 'online',
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('service_id', 'string', [
                ColumnSettings::SETTING_LENGTH => 20,
                ColumnSettings::SETTING_DEFAULT => '',
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('transaction_id', 'text', [
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('amount', 'double', [
                ColumnSettings::SETTING_LENGTH => 10,
                ColumnSettings::SETTING_DECIMALS => 2,
                ColumnSettings::SETTING_DEFAULT => 0,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('details', 'text', [
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('created_at', 'timestamp', [
                ColumnSettings::SETTING_DEFAULT => ColumnSettings::DEFAULT_VALUE_CURRENT_TIMESTAMP
            ])
            ->create();
    }

    protected function down(): void
    {
        $this->table('payments')
            ->drop();
    }
}
