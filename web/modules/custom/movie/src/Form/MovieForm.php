<?php

namespace Drupal\movie\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form handler for the Movie add and edit forms.
 */
class MovieForm extends EntityForm {

  /**
   * Constructs an Movie Form object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entityTypeManager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $movie = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Movie Name'),
      '#maxlength' => 255,
      '#default_value' => $movie->label(),
      '#description' => $this->t("Label for the Movie."),
      '#required' => TRUE,
    ];
    $form['year'] = [
      '#type' => 'number',
      '#min' => 1947,
      '#max' => 2025,
      '#title' => $this->t('Movie Year'),
      '#default_value' => $movie->year(),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $movie->id(),
      '#machine_name' => [
        'exists' => [$this, 'exist'],
      ],
      '#disabled' => !$movie->isNew(),
    ];

    // You will need additional form elements for your custom properties.
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $movie = $this->entity;
    $status = $movie->save();

    if ($status === SAVED_NEW) {
      $this->messenger()->addMessage($this->t('The %label Movie created.', [
        '%label' => $movie->label(),
      ]));
    }
    else {
      $this->messenger()->addMessage($this->t('The %label Movie updated.', [
        '%label' => $movie->label(),
      ]));
    }

    $form_state->setRedirect('entity.movie.collection');
  }

  /**
   * Helper function to check whether an Movie configuration entity exists.
   */
  public function exist($id) {
    $entity = $this->entityTypeManager->getStorage('movie')->getQuery()
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

}
