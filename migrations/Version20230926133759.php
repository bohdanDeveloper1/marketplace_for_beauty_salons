<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230926133759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salon ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE salon ADD CONSTRAINT FK_F268F4177E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F268F4177E3C61F9 ON salon (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salon DROP FOREIGN KEY FK_F268F4177E3C61F9');
        $this->addSql('DROP INDEX IDX_F268F4177E3C61F9 ON salon');
        $this->addSql('ALTER TABLE salon DROP owner_id');
    }
}
