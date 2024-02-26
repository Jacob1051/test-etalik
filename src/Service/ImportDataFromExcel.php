<?php

namespace App\Service;

use App\Entity\VehicleData;
use App\Repository\VehicleDataRepository;
use Doctrine\ORM\EntityManagerInterface;

class ImportDataFromExcel extends ImportExcelService
{
    CONST VIN_INDEX = 22;

    //liste de titre accepte
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

    //liste des setter pour chaque titre accepte
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
        protected EntityManagerInterface $entityManager,
        protected VehicleDataRepository $vehicleDataRepository
    ) {}

    public function getColTitle(): array
    {
        return self::POSSIBLE_COLUMN_TITLE;
    }

    /**
     * Check if columnName is a valid column title. Return the index inside POSSIBLE_COLUMN_TITLE otherwise null.
     * @param string $columnName
     * @return int|null
     */
    function getValidColumnIndex(string $columnName): ?int
    {
        $searchTermLowercase = strtolower(trim($columnName));
        $index = array_search($searchTermLowercase, array_map('strtolower', self::POSSIBLE_COLUMN_TITLE));

        return $index !== false ? $index : null;
    }


    /**
     * Start the process of excel importing. Create a new record if not inside db and update it in the other hand.
     * Check VIN field for updating.
     * Return the errors, warnings and success messages encounter during the process.
     * @param $file
     * @param $dir
     * @return array[]
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     */
    public function run($file, $dir): array
    {
        [$headers, $reader] = $this->import($file, $dir);
        [$errorMessages, $warningMessages, $successMessages] = [[], [], []];

        $arrangedCol = [];

        $countInserted = 0;
        $totalRow = 0;

        foreach ($headers as $index => $title) {
            $tempCol = $this->getValidColumnIndex($title);

            if($tempCol === null)
                $errorMessages[] = sprintf('Colonne "%s" non reconnue, ignoré.', self::POSSIBLE_COLUMN_TITLE[$index]);

            $arrangedCol[] = $tempCol;
        }

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                if($rowIndex > 1) {
                    $totalRow++;
                    $colIndex = 0;

                    try {
                        $vin = $row->getCellAtIndex(array_search(self::VIN_INDEX, $arrangedCol))->getValue();
                        $vehicleDataInDb = $this->vehicleDataRepository->findOneBy(['vin' => $vin]);

                        $vehicleData = $vehicleDataInDb ?? new VehicleData();

                        if($vehicleDataInDb){
                            $warningMessages[] = sprintf("Ligne %s : Données déjà existantes! Les données sont mises à jour avec les informations du fichier.", $rowIndex + 1);
                        }

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
                        $errorMessages[] = sprintf('Ligne %s: Donnée "%s" invalide', $rowIndex, self::POSSIBLE_COLUMN_TITLE[$colIndex]);
                        continue;
                    }
                }
            }
        }

        if ($countInserted == 0) {
            $errorMessages[] = 'Aucune donnée enregistrée, veuillez vérifier le fichier s\'il vous plaît';
        }else{
            $successMessages[] = $countInserted .' sur '.$totalRow.' donnée(s) enregistrée(s) avec succès';
        }

        $reader->close();

        return [$successMessages, $warningMessages, $errorMessages];
    }
}
