<?php

namespace Drupal\database_operations\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form to take a taxonomy term input.
 */
class TaxonomyTermForm extends FormBase {

  /**
   * Database Connection object.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * The controller constructor.
   *
   * Initialises the database connection.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'database_operations_taxonomy_term';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['#attached']['library'][] = 'database_operations/taxonomy_css';
    // Field to accept taxonomy term.
    $form['term'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Taxonomy Term'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Get Details'),
      ],
    ];

    // Field to store the result of query.
    $form['result'] = [];

    // If the form is submitted and is rebuild, then the result is displayed.
    if ($form_state->isRebuilding()) {

      // Taxonomy term.
      $term = $form_state->getValue('term');
      // Loading the taxonomy term entered.
      $storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term')
        ->loadByProperties(['name' => $term]);
      // Performing queries if the term exists.
      if ($storage) {
        $conn = $this->connection;
        $query = $conn->select('taxonomy_term_field_data', 'ttfd');
        $query->join('taxonomy_term_data', 'ttd', 'ttfd.tid = ttd.tid');
        $query->join('taxonomy_index', 'ti', 'ttd.tid = ti.tid');
        $query->join('node_field_data', 'nfd', 'ti.nid = nfd.nid');
        $query
          ->fields('ttfd', ['tid'])
          ->fields('ttd', ['uuid'])
          ->fields('ti', ['nid'])
          ->fields('nfd', ['title'])
          ->condition('ttfd.name', $term)
          ->addExpression("CONCAT('/node/', nfd.nid)", 'url');
        $result = $query->execute()->fetchAll();

        // Stores the query result in easily accessible format.
        $output = [];

        // Storing the result in a array for rendering.
        foreach ($result as $row) {
          if (!$output) {
            $output['Term ID'] = $row->tid;
            $output['Term UUID'] = $row->uuid;
            $output['Node Details'] = [];
          }
          $output['Node Details'][] = [
            'Node Title' => $row->title,
            'Node Url' => $row->url,
          ];
        }
        // Displaying the Term ID.
        $form['result']['term_id'] = [
          '#type' => 'item',
          '#title' => $this->t('Term ID:'),
          '#prefix' => '<div class="taxonomy-details">',
          '#suffix' => '</div>',
          '#markup' => '<span id="info"> ' . $output['Term ID'] . '</span>',
        ];
        // Displaying the Term UUID.
        $form['result']['term_uuid'] = [
          '#type' => 'item',
          '#title' => $this->t('Term UUID:'),
          '#prefix' => '<div class="taxonomy-details">',
          '#suffix' => '</div>',
          '#markup' => '<span id="info"> ' . $output['Term UUID'] . '</span>',
        ];

        // Displaying the node details.
        $i = 0;
        foreach ($output['Node Details'] as $details) {
          // Rendering node title.
          $form['result'][$i]['node_title'] = [
            '#type' => 'item',
            '#title' => $this->t('Node Title:'),
            '#prefix' => '<div class="taxonomy-details node-details">',
            '#suffix' => '</div>',
            '#markup' => '<span id="info"> ' . $details['Node Title'] . '</span>',
          ];
          // Displaying node Url.
          $form['result'][$i]['node_url'] = [
            '#type' => 'item',
            '#title' => $this->t('Node URL:'),
            '#prefix' => '<div class="taxonomy-details node-details">',
            '#suffix' => '</div>',
            '#markup' => '<span id="info"><a href="' . $details['Node Url'] .
            '">' . $details['Node Url'] . '</a></span>',
          ];
          $i++;
        }
      }
      else {
        // Rendering error message if term is not present.
        $form['result']['error'] = [
          '#type' => 'item',
          '#prefix' => '<div class="error">',
          '#suffix' => '</div>',
          '#markup' => 'Term does not exist',
        ];
      }
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->messenger()->addStatus($this->t('The message has been sent.'));
    $form_state->setRebuild(TRUE);
  }

}
