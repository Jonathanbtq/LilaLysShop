<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230922121519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD user_id INT DEFAULT NULL, ADD usertemp_id INT DEFAULT NULL, ADD adresse_fact VARCHAR(255) DEFAULT NULL, ADD telephone VARCHAR(255) NOT NULL, ADD fact_mail VARCHAR(255) NOT NULL, ADD work_modele VARCHAR(3) DEFAULT NULL, ADD description VARCHAR(255) DEFAULT NULL, ADD is_promo TINYINT(1) NOT NULL, ADD total_discount DOUBLE PRECISION NOT NULL, ADD nb_product INT NOT NULL, ADD ship_price DOUBLE PRECISION NOT NULL, ADD order_date DATE NOT NULL, ADD date_bon_commande DATE NOT NULL, ADD price_bfr_taxe DOUBLE PRECISION NOT NULL, ADD sous_total DOUBLE PRECISION NOT NULL, ADD sous_total_taxe DOUBLE PRECISION NOT NULL, ADD total_price DOUBLE PRECISION NOT NULL, ADD type_taxe_local VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398D28E4653 FOREIGN KEY (usertemp_id) REFERENCES temp_user (id)');
        $this->addSql('CREATE INDEX IDX_F5299398A76ED395 ON `order` (user_id)');
        $this->addSql('CREATE INDEX IDX_F5299398D28E4653 ON `order` (usertemp_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398D28E4653');
        $this->addSql('DROP INDEX IDX_F5299398A76ED395 ON `order`');
        $this->addSql('DROP INDEX IDX_F5299398D28E4653 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP user_id, DROP usertemp_id, DROP adresse_fact, DROP telephone, DROP fact_mail, DROP work_modele, DROP description, DROP is_promo, DROP total_discount, DROP nb_product, DROP ship_price, DROP order_date, DROP date_bon_commande, DROP price_bfr_taxe, DROP sous_total, DROP sous_total_taxe, DROP total_price, DROP type_taxe_local');
    }
}
