<?php

namespace Drupal\rgb_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'rgb_field' widget.
 *
 * @FieldWidget(
 *   id = "color_picker_field_widget",
 *   module = "rgb_field",
 *   label = @Translation("Color Picker"),
 *   field_types = {
 *     "rgb_field",
 *   }
 * )
 */
class ColorPickerWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element += [
      '#type' => 'color',
      '#default_value' => $items[$delta]->value ?? NULL,
    ];

    return ['value' => $element];
  }

}
