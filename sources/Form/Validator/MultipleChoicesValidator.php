<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Form\Validator;

use utils;

class MultipleChoicesValidator extends AbstractValidator
{
    public const VALIDATOR_NAME = 'multiple_choices_validator';

    /** @var array List of possible choices */
    private array $aChoices;

    public function __construct(array $aChoices)
    {
        parent::__construct();
        $this->aChoices = $aChoices;
    }

    /**
     * @param mixed $value Warning can either be an array (if multiple values are allowed in the field) or a primitive : {@see \Combodo\iTop\Form\Field\MultipleChoicesField::GetCurrentValue()}
     *
     * @return array|string[]
     */
    public function Validate($value): array
    {
        $aErrorMessages = [];
        if (false === is_array($value)) {
            $this->CheckValueAgainstChoices($value, $aErrorMessages);

            return $aErrorMessages;
        }

        if (count($value) === 0) {
            return [];
        }

        /** @noinspection PhpUnusedLocalVariableInspection */
        foreach ($value as $sCode => $valueItem) {
            if (utils::IsNullOrEmptyString($valueItem)) {
                continue;
            }
            $this->CheckValueAgainstChoices($valueItem, $aErrorMessages);
        }

        return $aErrorMessages;
    }

    private function CheckValueAgainstChoices(string $sValue, array &$aErrorMessages): void
    {
        if (false === array_key_exists($sValue, $this->aChoices)) {
	        $aErrorMessages[] = "Value ({$sValue}) is not part of the field possible values list";
        }
    }
}