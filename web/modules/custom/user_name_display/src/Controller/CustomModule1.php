<?php

namespace Drupal\user_name_display\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Displays the name of the current user in the display page.
 */
class CustomModule1 extends ControllerBase
{
  /**
   * Method to display the current user name.
   * @return array
   */
  public function displayPage() : array
  {
    if (\Drupal::currentUser()->hasPermission('access user_name_display page')) {
      $build = [
        '#type' => 'markup',
        '#markup' => $this->t('Hello @username', ['@username' => $this->currentUser()->getDisplayName()]),
        '#cache' => [
          'tags' => ['user:'.$this->currentUser()->id()],
        ],
      ];
    }
    else {
      $build = [
        '#markup' => $this->t("<h3>Permission Denied<h3>")
      ];
    }
    return $build;
  }
}
