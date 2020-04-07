<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200319161744 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE teaching_subject (id INT AUTO_INCREMENT NOT NULL, teaching_subject VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO `teaching_subject` (`id`, `teaching_subject`) VALUES
        (1, \'Matematika\'),
        (2, \'Istorija\'),
        (3, \'Lietuvių kalba\'),
        (4, \'Anglų kalba\'),
        (5, \'Rusų kalba\'),
        (6, \'Geografija\'),
        (7, \'Fizika\'),
        (8, \'Chemija\'),
        (9, \'Biologija\'),
        (10, \'Informatika\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE teaching_subject');
    }
}