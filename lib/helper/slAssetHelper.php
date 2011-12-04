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

function sl_include_stylesheets() {
  
  $config   = sfConfig::get('app_sl_asset_css');
  $subdir   = sfConfig::get('app_sl_asset_dir');
  $env      = sfConfig::get('sf_environment');
  $context  = sfContext::getInstance()->getModuleName() . "/" .sfContext::getInstance()->getActionName();
  
  echo $context;
  
  if ($env == 'prod')
  {
    sfConfig::set('symfony.asset.stylesheets_included', true);

    $html = '';
    foreach($config as $position => $script)
    {
      if ($position == $context || $position == 'default') 
      {
        if (isset($script['version']) && $script['version'] != 0)
        {
          $filename = $script['name'] . '.' . $script['version'] . '.min.css';
        }
        else
        {
          $filename = $script['name'] . '.min.css';
        }
        
        $html .= stylesheet_tag( $subdir . $filename, array());
      }
    }

    echo $html;
  }
  else
  {
    include_stylesheets();
  }
}

function sl_include_javascripts() {
  
  $config   = sfConfig::get('app_sl_asset_javascript');
  $subdir   = sfConfig::get('app_sl_asset_dir');
  $env      = sfConfig::get('sf_environment');
  $context  = sfContext::getInstance()->getModuleName() . "/" .sfContext::getInstance()->getActionName();
  
  if ($env == 'prod')
  {
    sfConfig::set('symfony.asset.javascripts_included', true);

    $html = '';
    foreach($config as $position => $script)
    {
      echo $position;
      if ($position == $context || $position == 'default') 
      {
        if (isset($script['version']) && $script['version'] != 0)
        {
          $filename = $script['name'] . '.' . $script['version'] . '.min.js';
        }
        else
        {
          $filename = $script['name'] . '.min.js';
        }

        $html .= javascript_include_tag($subdir . $filename, array());
      }
    }

    echo $html;
  }
  else
  {
    include_javascripts();
  }
}

/**
* Prints <link> tag for a javascript file.
*/
function sl_use_javascript($js)
{
  $config = sfConfig::get('app_sl_asset_javascript');
  $env = sfConfig::get('sf_environment');
  
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
  $env = sfConfig::get('sf_environment');
  
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