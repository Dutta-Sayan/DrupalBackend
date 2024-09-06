<?php

namespace Drupal\movie;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Interface defining a Movie Config Entity.
 */
interface MovieInterface extends ConfigEntityInterface {

  /**
   * Gets the year of the movie entity.
   *
   * @return int|null
   *   The year of the movie or null if not defined..
   */
  public function year();

  /**
   * Sets the year of the movie entity.
   *
   * @param int $year
   *   The movie year.
   *
   * @return void
   *   Sets the movie year.
   */
  public function setYear(int $year);

}
