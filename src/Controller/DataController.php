<?php

namespace App\Controller;

use App\Entity\VehicleData;
use App\Form\ImportExcelFormType;
use App\Repository\VehicleDataRepository;
use App\Service\ImportDataFromExcel;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DataController extends AbstractController
{
    public function __construct(
        protected DataTableFactory $dataTableFactory,
        protected VehicleDataRepository $vehicleDataRepository
    ) { }

    #[Route('/', name: 'app_data')]
    public function index(Request $request, ImportDataFromExcel $dataFromExcelService): Response
    {
        $importExcelForm = $this->createForm(ImportExcelFormType::class);
        $importExcelForm->handleRequest($request);

        if($importExcelForm->isSubmitted() and $importExcelForm->isValid()){
            $file = $request->files->get('import_excel_form')['file'];
            $dir = $this->getParameter('importedExcelDir');

            [$successMessages, $errorMessages] = $dataFromExcelService->run($file, $dir);

            if (count($errorMessages) > 0) {
                foreach ($errorMessages as $error) {
                    $this->addFlash('error', $error);
                }
            }

            if (count($successMessages) > 0) {
                foreach ($successMessages as $success) {
                    $this->addFlash('success', $success);
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

    #[Route('/{id}/delete', name: 'app_data_delete', methods: ['GET'])]
    public function delete(VehicleData $vehicleData)
    {
        $this->vehicleDataRepository->remove($vehicleData, true);

        $this->addFlash('success',sprintf("Donnée supprimé avec succès"));

        return $this->redirectToRoute('app_data');
    }
}
