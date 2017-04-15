<?php

namespace Drupal\od_ext_migration\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateSkipProcessException;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Construct the full directory private.
 *
 * @MigrateProcessPlugin(
 *   id = "od_dest_path",
 * )
 */
class DestPath extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (!$value) {
      throw new MigrateSkipProcessException();
    }
    return $this->getDestPath($value);
  }

  /**
   * Custom function for getDestPath().
   */
  protected function getDestPath($value) {
    return str_replace('public://', 'public://legacy/', $value);
  }

}
