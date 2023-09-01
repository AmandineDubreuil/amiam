<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230901132318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupe_ali (id INT AUTO_INCREMENT NOT NULL, groupe VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saison (id INT AUTO_INCREMENT NOT NULL, mois_l VARCHAR(20) NOT NULL, mois_c INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sous_groupe_ali (id INT AUTO_INCREMENT NOT NULL, groupe_id INT DEFAULT NULL, sous_groupe VARCHAR(50) NOT NULL, INDEX IDX_22AA32157A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sous_groupe_ali ADD CONSTRAINT FK_22AA32157A45358C FOREIGN KEY (groupe_id) REFERENCES groupe_ali (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sous_groupe_ali DROP FOREIGN KEY FK_22AA32157A45358C');
        $this->addSql('DROP TABLE groupe_ali');
        $this->addSql('DROP TABLE saison');
        $this->addSql('DROP TABLE sous_groupe_ali');
    }
}
