<?php

use Phoenix\Database\Element\ColumnSettings;
use Phoenix\Migration\AbstractMigration;

class QuickBuy extends AbstractMigration
{
    protected function up(): void
    {
        $this->table('quickbuy')
            ->addColumn('transaction_id', 'string', [ColumnSettings::SETTING_LENGTH => 50])
            ->addColumn('product', 'string', [ColumnSettings::SETTING_LENGTH => 50])
            ->addColumn('service_provider', 'string', [
                ColumnSettings::SETTING_NULL => true,
                ColumnSettings::SETTING_LENGTH => 50
            ])
            ->addColumn('details', 'text', [ColumnSettings::SETTING_NULL => true])
            ->addColumn('payment_id', 'integer', [ColumnSettings::SETTING_NULL => true])
            ->addColumn('payment_status', 'enum', [
                ColumnSettings::SETTING_VALUES => ['pending', 'completed', 'failed'],
                ColumnSettings::SETTING_DEFAULT => 'pending'
            ])
            ->addColumn('transaction_status', 'enum', [
                ColumnSettings::SETTING_VALUES => ['pending', 'processed', 'cancelled'],
                ColumnSettings::SETTING_DEFAULT => 'pending'
            ])
            ->create();
    }

    protected function down(): void
    {
        $this->table('quickbuy')
            ->drop();
    }
}
