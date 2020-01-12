<?php

namespace Drupal\migrations\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Minimalistic example for a SqlBase source plugin.
 *
 * @MigrateSource(
 *   id = "person",
 * )
 */
class Person extends SqlBase {

  /**
   * {@inheritdoc}
   */
  // public function query() {
  //   $query =
  //     $this->select('artist', 'a')
  //       ->fields('a', [
  //         'id',
  //         'name'
  //         ]);
  //   return $query;
  // }

public function query() {
    $query = $this->select('artist', 'a');
    $query->innerJoin('artist_item', 'ai', 'a.id = ai.artist_id');
    // $query->innerJoin('function', 'f', 'ai.function_id = f.id');
    $query->fields('a', [
          'id',
          'name'
          ],
        'ai', [
          'artist_id',
          'item_id'
          ]
        // 'f', [
        //   'id',
        //   'name'
     );
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'id' => $this->t('dram_id'),
      'name' => $this->t('name'),
      'ai.item_id' => $this->t('item_id')
    ];
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'id' => [
        'type' => 'integer',
        'alias' => 'a',
      ],
    ];
  }




  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    // This example shows how source properties can be added in
    // prepareRow(). The source dates are stored as 2017-12-17
    // and times as 16:00. Drupal 8 saves date and time fields
    // in ISO8601 format 2017-01-15T16:00:00 on UTC.
    // We concatenate source date and time and add the seconds.
    // The same result could also be achieved using the 'concat'
    // and 'format_date' process plugins in the migration
    // definition.
    $date = $row->getSourceProperty('date');
    $time = $row->getSourceProperty('time');
    $datetime = $date . 'T' . $time . ':00';
    $row->setSourceProperty('datetime', $datetime);
    return parent::prepareRow($row);
  }
}
