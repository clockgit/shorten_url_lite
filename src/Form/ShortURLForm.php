<?php

namespace Drupal\shorten_url_lite\Form;

use Drupal\Component\Utility\Random;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for Short URL edit forms.
 *
 * @ingroup shorten_url_lite
 */
class ShortURLForm extends ContentEntityForm {

  /**
   * The current user account.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $account;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    $instance = parent::create($container);
    $instance->account = $container->get('current_user');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var \Drupal\shorten_url_lite\Entity\ShortURL $entity */
    $form = parent::buildForm($form, $form_state);
    // set unique short code
    $form['name']['widget'][0]['value']['#default_value'] = (new Random)->name(9, True);
    // Get the current user
    $user = \Drupal::currentUser();
    // Check for permission
    if(!$user->hasPermission('add vanity urls to url entities')) {
      $form['name']['#disabled'] = 'disabled';
    }
    $form['count']['#disabled'] = 'disabled';
    //TODO change to use permissions
    $form['user_id']['#access'] = FALSE;
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Short URL.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Short URL.', [
          '%label' => $entity->label(),
        ]));
    }
    //$form_state->setRedirect('entity.short_url.canonical', ['short_url' => $entity->id()]);
    $form_state->setRedirect('shorten_url_lite.view', ['short' => $entity->name->value]);
  }

}
