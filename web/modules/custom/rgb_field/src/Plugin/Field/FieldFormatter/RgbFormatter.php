<?php

namespace Drupal\rgb_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'rgb_field' formatter.
 *
 * @FieldFormatter(
 *   id = "rgb_field_formatter",
 *   module = "rgb_field",
 *   label = @Translation("Display rgb value"),
 *   field_types = {
 *     "rgb_field",
 *   }
 * )
 */
class RgbFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#theme' => 'rgb_field_content',
        '#content' => $item->value,
        '#attached' => [
          'library' => [
            'rgb_field/rgb_field_css',
          ],
        ],
      ];
    }
    return $elements;
  }

}
