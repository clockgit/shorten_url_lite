<?php
namespace Drupal\shorten_url_lite\Controller;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Controller\ControllerBase;
use \Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\shorten_url_lite\Entity\ShortURL;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ShortUrlController extends ControllerBase
{
  /**
   * @param $short
   * @return TrustedRedirectResponse|RedirectResponse
   */
  public function deliver($short) {
    $entity =  $this->getByCode($short);
    if($entity) {
      $entity->incrementCount();
      return new TrustedRedirectResponse($entity->url->value);
    }
    dsm("No redirect setup for $short.");
    return $this->redirect('<front>');
  }

  /**
   * @param $short
   * @return array|RedirectResponse
   */
  public function view($short) {
    $entity =  $this->getByCode($short);
    if($entity) {
      $entity_type = 'short_url';
      $view_mode = 'teaser';
      $view_builder = \Drupal::entityTypeManager()->getViewBuilder($entity_type);
      $build = $view_builder->view($entity, $view_mode);
      $markup = render($build);
      $build = [
        '#markup' => $markup,
      ];
      return $build;
    }
    return $this->redirect('<front>');
  }

  public function buildForm() {
    $entity = ShortURL::create(['type' => 'short_url']);
    $form = \Drupal::service('entity.form_builder')->getForm($entity);
    return $form;
  }

  private function getByCode($short) {
    try {
      $entities = \Drupal::entityTypeManager()
        ->getStorage('short_url')
        ->loadByProperties(['name' => $short]);
    } catch (InvalidPluginDefinitionException $e) {
      //TODO Add error handling
      return FALSE;
    } catch (PluginNotFoundException $e) {
      //TODO Add error handling
      return FALSE;
    }
    if ($entity = reset($entities)) {
      return $entity;
    }
    return FALSE;
  }
}
