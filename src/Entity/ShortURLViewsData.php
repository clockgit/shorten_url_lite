<?php

namespace Drupal\shorten_url_lite\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Short URL entities.
 */
class ShortURLViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
