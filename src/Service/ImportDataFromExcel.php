<?php

namespace App\Service;

use App\Entity\VehicleData;
use App\Repository\VehicleDataRepository;
use Doctrine\ORM\EntityManagerInterface;

class ImportDataFromExcel extends ImportExcelService
{
    CONST POSSIBLE_COLUMN_TITLE = [
        "Compte Affaire", "Compte évènement (Veh)", "Compte dernier évènement (Veh)", "Numéro de fiche",
        "Libellé civilité", "Propriétaire actuel du véhicule", "Nom", "Prénom", "N° et Nom de la voie",
        "Complément adresse 1", "Code postal", "Ville", "Téléphone domicile", "Téléphone portable",
        "Téléphone job", "Email", "Date de mise en circulation", "Date achat (date de livraison)",
        "Date dernier évènement (Veh)", "Libellé marque (Mrq)", "Libellé modèle (Mod)", "Version",
        "VIN", "Immatriculation", "Type de prospect", "Kilométrage", "Libellé énergie (Energ)",
        "Vendeur VN", "Vendeur VO", "Commentaire de facturation (Veh)", "Type VN VO", "Numéro de dossier VN VO",
        "Intermediaire de vente VN", "Date évènement (Veh)", "Origine évènement (Veh)"
    ];

    CONST SETTER = [
        "setCompteAffaire", "setCompteEvenementVeh", "setCompteDernierEvenementVeh", "setNumeroDeFiche",
        "setLibelleCivilite", "setProprietaireActuelDuVehicule", "setNom", "setPrenom", "setNumeroEtNomDeLaVoie",
        "setComplementAdresse1", "setCodePostal", "setVille", "setTelephoneDomicile", "setTelephonePortable", "setTelephoneJob",
        "setEmail", "setDateDeMiseEnCirculation", "setDateAchatDateDeLivraison", "setDateDernierEvenementVeh", "setLibelleMarqueMrq",
        "setLibelleModeleMod", "setVersion", "setVin", "setImmatriculation", "setTypeDeProspect", "setKilometrage1", "setLibelleEnergieEnerg",
        "setVendeurVN", "setVendeurVO", "setCommentaireDeFacturationVeh", "setTypeVNVO", "setNumeroDeDossierVNVO",
        "setIntermediaireDeVenteVN", "setDateEvenementVeh", "setOrigineEvenementVeh"
    ];

    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {}

    public function getColTitle(): array
    {
        return self::POSSIBLE_COLUMN_TITLE;
    }

    function getValidColumnIndex(string $columnName): ?int
    {
        $searchTermLowercase = strtolower(trim($columnName));
        $index = array_search($searchTermLowercase, array_map('strtolower', self::POSSIBLE_COLUMN_TITLE));

        return $index !== false ? $index : null;
    }

    public function run($file, $dir): array
    {
        [$headers, $reader] = $this->import($file, $dir);
        [$errorMessages, $successMessages] = [[], []];

        $arrangedCol = [];

        $countInserted = 0;

        foreach ($headers as $index => $title) {
            $tempCol = $this->getValidColumnIndex($title);

            if($tempCol === null)
                $errorMessages[] = sprintf('Colonne "%s" non reconnue, ignoré.', self::POSSIBLE_COLUMN_TITLE[$index]);

            $arrangedCol[] = $tempCol;
        }

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                if($rowIndex > 1) {
                    $colIndex = 0;
                    try {
                        $vehicleData = new VehicleData();

                        foreach ($arrangedCol as $colNumber) {
                            $colIndex = $colNumber;

                            if($colNumber !== null){
                                $setterMethod = self::SETTER[$colNumber];
                                $value = $row->getCellAtIndex($colNumber)->getValue();
                                $vehicleData->$setterMethod(
                                    $value === "" ? null : $value
                                );
                            }
                        }

                        $this->entityManager->persist($vehicleData);
                        $this->entityManager->flush();

                        $countInserted += 1;
                    } catch (\Throwable $exception) {
                        dump($exception->getMessage());
                        $errorMessages[] = 'Donnée '.self::POSSIBLE_COLUMN_TITLE[$colIndex].' invalide au ligne '.$rowIndex;
                        continue;
                    }
                }
            }
        }

        if ($countInserted == 0) {
            $errorMessages[] = 'Aucune donnée enregistrée, veuillez vérifier le fichier s\'il vous plaît';
        }else{
            $successMessages[] = $countInserted .' donnée(s) enregistrée(s) avec succès';
        }

        $reader->close();

        return [$successMessages, $errorMessages];
    }
}
