<?php

namespace App\Controller;

use App\Entity\VehicleData;
use App\Form\EditVehicleDataType;
use App\Form\ImportExcelFormType;
use App\Repository\VehicleDataRepository;
use App\Service\ImportDataFromExcel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DataController extends AbstractController
{
    public function __construct(
        protected VehicleDataRepository $vehicleDataRepository
    ) { }

    #[Route('/', name: 'app_data')]
    public function index(Request $request, ImportDataFromExcel $dataFromExcelService): Response
    {
        $importExcelForm = $this->createForm(ImportExcelFormType::class);
        $importExcelForm->handleRequest($request);

        if($importExcelForm->isSubmitted() and $importExcelForm->isValid()){
            $file = $request->files->get('import_excel_form')['file'];

            [$successMessages, $warningMessages, $errorMessages] = $dataFromExcelService->run($file, $this->getParameter('importedExcelDir'));

            if (!empty($errorMessages)) {
                foreach ($errorMessages as $error) {
                    $this->addFlash('ctm_danger', $error);
                }
            }else{
                $this->addFlash('success', 'Importation du tableau terminé avec succès.');
            }

            if (!empty($successMessages)) {
                foreach ($successMessages as $success) {
                    $this->addFlash('ctm_success', $success);
                }
            }

            if (!empty($warningMessages)) {
                foreach ($warningMessages as $warning) {
                    $this->addFlash('ctm_warning', $warning);
                }
            }
        }

        if($request->isXmlHttpRequest()) {
            return new JsonResponse($this->vehicleDataRepository->findUsedByDatatable($request));
        }

        return $this->render('data/index.html.twig', [
            'importExcelForm'=> $importExcelForm->createView(),
            'colTitle' => $dataFromExcelService->getColTitle()
        ]);
    }

    #[Route('/add', name: 'app_data_add', methods: ['GET', 'POST'])]
    public function add(Request $request)
    {
        $addForm = $this->createForm(EditVehicleDataType::class, null);
        $addForm->handleRequest($request);

        if($addForm->isSubmitted() and $addForm->isValid()) {
            $this->vehicleDataRepository->save($addForm->getData(), true);

            $this->addFlash('success', "Donnée ajoutée avec succès");

            return $this->redirectToRoute('app_data');
        }

        return $this->render('data/add.html.twig', [
            'addForm'=> $addForm->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_data_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, VehicleData $vehicleData)
    {
        $editForm = $this->createForm(EditVehicleDataType::class, $vehicleData);
        $editForm->handleRequest($request);

        if($editForm->isSubmitted() and $editForm->isValid()) {
            $this->vehicleDataRepository->save($vehicleData, true);

            $this->addFlash('success', "Donnée modifiée avec succès");

            return $this->redirectToRoute('app_data');
        }

        return $this->render('data/edit.html.twig', [
            'editForm'=> $editForm->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_data_delete', methods: ['GET'])]
    public function delete(VehicleData $vehicleData)
    {
        $this->vehicleDataRepository->remove($vehicleData, true);

        $this->addFlash('success', "Donnée supprimée avec succès");

        return $this->redirectToRoute('app_data');
    }
}
