<?php

namespace Drupal\custom_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Custom block to render form data.
 *
 * @Block(
 *   id = "form_data_block",
 *   admin_label = @Translation("Form Data Block"),
 *   category = @Translation("Displays form data."),
 * )
 */
class FormDataBlock extends BlockBase {

  /**
   * Renders the form data.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup[]
   *   Returns the message to be displayed.
   */
  public function build(): array {
    // Making database conection.
    $database = \Drupal::database();
    // Fetching the selected table.
    $query = $database->select('custom_block_data_table', 'b');
    // Adding the fields to be selected from the table.
    $query->fields('b');
    // Running the query.
    $result = $query->execute();
    // Fetching the results of the query.
    $record = $result->fetchAll();

    // Nested array to store fetched results.
    $group_info = [];
    // Iterating through each fetched row and storing them in array.
    foreach ($record as $value) {
      $group_info[] = [
        'group_name' => $value->group_name,
        'first_label' => $value->first_label_name,
        'first_label_value' => $value->first_label_value,
        'second_label' => $value->second_label_name,
        'second_label_value' => $value->second_label_value,
      ];
    }
    return [
      '#theme' => 'my_custom_block',
      '#content' => $group_info,
      '#attached' => [
        'library' => [
          'custom_blocks/custom_blocks_css',
        ],
      ],
      '#cache' => [
        'tags' => ['custom_block_data_table'],
      ],
    ];
  }

}
