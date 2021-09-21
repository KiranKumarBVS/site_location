<?php

namespace Drupal\site_location\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\site_location\Service\CurrentTimeService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an Site location block.
 *
 * @Block(
 *   id = "site_location_block",
 *   admin_label = @Translation("Site location block"),
 *   category = @Translation("Site location")
 * )
 */
class SiteLocationBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * CurrentTimeService instance.
   *
   * @var \Drupal\site_location\Service\CurrentTimeService
   */
  protected $currentTimeService;

  /**
   * Constructs an siteblock object.
   *
   * @param array $configuration
   *   Block configuration.
   * @param string $plugin_id
   *   Plugin ID.
   * @param mixed $plugin_definition
   *   Plugin Definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\site_location\Service\CurrentTimeService $currentTime_service
   *   Current-time service.
   */
  public function __construct(array $configuration,
  $plugin_id,
  $plugin_definition,
  ConfigFactoryInterface $config_factory,
  CurrentTimeService $currentTime_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->currentTimeService = $currentTime_service;
  }

  /**
   * Adding dependency injection.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Dependency injection variable.
   * @param array $configuration
   *   Block configuration.
   * @param string $plugin_id
   *   Plugin ID.
   * @param mixed $plugin_definition
   *   Plugin Definition.
   *
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('site_location.current_time')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Used DI to render the timezone value from the CONFIG FORM.
    $time_zone = $this->configFactory->get('site_location.settings')->get('timezone');
    // Used DI to get the formatted date by calling the service.
    $data = $this->currentTimeService->getCurrentTime($time_zone);

    $build = [
      '#theme' => 'site_location_rendering',
      '#date_value' => $data[0],
      // '#cache' => [
      //   'max-age' => 0,
      // ],
    ];

    return $build;
  }

  /**
   * Disable caching for this block.
   *
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
