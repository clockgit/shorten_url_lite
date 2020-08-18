<?php

namespace Drupal\shorten_url_lite;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Short URL entities.
 *
 * @ingroup shorten_url_lite
 */
class ShortURLListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Short URL ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\shorten_url_lite\Entity\ShortURL $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.short_url.edit_form',
      ['short_url' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
