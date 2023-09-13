<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230912140754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ami (id INT AUTO_INCREMENT NOT NULL, famille_id INT DEFAULT NULL, prenom VARCHAR(50) NOT NULL, date_naissance DATE NOT NULL, INDEX IDX_5269B41397A77B84 (famille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ami_regime (ami_id INT NOT NULL, regime_id INT NOT NULL, INDEX IDX_71FEA8DACCE66A0B (ami_id), INDEX IDX_71FEA8DA35E7D534 (regime_id), PRIMARY KEY(ami_id, regime_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ami_allergene (ami_id INT NOT NULL, allergene_id INT NOT NULL, INDEX IDX_7C40638CCCE66A0B (ami_id), INDEX IDX_7C40638C4646AB2 (allergene_id), PRIMARY KEY(ami_id, allergene_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ami_aliment (ami_id INT NOT NULL, aliment_id INT NOT NULL, INDEX IDX_4F91E914CCE66A0B (ami_id), INDEX IDX_4F91E914415B9F11 (aliment_id), PRIMARY KEY(ami_id, aliment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ami ADD CONSTRAINT FK_5269B41397A77B84 FOREIGN KEY (famille_id) REFERENCES ami_famille (id)');
        $this->addSql('ALTER TABLE ami_regime ADD CONSTRAINT FK_71FEA8DACCE66A0B FOREIGN KEY (ami_id) REFERENCES ami (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ami_regime ADD CONSTRAINT FK_71FEA8DA35E7D534 FOREIGN KEY (regime_id) REFERENCES regime (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ami_allergene ADD CONSTRAINT FK_7C40638CCCE66A0B FOREIGN KEY (ami_id) REFERENCES ami (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ami_allergene ADD CONSTRAINT FK_7C40638C4646AB2 FOREIGN KEY (allergene_id) REFERENCES allergene (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ami_aliment ADD CONSTRAINT FK_4F91E914CCE66A0B FOREIGN KEY (ami_id) REFERENCES ami (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ami_aliment ADD CONSTRAINT FK_4F91E914415B9F11 FOREIGN KEY (aliment_id) REFERENCES aliment (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ami DROP FOREIGN KEY FK_5269B41397A77B84');
        $this->addSql('ALTER TABLE ami_regime DROP FOREIGN KEY FK_71FEA8DACCE66A0B');
        $this->addSql('ALTER TABLE ami_regime DROP FOREIGN KEY FK_71FEA8DA35E7D534');
        $this->addSql('ALTER TABLE ami_allergene DROP FOREIGN KEY FK_7C40638CCCE66A0B');
        $this->addSql('ALTER TABLE ami_allergene DROP FOREIGN KEY FK_7C40638C4646AB2');
        $this->addSql('ALTER TABLE ami_aliment DROP FOREIGN KEY FK_4F91E914CCE66A0B');
        $this->addSql('ALTER TABLE ami_aliment DROP FOREIGN KEY FK_4F91E914415B9F11');
        $this->addSql('DROP TABLE ami');
        $this->addSql('DROP TABLE ami_regime');
        $this->addSql('DROP TABLE ami_allergene');
        $this->addSql('DROP TABLE ami_aliment');
    }
}
