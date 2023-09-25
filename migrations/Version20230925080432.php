<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230925080432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE repas_ami (repas_id INT NOT NULL, ami_id INT NOT NULL, INDEX IDX_FA3B51C1D236AAA (repas_id), INDEX IDX_FA3B51CCCE66A0B (ami_id), PRIMARY KEY(repas_id, ami_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE repas_ami ADD CONSTRAINT FK_FA3B51C1D236AAA FOREIGN KEY (repas_id) REFERENCES repas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE repas_ami ADD CONSTRAINT FK_FA3B51CCCE66A0B FOREIGN KEY (ami_id) REFERENCES ami (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE repas_ami DROP FOREIGN KEY FK_FA3B51C1D236AAA');
        $this->addSql('ALTER TABLE repas_ami DROP FOREIGN KEY FK_FA3B51CCCE66A0B');
        $this->addSql('DROP TABLE repas_ami');
    }
}
