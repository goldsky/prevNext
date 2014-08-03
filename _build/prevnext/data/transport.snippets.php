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

/**
 * @param   string  $filename   filename
 * @return  string  file content
 */
function getSnippetContent($filename) {
    $o = file_get_contents($filename);
    $o = str_replace('<?php', '', $o);
    $o = str_replace('?>', '', $o);
    $o = trim($o);
    return $o;
}

$snippets = array();

$snippets[0] = $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'property_preprocess' => 1,
    'name' => 'prevNext',
    'description' => '',
    'snippet' => getSnippetContent($sources['source_core'] . '/elements/snippets/prevnext.snippet.php'),
        ), '', true, true);
$properties = include $sources['properties'] . 'prevnext.snippet.properties.php';
$snippets[0]->setProperties($properties);
unset($properties);

return $snippets;