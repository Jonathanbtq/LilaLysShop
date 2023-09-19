<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230919154249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE city ADD insee_code VARCHAR(255) NOT NULL, ADD city_code LONGTEXT NOT NULL, ADD label LONGTEXT NOT NULL, ADD region_geojson_name LONGTEXT NOT NULL, DROP city_name');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE city ADD city_name VARCHAR(50) NOT NULL, DROP insee_code, DROP city_code, DROP label, DROP region_geojson_name');
    }
}
