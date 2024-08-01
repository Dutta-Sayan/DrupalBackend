<?php

namespace Drupal\custom_routes\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controls dynamic content.
 */
class DynamicRoutesController extends ControllerBase {

  /**
   * Returns the dynamic content of the route.
   *
   * @return array
   *   Returns the content to be displayed.
   */
  public function content($slug): array {
    return [
      '#markup' => $this->t('The dynamic value from the route is @value.', ['@value' => $slug]),
    ];
  }

}
