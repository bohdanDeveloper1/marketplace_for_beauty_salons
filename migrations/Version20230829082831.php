<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230829082831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shedule DROP FOREIGN KEY FK_E7771B514066877A');
        $this->addSql('DROP INDEX IDX_E7771B514066877A ON shedule');
        $this->addSql('ALTER TABLE shedule ADD stylist_work_id INT NOT NULL, ADD duration INT NOT NULL, ADD time_start DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD time_end DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP stylist_id, DROP time, DROP day, DROP visit_time');
        $this->addSql('ALTER TABLE shedule ADD CONSTRAINT FK_E7771B5112FEB1B9 FOREIGN KEY (stylist_work_id) REFERENCES stylist_works (id)');
        $this->addSql('CREATE INDEX IDX_E7771B5112FEB1B9 ON shedule (stylist_work_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shedule DROP FOREIGN KEY FK_E7771B5112FEB1B9');
        $this->addSql('DROP INDEX IDX_E7771B5112FEB1B9 ON shedule');
        $this->addSql('ALTER TABLE shedule ADD stylist_id INT NOT NULL, ADD time INT NOT NULL, ADD day DATETIME NOT NULL, ADD visit_time TIME NOT NULL, DROP stylist_work_id, DROP duration, DROP time_start, DROP time_end');
        $this->addSql('ALTER TABLE shedule ADD CONSTRAINT FK_E7771B514066877A FOREIGN KEY (stylist_id) REFERENCES stylist_works (id)');
        $this->addSql('CREATE INDEX IDX_E7771B514066877A ON shedule (stylist_id)');
    }
}
