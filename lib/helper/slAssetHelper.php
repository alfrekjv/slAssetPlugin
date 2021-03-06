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

function sl_include_stylesheets() 
{
  
  $config   = sfConfig::get('app_sl_asset_css');
  $subdir   = sfConfig::get('app_sl_asset_dir');
  $env      = sfConfig::get('sf_environment');
  $gzip     = sfConfig::get('app_sl_asset_gzip');
  $context  = sfContext::getInstance()->getModuleName() . "/" .sfContext::getInstance()->getActionName();
  $cdn_host = sfConfig::get('app_cdn_host');
  $secured  = sfContext::getInstance()->getRequest()->isSecure();
  $protocol = $secured ? 'https://' : 'http://';
  $cdn      = isset($cdn_host) ? $protocol . $cdn_host . "/css/" : '';
  $ext      = !$gzip && stripos(sfContext::getInstance()->getRequest()->getHeader('Accept-Encoding'),'gzip') === false ? '.min.css' : '.css.cgz';
  
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
          $filename = $script['name'] . '.' . $script['version'] . $ext;
        }
        else
        {
          $filename = $script['name'] . $ext;
        }
        
        $html .= stylesheet_tag( $cdn . $subdir . $filename, array());
        
        foreach( $script['files'] as $file ) 
        {
          if (preg_match('%^http?://%', $file))
          {
            $html .= stylesheet_tag($file, array());
          }
        }
      }
    }

    echo $html;
  }
  else
  {
    $html = '';
    foreach($config as $position => $script)
    {
      if ($position == $context || $position == 'default') 
      {
        foreach( $script['files'] as $file ) 
        {
          $html .= stylesheet_tag($file, array());
        }
      }
    }
    
    echo $html;
  }
}

function sl_include_javascripts() 
{
  $config   = sfConfig::get('app_sl_asset_javascript');
  $subdir   = sfConfig::get('app_sl_asset_dir');
  $gzip     = sfConfig::get('app_sl_asset_gzip');
  $env      = sfConfig::get('sf_environment');
  $context  = sfContext::getInstance()->getModuleName() . "/" .sfContext::getInstance()->getActionName();
  $cdn_host = sfConfig::get('app_cdn_host');
  $secured  = sfContext::getInstance()->getRequest()->isSecure();
  $protocol = $secured ? 'https://' : 'http://';
  $cdn      = isset($cdn_host) ? $protocol . $cdn_host . "/js/" : '';
  $ext      = !$gzip && stripos(sfContext::getInstance()->getRequest()->getHeader('Accept-Encoding'),'gzip') === false ? '.min.css' : '.js.jgz';
  
  if ($env == 'prod')
  {
    sfConfig::set('symfony.asset.javascripts_included', true);

    $html = '';
    foreach($config as $position => $script)
    {
      if ($position == $context || $position == 'default') 
      {
        if (isset($script['version']) && $script['version'] != 0)
        {
          $filename = $script['name'] . '.' . $script['version'] . $ext;
        }
        else
        {
          $filename = $script['name'] . $ext;
        }

        $html .= javascript_include_tag( $cdn . $subdir . $filename, array());
        
        foreach( $script['files'] as $file ) 
        {
          if (preg_match('%^http?://%', $file))
          {
            $html .= javascript_include_tag($file, array());
          }
        }
      }
    }

    echo $html;
  }
  else
  {
    $html = '';
    foreach($config as $position => $script)
    {
      if ($position == $context || $position == 'default') 
      {
        foreach( $script['files'] as $file ) 
        {
          $html .= javascript_include_tag($file, array());
        }
      }
    }
    
    echo $html;
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