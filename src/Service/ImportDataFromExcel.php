<?php

namespace App\Service;

use App\Entity\VehicleData;
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
        "setLibelleModeleMod", "setVersion", "setVin", "setImmatriculation", "setTypeDeProspect", "setKilometrage", "setLibelleEnergieEnerg",
        "setVendeurVN", "setVendeurVO", "setCommentaireDeFacturationVeh", "setTypeVNVO", "setNumeroDeDossierVNVO",
        "setIntermediaireDeVenteVN", "setDateEvenementVeh", "setOrigineEvenementVeh"
    ];

    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {}

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

        $values = [];

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

                            if($colNumber){
                                $setterMethod = self::SETTER[$colNumber];
                                $value = $row->getCellAtIndex($colNumber)->getValue();
                                $vehicleData->$setterMethod(
                                    $value === "" ? null : $value
                                );
                            }
                        }

                        $values[] = $vehicleData;
                    } catch (\Throwable $exception) {
                        $errorMessages[] = 'Donnée '.self::POSSIBLE_COLUMN_TITLE[$colIndex].' invalide au ligne '.$rowIndex;
                        continue;
                    }
                }

            }
        }
//
//        $sheetCount = $dataExcel['loader']->getSheetCount();

        $en_count   = 0;
//        if ($sheetCount > 0) {
//            for ($i=0; $i < $sheetCount; $i++) {
//                $sheetResult = $this->getArrayDataBySheet($dataExcel['loader'], $i);
//
//                if ($sheetResult) {
//                    foreach ($sheetResult as $key => $data) {
//                        if ($key >= 1) {
//                            dump(
//                                $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9],
//                                $data[10], $data[11], $data[12], $data[13], $data[14], $data[15], $data[16], $data[17], $data[18], $data[19],
//                                $data[20], $data[21], $data[22], $data[23], $data[24], $data[25], $data[26], $data[27], $data[28], $data[29],
//                                $data[30], $data[31], $data[32], $data[33], $data[34]
//                            );
//                            dump('------------------------------');
//                            if (!empty($data)) {
//                                $user = $this->userRepo->findOneBy(['email'=>$data[2]]);
//
//                                if($user and in_array($data[3], DayoffName::DAYOFF_EXCEL_INDEX_ARRAY)){
//                                    $dayofftype = $this->dayofftypeRepo->findOneBy(['code'=>DayoffName::DAYOFF_EXCEL_ARRAY[$data[3]]]);
//                                    $initialDayoffStateInDB = $this->initialDayoffStateRepository->findOneBy(['user'=>$user, 'dayofftype'=>$dayofftype, 'refYear'=>$data[7]]);
//
//                                    $initialDayoffState = $initialDayoffStateInDB ?? new InitialDayoffState();
//                                    $initialDayoffState->setUser($user);
//                                    $initialDayoffState->setDayofftype($dayofftype);
//                                    $initialDayoffState->setEntreprise($user->getEntreprise());
//                                    $initialDayoffState->setBalance($data[4]);
//                                    $initialDayoffState->setReport($data[5]);
//                                    $initialDayoffState->setTaken($data[6]);
//                                    $initialDayoffState->setRefYear($data[7]);
//
//                                    $inserted = $this->dayOffSservice->modifyUserBalanceFromImport($initialDayoffState);
//
//                                    if($inserted){
//                                        $this->initialDayoffStateRepository->save($initialDayoffState, true);
//                                        $en_count++;
//                                    }
//                                }
//                            }
//                        }
//                    }
//                    if ($en_count == 0) {
//                        $errorMessage[] = 'Aucune solde modifiée, veuillez vérifier le fichier s\'il vous plaît';
//                    }else{
//                        $successMessages[] = $en_count .' solde(s) modifiée(s) avec succès';
//                    }
//                }
//            }
//        }
        $reader->close();

        return [$successMessages, $errorMessages, $values];
    }
}
