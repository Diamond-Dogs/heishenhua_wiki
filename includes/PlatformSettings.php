<?php
/**
 *          _,met$$$$$gg.         Debian defaults for MediaWiki
 *       ,g$$$$$$$$$$$$$$$P.
 *     ,g$$P""       """Y$$.".    NEVER EDIT THIS FILE
 *    ,$$P'              `$$$.
 *   ',$$P       ,ggs.     `$$b:  To customize your installation,
 *   `d$$'     ,$P"'   .    $$$   edit "LocalSettings.php".
 *    $$P      d$'     ,    $$P
 *    $$:      $$.   -    ,d$$'   See includes/DefaultSettings.php
 *    $$\;      Y$b._   _,d$P'    for more instructions and tips.
 *    Y$$.    `.`"Y$$$$P"'
 *    `$$b      "-.__
 *     `Y$$
 *      `Y$$.
 *        `$$b.
 *          `Y$$b.
 *             `"Y$b._
 *                 `""""
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

# Add a "powered by Debian" footer icon
$wgExtensionFunctions[] = function () {
	global $wgFooterIcons, $wgResourceBasePath;
	$wgFooterIcons['poweredby']['debian'] = [
		'src' => "$wgResourceBasePath/resources/assets/debian/poweredby_debian_1x.png",
		'url' => 'https://www.debian.org/',
		'alt' => 'Powered by Debian',
		'srcset' =>
			"$wgResourceBasePath/resources/assets/debian/poweredby_debian_1_5x.png 1.5x, " .
			"$wgResourceBasePath/resources/assets/debian/poweredby_debian_2x.png 2x",
	];
};

$wgCacheDirectory = '/var/cache/mediawiki';

// Log exceptions, errors and fatals
$wgDBerrorLog = '/var/log/mediawiki/dberror.log';
$wgDebugLogGroups['exception'] = '/var/log/mediawiki/exception.log';
$wgDebugLogGroups['error'] = '/var/log/mediawiki/error.log';
$wgDebugLogGroups['fatal'] = '/var/log/mediawiki/fatal.log';
