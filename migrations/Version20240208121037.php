<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240208121037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE city ADD coordinates_latitude VARCHAR(255) DEFAULT NULL, ADD coordinates_longtitude VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE salon ADD CONSTRAINT FK_F268F417558BFC9A FOREIGN KEY (belong_to_city_id) REFERENCES city (id)');
        $this->addSql('CREATE INDEX IDX_F268F417558BFC9A ON salon (belong_to_city_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE city DROP coordinates_latitude, DROP coordinates_longtitude');
        $this->addSql('ALTER TABLE salon DROP FOREIGN KEY FK_F268F417558BFC9A');
        $this->addSql('DROP INDEX IDX_F268F417558BFC9A ON salon');
    }
}
