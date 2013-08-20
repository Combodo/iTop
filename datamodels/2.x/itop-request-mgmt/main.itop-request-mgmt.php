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

define('PORTAL_POWER_USER_PROFILE', 'Portal power user');

define('PORTAL_SERVICECATEGORY_QUERY', 'SELECT Service AS s JOIN lnkCustomerContractToService AS l1 ON l1.service_id=s.id JOIN CustomerContract AS cc ON l1.customercontract_id=cc.id WHERE cc.org_id = :org_id');
define('PORTAL_SERVICE_SUBCATEGORY_QUERY', 'SELECT ServiceSubcategory WHERE service_id = :svc_id');

define('PORTAL_VALIDATE_SERVICECATEGORY_QUERY', 'SELECT Service AS s JOIN lnkCustomerContractToService AS l1 ON l1.service_id=s.id JOIN CustomerContract AS cc ON l1.customercontract_id=cc.id WHERE cc.org_id = :org_id AND s.id = :id');
define('PORTAL_VALIDATE_SERVICESUBCATEGORY_QUERY', 'SELECT ServiceSubcategory AS Sub JOIN Service AS Svc ON Sub.service_id = Svc.id WHERE Sub.id=:id');

define('PORTAL_ALL_PARAMS', 'from_service_id,org_id,caller_id,service_id,servicesubcategory_id,title,description,impact,emergency,moreinfo,caller_id,start_date,end_date,duration,impact_duration');

define('PORTAL_ATTCODE_LOG', 'public_log');
define('PORTAL_ATTCODE_COMMENT', 'user_comment');
define('PORTAL_REQUEST_FORM_ATTRIBUTES', 'title,description,impact,urgency,public_log');

define('PORTAL_ATTCODE_TYPE', 'request_type'); // optional if the type has to be set
define('PORTAL_SET_TYPE_FROM', 'request_type'); // The attribute to get the type from (Subcategory)

define('PORTAL_TICKETS_LIST_ZLIST', 'finalclass,title,start_date,status,servicesubcategory_id,priority,caller_id');
define('PORTAL_TICKETS_SEARCH_CRITERIA','ref,start_date,close_date,service_id,caller_id');
define('PORTAL_TICKETS_CLOSED_ZLIST', 'title,start_date,close_date,servicesubcategory_id');
// json encoded lists for the portal...
define('PORTAL_TICKET_DETAILS_ZLIST', '{"col:left":["ref","caller_id","servicesubcategory_id","title","description","solution"],"col:right":["status","priority","start_date","resolution_date","last_update","agent_id"]}');
?>
