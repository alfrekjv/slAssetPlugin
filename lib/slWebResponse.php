<?php

/**
 * 
 * slWebResponse: Extends the functionality of sfWebResponse.
 * 
 * @package     slAssetPlugin
 * @subpackage  slWebResponse
 * @author      Senico Labs, LLC 
 * @author      Alfredo Juarez <alfredo.juarez@mxforce.com>
 * @version     1.0.0
 */

class slWebResponse extends sfWebResponse
{
  public function getJavascripts($position = self::ALL)
  {
    $scripts = array();
    
    foreach(parent::getJavascripts($position) as $js => $opt)
    {
      $scripts[$js] = $opt;
    }
    
    return $scripts;
  }
  
  public function getStylesheets($position = self::ALL)
  {
    $scripts = array();
    
    foreach (parent::getStylesheets($position) as $css => $opt)
    {
      $scripts[$css] = $opt;
    }
    
    return $scripts;
  }
}