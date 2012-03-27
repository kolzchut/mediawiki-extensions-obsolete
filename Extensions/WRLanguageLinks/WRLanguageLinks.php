<?php
/**
 * WRLanguageLinks extension - Provides interlanguage links
 * @author Dror Snir
 * @copyright (C) 2006 Dror Snir (Kol-Zchut)
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is a MediaWiki extension, it is not a valid entry point' );
}

/* Setup */
$wgExtensionCredits['parserhook'][] = array(
    'path'           => __FILE__,
    'name'           => 'Kol-Zchut Language Links',
    'author'         => 'Dror Snir ([http://www.kolzchut.org.il Kol-Zchut])',
    'version'        => '0.1.0',
    'url'            => 'http://www.kolzchut.org.il/he/כל-זכות:Extensions/WRLanguageLinks',
    'descriptionmsg' => 'wrlanguagelinks-desc',
);

$wgWRLanguageLinksShowOnly = null;
$wgWRLanguageLinksShowTitles = false; //Show pagename instead of language name; namely <a title=langname>pagename</a>, instead of the opposite
$wgWRLanguageLinksListType = 'normal'; //Other options: flat (inliמe list) 

// Shortcut to this extension directory
$dir = dirname( __FILE__ ) . '/';

// Internationalization
$wgExtensionMessagesFiles['WRLanguageLinks'] = $dir . 'WRLanguageLinks.i18n.php';
$wgExtensionMessagesFiles['WRLanguageLinksMagic'] = $dir . 'WRLanguageLinks.i18n.magic.php';

// Auto load of classes
$wgAutoloadClasses['WRLanguageLinksHooks'] = $dir . 'WRLanguageLinks.hooks.php';
$wgAutoloadClasses['WRLanguageLinks'] = $dir . 'WRLanguageLinks.classes.php';

// Register hooks
$wgHooks['ParserFirstCallInit'][] = 'WRLanguageLinksHooks::register';
$wgHooks['ParserBeforeTidy'][] = 'WRLanguageLinksHooks::render';

$wgResourceModules['ext.WRLanguageLinks'] = array(
	'styles' => 'WRLanguageLinks.css',
	'localBasePath' => dirname( __FILE__ ),
	'remoteExtPath' => 'WRLanguageLinks',
);
