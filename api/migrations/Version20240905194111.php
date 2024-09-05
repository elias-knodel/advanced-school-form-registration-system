<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240905194111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE email_verification (id UUID NOT NULL, owner_id UUID NOT NULL, verification_key VARCHAR(64) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FE2235818709EF ON email_verification (verification_key)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FE223587E3C61F9 ON email_verification (owner_id)');
        $this->addSql('COMMENT ON COLUMN email_verification.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN email_verification.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE email_verification ADD CONSTRAINT FK_FE223587E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE email_verification DROP CONSTRAINT FK_FE223587E3C61F9');
        $this->addSql('DROP TABLE email_verification');
        $this->addSql('DROP TABLE "user"');
    }
}
