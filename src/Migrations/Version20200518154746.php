<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200518154746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teaching_subject (id INT AUTO_INCREMENT NOT NULL, teaching_subject VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mark (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, teaching_subject_id INT NOT NULL, mark INT NOT NULL, date DATE NOT NULL, INDEX IDX_6674F271CB944F1A (student_id), INDEX IDX_6674F271A1445E43 (teaching_subject_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mark ADD CONSTRAINT FK_6674F271CB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mark ADD CONSTRAINT FK_6674F271A1445E43 FOREIGN KEY (teaching_subject_id) REFERENCES teaching_subject (id)');
        $this->addSql('INSERT INTO user (username, name, surname, roles, password) VALUES
        (\'admin\', \'admin\', \'admin\', \'[]\', \'$argon2i$v=19$m=65536,t=4,p=1$SU9UVGVIUXFELlBmZ1pNcw$61mx6DV1U7YE1u8K5sHfrzEs+mHJLsVDy1TwB3bXdM0\')');
        $this->addSql('INSERT INTO `teaching_subject` (`teaching_subject`) VALUES
        ( \'Matematika\'),
        ( \'Istorija\'),
        ( \'Lietuvių kalba\'),
        ( \'Anglų kalba\'),
        ( \'Rusų kalba\'),
        ( \'Geografija\'),
        ( \'Fizika\'),
        ( \'Chemija\'),
        ( \'Biologija\'),
        ( \'Informatika\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mark DROP FOREIGN KEY FK_6674F271CB944F1A');
        $this->addSql('ALTER TABLE mark DROP FOREIGN KEY FK_6674F271A1445E43');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE teaching_subject');
        $this->addSql('DROP TABLE mark');
    }
}
