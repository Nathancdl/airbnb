<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230627133250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_logement (user_id INT NOT NULL, logement_id INT NOT NULL, INDEX IDX_1070A057A76ED395 (user_id), INDEX IDX_1070A05758ABF955 (logement_id), PRIMARY KEY(user_id, logement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_logement ADD CONSTRAINT FK_1070A057A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_logement ADD CONSTRAINT FK_1070A05758ABF955 FOREIGN KEY (logement_id) REFERENCES logements (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_logement DROP FOREIGN KEY FK_1070A057A76ED395');
        $this->addSql('ALTER TABLE user_logement DROP FOREIGN KEY FK_1070A05758ABF955');
        $this->addSql('DROP TABLE user_logement');
    }
}
