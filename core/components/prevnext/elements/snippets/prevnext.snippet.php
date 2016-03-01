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
 * @subpackage snippet
 */
$sort = $modx->getOption('sort', $scriptProperties, 'id,publishedon');
$scriptProperties['sort'] = array_map('trim', @explode(',', $sort));
$select = $modx->getOption('select', $scriptProperties, 'pagetitle');
$scriptProperties['select'] = array_map('trim', @explode(',', $select));
$scriptProperties['includeHidden'] = intval($modx->getOption('includeHidden', $scriptProperties, 0));
$scriptProperties['prevPrefix'] = $modx->getOption('prevPrefix', $scriptProperties, 'prev.');
$scriptProperties['nextPrefix'] = $modx->getOption('nextPrefix', $scriptProperties, 'next.');
$scriptProperties['tvPrefix'] = $modx->getOption('tvPrefix', $scriptProperties, 'tv.');
$scriptProperties['includeTVs'] = $modx->getOption('includeTVs', $scriptProperties, 0);
$includeTVList = $modx->getOption('includeTVList', $scriptProperties);
$scriptProperties['includeTVList'] = !empty($includeTVList) ? array_map('trim', @explode(',', $includeTVList)) : array();
$scriptProperties['processTVs'] = $modx->getOption('processTVs', $scriptProperties, 0);
$processTVList = $modx->getOption('processTVList', $scriptProperties);
$scriptProperties['processTVList'] = !empty($processTVList) ? array_map('trim', @explode(',', $processTVList)) : array();
$parents = $modx->getOption('parents', $scriptProperties);
$parents = (!empty($parents) || $parents === '0') ? array_map('trim', @explode(',', $parents)) : array($modx->resource->get('parent'));
$scriptProperties['parents'] = array_unique($parents);
$tpl = $modx->getOption('tpl', $scriptProperties, 'prevnext.tpl');
$css = $modx->getOption('css', $scriptProperties, 'assets/components/prevnext/css/default.css');

$defaultPrevNextCorePath = $modx->getOption('core_path') . 'components/prevnext/';
$prevnextCorePath = $modx->getOption('prevnext.core_path', null, $defaultPrevNextCorePath);
$prevnext = $modx->getService('prevnext', 'PrevNext', $prevnextCorePath . 'model/prevnext/', $scriptProperties);

if (!($prevnext instanceof PrevNext)) {
    return;
}

// Previous
$prevArray = $prevnext->getSibling();

// Next
$nextArray = $prevnext->getSibling('>');

$phs = array_merge($prevArray, $nextArray);
//$toArray = 1;
if ($toArray) {
    return '<pre>' . print_r($phs, 1) . '</pre>';
} else {
    if (!empty($css)) {
        $modx->regClientCSS($css);
    }
}
$output = $prevnext->parseTpl($tpl, $phs);
$output = $prevnext->processElementTags($output);

return $output;
