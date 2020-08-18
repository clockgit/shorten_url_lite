<?php

namespace Drupal\shorten_url_lite\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Short URL entity.
 *
 * @ingroup shorten_url_lite
 *
 * @ContentEntityType(
 *   id = "short_url",
 *   label = @Translation("Short URL"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\shorten_url_lite\ShortURLListBuilder",
 *     "views_data" = "Drupal\shorten_url_lite\Entity\ShortURLViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\shorten_url_lite\Form\ShortURLForm",
 *       "add" = "Drupal\shorten_url_lite\Form\ShortURLForm",
 *       "edit" = "Drupal\shorten_url_lite\Form\ShortURLForm",
 *       "delete" = "Drupal\shorten_url_lite\Form\ShortURLDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\shorten_url_lite\ShortURLHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\shorten_url_lite\ShortURLAccessControlHandler",
 *   },
 *   base_table = "short_url",
 *   translatable = FALSE,
 *   admin_permission = "administer short url entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/short_url/{short_url}",
 *     "add-form" = "/admin/structure/short_url/add",
 *     "edit-form" = "/admin/structure/short_url/{short_url}/edit",
 *     "delete-form" = "/admin/structure/short_url/{short_url}/delete",
 *     "collection" = "/admin/structure/short_url",
 *   },
 *   field_ui_base_route = "short_url.settings"
 * )
 */
class ShortURL extends ContentEntityBase implements ShortURLInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }
  /**
   * @return int
   */
  public function getCount() {
    return $this->get('count')->value;
  }

  /**
   * @return int
   */
  public function incrementCount() {
    $this->set('count', $this->getCount()+1);
    $this->save();
    return $this->get('count')->value;;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Short URL entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Short Code'))
      ->setDescription(t('The short code of the Short URL entity.'))
      ->setSettings([
        'max_length' => 9,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE)
      //->addConstraint('ShortCode')
      ->setPropertyConstraints('value', ['Length' => ['max' => 9, 'min' => 4]])
      ->addConstraint('UniqueField');

    $fields['url'] = BaseFieldDefinition::create('uri')
      ->setLabel(t('Redirect URL'))
      ->setDescription(t('The URL the code will redirect too.'))
      ->setSettings([
        'max_length' => 256,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['count'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Redirect Count'))
      ->setDescription(t('Number of time the short code has been used'))
      ->setDefaultValue(0)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'hidden',
      ])
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

  private function uniqueShort($value)
  {
    //TODO search db for value and return false if found.
    return TRUE;
  }

}
