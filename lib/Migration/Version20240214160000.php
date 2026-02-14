<?php

declare(strict_types = 1)
;

namespace OCA\TalkDiscordWebhook\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;
use OCP\Migration\IRequest;

class Version20240214160000 extends SimpleMigrationStep
{

    public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper
    {
        /** @var ISchemaWrapper $schema */
        $schema = $schemaClosure();

        if (!$schema->hasTable('talk_discord_webhooks')) {
            $table = $schema->createTable('talk_discord_webhooks');
            $table->addColumn('id', 'integer', [
                'autoincrement' => true,
                'notnull' => true,
            ]);
            $table->addColumn('token', 'string', [
                'notnull' => true,
                'length' => 64,
            ]);
            $table->addColumn('room_token', 'string', [
                'notnull' => true,
                'length' => 64,
            ]);
            $table->addColumn('name', 'string', [
                'notnull' => true,
                'length' => 64,
            ]);
            $table->addColumn('user_id', 'string', [
                'notnull' => true,
                'length' => 64,
            ]);
            $table->setPrimaryKey(['id']);
            $table->addIndex(['token'], 'webhook_token_index');
            $table->addIndex(['user_id'], 'webhook_user_id_index');
        }

        return $schema;
    }
}
