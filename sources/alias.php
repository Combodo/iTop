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
