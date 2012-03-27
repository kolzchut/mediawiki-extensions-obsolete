<?php
if (!defined('MEDIAWIKI')) die();
/**
 * An extension that adds i18n messages for skin:Victoria, until that skin can be re-written better.
 *
 * @file
 * @ingroup Extensions
 *
 * @copyright Copyright © 2012, Dror Snir (Kol-Zchut Ltd.)
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

$wgExtensionCredits['other'][] = array(
	'path'           => __FILE__,
	'name'           => 'Victoria',
	'author'         => 'Dror Snir ([http://www.kolzchut.org.il Kol-Zchut])',
	'version'        => '0.0.1',
	'url'            => 'http://www.kolzchut.org.il/he/Project:Extensions/Victoria',
	'descriptionmsg' => 'victoria-desc',
);

$dir = dirname(__FILE__) . '/';
$wgExtensionMessagesFiles['Victoria'] = $dir .'Victoria.i18n.php';
