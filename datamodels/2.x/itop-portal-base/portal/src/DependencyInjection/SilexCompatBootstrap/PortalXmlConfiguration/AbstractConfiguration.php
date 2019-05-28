<?php
/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 24/01/19
 * Time: 16:59
 */

namespace Combodo\iTop\Portal\DependencyInjection\SilexCompatBootstrap\PortalXmlConfiguration;


class AbstractConfiguration
{
    /**
     * @var \ModuleDesign
     */
    private $moduleDesign;

    public function __construct(\ModuleDesign $moduleDesign)
    {
        $this->moduleDesign = $moduleDesign;
    }

    /**
     * @return \ModuleDesign
     */
    public function getModuleDesign()
    {
        return $this->moduleDesign;
    }

}