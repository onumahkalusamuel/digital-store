<?php

use Phoenix\Database\Element\ColumnSettings;
use Phoenix\Migration\AbstractMigration;

class Settings extends AbstractMigration
{
    protected function up(): void
    {
        $this->table('settings')
            ->addColumn('setting', 'string', [ColumnSettings::SETTING_LENGTH => 191])
            ->addColumn('value', 'text', [ColumnSettings::SETTING_NULL => true])
            ->create();
    }

    protected function down(): void
    {
        $this->table('settings')
            ->drop();
    }
}
