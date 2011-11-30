<?php

class senicoAssetTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name.'),
    ));

    $this->addOptions(array(
      new sfCommandOption('type', null, sfCommandOption::PARAMETER_OPTIONAL, 'The file types to optimize','all'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev')
    ));

    $this->namespace            = 'senico';
    $this->name                 = 'asset';
    $this->briefDescription     = 'Compress Javascript and CSS Files';
    
    $this->detailedDescription  = <<<EOF
The [senico:asset|INFO] task uses the YUICompressor to minify css/javascript files.
type options = all/css/javascript
Call it with:

  [php symfony senico:asset --type=javascript|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    if ($options['type'] == 'all')
    {
      
    }
    
    if ($options['type'] == 'css')
    {
      
    }
    
    if ($options['type'] == 'js')
    {
      
    }
  }
  
  private function compressJS()
  {
    
  }
  
  private function compressCSS()
  {
    
  }
}
