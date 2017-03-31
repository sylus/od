<?php

namespace Drupal\od_ext_fonts\Plugin\IconProvider;

use Drupal\icon\IconProvider;
use Drupal\icon\Plugin\IconProvider\IconProviderBase;

/**
 * Open Government.
 *
 * @IconProvider(
 *   id = "opengov",
 *   label = @Translation("Open Government"),
 *   url = "http://github.com/open-data"
 * )
 */
class OpenGov extends IconProviderBase {

  /**
   * {@inheritdoc}
   */
  public function loadInstances(array $entity_ids = NULL) {
    $providers = [];

    return $providers;
  }

  /**
   * {@inheritdoc}
   */
  public function saveInstances(array $providers = []) {

  }

}
