<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240825214034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE email_verification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE email_verification (id INT NOT NULL, owner_id UUID NOT NULL, verification_key VARCHAR(64) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FE223587E3C61F9 ON email_verification (owner_id)');
        $this->addSql('COMMENT ON COLUMN email_verification.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE email_verification ADD CONSTRAINT FK_FE223587E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE email_verification_id_seq CASCADE');
        $this->addSql('ALTER TABLE email_verification DROP CONSTRAINT FK_FE223587E3C61F9');
        $this->addSql('DROP TABLE email_verification');
    }
}
