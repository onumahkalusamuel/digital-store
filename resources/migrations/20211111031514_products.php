<?php

use Phoenix\Database\Element\ColumnSettings;
use Phoenix\Migration\AbstractMigration;

class Products extends AbstractMigration
{
    protected function up(): void
    {
        $this->table('products')
            ->addColumn('category', 'string', [
                ColumnSettings::SETTING_LENGTH => 191,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('type', 'string', [
                ColumnSettings::SETTING_LENGTH => 50,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('title', 'string', [
                ColumnSettings::SETTING_LENGTH => 191,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('description', 'text', [
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('price', 'double', [
                ColumnSettings::SETTING_DEFAULT => 0,
                ColumnSettings::SETTING_LENGTH => 10,
                ColumnSettings::SETTING_DECIMALS => 2,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('stock', 'integer', [
                ColumnSettings::SETTING_LENGTH => 11,
                ColumnSettings::SETTING_DEFAULT => 0,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('content', 'text', [
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('balance', 'double', [
                ColumnSettings::SETTING_DEFAULT => 0,
                ColumnSettings::SETTING_LENGTH => 10,
                ColumnSettings::SETTING_DECIMALS => 2,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('loyalty_points', 'integer', [
                ColumnSettings::SETTING_LENGTH => 11,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('status', 'integer', [
                ColumnSettings::SETTING_DEFAULT => 1,
                ColumnSettings::SETTING_LENGTH => 1,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->create();
    }

    protected function down(): void
    {
        $this->table('products')
            ->drop();
    }
}
