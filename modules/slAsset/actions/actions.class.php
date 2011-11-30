<?php

class ggAsseticActions extends sfActions
{
  public function executeJavascript($request) 
  {
    try
    {
      $name   = $this->getRequest()->getParameter('name');
      $config = sfConfig::get('app_sl_asset_javascript');
    }
    catch(Exception $e)
    {
      throw new sfError404Exception($e->getMessage());
    }
  }
  
  public function executeStylesheet($request) 
  {
    try
    {
      $name   = $this->getRequest()->getParameter('name');
      $config = sfConfig::get('app_sl_asset_stylesheet');
    }
    catch(Exception $e)
    {
      throw new sfError404Exception($e->getMessage());
    }
  } 
}