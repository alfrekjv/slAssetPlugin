<?php

/**
* senicoAssetTask compress files using the YUI Compressor.
*
* @package ggAsseticPlugin
* @subpackage task
* @author Senico Labs, LLC
* @author Alfredo Juarez <alfredo.juarez@mxforce.com>
* @version 1.0.0
*/

class senicoAssetTask extends sfBaseTask
{
  
  protected $_options = array
            (
                'type'       => null,
                'linebreak'  => false,
                'verbose'    => false,
                'nomunge'    => false,
                'semi'       => false,
                'nooptimize' => false,
                'tofile'     => true,
                'filename'   => null
            );
  
  protected $yuipath  = '';
  protected $dir      = '';
  protected $subdir   = '';
  
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name.'),
    ));

    $this->addOptions(array(
      new sfCommandOption('type', null, sfCommandOption::PARAMETER_OPTIONAL, 'The file types to optimize','all'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_OPTIONAL, 'The environment', 'dev')
    ));

    $this->namespace            = 'senico';
    $this->name                 = 'compress';
    $this->briefDescription     = 'Compress Javascript and CSS Files';
    $this->detailedDescription  = <<<EOF
The [senico:compress|INFO] task uses the YUICompressor to minify css/javascript files.
type options = all/css/javascript
Call it with:

  [php symfony senico:asset frontend --type=javascript|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->yuipath                = sfConfig::get('app_sl_asset_yuipath');
    $this->dir                    = sfConfig::get('sf_web_dir');
    $this->subdir                 = sfConfig::get('app_sl_asset_dir');
    $cfOptions                    = sfConfig::get('app_sl_asset_options');
    
    $this->_options['linebreak']  = isset($cfOptions['linebreak'])  ? $cfOptions['linebreak']   : $this->_options['linebreak'];
    $this->_options['verbose']    = isset($cfOptions['verbose'])    ? $cfOptions['verbose']     : $this->_options['verbose'];
    $this->_options['nomunge']    = isset($cfOptions['nomunge'])    ? $cfOptions['nomunge']     : $this->_options['nomunge'];
    $this->_options['semi']       = isset($cfOptions['semi'])       ? $cfOptions['semi']        : $this->_options['semi'];
    $this->_options['nooptimize'] = isset($cfOptions['nooptimize']) ? $cfOptions['nooptimize']  : $this->_options['nooptimize'];
    
    print "Starting process...\n";
    
    if ($options['type'] == 'all')
    {
      print "Compressing javascript files...\n";
      $this->processJs();
      print "Compressing css files...\n";
      $this->processCss();
    }
    
    if ($options['type'] == 'css')
    {
      print "Compressing css files...\n";
      $this->processCss();
    }
    
    if ($options['type'] == 'js')
    {
      print "Compressing javascript files...\n";
      $this->processJs();
    }
    
    print "Files compressed succesully.\n";
  }
  
  private function processJs() 
  {
    $options          = $this->_options;
    $options['type']  = 'js';
    $js               = sfConfig::get('app_sl_asset_javascript');
    $output           = null;
    $yui              = new slYUICompressor($this->yuipath,$this->dir . '/tmp',$options);
    $dir              = $this->dir . "/js/";
    $subdir           = $this->subdir;   
    
    foreach($js as $script)
    {
      foreach($script['files'] as $file)
      {
        if (preg_match('%^http?://%', $file))
        {
          $yui->addFile($file); 
        }
        else
        {
          $yui->addFile($dir . $file);
        }
      }
      
      if (isset($script['version']) && $script['version'] != 0)
      {
        $filename = $script['name'].'.'.$script['version'].'.min.js';
      }
      else
      {
        $filename = $script['name'].'.min.js';
      }
      
      $yui->setOption('filename', $dir . $subdir . $filename);
      $output .= $yui->compress();
      $yui->clear();
    }
    
    return $output;
  }
  
  private function processCss() 
  {
    $options          = $this->_options;
    $options['type']  = 'css';
    $css              = sfConfig::get('app_sl_asset_css');
    $output           = null;
    $yui              = new slYUICompressor($this->yuipath,$this->dir . '/tmp',$options);
    $dir              = $this->dir . "/css/";
    $subdir           = $this->subdir;
    
    foreach($css as $script)
    {
      foreach($script['files'] as $file)
      {
        if (preg_match('%^http?://%', $file))
        {
         $yui->addFile($file); 
        }
        else
        {
          $yui->addFile($dir . $file);
        }
      }
      
      if (isset($script['version']) && $script['version'] != 0)
      {
        $filename = $script['name'].'.'.$script['version'].'.min.css';
      }
      else
      {
        $filename = $script['name'].'.min.css';
      }
      
      $yui->setOption('filename', $dir . $subdir . $filename);
      $output .= $yui->compress();
      $yui->clear();
    }
    
    return $output;
  }
}