<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230601153248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logements ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE logements ADD CONSTRAINT FK_EEF1F618A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EEF1F618A76ED395 ON logements (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logements DROP FOREIGN KEY FK_EEF1F618A76ED395');
        $this->addSql('DROP INDEX IDX_EEF1F618A76ED395 ON logements');
        $this->addSql('ALTER TABLE logements DROP user_id');
    }
}
