# slAssetPlugin #

This plugin uses YUICompressor to compress and store a collection of js and css files.

## Installation ##

### Clone this plugin ###

Use this to install as a plugin in a symfony app using git clone:

	$ cd plugins && git clone git@github.com:senico/slAssetPlugin.git

### enable the plugin in the config/ProjectConfiguration class ###

	public function setup()
	{
		$this->enablePlugins('slAssetPlugin');
	}

## Configuration ##

Update the settings.yml file in every application where you want to use this plugin so that plugin module can be used:

	enabled_modules: [slAsset]
	standard_helpers: [slAsset]

###Configuring the plugin###

In your app.tml file you specify the files that have to be compressed and included in your collection. The following configuration specifies that in the development environment the main javascript bundle must contain the frontend.js file and The main css bundle must contain two files.

	dev:
	  sl_asset:
	    javascript:
	      main:
	        files:
	          - frontend.js
	        version: 0
	    css:
	      main:
	        files:
	          - reset.css
	          - frontend.css
	        version: 0
	    options:
	      linebreak: false
              verbose: false
	      nomunge: false
	      semi: false
	      nooptimize: false
	      tofile: false
	      filename: ''


### Options ###

	Option: type
	YUI Param: --type
     	 
	The type of compressor (JavaScript or CSS) is chosen based on the
	extension of the input file name (.js or .css) This option is required
	if no input file has been specified. Otherwise, this option is only
	required if the input file extension is neither 'js' nor 'css'.

	Option: linebreak
	YUI Param: --line-break
      
	Some source control tools don't like files containing lines longer than,
	say 8000 characters. The linebreak option is used in that case to split
	long lines after a specific column. It can also be used to make the code
	more readable, easier to debug (especially with the MS Script Debugger)
	Specify 0 to get a line break after each semi-colon in JavaScript, and
	after each rule in CSS.

	Option: verbose
	YUI Param: -v, --verbose
      
	Display informational messages and warnings.

	Option: nomunge
	YUI Param: --nomunge
      
	Minify only. Do not obfuscate local symbols.

	Option: semi
	YUI Param: --preserve-semi
      
	Preserve unnecessary semicolons (such as right before a '}') This option
	is useful when compressed code has to be run through JSLint (which is the
	case of YUI for example)

	Option: nooptimize
	YUI Param: --disable-optimizations
      
	Disable all the built-in micro optimizations.

	Option: tofile and filename
	YUI Param: -o
      
	Specify an output file given it's file name.
