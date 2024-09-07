<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240907135136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add more data to school entities';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE custom_field ADD title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE custom_field ADD tooltip TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE custom_field ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE custom_form ADD title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE custom_form ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE custom_form ADD "order" SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE custom_form ADD start_date TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE custom_form ADD end_date TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE custom_form_field ADD position SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE custom_form_field ADD half BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE school_staff ADD role VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD verified BOOLEAN DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE custom_field DROP title');
        $this->addSql('ALTER TABLE custom_field DROP tooltip');
        $this->addSql('ALTER TABLE custom_field DROP type');
        $this->addSql('ALTER TABLE custom_form_field DROP position');
        $this->addSql('ALTER TABLE custom_form_field DROP half');
        $this->addSql('ALTER TABLE custom_form DROP title');
        $this->addSql('ALTER TABLE custom_form DROP description');
        $this->addSql('ALTER TABLE custom_form DROP "order"');
        $this->addSql('ALTER TABLE custom_form DROP start_date');
        $this->addSql('ALTER TABLE custom_form DROP end_date');
        $this->addSql('ALTER TABLE school_staff DROP role');
        $this->addSql('ALTER TABLE "user" DROP verified');
    }
}
