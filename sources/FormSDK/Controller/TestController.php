<?php

namespace Combodo\iTop\FormSDK\Controller;

use Combodo\iTop\Controller\AbstractAppController;
use Combodo\iTop\FormSDK\Dto\ObjectSearchDto;
use Combodo\iTop\FormSDK\Helper\SelectHelper;
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
use Symfony\Component\Validator\Constraints\Length;
use utils;

class TestController extends AbstractAppController
{

	#[Route('/formSDK/test_Form', name: 'formSDK_test_form')]
	public function form(Request $oRequest, FormManager $oFormManager): Response
	{
		// retrieve DB Object
		try {
			$oPerson = MetaModel::GetObject('Person', 1);
		}
		catch (Exception $e) {
			throw $this->createNotFoundException('unable to load object Person 1');
		}

		// build the form
		$oFormFactory = $oFormManager->CreateFactory();
		// object plugin
		$oObjectPlugin = $oFormFactory->CreateObjectAddon($oPerson, true);
		$oObjectPlugin->AddAttribute('name');
		$oObjectPlugin->AddAttribute('mobile_phone');
		// others data
		$oFormFactory->AddTextField('city', [
			'label' => 'Ma ville',
			'constraints' => new Length(['min' => 3])
		], 'Autun');
		$oFormFactory->AddTextField('country', [
			'label' => 'Pays'
		], 'FRANCE');
		$oFormFactory->AddDateField('birthday', [
			'label' => 'Anniversaire',
			'widget' => 'single_text',
		], new DateTime('1979/06/27'));
		$oFormFactory->AddSelectField('language', [
			'label' => 'Ma langue',
			'choices' => SelectHelper::GetApplicationLanguages()
		], 'FR FR');
		$oFormFactory->AddSelectAjaxField('dog', [
			'label' => 'Mon Chien',
			'placeholder' => 'Sélectionnez un chien'
		], [
			'url' => 'http://localhost' . $this->generateUrl('formSDK_ajax_select'),
			'ajax_query_parameter' => 'query',
			'value_field' => 'breed',
			'label_field' => 'breed',
			'search_field' => 'breed',
			'threshold' => 20
		]);
		$oFormFactory->AddSelectOqlField('friends', [
			'label' => 'Ma personne',
		], 'Person', 'SELECT Person', [], '', 20);

		// get the form
		$oForm = $oFormFactory->GetForm();

		// handle request
		$oForm->handleRequest($oRequest);

		// submitted and valid
		if ($oForm->isSubmitted() && $oForm->isValid()) {

			// retrieve form data
			$data = $oForm->getData();

			// ... perform some action, such as saving the data to the database

			return $this->redirectToRoute('app_success');
		}

		return $this->render('formSDK/form.html.twig', [
			'form' => $oForm->createView(),
			'theme' => 'formSDK/themes/portal.html.twig'
		]);

	}

	#[Route('/formSDK/ajax_select', name: 'formSDK_ajax_select')]
	public function ajax(Request $oRequest): Response
	{
		$oJson = file_get_contents('sources/FormSDK/Resources/dogs.json');
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