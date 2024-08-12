<?php

namespace Drupal\custom_blocks\Form;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Custom form.
 *
 * Contains a group of fields to take input to display in a block.
 * More groups can be added or removed during value input.
 */
class CustomForm extends FormBase {

  /**
   * Database Connection variable.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Initialises the database connection obejct.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   Database connection object.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'custom_blocks_custom';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    // Fetching the number of grouped fields present.
    $group_num = $form_state->get('group_num');

    // Handles nested values in forms.
    $form['#tree'] = TRUE;

    // Defining the fieldset.
    $form['group'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Group'),
      '#prefix' => '<div id="add-group-wrapper">',
      '#suffix' => '</div>',
    ];

    // Setting to one group initially.
    if (empty($group_num)) {
      $group_num = $form_state->set('group_num', 1);
    }

    // Rendering each fields under the group.
    for ($i = 0; $i < $form_state->get('group_num'); $i++) {

      // Group Heading.
      $form['group'][$i]['heading'] = [
        '#type' => 'markup',
        '#markup' => $this->t("<b>Group @number</b>", ['@number' => $i + 1]),
      ];
      // Field for group name.
      $form['group'][$i]['group_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Group Name'),
        '#maxlength' => 25,
      ];
      // Field for first label name.
      $form['group'][$i]['first_label'] = [
        '#type' => 'textfield',
        '#title' => $this->t('First Label'),
        '#maxlength' => 25,
      ];
      // Field for first label value.
      $form['group'][$i]['first_label_value'] = [
        '#type' => 'number',
        '#title' => $this->t('First Label Value'),
        '#min' => 0,
      ];
      // Field for 2nd label name.
      $form['group'][$i]['second_label'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Second Label'),
        '#maxlength' => 25,
      ];
      // Field for 2nd label value.
      $form['group'][$i]['second_label_value'] = [
        '#type' => 'number',
        '#title' => $this->t('Second Label Value'),
        '#min' => 0,
      ];
    }

    // Container for actions button.
    $form['group']['actions'] = [
      '#type' => 'actions',
    ];

    // Add More button.
    $form['group']['actions']['add_more'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add More'),
      '#submit' => ['::addGroup'],
      '#ajax' => [
        'callback' => '::addGroupCallback',
        'wrapper' => "add-group-wrapper",
      ],
    ];

    // Displaying remove button if more than ne group fields are present.
    if ($form_state->get('group_num') > 1) {
      $form['group']['actions']['remove'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove'),
        '#submit' => ['::removeGroup'],
        '#ajax' => [
          'callback' => '::addGroupCallback',
          'wrapper' => "add-group-wrapper",
        ],
      ];
    }

    // Submit button.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * Increases the fields group by 1.
   *
   * @param array $form
   *   The form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current form state.
   */
  public function addGroup(array &$form, FormStateInterface $form_state) {
    // Fetching the number of grouped fields present.
    $group_num = $form_state->get('group_num');
    // Increasing the grouped fields by 1.
    $form_state->set('group_num', $group_num + 1);
    $form_state->setRebuild();
  }

  /**
   * Decreases the fields group by 1.
   *
   * @param array $form
   *   The form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current form state.
   */
  public function removeGroup(array &$form, FormStateInterface $form_state) {
    // Fetching the number of grouped fields present.
    $group_num = $form_state->get('group_num');
    // Removing the last grouped fields if more than one groups are present.
    if ($group_num > 1) {
      $form_state->set('group_num', $group_num - 1);
    }
    $form_state->setRebuild();
  }

  /**
   * Returns the newly created form.
   *
   * @param array $form
   *   The form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current form state.
   *
   * @return array
   *   Returns the newly rendered form.
   */
  public function addGroupCallback(array &$form, FormStateInterface $form_state): array {
    return $form['group'];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // Fetching the number of groups.
    $group_num = $form_state->get('group_num');
    // Fetching the values from form state and inserting in database.
    // Iterating through each group.
    for ($i = 0; $i < $group_num; $i++) {
      // Associative array containing submitted values.
      $fields = $form_state->getValues();
      $group_info["group_name"] = $fields['group'][$i]['group_name'];
      $group_info["first_label_name"] = $fields['group'][$i]['first_label'];
      $group_info["first_label_value"] = $fields['group'][$i]['first_label_value'];
      $group_info["second_label_name"] = $fields['group'][$i]['second_label'];
      $group_info["second_label_value"] = $fields['group'][$i]['second_label_value'];
      $this->database->insert('custom_block_data_table')->fields($group_info)->execute();
    }
    // Invalidating cache when form data is submitted and stored in table.
    Cache::invalidateTags(['custom_block_data_table']);
    // Message on successful form submission.
    $this->messenger()->addStatus($this->t('Values are saved'));
  }

}
