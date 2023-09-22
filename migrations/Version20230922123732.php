<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230922123732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398D28E4653');
        $this->addSql('DROP INDEX IDX_F5299398D28E4653 ON `order`');
        $this->addSql('ALTER TABLE `order` ADD client_name VARCHAR(255) NOT NULL, DROP usertemp_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD usertemp_id INT DEFAULT NULL, DROP client_name');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398D28E4653 FOREIGN KEY (usertemp_id) REFERENCES temp_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F5299398D28E4653 ON `order` (usertemp_id)');
    }
}
