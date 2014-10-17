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

Goal: Construct proper regex to convert variable naming


To find PHP variables, use the following regex:

```
\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)
```



However, we do NOT want to match any variables already defined using $GLOBALS, so we must exclude this particular variable. I think we also need to exclude any $_GET, $_POST, $_SERVER, or other super-globals, but for the initial version of this script I will not be handling them since these should not be present in MediaWiki extensions.

To exclude $GLOBALS the following regex can be used:

```
\$(?!GLOBALS)([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)
```

Each match can then be replaced with:

```
$GLOBALS['$1']
```

This works well for many variables, but fails for variables not intended to be global. Most notably, if functions are defined within the ExtensionName.php file then any variables within those functions should not be converted.

How to handle this scenario? TBD...


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
