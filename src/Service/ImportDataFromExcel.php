<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class ImportDataFromExcel extends ImportExcelService
{
    const MAX_COL = 35;

    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {}

    public function run($file, $dir): array
    {
        $dataExcel = $this->import($file, $dir);

        $errorMessage = [];
        $successMessages = [];

        $sheetCount = $dataExcel['loader']->getSheetCount();

        $en_count   = 0;
        if ($sheetCount > 0) {
            for ($i=0; $i < $sheetCount; $i++) {
                $sheetResult = $this->getArrayDataBySheet($dataExcel['loader'], $i);

                if ($sheetResult) {
                    foreach ($sheetResult as $key => $data) {
                        if ($key >= 1) {
                            dump(
                                $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9],
                                $data[10], $data[11], $data[12], $data[13], $data[14], $data[15], $data[16], $data[17], $data[18], $data[19],
                                $data[20], $data[21], $data[22], $data[23], $data[24], $data[25], $data[26], $data[27], $data[28], $data[29],
                                $data[30], $data[31], $data[32], $data[33], $data[34]
                            );
                            dump('------------------------------');
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
                        }
                    }
                    if ($en_count == 0) {
                        $errorMessage[] = 'Aucune solde modifiée, veuillez vérifier le fichier s\'il vous plaît';
                    }else{
                        $successMessages[] = $en_count .' solde(s) modifiée(s) avec succès';
                    }
                }
            }
        }

        return [$successMessages, $errorMessage];
    }


    public function runWithResponse($file, $dir, $entreprise)
    {
        $dataExcel = $this->import($file, $dir);
        $errorMessage = [];
        $sheetCount = $dataExcel['loader']->getSheetCount();


        if ($sheetCount > 0) {
            for ($i=0; $i < $sheetCount; $i++) {
                $sheetResult = $this->getArrayDataBySheet($dataExcel['loader'], $i);
                if ($sheetResult) {
                    foreach ($sheetResult as $key => $data) {
                        $row = $key + 2;
                        if (!array_key_exists(23, $data) or !array_key_exists(24, $data) or !array_key_exists(25, $data) or !array_key_exists(26, $data)) {
                            $errorMessage[] = "Le fichier que vous avez importé ne correspond pas au type d'importation que vous avez choisi (Entretien avec décision)";
                            break;
                        }elseif (!empty($data)) {

                            if (!$data[2]) {
                                $errorMessage[] = "Email manquant à la ligne " . $row;
                                continue;
                            }
                            if (!$data[23]) {
                                $errorMessage[] = "Réponse ou décision manquante à la ligne " . $row;
                                continue;
                            }
                            if (!$data[26]) {
                                $errorMessage[] = "Date du réponse manquante à la ligne " . $row;
                                continue;
                            }

                            $name = $data[0];
                            $surname = $data[1];
                            $email = $data[2];
                            $dateEntryCompany = $data[4];
                            $posteDateInterview = $data[5];
                            $dateInterview = $data[6];
                            $dataCpf = $data[7];
                            $attenteEvoluProAttente = $data[8];
                            $attenteEvoluProWich = $data[9];
                            $attenteEvoluProWhy = $data[10];
                            $attenteEvoluProDelais = $data[11];
                            $attenteEvoluProAtouts = $data[12];
                            $attenteEvoluProFreins = $data[13];
                            $askingPostDifficultyTechnic = $data[14];
                            $wishFormationsTitle = $data[15];

                            $pendingFormationTitle = $data[16];
                            $attenteTitleProTitle = $data[17];
                            $internalSupportTitle = $data[18];
                            $externalSupportTitle = $data[19];

                            // $period = $data[20];
                            $dispositif = $data[21];
                            $modalite = $data[22];
                            $responseDMD = $data[23];
                            $motifDMD = $data[24];
                            $dataPeriodeMO = $data[25];
                            $dataDateRep = $data[26];

                            //===RESPONSE===//
                            $response = null;
                            if ($this->compareStrService->compare2String($responseDMD,"Acceptée")) {
                                $response = 1;
                            }elseif($this->compareStrService->compare2String($responseDMD,"Refusée")){
                                $response = "refusée";
                            }elseif($this->compareStrService->compare2String($responseDMD,"Reportée")){
                                $response = 2;
                            }

                            $periodeMO = false;
                            $dateRep = false;
                            if ($dataDateRep) {
                                $dateRep = sprintf("01/%s", $dataDateRep);
                                $dateRep = str_replace("/", ".", $dateRep);
                                $dateRep = new \DateTime(date('Y-m-d', strtotime($dateRep)));
                            }
                            if ($dataPeriodeMO) {
                                $periodeMO = sprintf("01/%s", $dataPeriodeMO);
                                $periodeMO = str_replace("/", ".", $periodeMO);
                                $periodeMO = new \DateTime(date('Y-m-d', strtotime($periodeMO)));
                            }

                            // dump(sprintf("01/%s", $dataDateRep),str_replace("/", ".", sprintf("01/%s", $dataDateRep)),$dateRep);
                            // dump(sprintf("01/%s", $dataPeriodeMO),str_replace("/", ".", sprintf("01/%s", $dataPeriodeMO)),$periodeMO);
                            // die();
                            $user = $this->entityManager->getRepository(User::class)->findOneBy([
                                'email' => $email,
                                'entreprise' => $entreprise,
                            ]);

                            if (!$dateInterview) {
                                $errorMessage[] = "Date d'entretien introuvable à la ligne " . $row;
                                continue;
                            }

                            // $dateInterview = str_replace("/", ".", $dateInterview);
                            // $dateInterview = new \DateTime(date('Y-m-d', strtotime($dateInterview)));
                            if (!is_float($dateInterview) && !is_int($dateInterview)) {
                                $errorMessage[] = sprintf("Ligne %s : La date de l'entretien est invalide.", $row);
                                continue;
                            }
                            $dateInterview = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateInterview);


                            if (!$user) {
                                $errorMessage[] = "Salarié introuvable à la ligne " . $row;
                                continue;
                            }

                            $jobInterview = $this->entityManager->getRepository(JobInterview::class)->findOneBy([
                                'entreprise' => $entreprise,
                                'user' => $user,
                                'type' => 1,
                                'number' => 1
                            ]);

                            if (!$jobInterview) {
                                $jobInterview = new JobInterview();
                            }

                            $checkPosteDateInterview = str_replace(' ', '', $posteDateInterview);

                            if ($checkPosteDateInterview) {
                                $jobInterview->setPosteDateEntretien($posteDateInterview);
                            }
                            $jobInterview->setStatusMail(true);
                            $jobInterview->setDateInterview($dateInterview);
                            $jobInterview->setType(Interview::PROFESSIONAL_INTERVIEW_TYPE);
                            $jobInterview->setStatus(Interview::JOB_INTERVIEW_FINISHED_STATUS);
                            $jobInterview->setNumber(1);
                            $jobInterview->setEntreprise($entreprise);
                            $jobInterview->setUser($user);

                            $dataCpf = str_replace(' ', '', $dataCpf);

                            if ($dataCpf) {
                                if (!$jobInterview->getCpfAccount()) {
                                    $cpf = new CpfAccount();
                                }else{
                                    $cpf = $jobInterview->getCpfAccount();
                                }

                                if ($this->compareStrService->compare2String($dataCpf,"Oui")) {
                                    $cpf->setIsStateActivated(true);
                                }else{
                                    $cpf->setIsStateActivated(false);
                                }

                                $jobInterview->setCpfAccount($cpf);
                            }

                            //===========================================================//
                            //=================== ATTENTE EVOLU PRO =====================//
                            //===========================================================//

                            if (str_replace(' ', '', $attenteEvoluProAttente)) {
                                $attenteEvoluPro = $jobInterview->getAttenteEvoluPro();
                                if (!$attenteEvoluPro) {
                                    $attenteEvoluPro = new AttenteEvoluPro();
                                }

                                if ($attenteEvoluProDelais) {
                                    $attenteEvoluProDelais = sprintf("01/%s", $attenteEvoluProDelais);
                                    $attenteEvoluProDelais = str_replace("/", ".", $attenteEvoluProDelais);
                                    $attenteEvoluPro->setDelais($attenteEvoluProDelais);
                                } else {
                                    $attenteEvoluPro->setDelais(null);
                                }

                                $attenteEvoluPro->setSalary($user);
                                $attenteEvoluPro->setAttente($attenteEvoluProAttente);
                                $attenteEvoluPro->setAtouts(explode(",", $attenteEvoluProAtouts));
                                $attenteEvoluPro->setFreins(explode(",", $attenteEvoluProFreins));
                                $attenteEvoluPro->setWhich($attenteEvoluProWich);
                                $attenteEvoluPro->setWhy($attenteEvoluProWhy);
                                $attenteEvoluPro->setEntreprise($entreprise);
                                $jobInterview->setIsAttenteEvoluPro(true);
                                $jobInterview->setAttenteEvoluPro($attenteEvoluPro);

                                //====RESPONSE EVOLUTION PRO====//
                                if ($response != null and $dateRep) {
                                    $reponseEvoluP = $attenteEvoluPro->getResponseAttentEvoluePro();
                                    if (!$reponseEvoluP) {
                                        $reponseEvoluP = new ResponseAttentEvoluePro();
                                    }
                                    $reponseEvoluP->setDateResponse($dateRep);
                                    $reponseEvoluP->setStatus($response != "refusée" ? $response : 0);
                                    $reponseEvoluP->setMotif($motifDMD);

                                    if ($periodeMO) {
                                        $reponseEvoluP->setPeriod($periodeMO);
                                    }

                                    $attenteEvoluPro->setResponseAttentEvoluePro($reponseEvoluP);
                                }
                            }



                            //===================== DIFFICULT TECHNIC ====================//
                            if (str_replace(' ', '', $askingPostDifficultyTechnic)) {
                                $askingPost = $jobInterview->getAskingPost();
                                if (!$askingPost) {
                                    $askingPost = new AskingPost();
                                }

                                $askingPost->setDifficultyTechnic(true);
                                $askingPost->setWhichDifficultyTechnic($askingPostDifficultyTechnic);

                                $jobInterview->setAskingPost($askingPost);
                            }


                            //====================== WISH FORMATION ========================//
                            if (str_replace(' ', '', $wishFormationsTitle)) {

                                $wishFormation = $this->entityManager->getRepository(WishFormation::class)->findOneBy([
                                    'title' => $wishFormationsTitle,
                                    'user' => $user,
                                    'jobInterview' => $jobInterview
                                ]);

                                if (!$wishFormation) {
                                    $wishFormation = new WishFormation();
                                }

                                $wishFormation->setUser($user);
                                $wishFormation->setTitle($wishFormationsTitle);

                                $jobInterview->addWishFormation($wishFormation);
                            }


                            //==============================================================//
                            //==================== PENDING FORMATION =======================//
                            //==============================================================//
                            if (str_replace(' ', '', $pendingFormationTitle)) {

                                $pendingFormation = $this->entityManager->getRepository(PendingFormation::class)->findOneBy([
                                    'title' => $pendingFormationTitle,
                                    'salary' => $user,
                                    'jobInterview' => $jobInterview
                                ]);

                                if (!$pendingFormation) {
                                    $pendingFormation = new PendingFormation();
                                }

                                $pendingFormation->setEntreprise($entreprise);
                                $pendingFormation->setTitle($pendingFormationTitle);
                                $pendingFormation->setSalary($user);

                                if ($data[20]) {
                                    $period = $data[20];
                                    $period = sprintf("01/%s", $period);
                                    $period = str_replace("/", ".", $period);
                                    $period = new \DateTime(date('Y-m-d', strtotime($period)));
                                    $pendingFormation->setPeriodRetenue($period->format('d/m/Y'));
                                }

                                if ($dispositif) {
                                    $dispositifs = explode(",", $dispositif);
                                    foreach ($dispositifs as $text) {
                                        if (
                                            $this->compareStrService->compare2String("Plan développement des compétences de l'entreprise", $text) ||
                                            $this->compareStrService->compare2String("PDCE", $text)
                                        ) {
                                            $pendingFormation->setPdce(1);
                                        } elseif ($this->compareStrService->compare2String("CPF", $text)) {
                                            $pendingFormation->setCpf(1);
                                        } else {
                                            $pendingFormation->setOtherValue($text);
                                        }
                                    }
                                }

                                if ($modalite) {
                                    $modalites = explode(",", $modalite);
                                    foreach ($modalites as $text) {
                                        if (
                                            $this->compareStrService->compare2String("Pendant le temps de travail", $text) ||
                                            $this->compareStrService->compare2String("PTT", $text) ||
                                            $this->compareStrService->compare2String("Pendant", $text)
                                        ) {
                                            $pendingFormation->setPtt(1);
                                        } elseif (
                                            $this->compareStrService->compare2String("Hors temps de travail", $text) ||
                                            $this->compareStrService->compare2String("Hors", $text) ||
                                            $this->compareStrService->compare2String("HTT", $text)
                                        ) {
                                            $pendingFormation->setHtt(1);
                                        } elseif ($this->compareStrService->compare2String("À distance", $text)) {
                                            $pendingFormation->setAd(1);
                                        } else {
                                            // $pendingFormation->setOther(1);
                                        }
                                    }
                                }

                                $jobInterview->addPendingFormation($pendingFormation);

                                //======== RESPONSE ========//
                                if ($response != null and $dateRep) {
                                    $reponseFormation = $pendingFormation->getResponseFormation();

                                    if (!$reponseFormation) {
                                        $reponseFormation = new ResponseFormation();
                                    }

                                    $reponseFormation->setDateResponse($dateRep);
                                    $reponseFormation->setStatus($response != "refusée" ? $response : 0);
                                    $reponseFormation->setMotif($motifDMD);

                                    if ($periodeMO) {
                                        $reponseFormation->setPeriode($periodeMO);
                                    }

                                    $pendingFormation->setResponseFormation($reponseFormation);
                                }
                            }

                            //=====================================================================//
                            //======================== ATTENTE TITLE PRO ==========================//
                            //=====================================================================//
                            if (str_replace(' ', '', $attenteTitleProTitle)) {

                                $attenteTitlePro = $this->entityManager->getRepository(AttenteTitlePro::class)->findOneBy([
                                    'title' => $attenteTitleProTitle,
                                    'salary' => $user,
                                    'jobInterview' => $jobInterview
                                ]);

                                if (!$attenteTitlePro) {
                                    $attenteTitlePro = new AttenteTitlePro();
                                }

                                $attenteTitlePro->setEntreprise($entreprise);
                                $attenteTitlePro->setTitle($attenteTitleProTitle);
                                $attenteTitlePro->setSalary($user);

                                if ($data[20]) {
                                    $period = $data[20];
                                    $period = sprintf("01/%s", $period);
                                    $period = str_replace("/", ".", $period);
                                    $period = new \DateTime(date('Y-m-d', strtotime($period)));
                                    $attenteTitlePro->setPeriod($period->format('d/m/Y'));
                                }

                                if ($dispositif) {
                                    $dispositifs = explode(",", $dispositif);
                                    foreach ($dispositifs as $text) {
                                        if (
                                            $this->compareStrService->compare2String("Plan développement des compétences de l'entreprise", $text) ||
                                            $this->compareStrService->compare2String("PDCE", $text)
                                        ) {
                                            $attenteTitlePro->setPdce(1);
                                        } elseif ($this->compareStrService->compare2String("CPF", $text)) {
                                            $attenteTitlePro->setCpf(1);
                                            // } else {
                                            // 	$attenteTitlePro->setOtherValue($text);
                                        }
                                    }
                                }

                                if ($modalite) {
                                    $modalites = explode(",", $modalite);
                                    foreach ($modalites as $text) {
                                        if (
                                            $this->compareStrService->compare2String("Pendant le temps de travail", $text) ||
                                            $this->compareStrService->compare2String("PTT", $text) ||
                                            $this->compareStrService->compare2String("Pendant", $text)
                                        ) {
                                            $attenteTitlePro->setPtt(1);
                                        } elseif (
                                            $this->compareStrService->compare2String("Hors temps de travail", $text) ||
                                            $this->compareStrService->compare2String("Hors", $text) ||
                                            $this->compareStrService->compare2String("HTT", $text)
                                        ) {
                                            $attenteTitlePro->setHtt(1);
                                        } elseif ($this->compareStrService->compare2String("À distance", $text)) {
                                            $attenteTitlePro->setAd(1);
                                        } else {
                                            // $attenteTitlePro->setOther(1);
                                        }
                                    }
                                }

                                $jobInterview->addAttenteTitlePro($attenteTitlePro);


                                //============= RESPONSE =============//
                                if ($response != null and $dateRep) {
                                    $responseTitle = $attenteTitlePro->getResponseAttentTitlePro();

                                    if (!$responseTitle) {
                                        $responseTitle = new ResponseAttentTitlePro();
                                    }


                                    $responseTitle->setDateResponse($dateRep);
                                    $responseTitle->setStatus($response != "refusée" ? $response : 0);
                                    $responseTitle->setMotif($motifDMD);

                                    if ($periodeMO) {
                                        $responseTitle->setPeriod($periodeMO);
                                    }

                                    $attenteTitlePro->setResponseAttentTitlePro($responseTitle);
                                }

                            }

                            //==============================================================//
                            //====================== INTERNAL SUPPORT ======================//
                            //==============================================================//
                            if (str_replace(' ', '', $internalSupportTitle)) {

                                $internalSupport = $this->entityManager->getRepository(InternalSupport::class)->findOneBy([
                                    'title' => $internalSupportTitle,
                                    'jobInterview' => $jobInterview
                                ]);

                                if (!$internalSupport) {
                                    $internalSupport = new InternalSupport();
                                }

                                $internalSupport->setTitle($internalSupportTitle);

                                if ($data[20]) {
                                    $period = $data[20];
                                    $period = sprintf("01/%s", $period);
                                    $period = str_replace("/", ".", $period);
                                    $period = new \DateTime(date('Y-m-d', strtotime($period)));
                                    $internalSupport->setImplementationPeriod($period->format('d/m/Y'));
                                }

                                $jobInterview->addInternalSupport($internalSupport);

                                //============= RESPONSE =============//
                                if ($response != null and $dateRep) {
                                    $responseInternalSup = $internalSupport->getResponseSearchAccompaniement();

                                    if (!$responseInternalSup) {
                                        $responseInternalSup = new ResponseSearchAccompaniement();
                                    }

                                    $responseInternalSup->setDateResponse($dateRep);
                                    $responseInternalSup->setStatus($response != "refusée" ? $response : 0);
                                    $responseInternalSup->setMotif($motifDMD);

                                    if ($periodeMO) {
                                        $responseInternalSup->setPeriod($periodeMO);
                                    }

                                    $internalSupport->setResponseSearchAccompaniement($responseInternalSup);
                                }

                            }

                            //====================================================================//
                            //========================= EXTERNAL SUPPORT =========================//
                            //====================================================================//
                            if (str_replace(' ', '', $externalSupportTitle)) {

                                $externalSupport = $this->entityManager->getRepository(ExternalSupport::class)->findOneBy([
                                    'title' => $externalSupportTitle,
                                    'jobInterview' => $jobInterview
                                ]);

                                if (!$externalSupport) {
                                    $externalSupport = new ExternalSupport();
                                }

                                $externalSupport->setTitle($externalSupportTitle);

                                if ($data[20]) {
                                    $period = $data[20];
                                    $period = sprintf("01/%s", $period);
                                    $period = str_replace("/", ".", $period);
                                    $period = new \DateTime(date('Y-m-d', strtotime($period)));
                                    $externalSupport->setPeriod($period->format('d/m/Y'));
                                }

                                if ($dispositif) {
                                    $externalSupport->setCpf($dispositif);
                                }

                                if ($modalite) {
                                    $externalSupport->setModal($modalite);
                                }

                                $jobInterview->addExternalSupport($externalSupport);

                                //===================RESPONSE=====================//
                                if ($response != null and $dateRep) {
                                    $responseExternalSup = $externalSupport->getResponseSearchAccompaniement();

                                    if (!$responseExternalSup) {
                                        $responseExternalSup = new ResponseSearchAccompaniement();
                                    }

                                    $responseExternalSup->setDateResponse($dateRep);
                                    $responseExternalSup->setStatus($response != "refusée" ? $response : 0);
                                    $responseExternalSup->setMotif($motifDMD);

                                    if ($periodeMO) {
                                        $responseExternalSup->setPeriod($periodeMO);
                                    }

                                    $externalSupport->setResponseSearchAccompaniement($responseExternalSup);
                                }

                            }

                            $this->entityManager->getRepository(JobInterview::class)->save($jobInterview, true);


                        } else {
                            $errorMessage[] = "Données manquantes à la ligne " . $row;
                        }
                    }
                }
            }
        }

        return $errorMessage;
    }
}