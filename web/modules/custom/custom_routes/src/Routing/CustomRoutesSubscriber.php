<?php

namespace Drupal\custom_routes\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Alters the requirements of the route.
 */
class CustomRoutesSubscriber extends RouteSubscriberBase {

  /**
   * Alters roles, permission and adds custom access checks.
   *
   * @param \Symfony\Component\Routing\RouteCollection $collection
   *   The route instance containing required routes.
   */
  protected function alterRoutes(RouteCollection $collection) {
    // If the required route is available, access and permissions are modified.
    if ($route = $collection->get('custom_routes.content')) {
      $route->setRequirements([
        '_role' => 'administrator',
        '_permission' => 'access the custom page',
        '_custom_access' => '\Drupal\custom_routes\Controller\CustomRoutesController::access',
      ]);
    }
  }

}
