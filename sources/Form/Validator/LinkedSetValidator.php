<?php

// Copyright (C) 2010-2024 Combodo SAS
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

namespace Combodo\iTop\Form\Validator;

use Dict;
use ormLinkSet;
use utils;

/**
 * Description of LinkedSetValidator
 *
 * @since 3.1.0 N°6414
 */
class LinkedSetValidator extends AbstractRegexpValidator
{
    public const VALIDATOR_NAME = 'linkedset_validator';
    private $aAttributesToDisplayCodes;

    public function __construct($aAttributesToDisplayCodes)
    {
        $this->aAttributesToDisplayCodes = $aAttributesToDisplayCodes;

        parent::__construct();
    }

    public function Validate($value): array
    {
        $aErrorMessages = [];

        /** @var ormLinkSet $oSet */
        $oSet = $value;

        // validate each links...
        /** @var \DBObject $oItem */
        foreach ($oSet as $oItem) {
            $aChanges = $oItem->ListChanges();
            foreach ($aChanges as $sAttCode => $AttValue) {
                if (!in_array($sAttCode, $this->aAttributesToDisplayCodes)) {
                    continue;
                }
                $res = $oItem->CheckValue($sAttCode);
                if ($res !== true) {
                    $sAttLabel = $oItem->GetLabel($sAttCode);
                    $sItem = utils::IsNullOrEmptyString($oItem->Get('friendlyname'))
                        ? Dict::S('UI:Links:NewItem')
                        : $oItem->Get('friendlyname');
                    $sIssue = Dict::Format('Core:CheckValueError', $sAttLabel, $sAttCode, $res);
                    $aErrorMessages[] = '<b>' . $sItem . ' : </b>' . $sIssue;
                }
            }
        }

        $oSet->Rewind();

        return $aErrorMessages;
    }
}
