<?php

class senicoAssetTask extends sfBaseTask
{
  
  protected $_options  = array
            (
				'type'    => 'js',
                'linebreak'  => false,
                'verbose'    => false,
                'nomunge'    => false,
                'semi'       => false,
                'nooptimize' => false,
                'tofile'	 => false,
                'filename'   => ''
            );
  
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
The [senico:asset|INFO] task uses the YUICompressor to minify css/javascript files.
type options = all/css/javascript
Call it with:

  [php symfony senico:asset frontend --type=javascript|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $yuipath    = sfConfig::get('app_sl_asset_yuipath');
    $dir        = sfConfig::get('sf_web_dir');
    $cfOptions  = sfConfig::get('app_sl_asset_options') ? sfConfig::get('app_sl_asset_options') : $this->_options;
    
    if ($options['type'] == 'all')
    {
      $files = array();
      $js = sfConfig::get('app_sl_asset_javascript');
      $css = sfConfig::get('app_sl_asset_css');
      
      array_push($files, $js);
      array_push($files, $css);
    }
    
    if ($options['type'] == 'css')
    {
      $files = sfConfig::get('app_sl_asset_css');
      array_push($cfOptions, array('type' => 'css'));
    }
    
    if ($options['type'] == 'js')
    {
      $files = sfConfig::get('app_sl_asset_javascript');
      array_push($cfOptions, array('type' => 'js'));
    }
    
    var_dump($files);
    
    $yui        = new slYUICompressor($yuipath,$dir . '/tmp',$cfOptions);
    $yui->addFile( $dir . '/js/stats.js' );
    $output     = $yui->compress();
    
    echo $output;
  }
}
