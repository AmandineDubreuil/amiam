<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230913090652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ami_aliment_allergie (ami_id INT NOT NULL, aliment_id INT NOT NULL, INDEX IDX_EA1A332CCCE66A0B (ami_id), INDEX IDX_EA1A332C415B9F11 (aliment_id), PRIMARY KEY(ami_id, aliment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ami_aliment_allergie ADD CONSTRAINT FK_EA1A332CCCE66A0B FOREIGN KEY (ami_id) REFERENCES ami (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ami_aliment_allergie ADD CONSTRAINT FK_EA1A332C415B9F11 FOREIGN KEY (aliment_id) REFERENCES aliment (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ami_aliment_allergie DROP FOREIGN KEY FK_EA1A332CCCE66A0B');
        $this->addSql('ALTER TABLE ami_aliment_allergie DROP FOREIGN KEY FK_EA1A332C415B9F11');
        $this->addSql('DROP TABLE ami_aliment_allergie');
    }
}
