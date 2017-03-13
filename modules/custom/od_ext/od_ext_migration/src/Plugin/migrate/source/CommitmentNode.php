<?php

namespace Drupal\od_ext_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source plugin for commitment content.
 *
 * @MigrateSource(
 *   id = "commitment_node"
 * )
 */
class CommitmentNode extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('node', 'n')
      ->fields('n',
      [
        'nid',
        'vid',
        'language',
        'title',
      ])
      ->condition('n.type', 'commitment');

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
      'department' => $this->t('Department'),
      'relevance' => $this->t('Relevance'),
      'deliverable' => $this->t('Deliverable'),
      'end_date' => $this->t('End Date'),
      'tags' => $this->t('Tags'),
      'pillars' => $this->t('Pillars'),
      'status' => $this->t('Status'),
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

    // Title Field.
    $title = $this->select('field_data_title_field', 'db')
      ->fields('db', ['title_field_value'])
      ->condition('entity_id', $row->getSourceProperty('nid'))
      ->condition('revision_id', $row->getSourceProperty('vid'))
      ->condition('language', $row->getSourceProperty('language'))
      ->condition('bundle', 'commitment')
      ->execute()
      ->fetchCol();

    // Body.
    $body = $this->select('field_data_body', 'db')
      ->fields('db', ['body_value'])
      ->condition('entity_id', $row->getSourceProperty('nid'))
      ->condition('revision_id', $row->getSourceProperty('vid'))
      ->condition('language', $row->getSourceProperty('language'))
      ->condition('bundle', 'commitment')
      ->execute()
      ->fetchCol();

    // Ambition.
    $ambition = $this->select('field_data_field_commitment_ambition', 'df')
      ->fields('df', ['field_commitment_ambition_value'])
      ->condition('entity_id', $row->getSourceProperty('nid'))
      ->condition('revision_id', $row->getSourceProperty('vid'))
      ->condition('language', $row->getSourceProperty('language'))
      ->condition('bundle', 'commitment')
      ->execute()
      ->fetchCol();

    // Department.
    // TODO: switch to fetchAllAssoc + remap in YML.
    $department = $this->select('field_data_field_department', 'df')
      ->fields('df', ['field_department_tid'])
      ->condition('entity_id', $row->getSourceProperty('nid'))
      ->condition('revision_id', $row->getSourceProperty('vid'))
      ->condition('language', $row->getSourceProperty('language'))
      ->condition('bundle', 'commitment')
      ->execute()
      ->fetchAssoc();

    // Relevance.
    $relevance = $this->select('field_data_field_commitment_relevance', 'df')
      ->fields('df', ['field_commitment_relevance_value'])
      ->condition('entity_id', $row->getSourceProperty('nid'))
      ->condition('revision_id', $row->getSourceProperty('vid'))
      ->condition('language', $row->getSourceProperty('language'))
      ->condition('bundle', 'commitment')
      ->execute()
      ->fetchCol();

    // Deliverable.
    // TODO: switch to fetchAllAssoc + remap in YML.
    $deliverable = $this->select('field_data_field_deliverable', 'df')
      ->fields('df', ['field_deliverable_target_id'])
      ->condition('entity_id', $row->getSourceProperty('nid'))
      ->condition('revision_id', $row->getSourceProperty('vid'))
      ->condition('language', 'und')
      ->condition('bundle', 'commitment')
      ->execute()
      ->fetchAssoc();

    // End Date.
    $end_date = $this->select('field_data_field_commitment_end_date_txt', 'df')
      ->fields('df', ['field_commitment_end_date_txt_value'])
      ->condition('entity_id', $row->getSourceProperty('nid'))
      ->condition('revision_id', $row->getSourceProperty('vid'))
      ->condition('language', $row->getSourceProperty('language'))
      ->condition('bundle', 'commitment')
      ->execute()
      ->fetchCol();

    // Tags.
    // TODO: switch to fetchAllAssoc + remap in YML.
    $tags = $this->select('field_data_field_commitment_tags', 'df')
      ->fields('df', ['field_commitment_tags_tid'])
      ->condition('entity_id', $row->getSourceProperty('nid'))
      ->condition('revision_id', $row->getSourceProperty('vid'))
      ->condition('language', $row->getSourceProperty('language'))
      ->condition('bundle', 'commitment')
      ->execute()
      ->fetchAssoc();

    // Pillars.
    // TODO: switch to fetchAllAssoc + remap in YML.
    $pillars = $this->select('field_data_field_pillars', 'df')
      ->fields('df', ['field_pillars_tid'])
      ->condition('entity_id', $row->getSourceProperty('nid'))
      ->condition('revision_id', $row->getSourceProperty('vid'))
      ->condition('language', $row->getSourceProperty('language'))
      ->condition('bundle', 'commitment')
      ->execute()
      ->fetchAssoc();

    // Status
    $status = $this->select('field_data_field_commitment_status', 'df')
      ->fields('df', ['field_commitment_status_value'])
      ->condition('entity_id', $row->getSourceProperty('nid'))
      ->condition('revision_id', $row->getSourceProperty('vid'))
      ->condition('language', $row->getSourceProperty('language'))
      ->condition('bundle', 'commitment')
      ->execute()
      ->fetchCol();

    if (!empty($title[0])) {
      $row->setSourceProperty('title', $title[0]);
    }
    $row->setSourceProperty('body', $body[0]);
    $row->setSourceProperty('department', $department['field_department_tid']);
    $row->setSourceProperty('ambition', $ambition[0]);
    $row->setSourceProperty('relevance', $relevance[0]);
    $row->setSourceProperty('deliverable', $deliverable['field_deliverable_target_id']);
    $row->setSourceProperty('end_date', $end_date[0]);
    $row->setSourceProperty('tags', $tags['field_commitment_tags_tid']);
    $row->setSourceProperty('pillars', $pillars['field_pillars_tid']);
    $row->setSourceProperty('status', $status[0]);

    return parent::prepareRow($row);
  }

}
