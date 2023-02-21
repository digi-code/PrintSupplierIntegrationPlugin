<?php declare(strict_types=1);

namespace GamesealPlugin\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1676913185CreateCurrencyTable extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1676913185;
    }

    public function update(Connection $connection): void
    {
        $sql = <<<SQL
                CREATE TABLE IF NOT EXISTS `gameseal_currency_exchange`
                (
                    `id`          BINARY(16)  NOT NULL,
                    `code`        VARCHAR(255) COLLATE utf8mb4_unicode_ci,
                    `value`       FLOAT COLLATE utf8mb4_unicode_ci,
                    `created_at`  DATETIME(3)NOT NULL,
                    `updated_at`  DATETIME(3)NULL,
                    `last_updated_at`  DATETIME(3)NOT NULL,
                    PRIMARY KEY (`id`)
                )
                    ENGINE = InnoDB
                    DEFAULT CHARSET = utf8mb4
                    COLLATE = utf8mb4_unicode_ci;
                SQL;
        $connection->executeStatement($sql);
    }

    public function updateDestructive(Connection $connection): void
    {
    }


}
