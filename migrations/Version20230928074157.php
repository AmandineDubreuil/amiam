<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230928074157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ami DROP FOREIGN KEY FK_5269B41397A77B84');
        $this->addSql('ALTER TABLE ami ADD CONSTRAINT FK_5269B41397A77B84 FOREIGN KEY (famille_id) REFERENCES ami_famille (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ami DROP FOREIGN KEY FK_5269B41397A77B84');
        $this->addSql('ALTER TABLE ami ADD CONSTRAINT FK_5269B41397A77B84 FOREIGN KEY (famille_id) REFERENCES ami_famille (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
