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
 * Persistent classes for a CMDB
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

class Problem extends Ticket
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,problemmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "status",
			"reconc_keys" => array("ref"),
			"db_table" => "ticket_problem",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
			"icon" => "../modules/itop-problem-mgmt-1.0.0/images/problem.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
//		MetaModel::Init_InheritLifecycle();

                MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('new,assigned,resolved,closed'), "sql"=>"status", "default_value"=>"new", "is_null_allowed"=>false, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"org_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeExternalField("org_name", array("allowed_values"=>null, "extkey_attcode"=>"org_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeExternalKey("service_id", array("targetclass"=>"Service", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT Service AS s JOIN SLA AS sla ON sla.service_id=s.id JOIN lnkContractToSLA AS ln ON ln.sla_id=sla.id JOIN CustomerContract AS cc ON ln.contract_id=cc.id WHERE cc.org_id =:this->org_id'), "sql"=>"service_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("org_id"))));
                MetaModel::Init_AddAttribute(new AttributeExternalField("service_name", array("allowed_values"=>null, "extkey_attcode"=>"service_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeExternalKey("servicesubcategory_id", array("targetclass"=>"ServiceSubcategory", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT ServiceSubcategory WHERE service_id = :this->service_id'), "sql"=>"servicesubcategory_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("service_id"))));
                MetaModel::Init_AddAttribute(new AttributeExternalField("servicesubcategory_name", array("allowed_values"=>null, "extkey_attcode"=>"servicesubcategory_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeString("product", array("allowed_values"=>null, "sql"=>"product", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeEnum("impact", array("allowed_values"=>new ValueSetEnum('1,2,3'), "sql"=>"impact", "default_value"=>"1", "is_null_allowed"=>false, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeEnum("urgency", array("allowed_values"=>new ValueSetEnum('1,2,3'), "sql"=>"urgency", "default_value"=>"1", "is_null_allowed"=>false, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeEnum("priority", array("allowed_values"=>new ValueSetEnum('1,2,3'), "sql"=>"priority", "default_value"=>"1", "is_null_allowed"=>false, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeExternalKey("workgroup_id", array("targetclass"=>"Team", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT Team AS t JOIN CustomerContract AS cc ON cc.support_team_id=t.id JOIN lnkContractToSLA AS ln ON ln.contract_id=cc.id JOIN SLA AS sla ON ln.sla_id=sla.id WHERE sla.service_id = :this->service_id AND cc.org_id = :this->org_id'), "sql"=>"workgroup_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("org_id","service_id"))));
                MetaModel::Init_AddAttribute(new AttributeExternalField("workgroup_name", array("allowed_values"=>null, "extkey_attcode"=>"workgroup_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
               MetaModel::Init_AddAttribute(new AttributeExternalKey("agent_id", array("targetclass"=>"Person", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT Person AS p JOIN lnkTeamToContact AS l ON l.contact_id=p.id JOIN Team AS t ON l.team_id=t.id WHERE t.id = :this->workgroup_id'), "sql"=>"agent_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("workgroup_id"))));
                MetaModel::Init_AddAttribute(new AttributeExternalField("agent_name", array("allowed_values"=>null, "extkey_attcode"=>"agent_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeExternalField("agent_email", array("allowed_values"=>null, "extkey_attcode"=>"agent_id", "target_attcode"=>"email", "is_null_allowed"=>true, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeExternalKey("related_change_id", array("targetclass"=>"Change", "jointype"=>null, "allowed_values"=>null, "sql"=>"related_change_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeExternalField("related_change_ref", array("allowed_values"=>null, "extkey_attcode"=>"related_change_id", "target_attcode"=>"ref", "is_null_allowed"=>true, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeDateTime("close_date", array("allowed_values"=>null, "sql"=>"close_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeDateTime("last_update", array("allowed_values"=>null, "sql"=>"last_update", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeDateTime("assignment_date", array("allowed_values"=>null, "sql"=>"assignment_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeDateTime("resolution_date", array("allowed_values"=>null, "sql"=>"resolution_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeLinkedSet("knownerrors_list", array("linked_class"=>"KnownError", "ext_key_to_me"=>"problem_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('document_list', 'ci_list', 'contact_list','incident_list',
			'col:col1' => array(
				'fieldset:Ticket:baseinfo' => array('ref','title','org_id','status','priority','service_id','servicesubcategory_id','product' ),
				'fieldset:Ticket:moreinfo' => array('impact','urgency','description',),
				'fieldset:Ticket:log' => array('ticket_log'),),
			'col:col2' => array(
				'fieldset:Ticket:date' => array('start_date','last_update','assignment_date','close_date',),
				'fieldset:Ticket:contact' => array('workgroup_id','agent_id',),
				'fieldset:Ticket:relation' => array('related_change_id',),
				)

		));


//		MetaModel::Init_SetZListItems('details', array('ref', 'title', 'org_id', 'description', 'ticket_log', 'start_date','knownerrors_list', 'document_list', 'ci_list', 'contact_list','incident_list', 'status', 'service_id', 'servicesubcategory_id','product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_change_id', 'close_date', 'last_update', 'assignment_date'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'org_id', 'start_date', 'status', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_change_id', 'close_date'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'org_id', 'start_date', 'status', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'close_date'));
		MetaModel::Init_SetZListItems('list', array('title', 'org_id', 'start_date', 'status', 'service_id', 'priority'));

                // Lifecycle
                MetaModel::Init_DefineState(
                        "new",
                        array(
                                "attribute_inherit" => null,
                                "attribute_list" => array(
                                        'ref' => OPT_ATT_READONLY,
                                        'ticket_log' => OPT_ATT_HIDDEN,
                                        'related_change_id' => OPT_ATT_HIDDEN,
                                        'description' => OPT_ATT_MUSTCHANGE,
                                        'contact_list' => OPT_ATT_READONLY,
                                        'start_date' => OPT_ATT_READONLY,
                                        'last_update' => OPT_ATT_READONLY,
                                        'assignment_date' => OPT_ATT_HIDDEN,
                                        'resolution_date' => OPT_ATT_HIDDEN,
                                        'close_date' => OPT_ATT_HIDDEN,
                                        'org_id' => OPT_ATT_MUSTCHANGE,
                                        'service_id' => OPT_ATT_MUSTCHANGE,
                                        'servicesubcategory_id' => OPT_ATT_MUSTCHANGE,
                                        'product' => OPT_ATT_MUSTPROMPT,
                                        'impact' => OPT_ATT_MUSTCHANGE,
                                        'urgency' => OPT_ATT_MUSTCHANGE,
                                        'priority' => OPT_ATT_READONLY,
                                        'workgroup_id' => OPT_ATT_MUSTCHANGE,
                                        'agent_id' => OPT_ATT_HIDDEN,
                                        'agent_email' => OPT_ATT_HIDDEN,
                                ),
                        )
                );
                MetaModel::Init_DefineState(
                        "assigned",
                        array(
                                "attribute_inherit" => 'new',
                                "attribute_list" => array(
                                        'title' => OPT_ATT_READONLY,
                                        'org_id' => OPT_ATT_READONLY,
                                        'ticket_log' => OPT_ATT_NORMAL,
					'assignment_date' => OPT_ATT_READONLY,
                                        'description' => OPT_ATT_READONLY,
                                        'agent_id' => OPT_ATT_MUSTPROMPT | OPT_ATT_MANDATORY,
                                        'agent_email' => OPT_ATT_READONLY,
                                        'workgroup_id' => OPT_ATT_MUSTPROMPT | OPT_ATT_MANDATORY,
                                        'related_change_id' => OPT_ATT_NORMAL,
                                ),
                        )
                );
               MetaModel::Init_DefineState(
                        "resolved",
                        array(
                                "attribute_inherit" => 'assigned',
                                "attribute_list" => array(
                                        'service_id' => OPT_ATT_READONLY,
                                        'servicesubcategory_id' => OPT_ATT_READONLY,
                                        'product' => OPT_ATT_READONLY,
                                        'impact' => OPT_ATT_READONLY,
                                        'workgroup_id' => OPT_ATT_READONLY,
                                        'agent_id' => OPT_ATT_READONLY,
                                        'urgency' => OPT_ATT_READONLY,
                                ),
                        )
                );
                MetaModel::Init_DefineState(
                        "closed",
                        array(
                                "attribute_inherit" => 'resolved',
                                "attribute_list" => array(
                                        'ticket_log' => OPT_ATT_READONLY,
                                        'close_date' => OPT_ATT_READONLY,
                                ),
                        )
                );

                MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_assign", array()));
                MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_reassign", array()));
                MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_resolve", array()));
                MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_close", array()));

                MetaModel::Init_DefineTransition("new", "ev_assign", array("target_state"=>"assigned", "actions"=>array('SetAssignedDate'), "user_restriction"=>null));
                MetaModel::Init_DefineTransition("assigned", "ev_reassign", array("target_state"=>"assigned", "actions"=>array(), "user_restriction"=>null));
                MetaModel::Init_DefineTransition("assigned", "ev_resolve", array("target_state"=>"resolved", "actions"=>array('SetResolveDate'), "user_restriction"=>null));

                MetaModel::Init_DefineTransition("resolved", "ev_reassign", array("target_state"=>"assigned", "actions"=>array(), "user_restriction"=>null));
                MetaModel::Init_DefineTransition("resolved", "ev_close", array("target_state"=>"closed", "actions"=>array('SetClosureDate'), "user_restriction"=>null));
        }

        // Lifecycle actions
        //
        public function SetAssignedDate($sStimulusCode)
        {
                $this->Set('assignment_date', time());
                return true;
        }
        public function SetResolveDate($sStimulusCode)
        {
                $this->Set('resolution_date', time());
                return true;
        }
        public function SetClosureDate($sStimulusCode)
        {
                $this->Set('close_date', time());
                return true;
        }

       /** Compute the priority of the ticket based on its impact and urgency
         * @return integer The priority of the ticket 1(high) .. 3(low)
         */
        public function ComputePriority()
        {
                // priority[impact][urgency]
                $aPriorities = array(
                        // single person
                        1 => array(
                                        1 => 1,
                                        2 => 1,
                                        3 => 2,
                        ),
                        // a group
                        2 => array(
                                1 => 1,
                                2 => 2,
                                3 => 3,
                        ),
                        // a departement!
                        3 => array(
                                        1 => 2,
                                        2 => 3,
                                        3 => 3,
                        ),
                );
                $iPriority = $aPriorities[(int)$this->Get('impact')][(int)$this->Get('urgency')];
                return $iPriority;              
        }


	public function ComputeValues()
	{
		// Compute the priority of the ticket
		$this->Set('priority', $this->ComputePriority());

		$sCurrRef = $this->Get('ref');
		if (strlen($sCurrRef) == 0)
		{
			$iKey = $this->GetKey();
			if ($iKey < 0)
			{
				// Object not yet in the Database
				$iKey = MetaModel::GetNextKey(get_class($this));
			}
			$sName = sprintf('P-%06d', $iKey);
			$this->Set('ref', $sName);
		}
	}

}


$oMyMenuGroup = new MenuGroup('ProblemManagement', 42 /* fRank */); // Will create if it does not exist
$iIndex = $oMyMenuGroup->GetIndex();
new TemplateMenuNode('Problem:Overview', '../modules/itop-problem-mgmt-1.0.0/overview.html', $iIndex /* oParent */, 0 /* fRank */);
new NewObjectMenuNode('NewProblem', 'Problem', $iIndex, 1 /* fRank */);
new SearchMenuNode('SearchProblems', 'Problem', $iIndex, 2 /* fRank */);
$oShortcutNode = new TemplateMenuNode('Problem:Shortcuts', '', $iIndex, 5 /* fRank */);
new OQLMenuNode('Problem:MyProblems', 'SELECT Problem WHERE agent_id = :current_contact_id AND status NOT IN ("closed", "resolved")', $oShortcutNode->GetIndex(), 1 /* fRank */);
new OQLMenuNode('Problem:OpenProblems', 'SELECT Problem WHERE status IN ("new", "assigned", "resolved")', $oShortcutNode->GetIndex(), 2 /* fRank */);

?>
