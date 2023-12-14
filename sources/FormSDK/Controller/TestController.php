<?php

namespace Combodo\iTop\FormSDK\Controller;

use Combodo\iTop\Controller\AbstractAppController;
use Combodo\iTop\FormSDK\Service\FormManager;
use Exception;
use MetaModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractAppController
{


	#[Route('/lucky/number/{max}', name: 'app_lucky_number')]
	public function number(Request $oRequest, int $max): Response
	{
		$number = random_int(0, $max);

		return $this->render('test.html.twig', [
			'number' => $number
		]);
	}

	#[Route('/success', name: 'app_success')]
	public function success(): Response
	{
		return $this->render('formSDK/success.html.twig');
	}

	#[Route('/form', name: 'app_form')]
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
		$oObjectPlugin = $oFormFactory->CreateObjectPlugin($oPerson, false);
		$oObjectPlugin->AddAttribute('name');
		$oObjectPlugin->AddAttribute('mobile_phone');
		// others data
		$oFormFactory->AddText('data1', ['label' => 'Ma ville'], 'Autun');
		$oFormFactory->AddText('data2', ['label' => 'Pays'], 'FRANCE');

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
			'form' => $oFormFactory->GetForm(),
			'theme' => 'formSDK/theme/portal.html.twig'
		]);

	}
}