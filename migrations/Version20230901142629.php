<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230901142629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE aliment (id INT AUTO_INCREMENT NOT NULL, sous_groupe_id INT DEFAULT NULL, aliment VARCHAR(50) NOT NULL, INDEX IDX_70FF972B614CDEC3 (sous_groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE aliment_allergene (aliment_id INT NOT NULL, allergene_id INT NOT NULL, INDEX IDX_DD58AE5415B9F11 (aliment_id), INDEX IDX_DD58AE54646AB2 (allergene_id), PRIMARY KEY(aliment_id, allergene_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE aliment_regime (aliment_id INT NOT NULL, regime_id INT NOT NULL, INDEX IDX_3F378172415B9F11 (aliment_id), INDEX IDX_3F37817235E7D534 (regime_id), PRIMARY KEY(aliment_id, regime_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE aliment_saison (aliment_id INT NOT NULL, saison_id INT NOT NULL, INDEX IDX_55611E88415B9F11 (aliment_id), INDEX IDX_55611E88F965414C (saison_id), PRIMARY KEY(aliment_id, saison_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE aliment ADD CONSTRAINT FK_70FF972B614CDEC3 FOREIGN KEY (sous_groupe_id) REFERENCES sous_groupe_ali (id)');
        $this->addSql('ALTER TABLE aliment_allergene ADD CONSTRAINT FK_DD58AE5415B9F11 FOREIGN KEY (aliment_id) REFERENCES aliment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aliment_allergene ADD CONSTRAINT FK_DD58AE54646AB2 FOREIGN KEY (allergene_id) REFERENCES allergene (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aliment_regime ADD CONSTRAINT FK_3F378172415B9F11 FOREIGN KEY (aliment_id) REFERENCES aliment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aliment_regime ADD CONSTRAINT FK_3F37817235E7D534 FOREIGN KEY (regime_id) REFERENCES regime (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aliment_saison ADD CONSTRAINT FK_55611E88415B9F11 FOREIGN KEY (aliment_id) REFERENCES aliment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aliment_saison ADD CONSTRAINT FK_55611E88F965414C FOREIGN KEY (saison_id) REFERENCES saison (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aliment DROP FOREIGN KEY FK_70FF972B614CDEC3');
        $this->addSql('ALTER TABLE aliment_allergene DROP FOREIGN KEY FK_DD58AE5415B9F11');
        $this->addSql('ALTER TABLE aliment_allergene DROP FOREIGN KEY FK_DD58AE54646AB2');
        $this->addSql('ALTER TABLE aliment_regime DROP FOREIGN KEY FK_3F378172415B9F11');
        $this->addSql('ALTER TABLE aliment_regime DROP FOREIGN KEY FK_3F37817235E7D534');
        $this->addSql('ALTER TABLE aliment_saison DROP FOREIGN KEY FK_55611E88415B9F11');
        $this->addSql('ALTER TABLE aliment_saison DROP FOREIGN KEY FK_55611E88F965414C');
        $this->addSql('DROP TABLE aliment');
        $this->addSql('DROP TABLE aliment_allergene');
        $this->addSql('DROP TABLE aliment_regime');
        $this->addSql('DROP TABLE aliment_saison');
    }
}
