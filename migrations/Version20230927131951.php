<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230927131951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salon ADD salon_owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE salon ADD CONSTRAINT FK_F268F41737FAB01 FOREIGN KEY (salon_owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F268F41737FAB01 ON salon (salon_owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salon DROP FOREIGN KEY FK_F268F41737FAB01');
        $this->addSql('DROP INDEX IDX_F268F41737FAB01 ON salon');
        $this->addSql('ALTER TABLE salon DROP salon_owner_id');
    }
}
