<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200422145555 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE save_login_user (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, date_embauche DATE NOT NULL, is_comptable TINYINT(1) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_forfait (id INT AUTO_INCREMENT NOT NULL, id_type_id INT NOT NULL, id_visiteur_id INT NOT NULL, date DATE NOT NULL, qte INT NOT NULL, INDEX IDX_A3B49F581BD125E3 (id_type_id), INDEX IDX_A3B49F586760FECA (id_visiteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, id_etat VARCHAR(2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_hors_forfait (id INT AUTO_INCREMENT NOT NULL, id_visiteur_id INT NOT NULL, id_etat_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, date DATE NOT NULL, nb_justificatifs INT DEFAULT NULL, INDEX IDX_5B7AF3716760FECA (id_visiteur_id), INDEX IDX_5B7AF371D3C32F8F (id_etat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_forfait_type (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fiche_forfait ADD CONSTRAINT FK_A3B49F581BD125E3 FOREIGN KEY (id_type_id) REFERENCES fiche_forfait_type (id)');
        $this->addSql('ALTER TABLE fiche_forfait ADD CONSTRAINT FK_A3B49F586760FECA FOREIGN KEY (id_visiteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE fiche_hors_forfait ADD CONSTRAINT FK_5B7AF3716760FECA FOREIGN KEY (id_visiteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE fiche_hors_forfait ADD CONSTRAINT FK_5B7AF371D3C32F8F FOREIGN KEY (id_etat_id) REFERENCES etat (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fiche_forfait DROP FOREIGN KEY FK_A3B49F586760FECA');
        $this->addSql('ALTER TABLE fiche_hors_forfait DROP FOREIGN KEY FK_5B7AF3716760FECA');
        $this->addSql('ALTER TABLE fiche_hors_forfait DROP FOREIGN KEY FK_5B7AF371D3C32F8F');
        $this->addSql('ALTER TABLE fiche_forfait DROP FOREIGN KEY FK_A3B49F581BD125E3');
        $this->addSql('DROP TABLE save_login_user');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE fiche_forfait');
        $this->addSql('DROP TABLE etat');
        $this->addSql('DROP TABLE fiche_hors_forfait');
        $this->addSql('DROP TABLE fiche_forfait_type');
    }
}
