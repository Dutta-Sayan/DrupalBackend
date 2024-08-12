<?php

namespace Drupal\custom_blocks\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controls the content and access of custom routes page.
 */
class CustomBlocksController extends ControllerBase {

  /**
   * Displays the content of the custom routes page.
   *
   * @return array
   *   Returns the content to be displayed.
   */
  public function content(): array {
    return [
      '#markup' => $this->t('This page tests custom blocks'),
    ];
  }

}
