<?php

namespace Drupal\custom_menu\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Displays a message on comparing movie price with budget.
 */
class NodeViewEventSubscriber implements EventSubscriberInterface {

  /**
   * Configuration Object.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Messenger object.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Object of current route.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Initialises the configuration, messenger and rooute services objects.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Object of configuration service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   Object of messenger service.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   Object of route service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, MessengerInterface $messenger, RouteMatchInterface $route_match) {
    $this->configFactory = $config_factory;
    $this->messenger = $messenger;
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {

    $events[KernelEvents::VIEW][] = ['alterNodeView', 20];
    return $events;
  }

  /**
   * Displays a message after comparing movie price with budget.
   *
   * @return void
   *   Returns void.
   */
  public function alterNodeView() {

    // Fetches the node object of the current route.
    $node = $this->routeMatch->getParameter('node');
    if ($node instanceof NodeInterface) {
      if ($node->getType() == 'movie') {
        $config = $this->configFactory->get('custom_menu.admin_settings');
        $budget = $config->get('budget');
        $movie_price = $node->get('field_movie_price')->value;
        if ($budget > $movie_price) {
          $message = 'The movie is under budget';
        }
        elseif ($budget < $movie_price) {
          $message = 'The movie is over budget';
        }
        else {
          $message = 'The movie is within budget';
        }
        $this->messenger->addMessage($message);
      }
    }
  }

}
