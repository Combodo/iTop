<?php

namespace Combodo\iTop\FormImplementation\Controller;

use Combodo\iTop\Controller\AbstractAppController;
use Combodo\iTop\FormImplementation\Dto\CountDto;
use Combodo\iTop\FormImplementation\Dto\ObjectSearchDto;
use Combodo\iTop\FormImplementation\Helper\FormHelper;
use Combodo\iTop\FormSDK\Service\FormManager;
use Combodo\iTop\Service\Base\ObjectRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;

class TestController extends AbstractAppController
{

	/**
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	#[Route('/formSDK/test_form/', name: 'formSDK_test_form')]
	public function Form(Request $oRequest, FormManager $oFormManager, RouterInterface $oRouter, #[MapQueryParameter] int $mode=0): Response
	{
		// create factory
		$oFactory = FormHelper::CreateSampleFormFactory($oFormManager, $oRouter, $mode);

		// get the form
		$oForm = $oFactory->CreateForm();

		// handle request
		$oForm->handleRequest($oRequest);

		// submitted and valid
		if ($oForm->isSubmitted() && $oForm->isValid()) {

			// retrieve form data
			$data = $oForm->getData();

			// let's adapters save theirs data
			foreach($oFactory->GetAllAdapters() as $oAdapter){
				$oAdapter->UpdateFieldsData($data);
			}

			return $this->redirectToRoute('app_success');
		}

		// render view
		return $this->render('formSDK/form.html.twig', [
			'form' => $oForm->createView(),
			'theme' => 'formSDK/themes/portal.html.twig'
		]);

	}

	#[Route('/formSDK/test/', name: 'formSDK_test')]
	public function Test(Request $oRequest, FormManager $oFormManager)
	{

		// get the form
		$oFactory = $oFormManager->CreateFactory();
		$oFactory->AddNumberField('count1', []);
		$oFactory->SetData(new CountDto());
		$oForm = $oFactory->CreateForm();

		// handle request
		$oForm->handleRequest($oRequest);

		// submitted and valid
		if ($oForm->isSubmitted() && $oForm->isValid()) {

			// retrieve form data
			$data = $oForm->getData();

			return $this->redirectToRoute('app_success');
		}

		// render view
		return $this->render('formSDK/form.html.twig', [
			'form' => $oForm->createView(),
			'theme' => 'formSDK/themes/portal.html.twig'
		]);
	}

	/**
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	#[Route('/formSDK/test_theme', name: 'formSDK_test_theme')]
	public function Theme(Request $oRequest, FormManager $oFormManager, RouterInterface $oRouter, #[MapQueryParameter] int $mode = 0): Response
	{
		// create factory
		$oFactory = FormHelper::CreateSampleFormFactory($oFormManager, $oRouter, $mode);

		// get the forms (named instances)
		$oForm1 = $oFactory->CreateForm('form1');
		$oForm2 = $oFactory->CreateForm('form2');

		// handle request
		$oForm1->handleRequest($oRequest);
		$oForm2->handleRequest($oRequest);

		// render view
		return $this->render('formSDK/theme.html.twig', [
			'name1' => 'Portal',
			'name2' => 'Console',
			'form1' => $oForm1->createView(),
			'form2' => $oForm2->createView(),
			'theme1' => 'formSDK/themes/portal.html.twig',
			'theme2' => 'formSDK/themes/console.html.twig'
		]);

	}

	#[Route('/formSDK/ajax_select', name: 'formSDK_ajax_select')]
	public function Ajax(Request $oRequest): Response
	{
		$oJson = file_get_contents('sources/FormImplementation/Resources/dogs.json');
		$aDogs = json_decode($oJson, true);
		$sQuery = $oRequest->request->get('query');

		return new JsonResponse([
			"items" => $aDogs['dogBreeds']
		]);
	}

	#[Route('/formSDK/object_search', name: 'formSDK_object_search')]
	public function OperationSearch(
		#[MapQueryString] ObjectSearchDto $objectSearch,
	): JsonResponse
	{
		// Search objects
		$aResult = ObjectRepository::SearchFromOql($objectSearch->class, json_decode($objectSearch->fields, true), $objectSearch->oql, $objectSearch->search);

		return new JsonResponse([
			'items' => $aResult,
		]);
	}

	#[Route('/success', name: 'app_success')]
	public function Success(): Response
	{
		return $this->render('formSDK/success.html.twig');
	}
}