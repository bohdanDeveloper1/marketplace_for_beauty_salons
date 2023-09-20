<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230907134819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shedule ADD day DATETIME NOT NULL, ADD start_time TIME NOT NULL, ADD end_time TIME NOT NULL, ADD stylist_id INT NOT NULL, DROP time_start, DROP time_end');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shedule ADD time_start DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD time_end DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP day, DROP start_time, DROP end_time, DROP stylist_id');
    }
}
