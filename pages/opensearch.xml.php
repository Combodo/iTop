<?php
// Copyright (C) 2010-2012 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * ???
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/startup.inc.php');


$sFullUrl = utils::GetAbsoluteUrlAppRoot().'pages/UI.php';
$sICOFullUrl = utils::GetAbsoluteUrlAppRoot().'/images/iTop-icon.ico';
$sPNGFullUrl = utils::GetAbsoluteUrlAppRoot().'images/iTop-icon.png';
header('Content-type: text/xml');
?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/" xmlns:moz="http://www.mozilla.org/2006/browser/search/">
<ShortName>iTop</ShortName>
<Contact>webmaster@itop.com</Contact>
<Description>Search in iTop</Description>
<InputEncoding>UTF-8</InputEncoding>
<Url type="text/html" method="get" template="<?php echo $sFullUrl;?>?text={searchTerms}&amp;operation=full_text"/>
<moz:SearchForm><?php echo $sFullUrl;?></moz:SearchForm>
<Image height="16" width="16" type="image/x-icon"><?php echo $sICOFullUrl;?></Image>
<Image height="64" width="64" type="image/png"><?php echo $sPNGFullUrl;?></Image>
</OpenSearchDescription>
