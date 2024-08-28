<?php

namespace Drupal\movie\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\movie\MovieInterface;

/**
 * Defines the Movie entity.
 *
 * @ConfigEntityType(
 *   id = "movie",
 *   label = @Translation("Movie"),
 *   handlers = {
 *     "list_builder" = "Drupal\movie\Controller\MovieListBuilder",
 *     "form" = {
 *       "add" = "Drupal\movie\Form\MovieForm",
 *       "edit" = "Drupal\movie\Form\MovieForm",
 *       "delete" = "Drupal\movie\Form\MovieDeleteForm",
 *     }
 *   },
 *   config_prefix = "movie",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "year" = "year",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "year"
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/system/movie/{movie}",
 *     "delete-form" = "/admin/config/system/movie/{movie}/delete",
 *   }
 * )
 */
class Movie extends ConfigEntityBase implements MovieInterface {

  /**
   * The Movie ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Movie label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Movie Year.
   *
   * @var int
   */
  protected $year;

  /**
   * Returns the movie year.
   *
   * @return int
   *   Returns the movie year as an integer.
   */
  public function year() {
    return $this->year;
  }

  /**
   * Sets the year of the movie entity.
   *
   * @param int $year
   *   The movie year.
   *
   * @return void
   *   Sets the movie year.
   */
  public function setYear(int $year) {
    $this->year = $year;
  }

}
