MediaWiki-Composerify
=====================

Making more MediaWiki extensions installable via Composer

Purpose
-------

To make MediaWiki extensions installable via Composer, the following steps must be followed:

1. Modify the main extension file (e.g. ExtensionName.php) to change all $wgVariableName global variables to $GLOBALS['wgVariableName']
2. Create a composer.json file with information pull from main extension file
3. Submit patch to main repository (Gerrit or GitHub as required)
4. Create corresponding Packagist.org package (e.g. mediawiki/extension-name)

Modify main extension file
--------------------------

To-do: Construct proper regex to convert variable naming

Create a composer.json file
---------------------------

Composer.json file should look like the following, with proper values inserted based on main extension file.

```json
{
	"name": "mediawiki/<extension-name-with-hyphens>",
	"type": "mediawiki-extension",
	"description": "<try to pull from ExtensionName.php, else use dummy text>",
	"keywords": [
		"MediaWiki"
	],
	"homepage": "<attempt to pull from ExtensionName.php, else blank>",
	"license": "<leave as ‘unknown’?>",
	
	<for “authors”: $wgExtensionCredits variable, loop over the relevant “author” field>
  "authors": [
		{
			"name": "Yaron Koren",
			"role": "Author"
		}
	],

	"support": {<leave blank if possible>},
	"require": {
		"composer/installers": ">=1.0.1"
	},
	"autoload": {
		"files": ["<ExtensionName>.php"]
	}
}
```

Submit patch to main repository
-------------------------------

Should we automate this somehow? Is that desireable?

Create Packagist.org package
----------------------------

Should we automate this somehow? Is that desireable?
