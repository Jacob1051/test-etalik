<?php

namespace App\Controller;

use App\Form\ImportExcelFormType;
use App\Service\ImportDataFromExcel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DataController extends AbstractController
{
    #[Route('/', name: 'app_data')]
    public function index(Request $request, ImportDataFromExcel $dataFromExcelService): Response
    {
        $importExcelForm = $this->createForm(ImportExcelFormType::class);
        $importExcelForm->handleRequest($request);

        if($importExcelForm->isSubmitted() and $importExcelForm->isValid()){
            $file = $request->files->get('import_excel_form')['file'];
            $dir = $this->getParameter('importedExcelDir');

            [$successMessages, $errorMessages, $value] = $dataFromExcelService->run($file, $dir);

            dd($value, $errorMessages);
//            if (count($errorMessages) > 0) {
//                foreach ($errorMessages as $error) {
//                    $this->addFlash('error', $error);
//                }
//            }
//
//            if (count($successMessages) > 0) {
//                foreach ($successMessages as $success) {
//                    $this->addFlash('success', $success);
//                }
//            }
        }

        return $this->render('data/index.html.twig', [
            'importExcelForm'=> $importExcelForm->createView(),
            'controller_name' => 'DataController',
        ]);
    }
}
