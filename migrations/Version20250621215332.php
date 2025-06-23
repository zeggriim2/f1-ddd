<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250621215332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE championships (id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', season INT NOT NULL, name VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE drivers (id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, nationality VARCHAR(100) NOT NULL, number INT NOT NULL, race_count INT NOT NULL, UNIQUE INDEX UNIQ_E410C30796901F54 (number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE race_results (id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', race_id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', driver_id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', position_value VARCHAR(255) DEFAULT NULL, best_lap_time_minutes INT NOT NULL, best_lap_time_seconds INT NOT NULL, best_lap_time_milliseconds INT NOT NULL, points_value INT NOT NULL, INDEX IDX_801331646E59D40D (race_id), INDEX IDX_80133164C3423909 (driver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE races (id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', name VARCHAR(200) NOT NULL, circuit VARCHAR(200) NOT NULL, date DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE race_results ADD CONSTRAINT FK_801331646E59D40D FOREIGN KEY (race_id) REFERENCES races (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE race_results ADD CONSTRAINT FK_80133164C3423909 FOREIGN KEY (driver_id) REFERENCES drivers (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE race_results DROP FOREIGN KEY FK_801331646E59D40D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE race_results DROP FOREIGN KEY FK_80133164C3423909
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE championships
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE drivers
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE race_results
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE races
        SQL);
    }
}
