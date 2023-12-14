<?php

/**
 * @since 3.2
 * @see N°7059 - Symfony 6.4 - Application skeleton
 * All classes in sources directory needs to be PSR-4 compatible, this alias covers the namespaces corrections.
 * PSR-4 Exception with directory sources/Application/WebPage configured in \symfony\config\services.yaml
 */
class_alias(\Combodo\iTop\Application\UI\Hook\iKeyboardShortcut::class, 'iKeyboardShortcut');
class_alias(\Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableConfig\DataTableConfig::class, 'DataTableConfig');
class_alias(\Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectUIBlockFactory::class, 'Combodo\\iTop\\Application\\UI\\Base\\Component\\Input\\SelectUIBlockFactory');
class_alias(\Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\CaseLogEntryForm\CaseLogEntryFormFactory::class, 'Combodo\\iTop\\Application\\UI\\Base\\Layout\\ActivityPanel\\CaseLogEntryFormFactory\\CaseLogEntryFormFactory');
class_alias(\Combodo\iTop\Core\Authentication\Client\Smtp\Oauth::class, 'Laminas\\Mail\\Protocol\\Smtp\\Auth\\Oauth');
class_alias(\Combodo\iTop\Core\Email\EMailLaminas::class, 'EMailLaminas');
class_alias(\Combodo\iTop\Application\WebPage\AjaxPage::class, 'AjaxPage');
class_alias(\Combodo\iTop\Application\WebPage\CaptureWebPage::class, 'CaptureWebPage');
class_alias(\Combodo\iTop\Application\WebPage\CLILikeWebPage::class, 'CLILikeWebPage');
class_alias(\Combodo\iTop\Application\WebPage\CLIPage::class, 'CLIPage');
class_alias(\Combodo\iTop\Application\WebPage\CSVPage::class, 'CSVPage');
class_alias(\Combodo\iTop\Application\WebPage\DownloadPage::class, 'DownloadPage');
class_alias(\Combodo\iTop\Application\WebPage\ErrorPage::class, 'ErrorPage');
class_alias(\Combodo\iTop\Application\WebPage\iTabbedPage::class, 'iTabbedPage');
class_alias(\Combodo\iTop\Application\WebPage\iTopPDF::class, 'iTopPDF');
class_alias(\Combodo\iTop\Application\WebPage\iTopWebPage::class, 'iTopWebPage');
class_alias(\Combodo\iTop\Application\WebPage\iTopWizardWebPage::class, 'iTopWizardWebPage');
class_alias(\Combodo\iTop\Application\WebPage\JsonPage::class, 'JsonPage');
class_alias(\Combodo\iTop\Application\WebPage\JsonPPage::class, 'JsonPPage');
class_alias(\Combodo\iTop\Application\WebPage\NiceWebPage::class, 'NiceWebPage');
class_alias(\Combodo\iTop\Application\WebPage\Page::class, 'Page');
class_alias(\Combodo\iTop\Application\WebPage\PDFPage::class, 'PDFPage');
class_alias(\Combodo\iTop\Application\WebPage\TabManager::class, 'TabManager');
class_alias(\Combodo\iTop\Application\WebPage\UnauthenticatedWebPage::class, 'UnauthenticatedWebPage');
class_alias(\Combodo\iTop\Application\WebPage\WebPage::class, 'WebPage');
class_alias(\Combodo\iTop\Application\WebPage\XMLPage::class, 'XMLPage');