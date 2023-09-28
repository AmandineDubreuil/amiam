<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230928074504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE repas DROP FOREIGN KEY FK_A8D351B33E2ED6D6');
        $this->addSql('ALTER TABLE repas ADD CONSTRAINT FK_A8D351B33E2ED6D6 FOREIGN KEY (recettes_id) REFERENCES recette (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE repas DROP FOREIGN KEY FK_A8D351B33E2ED6D6');
        $this->addSql('ALTER TABLE repas ADD CONSTRAINT FK_A8D351B33E2ED6D6 FOREIGN KEY (recettes_id) REFERENCES recette (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
