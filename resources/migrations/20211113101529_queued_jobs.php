<?php

use Phoenix\Database\Element\ColumnSettings;
use Phoenix\Migration\AbstractMigration;

class QueuedJobs extends AbstractMigration
{
    protected function up(): void
    {

        $this->table('queued_jobs')
            ->addColumn('type', 'string', [
                ColumnSettings::SETTING_LENGTH => 20,
                ColumnSettings::SETTING_DEFAULT => 'mail',
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('data', 'text', [
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('status', 'enum', [
                ColumnSettings::SETTING_VALUES => array('queued', 'processing', 'done', 'failed'),
                ColumnSettings::SETTING_DEFAULT => 'queued',
                ColumnSettings::SETTING_NULL => true,
            ])
            ->addColumn('error', 'text', [
                ColumnSettings::SETTING_NULL => true
            ])
            ->addColumn('created_at', 'timestamp', [
                ColumnSettings::SETTING_DEFAULT => ColumnSettings::DEFAULT_VALUE_CURRENT_TIMESTAMP
            ])
            ->create();
    }

    protected function down(): void
    {
        $this->table('queued_jobs')
            ->drop();
    }
}
