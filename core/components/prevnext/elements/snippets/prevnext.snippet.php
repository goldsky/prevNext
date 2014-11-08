<?php

/**
 * PrevNext
 *
 * Copyright 2014 by goldsky <goldsky@virtudraft.com>
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

$sort = $modx->getOption('sort', $scriptProperties, 'publishedon');
$includeHidden = intval($modx->getOption('includeHidden', $scriptProperties, 0));
$prevPrefix = $modx->getOption('prevPrefix', $scriptProperties, 'prev.');
$nextPrefix = $modx->getOption('nextPrefix', $scriptProperties, 'next.');
$tvPrefix = $modx->getOption('tvPrefix', $scriptProperties, 'tv.');
$tpl = $modx->getOption('tpl', $scriptProperties, 'prevnext.tpl');
$includeTVs = $modx->getOption('includeTVs', $scriptProperties, 0);
$includeTVList = $modx->getOption('includeTVList', $scriptProperties);
$includeTVList = !empty($includeTVList) ? explode(',', $includeTVList) : array();
$processTVs = $modx->getOption('processTVs', $scriptProperties, 0);
$processTVList = $modx->getOption('processTVList', $scriptProperties);
$processTVList = !empty($processTVList) ? explode(',', $processTVList) : array();
$parents = $modx->getOption('parents', $scriptProperties);
$parents = (!empty($parents) || $parents === '0') ? explode(',', $parents) : array($modx->resource->get('parent'));
array_walk($parents, 'trim');
$parents = array_unique($parents);

$defaultPrevNextCorePath = $modx->getOption('core_path') . 'components/prevnext/';
$prevnextCorePath = $modx->getOption('prevnext.core_path', null, $defaultPrevNextCorePath);
$prevnext = $modx->getService('prevnext', 'PrevNext', $prevnextCorePath . 'model/prevnext/', $scriptProperties);

if (!($prevnext instanceof PrevNext)) {
    return;
}

$resource = $modx->resource->toArray();
$resource['createdon'] = strtotime($resource['createdon']);
$resource['editedon'] = strtotime($resource['editedon']);
$resource['publishedon'] = strtotime($resource['publishedon']);

// Previous
$c = $modx->newQuery('modResource');
$c->select(array(
    'modResource.*'
));
$c->where(array(
    $sort . ':<' => $resource[$sort],
    'parent:IN' => $parents,
    'published:=' => 1,
    'deleted:!=' => 1,
));
if (!$includeHidden) {
    $c->where(array(
        'hidemenu:!=' => 1,
    ));
}
$c->sortby($sort, 'desc'); // reverse the sort to get the closest sibling
$c->limit(1);

$templateVars = array();
if (!empty($includeTVs) && !empty($includeTVList)) {
    $templateVars = $modx->getCollection('modTemplateVar', array('name:IN' => $includeTVList));
}

$prevResource = $modx->getObject('modResource', $c);
if ($prevResource) {
    $prevArray = $prevResource->toArray($prevPrefix);
    if (!empty($includeTVs)) {
        if (empty($includeTVList)) {
            $templateVars = $prevResource->getMany('TemplateVars');
        }
        foreach ($templateVars as $tvId => $templateVar) {
            if (!empty($includeTVList) && !in_array($templateVar->get('name'), $includeTVList))
                continue;
            if ($processTVs && (empty($processTVList) || in_array($templateVar->get('name'), $processTVList))) {
                $prevArray[$prevPrefix . $tvPrefix . $templateVar->get('name')] = $templateVar->renderOutput($prevResource->get('id'));
            } else {
                $prevArray[$prevPrefix . $tvPrefix . $templateVar->get('name')] = $templateVar->getValue($prevResource->get('id'));
            }
        }
    }
} else {
    $prevArray = array(
        $prevPrefix . 'id' => ''
    );
}

// Next
$c = $modx->newQuery('modResource');
$c->where(array(
    $sort . ':>' => $resource[$sort],
    'parent:IN' => $parents,
    'published:=' => 1,
    'deleted:!=' => 1,
));
if (!$includeHidden) {
    $c->where(array(
        'hidemenu:!=' => 1,
    ));
}
$c->sortby($sort, 'asc'); // reverse the sort to get the closest sibling
$c->limit(1);
$nextResource = $modx->getObject('modResource', $c);
if ($nextResource) {
    $nextArray = $nextResource->toArray($nextPrefix);
    if (!empty($includeTVs)) {
        if (empty($includeTVList)) {
            $templateVars = $nextResource->getMany('TemplateVars');
        }
        foreach ($templateVars as $tvId => $templateVar) {
            if (!empty($includeTVList) && !in_array($templateVar->get('name'), $includeTVList))
                continue;
            if ($processTVs && (empty($processTVList) || in_array($templateVar->get('name'), $processTVList))) {
                $nextArray[$nextPrefix . $tvPrefix . $templateVar->get('name')] = $templateVar->renderOutput($nextResource->get('id'));
            } else {
                $nextArray[$nextPrefix . $tvPrefix . $templateVar->get('name')] = $templateVar->getValue($nextResource->get('id'));
            }
        }
    }
} else {
    $nextArray = array(
        $nextPrefix . 'id' => ''
    );
}

//$toArray = 1;
if ($toArray) {
    return '<pre>' . print_r(array_merge($prevArray, $nextArray), 1) . '</pre>';
}
$output = $prevnext->parseTpl($tpl, array_merge($prevArray, $nextArray));
$output = $prevnext->processElementTags($output);

return $output;