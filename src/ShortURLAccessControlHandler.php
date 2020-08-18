<?php

namespace Drupal\shorten_url_lite;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Short URL entity.
 *
 * @see \Drupal\shorten_url_lite\Entity\ShortURL.
 */
class ShortURLAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\shorten_url_lite\Entity\ShortURLInterface $entity */

    switch ($operation) {

      case 'view':

        return AccessResult::allowedIfHasPermission($account, 'view published short url entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit short url entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete short url entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add short url entities');
  }


}
