<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\UI\Base\Component\FieldBadge\FieldBadgeUIBlockFactory;
use Combodo\iTop\Application\UI\Links\Set\BlockLinkSetDisplayAsProperty;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Form\Field\LabelField;
use Combodo\iTop\Form\Field\TextAreaField;
use Combodo\iTop\Form\Form;
use Combodo\iTop\Form\Validator\CustomRegexpValidator;
use Combodo\iTop\Renderer\BlockRenderer;
use Combodo\iTop\Renderer\Console\ConsoleBlockRenderer;
use Combodo\iTop\Service\Links\LinkSetModel;

require_once('MyHelpers.class.inc.php');
require_once('ormdocument.class.inc.php');
require_once('ormstopwatch.class.inc.php');
require_once('ormpassword.class.inc.php');
require_once('ormcaselog.class.inc.php');
require_once('ormlinkset.class.inc.php');
require_once('ormset.class.inc.php');
require_once('ormtagset.class.inc.php');
require_once('htmlsanitizer.class.inc.php');
require_once('customfieldshandler.class.inc.php');
require_once('ormcustomfieldsvalue.class.inc.php');
require_once('datetimeformat.class.inc.php');


require_once(APPROOT.'core/attributedef/MissingColumnException.php');
require_once(APPROOT.'core/attributedef/iAttributeNoGroupBy.php');
require_once(APPROOT.'core/attributedef/AttributeDefinition.php');
require_once(APPROOT.'core/attributedef/AttributeDashboard.php');
require_once(APPROOT.'core/attributedef/AttributeLinkedSet.php');
require_once(APPROOT.'core/attributedef/AttributeLinkedSetIndirect.php');
require_once(APPROOT.'core/attributedef/AttributeDBFieldVoid.php');
require_once(APPROOT.'core/attributedef/AttributeDBField.php');
require_once(APPROOT.'core/attributedef/AttributeInteger.php');
require_once(APPROOT.'core/attributedef/AttributeObjectKey.php');
require_once(APPROOT.'core/attributedef/AttributePercentage.php');
require_once(APPROOT.'core/attributedef/AttributeDecimal.php');
require_once(APPROOT.'core/attributedef/AttributeBoolean.php');
require_once(APPROOT.'core/attributedef/AttributeString.php');
require_once(APPROOT.'core/attributedef/AttributeClass.php');
require_once(APPROOT.'core/attributedef/AttributeClassState.php');
require_once(APPROOT.'core/attributedef/AttributeApplicationLanguage.php');
require_once(APPROOT.'core/attributedef/AttributeFinalClass.php');
require_once(APPROOT.'core/attributedef/AttributePassword.php');
require_once(APPROOT.'core/attributedef/AttributeEncryptedString.php');
require_once(APPROOT.'core/attributedef/AttributeText.php');
require_once(APPROOT.'core/attributedef/AttributeLongText.php');
require_once(APPROOT.'core/attributedef/AttributeCaseLog.php');
require_once(APPROOT.'core/attributedef/AttributeHTML.php');
require_once(APPROOT.'core/attributedef/AttributeEmailAddress.php');
require_once(APPROOT.'core/attributedef/AttributeIPAddress.php');
require_once(APPROOT.'core/attributedef/AttributePhoneNumber.php');
require_once(APPROOT.'core/attributedef/AttributeOQL.php');
require_once(APPROOT.'core/attributedef/AttributeTemplateString.php');
require_once(APPROOT.'core/attributedef/AttributeTemplateText.php');
require_once(APPROOT.'core/attributedef/AttributeTemplateHTML.php');
require_once(APPROOT.'core/attributedef/AttributeEnum.php');
require_once(APPROOT.'core/attributedef/AttributeMetaEnum.php');
require_once(APPROOT.'core/attributedef/AttributeDateTime.php');
require_once(APPROOT.'core/attributedef/AttributeDuration.php');
require_once(APPROOT.'core/attributedef/AttributeDate.php');
require_once(APPROOT.'core/attributedef/AttributeDeadline.php');
require_once(APPROOT.'core/attributedef/AttributeExternalKey.php');
require_once(APPROOT.'core/attributedef/AttributeHierarchicalKey.php');
require_once(APPROOT.'core/attributedef/AttributeExternalField.php');
require_once(APPROOT.'core/attributedef/AttributeURL.php');
require_once(APPROOT.'core/attributedef/AttributeBlob.php');
require_once(APPROOT.'core/attributedef/AttributeImage.php');
require_once(APPROOT.'core/attributedef/AttributeStopWatch.php');
require_once(APPROOT.'core/attributedef/AttributeSubItem.php');
require_once(APPROOT.'core/attributedef/AttributeOneWayPassword.php');
require_once(APPROOT.'core/attributedef/AttributeTable.php');
require_once(APPROOT.'core/attributedef/AttributePropertySet.php');
require_once(APPROOT.'core/attributedef/AttributeSet.php');
require_once(APPROOT.'core/attributedef/AttributeEnumSet.php');
require_once(APPROOT.'core/attributedef/AttributeClassAttCodeSet.php');
require_once(APPROOT.'core/attributedef/AttributeQueryAttCodeSet.php');
require_once(APPROOT.'core/attributedef/AttributeTagSet.php');
require_once(APPROOT.'core/attributedef/AttributeFriendlyName.php');
require_once(APPROOT.'core/attributedef/AttributeRedundancySettings.php');
require_once(APPROOT.'core/attributedef/AttributeObsolescenceDate.php');
require_once(APPROOT.'core/attributedef/AttributeCustomFields.php');
require_once(APPROOT.'core/attributedef/AttributeArchiveFlag.php');
require_once(APPROOT.'core/attributedef/AttributeArchiveDate.php');
require_once(APPROOT.'core/attributedef/AttributeObsolescenceFlag.php');
require_once(APPROOT.'core/attributedef/AttributeObsolescenceDate.php');


