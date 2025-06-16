<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250610134510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE driver (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, name VARCHAR(255) NOT NULL, abbreviation VARCHAR(3) NOT NULL, race_number INT NOT NULL, INDEX IDX_11667CD9296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE race (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, circuit VARCHAR(255) NOT NULL, date DATETIME NOT NULL, total_laps INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE race_result (id INT AUTO_INCREMENT NOT NULL, race_id INT NOT NULL, driver_id INT NOT NULL, position INT NOT NULL, best_lap_ms INT NOT NULL, points INT NOT NULL, recorded_at DATETIME NOT NULL, INDEX IDX_793CDFC06E59D40D (race_id), INDEX IDX_793CDFC0C3423909 (driver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE driver ADD CONSTRAINT FK_11667CD9296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE race_result ADD CONSTRAINT FK_793CDFC06E59D40D FOREIGN KEY (race_id) REFERENCES race (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE race_result ADD CONSTRAINT FK_793CDFC0C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE driver DROP FOREIGN KEY FK_11667CD9296CD8AE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE race_result DROP FOREIGN KEY FK_793CDFC06E59D40D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE race_result DROP FOREIGN KEY FK_793CDFC0C3423909
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE driver
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE race
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE race_result
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE team
        SQL);
    }
}
