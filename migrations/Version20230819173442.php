<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230819173442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stylist_works (id INT AUTO_INCREMENT NOT NULL, stylist_id INT NOT NULL, service_name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, photo VARCHAR(255) DEFAULT NULL, INDEX IDX_66957E034066877A (stylist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stylist_works ADD CONSTRAINT FK_66957E034066877A FOREIGN KEY (stylist_id) REFERENCES stylist (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stylist_works DROP FOREIGN KEY FK_66957E034066877A');
        $this->addSql('DROP TABLE stylist_works');
    }
}
