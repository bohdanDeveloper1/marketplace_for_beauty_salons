<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230821152230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shedule (id INT AUTO_INCREMENT NOT NULL, stylist_id INT NOT NULL, time INT NOT NULL, day DATETIME NOT NULL, service_name VARCHAR(255) NOT NULL, service_price DOUBLE PRECISION NOT NULL, INDEX IDX_E7771B514066877A (stylist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shedule ADD CONSTRAINT FK_E7771B514066877A FOREIGN KEY (stylist_id) REFERENCES stylist_works (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shedule DROP FOREIGN KEY FK_E7771B514066877A');
        $this->addSql('DROP TABLE shedule');
    }
}
