<?php

namespace Drupal\rgb_field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of Rgb field type.
 *
 * @FieldType(
 *  id = "rgb_field",
 *  label = @Translation("Rgb color code"),
 *  description = @Translation("Field taking a colour input as an rgb hex code"),
 *  default_widget = "rgb_field_widget",
 *  default_formatter = "rgb_field_formatter",
 * )
 */
class RgbItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    $columns = [
      'value' => [
        'type' => 'text',
        'size' => 'tiny',
        'not null' => FALSE,
      ],
    ];

    $schema = ['columns' => $columns];
    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    // Defines the value field will be stored as a string.
    $properties['value'] = DataDefinition::create('string')->setLabel(t('Hex Value'));
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }

}
