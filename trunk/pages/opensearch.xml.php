<?php
	$sFullUrl = 'http'.(empty($_SERVER['HTTPS']) ? '' : 's').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/UI.php';
	$sICOFullUrl = 'http'.(empty($_SERVER['HTTPS']) ? '' : 's').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/../images/iTop-icon.ico';
	$sPNGFullUrl = 'http'.(empty($_SERVER['HTTPS']) ? '' : 's').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/../images/iTop-icon.png';
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
