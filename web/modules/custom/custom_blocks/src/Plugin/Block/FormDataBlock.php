<?php

namespace Drupal\custom_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Custom block to render form data.
 *
 * @Block(
 *   id = "form_data_block",
 *   admin_label = @Translation("Form Data Block"),
 *   category = @Translation("Displays form data."),
 * )
 */
class FormDataBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Database Connection variable.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a FormDataBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Database\Connection $database
   *   Database connection object.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    Connection $database,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('database')
    );
  }

  /**
   * Renders the form data.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup[]
   *   Returns the message to be displayed.
   */
  public function build(): array {
    // Fetching the selected table.
    $query = $this->database->select('custom_block_data_table', 'b');
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
