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
    // Accessing the hexcode value.
    $value = $items[$delta]->value ?? '';
    // If hexcode value is present, converting it to rgb.
    if ($value) {
      $rgb = Color::hexToRgb($value);
    }
    // Field to take input of red value.
    $element['red'] = [
      '#type' => 'number',
      '#title' => 'Red',
      '#size' => 7,
      '#maxlength' => 7,
      '#min' => 0,
      '#max' => 255,
      '#default_value' => $rgb['red'] ?? '',
    ];
    // Field to take input of green value.
    $element['green'] = [
      '#type' => 'number',
      '#title' => 'Green',
      '#size' => 7,
      '#maxlength' => 7,
      '#min' => 0,
      '#max' => 255,
      '#default_value' => $rgb['green'] ?? '',
    ];
    // Field to take input of blue value.
    $element['blue'] = [
      '#type' => 'number',
      '#title' => 'Blue',
      '#size' => 7,
      '#maxlength' => 7,
      '#min' => 0,
      '#max' => 255,
      '#default_value' => $rgb['blue'] ?? '',
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
    $red = $values[0]['red'] ?? '';
    $green = $values[0]['green'] ?? '';
    $blue = $values[0]['blue'] ?? '';

    if ($red != '' || $green != '' || $blue != '') {
      // Convert RGB to hex code.
      $hexcode = Color::rgbToHex("$red, $green, $blue");

      // Update the value field with the hex code.
      $values[0]['value'] = $hexcode;
    }
    else {
      $values[0]['value'] = '';
    }

    return $values;

  }

}
