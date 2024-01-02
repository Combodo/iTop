<?php

namespace Combodo\iTop\FormImplementation\Controller;

use Combodo\iTop\Controller\AbstractAppController;
use Combodo\iTop\FormImplementation\Dto\ObjectSearchDto;
use Combodo\iTop\FormImplementation\Helper\FormHelper;
use Combodo\iTop\FormImplementation\Helper\SelectDataProvider;
use Combodo\iTop\FormSDK\Service\FormManager;
use Combodo\iTop\Service\Base\ObjectRepository;
use DateTime;
use Exception;
use JsonPage;
use MetaModel;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use utils;

class TestController extends AbstractAppController
{

	#[Route('/formSDK/test_form', name: 'formSDK_test_form')]
	public function form(Request $oRequest, FormManager $oFormManager, RouterInterface $oRouter): Response
	{
		// create factory
		try{
			$oFactory = FormHelper::CreateSampleFormFactory($oFormManager, $oRouter);
		}
		catch (Exception $e) {
			throw $this->createNotFoundException('unable to create sample form factory', $e);
		}

		// get the form
		$oForm = $oFactory->CreateForm();

		// handle request
		$oForm->handleRequest($oRequest);

		// submitted and valid
		if ($oForm->isSubmitted() && $oForm->isValid()) {

			// retrieve form data
			$data = $oForm->getData();

			// let's adaptaters save theirs data
			foreach($oFactory->GetAllAdapters() as $oAdapter){
				$oAdapter->UpdateFieldsData($data);
			}

			dump($data);

//			return $this->redirectToRoute('app_success');
		}

		// render view
		return $this->render('formSDK/form.html.twig', [
			'form' => $oForm->createView(),
			'theme' => 'formSDK/themes/portal.html.twig'
		]);

	}

	#[Route('/formSDK/test_theme', name: 'formSDK_test_theme')]
	public function theme(Request $oRequest, FormManager $oFormManager, RouterInterface $oRouter): Response
	{
		// create factory
		try{
			$oFactory = FormHelper::CreateSampleFormFactory($oFormManager, $oRouter);
		}
		catch (Exception $e) {
			throw $this->createNotFoundException('unable to create sample form factory');
		}

		// get the forms (named instances)
		$oForm1 = $oFactory->CreateForm('form1');
		$oForm2 = $oFactory->CreateForm('form2');

		// handle request
		$oForm1->handleRequest($oRequest);
		$oForm2->handleRequest($oRequest);

		// render view
		return $this->render('formSDK/theme.html.twig', [
			'name1' => 'Portail',
			'name2' => 'Console',
			'form1' => $oForm1->createView(),
			'form2' => $oForm2->createView(),
			'theme1' => 'formSDK/themes/portal.html.twig',
			'theme2' => 'formSDK/themes/console.html.twig'
		]);

	}

	#[Route('/formSDK/ajax_select', name: 'formSDK_ajax_select')]
	public function ajax(Request $oRequest): Response
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
	public function success(): Response
	{
		return $this->render('formSDK/success.html.twig');
	}
}