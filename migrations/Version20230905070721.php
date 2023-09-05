<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230905070721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mesure (id INT AUTO_INCREMENT NOT NULL, mesure VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette_ingredient (id INT AUTO_INCREMENT NOT NULL, aliment_id INT DEFAULT NULL, mesure_id INT DEFAULT NULL, recette_id INT DEFAULT NULL, quantite DOUBLE PRECISION NOT NULL, INDEX IDX_17C041A9415B9F11 (aliment_id), INDEX IDX_17C041A943AB22FA (mesure_id), INDEX IDX_17C041A989312FE9 (recette_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recette_ingredient ADD CONSTRAINT FK_17C041A9415B9F11 FOREIGN KEY (aliment_id) REFERENCES aliment (id)');
        $this->addSql('ALTER TABLE recette_ingredient ADD CONSTRAINT FK_17C041A943AB22FA FOREIGN KEY (mesure_id) REFERENCES mesure (id)');
        $this->addSql('ALTER TABLE recette_ingredient ADD CONSTRAINT FK_17C041A989312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE recette ADD categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390BCF5E72D FOREIGN KEY (categorie_id) REFERENCES recette_categorie (id)');
        $this->addSql('CREATE INDEX IDX_49BB6390BCF5E72D ON recette (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette_ingredient DROP FOREIGN KEY FK_17C041A9415B9F11');
        $this->addSql('ALTER TABLE recette_ingredient DROP FOREIGN KEY FK_17C041A943AB22FA');
        $this->addSql('ALTER TABLE recette_ingredient DROP FOREIGN KEY FK_17C041A989312FE9');
        $this->addSql('DROP TABLE mesure');
        $this->addSql('DROP TABLE recette_ingredient');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390BCF5E72D');
        $this->addSql('DROP INDEX IDX_49BB6390BCF5E72D ON recette');
        $this->addSql('ALTER TABLE recette DROP categorie_id');
    }
}
