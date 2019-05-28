<?php
/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 28/02/19
 * Time: 16:59
 */

namespace Combodo\iTop\Portal\VariableAccessor;


abstract class AbstractVariableAccessor
{
    /**
     * @var string
     */
    private $mStoredVariable;

    /**
     * @param string $mVariableToStore
     */
    public function __construct($mVariableToStore)
    {

        $this->mStoredVariable = $mVariableToStore;
    }
    
    public function getVariable()
    {
        return $this->mStoredVariable;
    }
}