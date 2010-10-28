<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * ???
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

	$sFullUrl = 'http'.((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']!='off')) ? 's' : '').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/UI.php';
	$sICOFullUrl = 'http'.((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']!='off')) ? 's' : '').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/../images/iTop-icon.ico';
	$sPNGFullUrl = 'http'.((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']!='off')) ? 's' : '').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/../images/iTop-icon.png';
	header('Content-type: text/xml');
?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/" xmlns:moz="http://www.mozilla.org/2006/browser/search/">
<ShortName>iTop</ShortName>
<Contact>webmaster@itop.com</Contact>
<Description>Search in iTop</Description>
<InputEncoding>ISO-8859-1</InputEncoding>
<Url type="text/html" method="get" template="<?php echo $sFullUrl;?>?text={searchTerms}&amp;operation=full_text"/>
<moz:SearchForm><?php echo $sFullUrl;?></moz:SearchForm>
<Image height="16" width="16" type="image/x-icon"><?php echo $sICOFullUrl;?></Image>
<Image height="64" width="64" type="image/png"><?php echo $sPNGFullUrl;?></Image>
</OpenSearchDescription>
