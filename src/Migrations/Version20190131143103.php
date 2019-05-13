<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190131143103 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE chapter (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, story_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, votes INT NOT NULL, reports INT NOT NULL, validated TINYINT(1) NOT NULL, chapter_number INT DEFAULT NULL, INDEX IDX_F981B52EF675F31B (author_id), INDEX IDX_F981B52EAA5D4036 (story_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, story_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, reports INT NOT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526CAA5D4036 (story_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE story (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, chapter_limit INT NOT NULL, chapter_number INT NOT NULL, INDEX IDX_EB560438F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, image_name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, votes_received INT NOT NULL, votes_number INT NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chapter ADD CONSTRAINT FK_F981B52EF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chapter ADD CONSTRAINT FK_F981B52EAA5D4036 FOREIGN KEY (story_id) REFERENCES story (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CAA5D4036 FOREIGN KEY (story_id) REFERENCES story (id)');
        $this->addSql('ALTER TABLE story ADD CONSTRAINT FK_EB560438F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52EAA5D4036');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CAA5D4036');
        $this->addSql('ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52EF675F31B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE story DROP FOREIGN KEY FK_EB560438F675F31B');
        $this->addSql('DROP TABLE chapter');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE story');
        $this->addSql('DROP TABLE user');
    }
}