/**
 * add some description here...
 *
 * @package     iTopORM
 */
define('EXTKEY_RELATIVE', 1);

/**
 * add some description here...
 *
 * @package     iTopORM
 */
define('EXTKEY_ABSOLUTE', 2);

/**
 * Propagation of the deletion through an external key - ask the user to delete the referencing object
 *
 * @package     iTopORM
 */
define('DEL_MANUAL', 1);

/**
 * Propagation of the deletion through an external key - remove linked objects if ext key has is_null_allowed=false
 *
 * @package     iTopORM
 */
define('DEL_AUTO', 2);
/**
 * Fully silent delete... not yet implemented
 */
define('DEL_SILENT', 2);
/**
 * For HierarchicalKeys only: move all the children up one level automatically
 */
define('DEL_MOVEUP', 3);

/**
 * Do nothing at least automatically
 */
define('DEL_NONE', 4);


/**
 * For Link sets: tracking_level
 *
 * @package     iTopORM
 */
define('ATTRIBUTE_TRACKING_NONE', 0); // Do not track changes of the attribute
define('ATTRIBUTE_TRACKING_ALL', 3); // Do track all changes of the attribute
define('LINKSET_TRACKING_NONE', 0); // Do not track changes in the link set
define('LINKSET_TRACKING_LIST', 1); // Do track added/removed items
define('LINKSET_TRACKING_DETAILS', 2); // Do track modified items
define('LINKSET_TRACKING_ALL', 3); // Do track added/removed/modified items

define('LINKSET_EDITMODE_NONE', 0); // The linkset cannot be edited at all from inside this object
define('LINKSET_EDITMODE_ADDONLY', 1); // The only possible action is to open a new window to create a new object
define('LINKSET_EDITMODE_ACTIONS', 2); // Show the usual 'Actions' popup menu
define('LINKSET_EDITMODE_INPLACE', 3); // The "linked" objects can be created/modified/deleted in place
define('LINKSET_EDITMODE_ADDREMOVE', 4); // The "linked" objects can be added/removed in place

define('LINKSET_EDITWHEN_NEVER', 0); // The linkset cannot be edited at all from inside this object
define('LINKSET_EDITWHEN_ON_HOST_EDITION', 1); // The only possible action is to open a new window to create a new object
define('LINKSET_EDITWHEN_ON_HOST_DISPLAY', 2); // Show the usual 'Actions' popup menu
define('LINKSET_EDITWHEN_ALWAYS', 3); // Show the usual 'Actions' popup menu


define('LINKSET_DISPLAY_STYLE_PROPERTY', 'property');
define('LINKSET_DISPLAY_STYLE_TAB', 'tab');


/**
 * Wiki formatting - experimental
 *
 * [[<objClass>:<objName|objId>|<label>]]
 * <label> is optional
 *
 * Examples:
 * - [[Server:db1.tnut.com]]
 * - [[Server:123]]
 * - [[Server:db1.tnut.com|Production server]]
 * - [[Server:123|Production server]]
 */
define('WIKI_OBJECT_REGEXP', '/\[\[(.+):(.+)(\|(.+))?\]\]/U');


// Indexed array having two dimensions

// The PHP value is a hash array, it is stored as a TEXT column
