<?php

namespace Drupal\custom_routes\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Controls the content and access of custom routes page.
 */
class CustomRoutesController extends ControllerBase {

  /**
   * Displays the content of the custom routes page..
   *
   * @return array
   *   Returns the content to be displayed.
   */
  public function content(): array {
    return [
      '#markup' => $this->t('This page is displayed by custom routes.'),
    ];
  }

  /**
   * Controls the access of the custom routes page.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Current user account object.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   The AccessResult value object.
   */
  public function access(AccountInterface $account) {
    // Checks for required permission and roles for providing access.
    return AccessResult::allowedIf($account->hasPermission('access the custom page') &&
    in_array('administrator', $account->getRoles()));
  }

}
