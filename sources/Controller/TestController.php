<?php

namespace Combodo\iTop\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractAppController
{


	#[Route('/lucky/number/{max}', name: 'app_lucky_number')]
	public function number(int $max): Response
	{
		$number = random_int(0, $max);

		return new Response(
			'<html><body>Lucky number: '.$number.'</body></html>'
		);
	}
}