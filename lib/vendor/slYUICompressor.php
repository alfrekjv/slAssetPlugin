<?php
/**
 * slYUICompressor PHP Class to interface with the YUI Compressor
 * 
 * @package     slAssetPlugin
 * @subpackage  config
 * @author      Senico Labs, LLC <alfredo.juarez@mxforce.com>
 * @version     1.0.0
 */
class slYUICompressor
{
    
  // absolute path to YUI jar file.
  private $jar_path;
  private $temp_files_dir;
  private $files    = array();
  private $string   = '';
  private $options  = array
                      (
                        'type'    => 'js',
                        'linebreak'  => false,
                        'verbose'    => false,
                        'nomunge'    => false,
                        'semi'       => false,
                        'nooptimize' => false,
                        'tofile'     => true,
                        'filename'   => ''
                      );
    
  // construct with a path to the YUI jar and a path to a place to put temporary files
  function __construct($jar_path, $temp_files_dir, $options = array())
  {
    $this->JAR_PATH       = $jar_path;
    $this->temp_files_dir = $temp_files_dir;

    foreach ($options as $option => $value)
    {
      $this->setOption($option, $value);
    }
  }
    
  // set one of the YUI compressor options
  function setOption($option, $value)
  {
    $this->options[$option] = $value;
  }

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
    
  // the meat and potatoes, executes the compression command in shell
  function compress()
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
      }
    }
    catch (Exception $e)
    {
      echo $e->getMessage();
    }
    
    // create single file from all input
    $input_hash = sha1($this->string);
    $file       = $this->temp_files_dir . '/' . $input_hash . '.txt';
    $fh         = fopen($file, 'w') or die("Can't create new file");
    $type       = (strtolower($this->options['type']) == "css" ? "css" : "js");
    $charset    = isset($this->options['charset']) ? $this->options['charset'] : 'UTF-8';
        
    fwrite($fh, $this->string);
    
    // start with basic command
    $cmd        = "java -Xmx32m -jar {$this->JAR_PATH} {$file} --charset {$charset}";
    
    /**
     * 
     * Option: type
     * YUI Param: --type
     * 
     * The type of compressor (JavaScript or CSS) is chosen based on the
     * extension of the input file name (.js or .css) This option is required
     * if no input file has been specified. Otherwise, this option is only
     * required if the input file extension is neither 'js' nor 'css'.
     */
    $cmd       .= " --type {$type}";
    
    /**
     * 
     * Option: linebreak
     * YUI Param: --line-break
     * 
     * Some source control tools don't like files containing lines longer than,
     * say 8000 characters. The linebreak option is used in that case to split
     * long lines after a specific column. It can also be used to make the code
     * more readable, easier to debug (especially with the MS Script Debugger)
     * Specify 0 to get a line break after each semi-colon in JavaScript, and
     * after each rule in CSS.
     */
    if ($this->options['linebreak'] && intval($this->options['linebreak']) > 0) 
    {
      $cmd .= " --line-break " . intval($this->options['linebreak']);
    }

    /**
     * 
     * Option: verbose
     * YUI Param: -v, --verbose
     * 
     * Display informational messages and warnings.
     */
    if ($this->options['verbose']) 
    {
      $cmd .= ' -v';
    }
    
    /**
     * 
     * Option: nomunge
     * YUI Param: --nomunge
     * 
     * Minify only. Do not obfuscate local symbols.
     */            
    if ($this->options['nomunge']) 
    {
      $cmd .= ' --nomunge';
    }

    /**
     * 
     * Option: semi
     * YUI Param: --preserve-semi
     * 
     * Preserve unnecessary semicolons (such as right before a '}') This option
     * is useful when compressed code has to be run through JSLint (which is the
     * case of YUI for example)
     */
    if ($this->options['semi']) 
    {
      $cmd .= ' --preserve-semi';
    }
    
    /**
     * 
     * Option: nooptimize
     * YUI Param: --disable-optimizations
     * 
     * Disable all the built-in micro optimizations.
     */
    if ($this->options['nooptimize']) 
    {
      $cmd .= ' --disable-optimizations';
    }
    
    /**
     * 
     * Option: tofile and filename
     * YUI Param: -o
     * 
     * Especify an output file given it's file name.
     */
    if ($this->options['tofile'])
    {
      $cmd .= " -o {$this->options['filename']}";
    }

    // execute the command
    exec($cmd . ' 2>&1', $raw_output);

    // add line breaks to show errors in an intelligible manner
    $flattened_output = implode("\n", $raw_output);

    // clean up (remove temp file)
    unlink($file);

    // return compressed output
    return $flattened_output;
  }
}