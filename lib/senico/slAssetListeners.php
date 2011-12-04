<?php

/**
 * 
 * slAssetListeners is this plugin's listeners manager for symfony.
 * 
 * @package     slAssetListeners
 * @subpackage  lib
 * @author      Senico Labs, LLC 
 * @author      Alfredo Juarez <alfredo.juarez@mxforce.com>
 * @version     1.0.0
 */

class slAssetListeners
{
  /**
  * Listens to the routing.load_configuration event. redirects asset link to plugin module
  *
  * @param sfEvent $event an sfEvent instance
  */
  static public function loadAsset(sfEvent $event)
  {
    $routing = $event->getSubject();
    $routing->prependRoute('slasset_js', new sfRoute('/js/:name.js', array(
          'module' => 'slAsset',
          'action' => 'javascript')
        ));
    $routing->prependRoute('slasset_css', new sfRoute('/css/:name.css', array(
          'module' => 'slAsset',
          'action' => 'stylesheet')
        ));
  }
}