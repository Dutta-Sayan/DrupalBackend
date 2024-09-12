<?php

namespace Drupal\database_operations\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Database operations routes.
 */
class EventsController extends ControllerBase {

  /**
   * Database Connection object.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * The controller constructor.
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
   * Fetches the results required by database operations.
   *
   * @return array[]
   *   Returns an array ofthe result.
   */
  public function getData() {

    // Array tostore the result.
    $result = [];
    // Database connection object.
    $conn = $this->connection;

    // Query to fetch number of events- yearly.
    $query = $conn->select('node__field_event_date', 'nfed');
    $query->addExpression('YEAR(nfed.field_event_date_value)', 'year');
    $query->addExpression('count(nfed.entity_id)', 'event_count');
    $query->groupBy('year');
    $query->orderBy('event_count');
    $result_yearly = $query->execute()->fetchAll();

    // Query to fetch number of events- quarterly.
    $query = $conn->select('node__field_event_date', 'nfed');
    $query->addExpression('QUARTER(nfed.field_event_date_value)', 'quarter');
    $query->addExpression('count(nfed.entity_id)', 'event_count');
    $query->groupBy('quarter');
    $query->orderBy('event_count');
    $result_quarterly = $query->execute()->fetchAll();

    // Query to fetch number of events of each type.
    $query = $conn->select('node__field_event_type', 'nfet');
    $query->addField('nfet', 'field_event_type_value', 'event_type');
    $query->addExpression('count(nfet.entity_id)', 'event_count');
    $query->groupBy('field_event_type_value');
    $query->orderBy('event_count');
    $result_type = $query->execute()->fetchAll();

    // Storing each query result in the array.
    $result = [
      "Yearly Events" => $result_yearly,
      "Quarterly Events" => $result_quarterly,
      "Events By Type" => $result_type,
    ];
    return $result;

  }

  /**
   * Builds the response.
   */
  public function content(): array {
    $result = $this->getData();
    return [
      '#theme' => 'events_data',
      '#content' => $result,
      '#attached' => [
        'library' => [
          'database_operations/events_css',
        ],
      ],
    ];
  }

}
