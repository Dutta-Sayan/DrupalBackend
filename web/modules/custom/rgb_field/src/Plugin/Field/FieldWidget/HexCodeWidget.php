<?php

namespace Drupal\rgb_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'rgb_field' widget.
 *
 * @FieldWidget(
 *   id = "rgb_field_widget",
 *   module = "rgb_field",
 *   label = @Translation("Hexcode value"),
 *   field_types = {
 *     "rgb_field",
 *   }
 * )
 */
class HexCodeWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // Setting the value of the 'value' field initially to NULL.
    $value = $items[$delta]->value ?? '';
    // Field for taking the hex value input.
    $element += [
      '#type' => 'textfield',
      '#default_value' => $value,
      '#size' => 7,
      '#maxlength' => 7,
      '#element_validate' => [
        [$this, 'validate'],
      ],
    ];

    return ['value' => $element];
  }

  /**
   * Validates the hex code.
   *
   * @param mixed $element
   *   The form element.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return void
   *   Returns to the formElement() function.
   */
  public function validate(&$element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if (empty($value)) {
      $form_state->setValueForElement($element, '');
      return;
    }
    // Checking for valid hexcode pattern.
    if (!preg_match('/^#([a-f0-9]{6})$/i', strtolower($value))) {
      $form_state->setError($element, t("Invalid color code"));
    }
  }

}
