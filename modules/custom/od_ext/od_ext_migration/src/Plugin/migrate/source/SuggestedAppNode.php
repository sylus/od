<?php

namespace Drupal\od_ext_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source plugin for app content.
 *
 * @MigrateSource(
 *   id = "suggested_app_node"
 * )
 */
class SuggestedAppNode extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('node', 'n')
                  ->fields('n', ['nid', 'vid', 'language', 'title'])
                  ->condition('n.type', 'suggested_applications');

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'nid' => $this->t('Node ID'),
      'vid' => $this->t('Revision ID'),
      'language' => $this->t('Language'),
      'title' => $this->t('Title'),
      'body' => $this->t('Body'),
      'file_fid' => $this->t('File fid'),
      'file_alt' => $this->t('File alt'),
      'file_title' => $this->t('File title'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'nid' => [
        'type' => 'integer',
        'alias' => 'n',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {

    // Body.
    $body = $this->select('field_data_body', 'db')
                 ->fields('db', ['body_value'])
                 ->condition('entity_id', $row->getSourceProperty('nid'))
                 ->condition('revision_id', $row->getSourceProperty('vid'))
                 ->condition('language', $row->getSourceProperty('language'))
                 ->execute()
                 ->fetchCol();

    $row->setSourceProperty('body', $body[0]);

    return parent::prepareRow($row);
  }

}
