<?php

/**
 * PrevNext
 *
 * Copyright 2014-2016 by goldsky <goldsky@virtudraft.com>
 *
 * This file is part of PrevNext, a navigator snippet for MODX Revolution to 
 * create Previous and Next links in a page
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

$settings['prevnext.core_path'] = $modx->newObject('modSystemSetting');
$settings['prevnext.core_path']->fromArray(array(
    'key' => 'prevnext.core_path',
    'value' => '{core_path}components/prevnext/',
    'xtype' => 'textfield',
    'namespace' => 'prevnext',
    'area' => 'URL',
        ), '', true, true);


return $settings;