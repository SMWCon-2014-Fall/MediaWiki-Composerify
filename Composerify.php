<?php

/**
 * Extract extension information and write to new composer file
 *
 * Author: Ali King
**/

define( 'MEDIAWIKI', '2.0' );

function createJson( $extName ) {
	$extPath = __DIR__ . '/extensions/' . $extName . '/';

	$sourceFilePath = $extPath . $extName . '.php';
	$destFilePath = $extPath . 'composer.json';
	$creditFilePath = $extPath . 'credits.php'; // this will be used as a temp file for accessing extension credits

	$file = fopen( $sourceFilePath, 'r' ) or die('Unable to open file!');
	$fileContents = fread( $file, filesize($sourceFilePath) );

	// grab extension credits array from main extension file
	$creditContent = preg_replace( '/[\s\S]*(\$wgExtensionCredits)\[.*\]\[\](\s+=\s+array\([\s\S]*\);)[\s\S]*/', '<?php' . "\n" . '$1$2', $fileContents );
	// write to a file
	$creditFile = fopen( $creditFilePath, 'w' ) or die ('Unable to open file!');
	fwrite( $creditFile, $creditContent );

	// include file in this one to access variables
	include_once( $creditFilePath );
	// parse the array to extract author info
	$authorsList = $wgExtensionCredits['author'];

	$authors = explode( ',', $authorsList );

	foreach ($authors as $author) {
		$containsURL = preg_match('/\[http.*\]/', $author, $matches);
		if ( !$containsURL ) {
			$profile['name'] = $author;
		} else {
			preg_match('/\[(http\S*) ?(.*)\]/', $author, $uMatches);
			$profile['name'] = $uMatches[2];
			$profile['homepage'] = $uMatches[1];
			$profile['role'] = 'Extension developer';
		}
		$authorCredits[] = $profile;
	}

	// read composer template to substitute values into
	$composerTemplateFile= fopen( 'composerTemplate.json', 'r' ) or die ('Unable to open file!');
	$composerTemplateContents = fread( $composerTemplateFile, filesize('composerTemplate.json') ); 
// TODO substitute array values into template
// TODO delete credits temp file after use
}

function main() {

createJson('WikiObjectModel'); // substitute in name of any installed extension to test
}
