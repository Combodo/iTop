<?php
/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 30/01/19
 * Time: 18:06
 */

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use App\Controller\BlogController;


$aRoutes = \Combodo\iTop\Portal\Routing\ItopExtensionsExtraRoutes::getRoutes();

$routes = new RouteCollection();

foreach ($aRoutes as $route) {
    $route['values'] = (isset($route['values'])) ? $route['values'] : [];
    $route['asserts'] = (isset($route['asserts'])) ? $route['asserts'] : [];

    $routes->add(
        $route['bind'],
        new Route(
            $route['pattern'],
            array_merge(
                ['_controller' => $route['callback']],
                $route['values']
            ),
            $route['asserts']
        )
    );
}

return $routes;