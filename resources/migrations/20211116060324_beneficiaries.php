<?php

use Phoenix\Database\Element\ColumnSettings;
use Phoenix\Migration\AbstractMigration;

class Beneficiaries extends AbstractMigration
{
    protected function up(): void
    {

        $this->table('beneficiaries')
            ->addColumn('name', 'string', [
                ColumnSettings::SETTING_LENGTH => 191,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('phone', 'string', [
                ColumnSettings::SETTING_LENGTH => 20,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('phone_network', 'enum', [
                ColumnSettings::SETTING_NULL => true,
                ColumnSettings::SETTING_VALUES => array('mtn', 'airtel', 'glo', 'ninemobile')
            ])
            ->addColumn('email', 'string', [
                ColumnSettings::SETTING_LENGTH => 191,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('account_number', 'string', [
                ColumnSettings::SETTING_LENGTH => 20,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('account_name', 'string', [
                ColumnSettings::SETTING_LENGTH => 191,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('bank_name', 'string', [
                ColumnSettings::SETTING_LENGTH => 191,
                ColumnSettings::SETTING_NULL => true,
            ])
            ->create();
    }

    protected function down(): void
    {
        $this->table('beneficiaries')
            ->drop();
    }
}
