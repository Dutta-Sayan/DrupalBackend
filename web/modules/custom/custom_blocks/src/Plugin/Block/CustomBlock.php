<?php

namespace Drupal\custom_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Custom block.
 *
 * @Block(
 *   id = "custom_block",
 *   admin_label = @Translation("Custom Block"),
 *   category = @Translation("First custom block"),
 * )
 */
class CustomBlock extends BlockBase {

  /**
   * Prints the role of the currently logged in user.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup[]
   *   Returns the message to be displayed.
   */
  public function build(): array {
    // Array containing user roles.
    $roles = implode(',', \Drupal::currentUser()->getRoles());
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Welcome @role', ['@role' => $roles]),
      '#cache' => [
        'tags' => ['user:' . \Drupal::currentUser()->id()],
      ],
    ];
  }

}
