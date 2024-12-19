<?php

declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118141246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS event_streams (no BIGINT AUTO_INCREMENT NOT NULL, real_stream_name VARCHAR(150) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_bin`, stream_name CHAR(41) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_bin`, metadata LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_bin`, category VARCHAR(150) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_bin`, UNIQUE INDEX ix_rsn (real_stream_name), INDEX ix_cat (category), PRIMARY KEY(no)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_bin` ENGINE = InnoDB COMMENT = \'\' ',
        );
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS projections (no BIGINT AUTO_INCREMENT NOT NULL, name VARCHAR(150) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_bin`, position LONGTEXT CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_bin`, state LONGTEXT CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_bin`, status VARCHAR(28) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_bin`, locked_until CHAR(26) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_bin`, UNIQUE INDEX ix_name (name), PRIMARY KEY(no)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_bin` ENGINE = InnoDB COMMENT = \'\' ',
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE event_streams');
        $this->addSql('DROP TABLE projections');
    }
}
