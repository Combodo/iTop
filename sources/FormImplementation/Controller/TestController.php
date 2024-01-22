<?php

namespace Combodo\iTop\FormImplementation\Controller;

use Combodo\iTop\Application\UI\Base\Component\Html\HtmlFactory;
use Combodo\iTop\Application\WebPage\iTopWebPage;
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

	/**
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	#[Route('/formSDK/test_form_fragment/', name: 'formSDK_test_form_fragment')]
	public function FormFragment(Request $oRequest, FormManager $oFormManager, RouterInterface $oRouter, #[MapQueryParameter] int $mode=0): Response
	{
		$sLoginMessage = \LoginWebPage::DoLogin();

		$oPage = new iTopWebPage('Form SDK');

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

		$oPage->add_linked_stylesheet(\utils::GetAbsoluteUrlAppRoot() . 'css/form-sdk/form.css');
		$oPage->add_linked_stylesheet(\utils::GetAbsoluteUrlAppRoot() . 'css/form-sdk/test.css');
		$oPage->add_linked_stylesheet(\utils::GetAbsoluteUrlAppRoot() . 'node_modules/tom-select/dist/css/tom-select.bootstrap5.css');
		$oPage->add_linked_stylesheet('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');

		$oPage->add_linked_script(\utils::GetAbsoluteUrlAppRoot() . 'js/form-sdk/widget-factory.js');
		$oPage->add_linked_script(\utils::GetAbsoluteUrlAppRoot() . 'js/ckeditor/ckeditor.js');
		$oPage->add_linked_script('https://unpkg.com/imask');
		$oPage->add_linked_script(\utils::GetAbsoluteUrlAppRoot() . 'node_modules/tom-select/dist/js/tom-select.complete.js');
		$oPage->add_linked_script('https://kit.fontawesome.com/f2d58012d0.js');

		$oPage->add_ready_script('iTopFormWidgetFactory.AutoInstall();');

		// render view
		$sFormContent = $this->renderView('formSDK/form_fragment.html.twig', [
			'form' => $oForm->createView(),
			'theme' => 'formSDK/themes/portal.html.twig'
		]);
		$oPage->AddUiBlock(HtmlFactory::MakeRaw($sFormContent));

		return $oPage->GenerateResponse();

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