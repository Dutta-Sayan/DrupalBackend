<?php

namespace Drupal\custom_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Custom block.
 *
 * @Block(
 *   id = "custom_block_1",
 *   admin_label = @Translation("Custom Block 1"),
 *   category = @Translation("First custom block"),
 * )
 */
class CustomBlock1 extends BlockBase {

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
