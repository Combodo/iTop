<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Form\Validator;

use Combodo\iTop\Form\Helper\FieldHelper;
use DBSearch;
use utils;

class SelectObjectValidator extends AbstractValidator
{
    public const VALIDATOR_NAME = 'select_object_validator';

    /** @var \DBSearch $oSearch */
    private $oSearch;

    public function __construct(DBSearch $oSearch)
    {
        parent::__construct();

        $this->oSearch = $oSearch;
    }

    public function Validate($value): array
    {
        if (utils::IsNullOrEmptyString($value)) {
            return [];
        }
        if (($value === 0) || ($value === '0')) {
            return [];
        }

        $oSetForExistingCurrentValue = FieldHelper::GetObjectsSetFromSearchAndCurrentValueId($this->oSearch, $value);
        $iObjectsCount = $oSetForExistingCurrentValue->CountWithLimit(1);

        if ($iObjectsCount === 0) {
            return ["Value $value does not match the corresponding filter set"];
        }

        return [];
    }
}