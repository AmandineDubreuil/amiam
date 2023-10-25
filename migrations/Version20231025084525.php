<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025084525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ami_sous_groupe_ali (ami_id INT NOT NULL, sous_groupe_ali_id INT NOT NULL, INDEX IDX_D39F99CFCCE66A0B (ami_id), INDEX IDX_D39F99CFA7DFBA0E (sous_groupe_ali_id), PRIMARY KEY(ami_id, sous_groupe_ali_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ami_sous_groupe_ali ADD CONSTRAINT FK_D39F99CFCCE66A0B FOREIGN KEY (ami_id) REFERENCES ami (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ami_sous_groupe_ali ADD CONSTRAINT FK_D39F99CFA7DFBA0E FOREIGN KEY (sous_groupe_ali_id) REFERENCES sous_groupe_ali (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ami_sous_groupe_ali DROP FOREIGN KEY FK_D39F99CFCCE66A0B');
        $this->addSql('ALTER TABLE ami_sous_groupe_ali DROP FOREIGN KEY FK_D39F99CFA7DFBA0E');
        $this->addSql('DROP TABLE ami_sous_groupe_ali');
    }
}
