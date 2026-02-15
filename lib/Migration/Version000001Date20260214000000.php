<?php

declare(strict_types=1);

namespace OCA\TalkDiscordWebhook\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\DB\Types;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

class Version000001Date20260214000000 extends SimpleMigrationStep {

    public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
        /** @var ISchemaWrapper $schema */
        $schema = $schemaClosure();

        if (!$schema->hasTable('talk_dw_webhooks')) {
            $table = $schema->createTable('talk_dw_webhooks');

            $table->addColumn('id', Types::BIGINT, [
                'autoincrement' => true,
                'notnull' => true,
                'length' => 20,
            ]);
            $table->addColumn('title', Types::STRING, [
                'notnull' => true,
                'length' => 255,
                'default' => '',
            ]);
            $table->addColumn('description', Types::TEXT, [
                'notnull' => false,
                'default' => '',
            ]);
            $table->addColumn('token', Types::STRING, [
                'notnull' => true,
                'length' => 64,
            ]);
            $table->addColumn('conversation_token', Types::STRING, [
                'notnull' => true,
                'length' => 64,
            ]);
            $table->addColumn('user_id', Types::STRING, [
                'notnull' => true,
                'length' => 64,
            ]);
            $table->addColumn('created_at', Types::DATETIME, [
                'notnull' => true,
            ]);

            $table->setPrimaryKey(['id']);
            $table->addUniqueIndex(['token'], 'talk_dw_token_idx');
            $table->addIndex(['user_id'], 'talk_dw_user_idx');
            $table->addIndex(['conversation_token'], 'talk_dw_conv_idx');
        }

        return $schema;
    }
}