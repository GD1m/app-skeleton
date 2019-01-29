<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190129085056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial schema';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE actions (
          id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          title VARCHAR(50) NOT NULL,
          todoList_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          completed TINYINT(1) NOT NULL,
          createdAt DATETIME NOT NULL,
          updatedAt DATETIME NOT NULL,
          INDEX IDX_548F1EF62AB5DB6 (todoList_id),
          PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');

        $this->addSql('CREATE TABLE sessions (
          id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          token VARCHAR(255) NOT NULL,
          created_at DATETIME NOT NULL,
          UNIQUE INDEX UNIQ_9A609D135F37A13B (token),
          INDEX IDX_9A609D13A76ED395 (user_id),
          PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');

        $this->addSql('CREATE TABLE todo_lists (
          id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          title VARCHAR(50) NOT NULL,
          createdAt DATETIME NOT NULL,
          updatedAt DATETIME NOT NULL,
          INDEX IDX_85714336A76ED395 (user_id),
          PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
      ');

        $this->addSql('CREATE TABLE users (
          id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          username VARCHAR(50) NOT NULL,
          password VARCHAR(255) NOT NULL,
          created_at DATETIME NOT NULL,
          UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username),
          PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
      ');

        $this->addSql('ALTER TABLE actions ADD CONSTRAINT FK_548F1EF62AB5DB6 FOREIGN KEY (todoList_id) REFERENCES todo_lists (id)');

        $this->addSql('ALTER TABLE sessions ADD CONSTRAINT FK_9A609D13A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');

        $this->addSql('ALTER TABLE todo_lists ADD CONSTRAINT FK_85714336A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE actions DROP FOREIGN KEY FK_548F1EF62AB5DB6');
        $this->addSql('ALTER TABLE sessions DROP FOREIGN KEY FK_9A609D13A76ED395');
        $this->addSql('ALTER TABLE todo_lists DROP FOREIGN KEY FK_85714336A76ED395');
        $this->addSql('DROP TABLE actions');
        $this->addSql('DROP TABLE sessions');
        $this->addSql('DROP TABLE todo_lists');
        $this->addSql('DROP TABLE users');
    }
}
