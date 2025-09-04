<?php

/**
 * Class TriggerOnObject
 */
abstract class TriggerOnObject extends Trigger
{
    /**
     * @throws \CoreException
     * @throws \Exception
     */
    public static function Init()
    {
        $aParams = array
        (
            "category" => "grant_by_profile,core/cmdb",
            "key_type" => "autoincrement",
            "name_attcode" => "description",
            "complementary_name_attcode" => ['finalclass', 'complement'],
            "state_attcode" => "",
            "reconc_keys" => ['description'],
            "db_table" => "priv_trigger_onobject",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeClass("target_class",
            array("class_category" => "bizmodel", "more_values" => "User,UserExternal,UserInternal,UserLDAP,UserLocal", "sql" => "target_class", "default_value" => null, "is_null_allowed" => false, "depends_on" => array(), "class_exclusion_list" => "Attachment")));
        MetaModel::Init_AddAttribute(new AttributeOQL("filter", array("allowed_values" => null, "sql" => "filter", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('description', 'context', 'target_class', 'filter', 'subscription_policy', 'action_list')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class', 'description')); // Attributes to be displayed for a list
        // Search criteria
        MetaModel::Init_SetZListItems('default_search', array('description', 'target_class'));  // Default criteria of the search banner
        //		MetaModel::Init_SetZListItems('standard_search', array('name', 'target_class', 'description')); // Criteria of the search form
    }

    /**
     * @throws \CoreException
     */
    public function DoCheckToWrite()
    {
        parent::DoCheckToWrite();

        $sFilter = trim($this->Get('filter') ?? '');
        if (strlen($sFilter) > 0) {
            try {
                $oSearch = DBObjectSearch::FromOQL($sFilter);

                if (!MetaModel::IsParentClass($this->Get('target_class'), $oSearch->GetClass())) {
                    $this->m_aCheckIssues[] = Dict::Format('TriggerOnObject:WrongFilterClass', $this->Get('target_class'));
                }
            } catch (OqlException $e) {
                $this->m_aCheckIssues[] = Dict::Format('TriggerOnObject:WrongFilterQuery', $e->getMessage());
            }
        }
    }

    /**
     * @throws \CoreException
     */
    public function ComputeValues()
    {
        parent::ComputeValues();

        // Complementary name of a Trigger is manually built
        //   - the Trigger finalclass code not translated
        //   - an hardcoded text in english
        //   - the target class code not translated for TriggerOnObject subclasses
        $this->Set('complement', 'class restriction: ' . $this->Get('target_class'));
    }


    /**
     * Check whether the given object is in the scope of this trigger
     * and can potentially be the subject of notifications
     *
     * @param DBObject $oObject The object to check
     *
     * @return bool
     * @throws \CoreException
     */
    public function IsInScope(DBObject $oObject)
    {
        $sRootClass = $this->Get('target_class');

        return ($oObject instanceof $sRootClass);
    }

    /**
     * @param $aContextArgs
     *
     * @throws \ArchivedObjectException
     * @throws \CoreException
     */
    public function DoActivate($aContextArgs)
    {
        $bGo = true;
        if (isset($aContextArgs['this->object()'])) {
            /** @var \DBObject $oObject */
            $oObject = $aContextArgs['this->object()'];
            $bGo = $this->IsTargetObject($oObject->GetKey(), $oObject->ListPreviousValuesForUpdatedAttributes());
        }
        if ($bGo) {
            parent::DoActivate($aContextArgs);
        }
    }

    /**
     * if the target class is Attachment, then the trigger is read-only
     * @param $sAttCode
     * @param $aReasons
     * @param $sTargetState
     * @return int
     * @throws ArchivedObjectException
     * @throws CoreException
     */
    public function GetAttributeFlags($sAttCode, &$aReasons = array(), $sTargetState = '')
    {
        // Force the computed field to be read-only, preventing it to be written
        if ($this->Get('target_class') == 'Attachment') {
            return OPT_ATT_READONLY;
        }
        return parent::GetAttributeFlags($sAttCode, $aReasons, $sTargetState);
    }


    public function DisplayBareHeader(WebPage $oPage, $bEditMode = false)
    {
        $aHeaderBlocks = parent::DisplayBareHeader($oPage, $bEditMode);
        if ($this->Get('target_class') == 'Attachment') {
            $oPage->AddUiBlock(\Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory::MakeForWarning('', Dict::S('Class:TriggerOnObject:TriggerClassAttachment/ReadOnlyMessage')));
            $oPage->add_ready_script("$('#UIMenuModify').hide();");
        }

        return $aHeaderBlocks;
    }

    /**
     * Activate trigger based on attribute list given instead of changed attributes
     *
     * @param array $aContextArgs
     * @param array|null $aAttributes if null default to changed attributes
     *
     * @throws \ArchivedObjectException
     * @throws \CoreException
     * @throws \MissingQueryArgument
     * @throws \MySQLException
     * @throws \MySQLHasGoneAwayException
     * @throws \OQLException
     * @since 3.1.1 3.2.0 N°6228
     */
    public function DoActivateForSpecificAttributes(array $aContextArgs, ?array $aAttributes)
    {
        if (isset($aContextArgs['this->object()'])) {
            /** @var \DBObject $oObject */
            $oObject = $aContextArgs['this->object()'];
            if (is_null($aAttributes)) {
                $aChanges = $oObject->ListPreviousValuesForUpdatedAttributes();
            } else {
                $aChanges = array_fill_keys($aAttributes, true);
            }
            if (false === $this->IsTargetObject($oObject->GetKey(), $aChanges)) {
                return;
            }
        }
        parent::DoActivate($aContextArgs);
    }

    /**
     * @param $iObjectId
     * @param array $aChanges
     *
     * @return bool True if the object of ID $iObjectId is within the scope of the OQL defined by the "filter" attribute
     *
     * @throws \CoreException
     * @throws \MissingQueryArgument
     * @throws \MySQLException
     * @throws \MySQLHasGoneAwayException
     * @throws \OQLException
     */
    public function IsTargetObject($iObjectId, $aChanges = array())
    {
        $sFilter = trim($this->Get('filter') ?? '');
        if (strlen($sFilter) > 0) {
            $oSearch = DBObjectSearch::FromOQL($sFilter);
            $oSearch->AddCondition('id', $iObjectId, '=');
            $oSearch->AllowAllData();
            $oSet = new DBObjectSet($oSearch);
            $bRet = ($oSet->Count() > 0);
        } else {
            $bRet = true;
        }

        return $bRet;
    }

    /**
     * @param Exception $oException
     * @param \DBObject $oObject
     *
     * @return void
     *
     * @uses \IssueLog::Error()
     *
     * @since 2.7.9 3.0.3 3.1.0 N°5893
     */
    public function LogException($oException, $oObject)
    {
        $sObjectKey = $oObject->GetKey(); // if object wasn't persisted yet, then we'll have a negative value

        $aContext = [
            'exception.class' => get_class($oException),
            'exception.message' => $oException->getMessage(),
            'trigger.class' => get_class($this),
            'trigger.id' => $this->GetKey(),
            'trigger.friendlyname' => $this->GetRawName(),
            'object.class' => get_class($oObject),
            'object.id' => $sObjectKey,
            'object.friendlyname' => $oObject->GetRawName(),
            'current_user' => UserRights::GetUser(),
            'exception.stack' => $oException->getTraceAsString(),
        ];

        IssueLog::Error('A trigger did throw an exception', null, $aContext);
    }
}