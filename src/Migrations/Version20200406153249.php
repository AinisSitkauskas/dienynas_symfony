<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200406153249 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mark DROP FOREIGN KEY student_id');
        $this->addSql('ALTER TABLE mark DROP FOREIGN KEY teaching_subject_id');
        $this->addSql('DROP INDEX student_id ON mark');
        $this->addSql('DROP INDEX teaching_subject_id ON mark');
        $this->addSql('ALTER TABLE mark ADD fk_student_id INT NOT NULL, ADD fk_teaching_subject_id INT NOT NULL, DROP student_id, DROP teaching_subject_id, CHANGE id id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE mark ADD CONSTRAINT FK_6674F2713BFA8589 FOREIGN KEY (fk_student_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mark ADD CONSTRAINT FK_6674F271772155E6 FOREIGN KEY (fk_teaching_subject_id) REFERENCES teaching_subject (id)');
        $this->addSql('CREATE INDEX IDX_6674F2713BFA8589 ON mark (fk_student_id)');
        $this->addSql('CREATE INDEX IDX_6674F271772155E6 ON mark (fk_teaching_subject_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mark MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE mark DROP FOREIGN KEY FK_6674F2713BFA8589');
        $this->addSql('ALTER TABLE mark DROP FOREIGN KEY FK_6674F271772155E6');
        $this->addSql('DROP INDEX IDX_6674F2713BFA8589 ON mark');
        $this->addSql('DROP INDEX IDX_6674F271772155E6 ON mark');
        $this->addSql('ALTER TABLE mark DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE mark ADD student_id INT NOT NULL, ADD teaching_subject_id INT NOT NULL, DROP fk_student_id, DROP fk_teaching_subject_id, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE mark ADD CONSTRAINT student_id FOREIGN KEY (student_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mark ADD CONSTRAINT teaching_subject_id FOREIGN KEY (teaching_subject_id) REFERENCES teaching_subject (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX student_id ON mark (student_id)');
        $this->addSql('CREATE INDEX teaching_subject_id ON mark (teaching_subject_id)');
    }
}
