<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250310113845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD stripe_product_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product DROP strice_product_id');
        $this->addSql('ALTER TABLE product DROP image_file');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product ADD image_file VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product RENAME COLUMN stripe_product_id TO strice_product_id');
    }
}
