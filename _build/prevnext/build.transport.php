<?php

/**
 * PrevNext
 *
 * Copyright 2014 by goldsky <goldsky@virtudraft.com>
 *
 * This file is part of PrevNext, a navigator snippet for MODX Revolution to get
 * sibling pages
 *
 * PrevNext is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation version 3.
 *
 * PrevNext is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * PrevNext; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * PrevNext build script
 *
 * @package prevnext
 * @subpackage build
 */

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* define version */
define('PKG_NAME', 'PrevNext');
define('PKG_NAME_LOWER', 'prevnext');

/* override with your own defines here (see build.config.sample.php) */
require_once dirname(__FILE__) . '/build.config.php';
require_once realpath(MODX_CORE_PATH) . '/model/modx/modx.class.php';

/* define sources */
$root = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR;
$sources = array(
	'root' => $root,
	'build' => BUILD_PATH,
	'resolvers' => BUILD_PATH . 'resolvers' . DIRECTORY_SEPARATOR,
    'validators' => realpath(BUILD_PATH . 'validators/') . DIRECTORY_SEPARATOR,
	'data' => BUILD_PATH . 'data' . DIRECTORY_SEPARATOR,
	'properties' => realpath(BUILD_PATH . 'data/properties/') . DIRECTORY_SEPARATOR,
	'source_core' => realpath(MODX_CORE_PATH . 'components') . DIRECTORY_SEPARATOR . PKG_NAME_LOWER,
	'source_assets' => realpath(MODX_ASSETS_PATH . 'components') . DIRECTORY_SEPARATOR . PKG_NAME_LOWER,
	'docs' => realpath(MODX_CORE_PATH . 'components/' . PKG_NAME_LOWER . '/docs/') . DIRECTORY_SEPARATOR,
	'lexicon' => realpath(MODX_CORE_PATH . 'components/' . PKG_NAME_LOWER . '/lexicon/') . DIRECTORY_SEPARATOR,
);
unset($root);

$modx = new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');
echo '<pre>';

$prevnext = $modx->getService('prevnext', 'PrevNext', MODX_CORE_PATH . 'components/prevnext/model/prevnext/');

if (!($prevnext instanceof PrevNext))
    return '';
define('PKG_VERSION', PrevNext::VERSION);
define('PKG_RELEASE', PrevNext::RELEASE);

$modx->loadClass('transport.modPackageBuilder', '', false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER, false, true, '{core_path}components/' . PKG_NAME_LOWER . '/');

/**
 * SYSTEM SETTINGS
 */
$settings = include $sources['data'] . 'transport.settings.php';
if (!is_array($settings)) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not package in settings.');
} else {
    $modx->log(modX::LOG_LEVEL_INFO, 'Packaging in System Settings...');
    $settingAttributes = array(
        xPDOTransport::UNIQUE_KEY => 'key',
        xPDOTransport::PRESERVE_KEYS => true,
        xPDOTransport::UPDATE_OBJECT => false,
    );
    foreach ($settings as $setting) {
        $settingVehicle = $builder->createVehicle($setting, $settingAttributes);
        $builder->putVehicle($settingVehicle);
    }
    $modx->log(modX::LOG_LEVEL_INFO, 'Packaged in ' . count($settings) . ' System Settings done.');
    unset($settingVehicle, $settings, $setting, $settingAttributes);
}

/**
 * CATEGORY
 */
$category = $modx->newObject('modCategory');
$category->set('id', 1);
$category->set('category', 'PrevNext');

/**
 * SNIPPETS
 */
$modx->log(modX::LOG_LEVEL_INFO, 'Adding in snippets.');
$snippets = include $sources['data'] . 'transport.snippets.php';
if (is_array($snippets)) {
	$category->addMany($snippets);
    $modx->log(modX::LOG_LEVEL_INFO, 'Adding in ' . count($snippets) . ' snippets done.');
} else {
	$modx->log(modX::LOG_LEVEL_FATAL, 'Adding snippets failed.');
}


/**
 * Apply category to the elements
 */
$elementsAttribute = array(
	xPDOTransport::UNIQUE_KEY => 'category',
	xPDOTransport::PRESERVE_KEYS => false,
	xPDOTransport::UPDATE_OBJECT => true,
	xPDOTransport::RELATED_OBJECTS => true,
	xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
		'Snippets' => array(
			xPDOTransport::PRESERVE_KEYS => false,
			xPDOTransport::UPDATE_OBJECT => true,
			xPDOTransport::UNIQUE_KEY => 'name',
		),
	)
);
$elementsVehicle = $builder->createVehicle($category, $elementsAttribute);

/**
 * FILE RESOLVERS
 */
$elementsVehicle->resolve('file', array(
	'source' => $sources['source_core'],
	'target' => "return MODX_CORE_PATH . 'components/';",
));
//$elementsVehicle->resolve('file', array(
//	'source' => $sources['source_assets'],
//	'target' => "return MODX_ASSETS_PATH . 'components/';",
//));
$modx->log(modX::LOG_LEVEL_INFO, 'Adding in files done.');

$builder->putVehicle($elementsVehicle);
unset($elementsVehicle);
$modx->log(modX::LOG_LEVEL_INFO, 'Packaged in Elements done.');
flush();


/**
 * license file, readme and setup options
 */
$builder->setPackageAttributes(array(
	'license' => file_get_contents($sources['docs'] . 'license.txt'),
	'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
	'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),
));

$builder->pack();

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tend = $mtime;
$totalTime = ($tend - $tstart);
$totalTime = sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO, "\n<br />" . PKG_NAME . " package built.<br />\nExecution time: {$totalTime}\n");

exit();