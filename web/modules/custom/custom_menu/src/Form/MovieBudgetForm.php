<?php

namespace Drupal\custom_menu\Form;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Config form to take input of movie budget.
 */
class MovieBudgetForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_menu_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return [
      'custom_menu.admin_settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('custom_menu.admin_settings');
    $form['budget'] = [
      '#type' => 'number',
      '#title' => $this->t('Movie Budget'),
      '#default_value' => $config->get('budget'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('custom_menu.admin_settings')
      ->set('budget', $form_state->getValue('budget'))
      ->save();
    Cache::invalidateTags(['BUDGET_UPDATE']);
    parent::submitForm($form, $form_state);
  }

}
