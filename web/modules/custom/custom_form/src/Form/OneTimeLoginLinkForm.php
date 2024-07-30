<?php

namespace Drupal\employee\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * Summary of CustomForm.
 */
class OneTimeLoginLinkForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'one_time_login';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['element'] = [
      '#type' => 'markup',
      '#markup' => "<div class='success'></div>",
    ];
    $form['user_id'] = [
      '#title' => t('User Id'),
      '#type' => 'number',
      '#size' => 25,
      // '#required' => TRUE,
      '#description' => t('Enter the user id'),
      '#suffix' => '<div class="error" id="userid"></div>',
    ];

    $form['actions'] = [
      '#type' => 'submit',
      '#value' => t('Submit'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $uid = $form_state->getValue('user_id');
    $user = User::load($uid);
    // dd($users);
    if ($user) {
      // Generate a one-time login link.
      $otll = user_pass_reset_url($user);
      $this->messenger()->addMessage($this->t('Generated Link: <a href="@link">@link</a>', ['@link' => $otll]));
    }
    else {
      $form_state->setErrorByName('otll', $this->t('User does not exist'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
