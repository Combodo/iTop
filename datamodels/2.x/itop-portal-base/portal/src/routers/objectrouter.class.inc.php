<?php

// Copyright (C) 2010-2017 Combodo SARL
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

namespace Combodo\iTop\Portal\Router;

use Silex\Application;

class ObjectRouter extends AbstractRouter
{
	static $aRoutes = array(
		array('pattern' => '/object/create/{sObjectClass}',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\ObjectController::CreateAction',
			'bind' => 'p_object_create'
		),
		array('pattern' => '/object/create-from-factory/{sObjectClass}/{sObjectId}/{sEncodedMethodName}',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\ObjectController::CreateFromFactoryAction',
			'bind' => 'p_object_create_from_factory'
		),
		array('pattern' => '/object/edit/{sObjectClass}/{sObjectId}',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\ObjectController::EditAction',
			'bind' => 'p_object_edit'
		),
		array('pattern' => '/object/view/{sObjectClass}/{sObjectId}',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\ObjectController::ViewAction',
			'bind' => 'p_object_view'
		),
		array('pattern' => '/object/apply-stimulus/{sStimulusCode}/{sObjectClass}/{sObjectId}',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\ObjectController::ApplyStimulusAction',
			'bind' => 'p_object_apply_stimulus'
		),
		array('pattern' => '/object/search',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\ObjectController::SearchRegularAction',
			'bind' => 'p_object_search_regular'
		),
		array('pattern' => '/object/search/from-attribute/{sTargetAttCode}/{sHostObjectClass}/{sHostObjectId}',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\ObjectController::SearchFromAttributeAction',
			'bind' => 'p_object_search_from_attribute',
			'values' => array(
				'sHostObjectClass' => null,
				'sHostObjectId' => null
			)
		),
		array('pattern' => '/object/search/autocomplete/{sTargetAttCode}/{sHostObjectClass}/{sHostObjectId}',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\ObjectController::SearchAutocompleteAction',
			'bind' => 'p_object_search_autocomplete',
			'values' => array(
				'sHostObjectClass' => null,
				'sHostObjectId' => null
			)
		),
		array('pattern' => '/object/search/hierarchy/{sTargetAttCode}/{sHostObjectClass}/{sHostObjectId}',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\ObjectController::SearchHierarchyAction',
			'bind' => 'p_object_search_hierarchy',
			'values' => array(
				'sHostObjectClass' => null,
				'sHostObjectId' => null
			)
		),
		array('pattern' => '/object/search/{sMode}/{sTargetAttCode}/{sHostObjectClass}/{sHostObjectId}',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\ObjectController::SearchAction',
			'bind' => 'p_object_search_generic',
			'values' => array(
				'sMode' => '-sMode-',
				'sHostObjectClass' => null,
				'sHostObjectId' => null
			)
		),
		array('pattern' => '/object/get-informations/json',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\ObjectController::GetInformationsAsJsonAction',
			'bind' => 'p_object_get_informations_json',
		),
        array('pattern' => '/object/document/display/{sObjectClass}/{sObjectId}/{sObjectField}',
            'callback' => 'Combodo\\iTop\\Portal\\Controller\\ObjectController::DocumentAction',
            'bind' => 'p_object_document_display',
            'values' => array(
                'sOperation' => 'display'
            )
        ),
        array('pattern' => '/object/document/download/{sObjectClass}/{sObjectId}/{sObjectField}',
            'callback' => 'Combodo\\iTop\\Portal\\Controller\\ObjectController::DocumentAction',
            'bind' => 'p_object_document_download',
            'values' => array(
                'sOperation' => 'download'
            )
        ),
        array('pattern' => '/object/attachment/add',
            'callback' => 'Combodo\\iTop\\Portal\\Controller\\ObjectController::AttachmentAction',
            'bind' => 'p_object_attachment_add'
        ),
        array('pattern' => '/object/attachment/download/{sAttachmentId}',
            'callback' => 'Combodo\\iTop\\Portal\\Controller\\ObjectController::AttachmentAction',
            'bind' => 'p_object_attachment_download',
            'values' => array(
                'sOperation' => 'download'
            )
        ),
	);

}

?>