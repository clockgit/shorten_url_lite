<?php

namespace Drupal\shorten_url_lite\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Short URL entities.
 *
 * @ingroup shorten_url_lite
 */
interface ShortURLInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Short URL name.
   *
   * @return string
   *   Name of the Short URL.
   */
  public function getName();

  /**
   * Sets the Short URL name.
   *
   * @param string $name
   *   The Short URL name.
   *
   * @return \Drupal\shorten_url_lite\Entity\ShortURLInterface
   *   The called Short URL entity.
   */
  public function setName($name);

  /**
   * Gets the Short URL creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Short URL.
   */
  public function getCreatedTime();

  /**
   * Sets the Short URL creation timestamp.
   *
   * @param int $timestamp
   *   The Short URL creation timestamp.
   *
   * @return \Drupal\shorten_url_lite\Entity\ShortURLInterface
   *   The called Short URL entity.
   */
  public function setCreatedTime($timestamp);

}
