<?php
/**
 * slGZCompress PHP Class to compress css and js files with gzip.
 * 
 * @package     slAssetPlugin
 * @subpackage  slYUICompressor
 * @author      Senico Labs, LLC 
 * @author      Alfredo Juarez <alfredo.juarez@mxforce.com>
 * @version     1.0.0
 */
class slGZCompress
{
  private $files    = array();
  private $string   = '';
  private $filename = '';
  
  function __construct() { }

  // add a file or array of files (absolute path) to be compressed
  function addFile($file)
  {
    if (is_array($file)) 
    {
      foreach( $file as $single )
      {
        $this->addFile($single);
      }
    }
    
    array_push($this->files, $file);
  }
  
  function clear() 
  {
    $this->files  = array();
    $this->string = '';
  }
    
  // add a string or array of strings to be compressed
  function addString($string)
  {
    if (is_array($string))
    {
      foreach($string as $single)
      {
        $this->addString($single);
      }
    }
    
    $this->string .= ' ' . $string;
  }
    
  /**
   *
   * Uses gzcompress() to gzip the file bundles.
   * 
   * @param String $filename The Compressed filename.
   * @param integer $level The level of compression. Can be given as 0 for no compression up to 9 for maximum compression.
   * @return string The compress string.
   * @author Senico Labs, LLC
   * @author Alfredo Juarez
   */
  function compress($filename = '', $level = 9)
  {
    // read the input
    try
    {
      foreach ($this->files as $file) 
      {
        if (!$this->string .= file_get_contents($file))
        {
          throw new Exception("Cannot read from uploaded file");
        }
        
        $this->string .= ";\n";
      }
    }
    catch (Exception $e)
    {
      echo $e->getMessage();
    }
    
    $file = tempnam(sys_get_temp_dir(), 'sl');
    $fh   = fopen($file,'w');
    fwrite($fh, $this->string);
    exec("gzip -9 -c {$file} > {$filename}");
    fclose($fh);
    
    return gzcompress($this->string, $level);
  }
}