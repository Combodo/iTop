<?php

// Copyright (C) 2010-2016 Combodo SARL
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

/**
 * This is a "manual autoloader" for now that is meant to evolve into a real autoloader.
 */
require_once APPROOT . 'sources/form/form.class.inc.php';
require_once APPROOT . 'sources/form/formmanager.class.inc.php';
require_once APPROOT . 'sources/form/field/field.class.inc.php';
require_once APPROOT . 'sources/form/field/textfield.class.inc.php';
require_once APPROOT . 'sources/form/field/hiddenfield.class.inc.php';
require_once APPROOT . 'sources/form/field/stringfield.class.inc.php';
require_once APPROOT . 'sources/form/field/textareafield.class.inc.php';
require_once APPROOT . 'sources/form/field/multiplechoicesfield.class.inc.php';
require_once APPROOT . 'sources/form/field/selectfield.class.inc.php';
require_once APPROOT . 'sources/form/field/checkboxfield.class.inc.php';
require_once APPROOT . 'sources/form/field/radiofield.class.inc.php';
require_once APPROOT . 'sources/form/validator/validator.class.inc.php';
require_once APPROOT . 'sources/form/validator/mandatoryvalidator.class.inc.php';
require_once APPROOT . 'sources/form/validator/notemptyvalidator.class.inc.php';
require_once APPROOT . 'sources/form/validator/integervalidator.class.inc.php';
require_once APPROOT . 'sources/renderer/formrenderer.class.inc.php';
require_once APPROOT . 'sources/renderer/fieldrenderer.class.inc.php';
require_once APPROOT . 'sources/renderer/renderingoutput.class.inc.php';
require_once APPROOT . 'sources/renderer/bootstrap/bsformrenderer.class.inc.php';
require_once APPROOT . 'sources/renderer/bootstrap/fieldrenderer/bssimplefieldrenderer.class.inc.php';
