<?php
/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 28/02/19
 * Time: 16:59
 */

namespace Combodo\iTop\Portal\VariableAccessor;


abstract class AbstractStringVariableAccessor extends AbstractVariableAccessor
{

    
    public function __toString()
    {
        return $this->getVariable();
    }
}