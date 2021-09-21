<?php

namespace Drupal\site_location\Service;

use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Provides the service that gives the current time based on timezone selection.
 */
class CurrentTimeService {

  /**
   * {@inheritdoc}
   */
  public function __construct() {
  }

  /**
   * GetCurrentTime function used to get formatted time.
   *
   * @param string $time_zone
   *   Timezone value passed from block.
   *
   * @return array
   *   Returns array value.
   */
  public function getCurrentTime(string $time_zone) {

    $date = new DrupalDateTime();
    $time_stamp = $date->setTimezone(new \DateTimeZone($time_zone));
    $date_time = $time_stamp->format('jS M Y - g:i A');
    $array_datetime[] = [
      $time_zone,
      $date_time,
    ];

    return $array_datetime;
  }

}
