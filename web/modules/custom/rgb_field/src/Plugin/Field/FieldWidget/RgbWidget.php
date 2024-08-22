<?php

namespace Drupal\rgb_field\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'rgb_field' widget.
 *
 * @FieldWidget(
 *   id = "rgbcode_field_widget",
 *   module = "rgb_field",
 *   label = @Translation("RGB value"),
 *   field_types = {
 *     "rgb_field",
 *   }
 * )
 */
class RgbWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // Field to take input of red value.
    $element['red'] = [
      '#type' => 'number',
      '#title' => 'Red',
      '#size' => 7,
      '#maxlength' => 7,
      '#min' => 0,
      '#max' => 255,
      '#default_value' => $items[$delta]->red ?? 0,
    ];
    // Field to take input of green value.
    $element['green'] = [
      '#type' => 'number',
      '#title' => 'Green',
      '#size' => 7,
      '#maxlength' => 7,
      '#min' => 0,
      '#max' => 255,
      '#default_value' => $items[$delta]->green ?? 0,
    ];
    // Field to take input of blue value.
    $element['blue'] = [
      '#type' => 'number',
      '#title' => 'Blue',
      '#size' => 7,
      '#maxlength' => 7,
      '#min' => 0,
      '#max' => 255,
      '#default_value' => $items[$delta]->blue ?? 0,
    ];
    $element['value'] = [
      '#type' => 'hidden',
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    // Settings default value to 0 if no input is given.
    $red = $values[0]['red'] ?? 0;
    $green = $values[0]['green'] ?? 0;
    $blue = $values[0]['blue'] ?? 0;

    // Convert RGB to hex code.
    $hexcode = Color::rgbToHex("$red, $green, $blue");

    // Update the value field with the hex code.
    $values[0]['value'] = $hexcode;

    return $values;

  }

}
