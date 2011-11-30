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
