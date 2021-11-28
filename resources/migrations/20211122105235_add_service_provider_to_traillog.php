<?php

use Phoenix\Database\Element\ColumnSettings;
use Phoenix\Migration\AbstractMigration;

class AddServiceProviderToTraillog extends AbstractMigration
{
    protected function up(): void
    {
        $this->table('traillog')
            ->addColumn('service_provider', 'string', [
                ColumnSettings::SETTING_DEFAULT => "",
                ColumnSettings::SETTING_LENGTH => 191,
                ColumnSettings::SETTING_NULL => true,
            ]);
    }


    protected function down(): void
    {
        $this->table('traillog')
            ->dropColumn('service_provider');
    }
}
