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
 * @subpackage lexicon
 */

$_lang['property_sort_desc'] = 'Define the sorting method of the siblings to identify the closest one. Default: publishedon.';
$_lang['property_includeHidden_desc'] = 'Include hidden resources.';
$_lang['property_prevPrefix_desc'] = 'Placeholder\'s prefix for the previous page. Default: "prev."';
$_lang['property_nextPrefix_desc'] = 'Placeholder\'s prefix for the next page. Default: "next."';
$_lang['property_tvPrefix_desc'] = 'Placeholder\'s prefix for template variables fn each page. Default: "tv."';
$_lang['property_tpl_desc'] = 'Name of chunk of the template. You can use @FILE/@CODE/@INLINE or the chunk\'s name. Default: prevnext.tpl';
$_lang['property_includeTVs_desc'] = 'Include Template Variables to the output? Default: No/0 (zero)';
$_lang['property_includeTVList_desc'] = 'List of the Template Variables to the output (optional), seperated with commas';
$_lang['property_processTVs_desc'] = 'Process Template Variables';
$_lang['property_processTVList_desc'] = 'Select of the Template Variables to be processed (optional), separated with commas';
$_lang['property_parents_desc'] = 'Rather than the page\'s parent, you can define different parents. Multiple parents\' ids should be separated with commas.';
$_lang['property_toArray_desc'] = 'Return the output as an array of placeholders instead';


