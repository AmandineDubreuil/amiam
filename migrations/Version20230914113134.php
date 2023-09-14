<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230914113134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE repas (id INT AUTO_INCREMENT NOT NULL, recettes_id INT DEFAULT NULL, date DATE NOT NULL, commentaire VARCHAR(255) DEFAULT NULL, INDEX IDX_A8D351B33E2ED6D6 (recettes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repas_ami_famille (repas_id INT NOT NULL, ami_famille_id INT NOT NULL, INDEX IDX_14BAA39C1D236AAA (repas_id), INDEX IDX_14BAA39CA326C131 (ami_famille_id), PRIMARY KEY(repas_id, ami_famille_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE repas ADD CONSTRAINT FK_A8D351B33E2ED6D6 FOREIGN KEY (recettes_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE repas_ami_famille ADD CONSTRAINT FK_14BAA39C1D236AAA FOREIGN KEY (repas_id) REFERENCES repas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE repas_ami_famille ADD CONSTRAINT FK_14BAA39CA326C131 FOREIGN KEY (ami_famille_id) REFERENCES ami_famille (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE repas DROP FOREIGN KEY FK_A8D351B33E2ED6D6');
        $this->addSql('ALTER TABLE repas_ami_famille DROP FOREIGN KEY FK_14BAA39C1D236AAA');
        $this->addSql('ALTER TABLE repas_ami_famille DROP FOREIGN KEY FK_14BAA39CA326C131');
        $this->addSql('DROP TABLE repas');
        $this->addSql('DROP TABLE repas_ami_famille');
    }
}
