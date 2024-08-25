<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240821215453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE custom_field (id UUID NOT NULL, school_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_98F8BD31C32A47EE ON custom_field (school_id)');
        $this->addSql('COMMENT ON COLUMN custom_field.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN custom_field.school_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN custom_field.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN custom_field.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE custom_form (id UUID NOT NULL, school_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_53FE35B2C32A47EE ON custom_form (school_id)');
        $this->addSql('COMMENT ON COLUMN custom_form.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN custom_form.school_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN custom_form.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN custom_form.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE custom_form_field (id UUID NOT NULL, form_id UUID NOT NULL, field_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E74DDD315FF69B7D ON custom_form_field (form_id)');
        $this->addSql('CREATE INDEX IDX_E74DDD31443707B0 ON custom_form_field (field_id)');
        $this->addSql('COMMENT ON COLUMN custom_form_field.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN custom_form_field.form_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN custom_form_field.field_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN custom_form_field.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN custom_form_field.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE school (id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN school.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN school.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN school.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE school_register_request (id UUID NOT NULL, custom_form_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A4057E9A58AFF2B0 ON school_register_request (custom_form_id)');
        $this->addSql('COMMENT ON COLUMN school_register_request.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN school_register_request.custom_form_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN school_register_request.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN school_register_request.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE custom_field ADD CONSTRAINT FK_98F8BD31C32A47EE FOREIGN KEY (school_id) REFERENCES school (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE custom_form ADD CONSTRAINT FK_53FE35B2C32A47EE FOREIGN KEY (school_id) REFERENCES school (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE custom_form_field ADD CONSTRAINT FK_E74DDD315FF69B7D FOREIGN KEY (form_id) REFERENCES custom_form (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE custom_form_field ADD CONSTRAINT FK_E74DDD31443707B0 FOREIGN KEY (field_id) REFERENCES custom_field (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE school_register_request ADD CONSTRAINT FK_A4057E9A58AFF2B0 FOREIGN KEY (custom_form_id) REFERENCES custom_form (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE custom_field DROP CONSTRAINT FK_98F8BD31C32A47EE');
        $this->addSql('ALTER TABLE custom_form DROP CONSTRAINT FK_53FE35B2C32A47EE');
        $this->addSql('ALTER TABLE custom_form_field DROP CONSTRAINT FK_E74DDD315FF69B7D');
        $this->addSql('ALTER TABLE custom_form_field DROP CONSTRAINT FK_E74DDD31443707B0');
        $this->addSql('ALTER TABLE school_register_request DROP CONSTRAINT FK_A4057E9A58AFF2B0');
        $this->addSql('DROP TABLE custom_field');
        $this->addSql('DROP TABLE custom_form');
        $this->addSql('DROP TABLE custom_form_field');
        $this->addSql('DROP TABLE school');
        $this->addSql('DROP TABLE school_register_request');
    }
}
