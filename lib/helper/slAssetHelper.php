<?php
/**
* slAssetHelper handles the js and css files.
*
* @package slAssetPlugin
* @subpackage helper
* @author Senico Labs, LLC
* @author Alfredo Juarez <alfredo.juarez@mxforce.com>
* @version 1.0.0
*/

/**
* Prints <link> tag for a javascript file.
*/
function sl_use_javascript($js)
{
  $config = sfConfig::get('app_sl_asset_javascript');
  $env    = sfConfig::get('sf_environment');
  
  if ($env == 'prod')
  {
    if (isset($config[$js]['version']) && $config[$js]['version'] > 0) 
    {
      use_javascript($js.'.'.$config[$js]['version'].'.min.js');
    }
    else
    {
      use_javascript($js.'.min.js');
    }
  }
  else
  {
    use_javascript($js.'.js');
  }
}

/**
* Prints <link> tag for a css file.
*/
function sl_use_stylesheet($css)
{
  $config = sfConfig::get('app_sl_asset_css');
  $env    = sfConfig::get('sf_environment');
  
  if ($env == 'prod')
  {
    if (isset($config[$css]['version']) && $config[$css]['version'] > 0) 
    {
      use_stylesheet($css.'.'.$config[$css]['version'].'.min.css');
    }
    else
    {
      use_stylesheet($css.'.min.css');
    }
  }
  else
  {
    use_stylesheet($css.'.css');
  }
}