<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230922140229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier_produit (id INT AUTO_INCREMENT NOT NULL, cart_id INT DEFAULT NULL, id_client_id INT DEFAULT NULL, id_produit_id INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_D31F28A61AD5CDBF (cart_id), INDEX IDX_D31F28A699DED506 (id_client_id), INDEX IDX_D31F28A6AABEFE2C (id_produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A61AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A699DED506 FOREIGN KEY (id_client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A6AABEFE2C FOREIGN KEY (id_produit_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY FK_D31F28A61AD5CDBF');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY FK_D31F28A699DED506');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY FK_D31F28A6AABEFE2C');
        $this->addSql('DROP TABLE panier_produit');
    }
}
