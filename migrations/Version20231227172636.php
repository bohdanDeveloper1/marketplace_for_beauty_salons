<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231227172636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE city DROP name_of_city, DROP photo_of_city');
        $this->addSql('ALTER TABLE salon DROP city_name');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE city ADD name_of_city VARCHAR(255) NOT NULL, ADD photo_of_city VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE salon ADD city_name VARCHAR(255) NOT NULL');
    }
}
