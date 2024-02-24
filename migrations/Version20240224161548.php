<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240224161548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vehicle_data (id INT AUTO_INCREMENT NOT NULL, compte_affaire VARCHAR(255) NOT NULL, compte_evenement_veh VARCHAR(255) NOT NULL, compte_dernier_evenement_veh VARCHAR(255) DEFAULT NULL, numero_de_fiche VARCHAR(255) NOT NULL, libelle_civilite VARCHAR(255) DEFAULT NULL, proprietaire_actuel_du_vehicule VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) DEFAULT NULL, numero_et_nom_de_la_voie VARCHAR(255) NOT NULL, complement_adresse1 VARCHAR(255) DEFAULT NULL, code_postal VARCHAR(255) NOT NULL, ville VARCHAR(255) DEFAULT NULL, telephone_domicile VARCHAR(255) DEFAULT NULL, telephone_portable VARCHAR(255) DEFAULT NULL, telephone_job VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, date_de_mise_en_circulation DATE DEFAULT NULL, date_achat_date_de_livraison DATE DEFAULT NULL, date_dernier_evenement_veh DATE DEFAULT NULL, libelle_marque_mrq VARCHAR(255) NOT NULL, libelle_modele_mod VARCHAR(255) DEFAULT NULL, version VARCHAR(255) DEFAULT NULL, vin VARCHAR(255) NOT NULL, immatriculation VARCHAR(255) DEFAULT NULL, type_de_prospect VARCHAR(255) NOT NULL, kilometrage DOUBLE PRECISION NOT NULL, libelle_energie_energ VARCHAR(255) DEFAULT NULL, vendeur_vn VARCHAR(255) DEFAULT NULL, vendeur_vo VARCHAR(255) DEFAULT NULL, commentaire_de_facturation_veh VARCHAR(255) DEFAULT NULL, type_vnvo VARCHAR(255) DEFAULT NULL, numero_de_dossier_vnvo VARCHAR(255) DEFAULT NULL, intermediaire_de_vente_vn VARCHAR(255) DEFAULT NULL, date_evenement_veh DATE NOT NULL, origine_evenement_veh VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE vehicle_data');
    }
}
