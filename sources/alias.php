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
class_alias(\Combodo\iTop\Core\Email\EMailSymfony::class, 'EMailSymfony');
class_alias(\Combodo\iTop\Core\Email\Transport\SymfonyFileTransport::class, 'SymfonyFileTransport');
class_alias(\Combodo\iTop\Core\Email\Transport\SymfonyOAuthTransport::class, 'SymfonyOAuthTransport');
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

// attribute definitions
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeApplicationLanguage::class, 'AttributeApplicationLanguage');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeArchiveDate::class, 'AttributeArchiveDate');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeArchiveFlag::class, 'AttributeArchiveFlag');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeBlob::class, 'AttributeBlob');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeBoolean::class, 'AttributeBoolean');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeCaseLog::class, 'AttributeCaseLog');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeClass::class, 'AttributeClass');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeClassAttCodeSet::class, 'AttributeClassAttCodeSet');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeClassState::class, 'AttributeClassState');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeCustomFields::class, 'AttributeCustomFields');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeDashboard::class, 'AttributeDashboard');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeDate::class, 'AttributeDate');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeDateTime::class, 'AttributeDateTime');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeDBField::class, 'AttributeDBField');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeDBFieldVoid::class, 'AttributeDBFieldVoid');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeDeadline::class, 'AttributeDeadline');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeDecimal::class, 'AttributeDecimal');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeDefinition::class, 'AttributeDefinition');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeDuration::class, 'AttributeDuration');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeEmailAddress::class, 'AttributeEmailAddress');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeEncryptedString::class, 'AttributeEncryptedString');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeEnum::class, 'AttributeEnum');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeEnumSet::class, 'AttributeEnumSet');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeExternalField::class, 'AttributeExternalField');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeExternalKey::class, 'AttributeExternalKey');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeFinalClass::class, 'AttributeFinalClass');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeFriendlyName::class, 'AttributeFriendlyName');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeHierarchicalKey::class, 'AttributeHierarchicalKey');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeHTML::class, 'AttributeHTML');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeImage::class, 'AttributeImage');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeInteger::class, 'AttributeInteger');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeIPAddress::class, 'AttributeIPAddress');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeLinkedSet::class, 'AttributeLinkedSet');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeLinkedSetIndirect::class, 'AttributeLinkedSetIndirect');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeLongText::class, 'AttributeLongText');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeMetaEnum::class, 'AttributeMetaEnum');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeObjectKey::class, 'AttributeObjectKey');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeObsolescenceDate::class, 'AttributeObsolescenceDate');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeObsolescenceFlag::class, 'AttributeObsolescenceFlag');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeOneWayPassword::class, 'AttributeOneWayPassword');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeOQL::class, 'AttributeOQL');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributePassword::class, 'AttributePassword');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributePercentage::class, 'AttributePercentage');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributePhoneNumber::class, 'AttributePhoneNumber');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributePropertySet::class, 'AttributePropertySet');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeQueryAttCodeSet::class, 'AttributeQueryAttCodeSet');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeRedundancySettings::class, 'AttributeRedundancySettings');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeSet::class, 'AttributeSet');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeStopWatch::class, 'AttributeStopWatch');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeString::class, 'AttributeString');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeSubItem::class, 'AttributeSubItem');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeTable::class, 'AttributeTable');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeTagSet::class, 'AttributeTagSet');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeTemplateHTML::class, 'AttributeTemplateHTML');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeTemplateString::class, 'AttributeTemplateString');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeTemplateText::class, 'AttributeTemplateText');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeText::class, 'AttributeText');
class_alias(\Combodo\iTop\Core\AttributeDefinition\AttributeURL::class, 'AttributeURL');
class_alias(\Combodo\iTop\Core\AttributeDefinition\iAttributeNoGroupBy::class, 'iAttributeNoGroupBy');
class_alias(\Combodo\iTop\Core\AttributeDefinition\MissingColumnException::class, 'MissingColumnException');
