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
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_OPTIONAL, 'The environment', 'dev'),
      new sfCommandOption('level', null, sfCommandOption::PARAMETER_OPTIONAL, 'The compression level for gzip if enabled', 9)
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
      $this->processJs($options);
      print "Compressing css files...\n";
      $this->processCss($options);
    }
    
    if ($options['type'] == 'css')
    {
      print "Compressing css files...\n";
      $this->processCss($options);
    }
    
    if ($options['type'] == 'js')
    {
      print "Compressing javascript files...\n";
      $this->processJs($options);
    }
    
    print "Files compressed succesully.\n";
  }
  
  private function processJs($args) 
  {
    $options          = $this->_options;
    $options['type']  = 'js';
    $js               = sfConfig::get('app_sl_asset_javascript');
    $gzip             = sfConfig::get('app_sl_asset_gzip');
    $output           = null;
    $yui              = new slYUICompressor($this->yuipath,$options);
    $dir              = $this->dir . "/js/";
    $subdir           = $this->subdir;
    $slgzcompress     = new slGZCompress;
    
    foreach($js as $script)
    {
      foreach($script['files'] as $file)
      {
        if (!preg_match('%^http?://%', $file))
        {
          $yui->addFile($dir . $file);
        }
      }
      
      if (isset($script['version']) && $script['version'] != 0)
      {
        $filename   = $script['name'].'.'.$script['version'].'.min.js';
        $gfilename  = $script['name'].'.'.$script['version'].'.js.jgz';
      }
      else
      {
        $filename = $script['name'].'.min.js';
        $gfilename  = $script['name'].'.js.jgz';
      }
      
      // yuicompressor stuff
      $yui->setOption('filename', $dir . $subdir . $filename);
      $output .= $yui->compress();
      $yui->clear();
      
      // gzip stuff
      if ($gzip)
      {
        $slgzcompress->addFile($dir . $subdir . $filename);
        $slgzcompress->compress($dir . $subdir . $gfilename,$args['level']);
        $slgzcompress->clear();
      }
      
      $output = '';
    }
    
    return $output;
  }
  
  private function processCss($args)
  {
    $options          = $this->_options;
    $options['type']  = 'css';
    $css              = sfConfig::get('app_sl_asset_css');
    $gzip             = sfConfig::get('app_sl_asset_gzip');
    $output           = null;
    $yui              = new slYUICompressor($this->yuipath,$options);
    $dir              = $this->dir . "/css/";
    $subdir           = $this->subdir;
    $slgzcompress     = new slGZCompress;
    
    foreach($css as $script)
    {
      foreach($script['files'] as $file)
      {
        if (!preg_match('%^http?://%', $file))
        {
          $yui->addFile($dir . $file);
        }
      }
      
      if (isset($script['version']) && $script['version'] != 0)
      {
        $filename   = $script['name'].'.'.$script['version'].'.min.css';
        $gfilename  = $script['name'].'.'.$script['version'].'.css.cgz';
      }
      else
      {
        $filename   = $script['name'].'.min.css';
        $gfilename  = $script['name'].'.css.cgz';
      }
      
      $yui->setOption('filename', $dir . $subdir . $filename);
      $output .= $yui->compress();
      $yui->clear();
      
      // gzip stuff
      if ($gzip)
      {
        $slgzcompress->addFile($dir . $subdir . $filename);
        $slgzcompress->compress($dir . $subdir . $gfilename,$args['level']);
        $slgzcompress->clear();
      }
      
      $output = '';
    }
    
    return $output;
  }
}