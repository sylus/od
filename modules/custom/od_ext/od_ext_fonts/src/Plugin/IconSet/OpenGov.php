<?php

namespace Drupal\od_ext_fonts\Plugin\IconSet;

use Drupal\icon\IconSet;
use Drupal\icon\Plugin\IconSet\IconSetBase;

/**
 * Open Government Icon Set.
 *
 * @IconSet(
 *   id = "opengov",
 *   label = @Translation("Open Government Icons"),
 *   provider = "opengov",
 *   renderer = "image"
 * )
 */
class OpenGov extends IconSetBase {

  /**
   * {@inheritdoc}
   */
  public function loadInstances(array $entity_ids = NULL) {
    $sets = [];

    $mask = '/.svg/';
    $svg_icons = file_scan_directory(DRUPAL_ROOT . '/core/misc/icons', $mask, ['recurse' => TRUE]);
    foreach ($svg_icons as $id => $config) {
      $id = $config->name;
      $settings = [
        'filename' => $config->filename,
      ];
      $path = $config->uri;
      $sets[$id] = new IconSet($id, $settings, $path);
    }
    return $sets;
  }

  /**
   * {@inheritdoc}
   */
  public function saveInstances(array $sets = []) {

  }

}
