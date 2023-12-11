<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230926132319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE salon DROP FOREIGN KEY FK_F268F4174C91BDE4');
//        $this->addSql('DROP INDEX IDX_F268F4174C91BDE4 ON salon');
//        $this->addSql('ALTER TABLE salon DROP salon_id');
//        $this->addSql('ALTER TABLE service DROP salon_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salon ADD salon_id INT NOT NULL');
        $this->addSql('ALTER TABLE salon ADD CONSTRAINT FK_F268F4174C91BDE4 FOREIGN KEY (salon_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F268F4174C91BDE4 ON salon (salon_id)');
        $this->addSql('ALTER TABLE service ADD salon_id INT NOT NULL');
    }
}
