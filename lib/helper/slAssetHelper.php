<?php
/**
* ggAsseticHelper handles the Assetic js and css.
*
* @package ggAsseticPlugin
* @subpackage helper
* @author Alfredo Juarez for Senico Labs, LLC <alfredo.juarez@mxforce.com>
* @version 1.0.0
*/

/**
* Prints <link> tag for a javascript file.
*/
function sl_use_javascript($js)
{
  $config = sfConfig::get('app_sl_asset_javascript');
  
  if (isset($config[$js]['version']) && $config[$js]['version'] > 0) {
   use_javascript($js.'.'.$config[$js]['version'].'.min.js');
  } else {
    use_javascript(url_for('@asset_js?name='.$js));
  }
}

/**
* Prints <link> tag for a css file.
*/
function sl_use_stylesheet($css)
{
  $config = sfConfig::get('app_sl_asset_css');
  
  if (isset($config[$css]['version']) && $config[$css]['version'] > 0) {
   use_stylesheet($css.'.'.$config[$css]['version'].'.min.css');
  } else {
    use_stylesheet(url_for('@asset_css?name='.$css), '', array('media' => 'all'));
  }
}