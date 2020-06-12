<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200611061430 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE message (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, person_id INTEGER DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, posted DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_B6BD307F217BBB47 ON message (person_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__person AS SELECT id, name, mail, age FROM person');
        $this->addSql('DROP TABLE person');
        $this->addSql('CREATE TABLE person (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, mail VARCHAR(255) DEFAULT NULL COLLATE BINARY, age INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO person (id, name, mail, age) SELECT id, name, mail, age FROM __temp__person');
        $this->addSql('DROP TABLE __temp__person');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE message');
        $this->addSql('CREATE TEMPORARY TABLE __temp__person AS SELECT id, name, mail, age FROM person');
        $this->addSql('DROP TABLE person');
        $this->addSql('CREATE TABLE person (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, mail VARCHAR(255) DEFAULT NULL, age INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO person (id, name, mail, age) SELECT id, name, mail, age FROM __temp__person');
        $this->addSql('DROP TABLE __temp__person');
    }
}
