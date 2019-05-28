<?php
/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 24/01/19
 * Time: 16:52
 */

namespace Combodo\iTop\Portal\DependencyInjection\SilexCompatBootstrap\PortalXmlConfiguration;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Exception;
use utils;

class Lists extends AbstractConfiguration
{
    public function process(ContainerBuilder $container)
    {
        $iDefaultItemRank = 0;
        $aClassesLists = array();

        // Parsing XML file
        // - Each classes
        foreach ($this->getModuleDesign()->GetNodes('/module_design/classes/class') as $oClassNode)
        {
            $aClassLists = array();
            $sClassId = $oClassNode->getAttribute('id');
            if ($sClassId === null)
            {
                throw new DOMFormatException('Class tag must have an id attribute', null, null, $oClassNode);
            }

            // - Each lists
            foreach ($oClassNode->GetNodes('./lists/list') as $oListNode)
            {
                $aListItems = array();
                $sListId = $oListNode->getAttribute('id');
                if ($sListId === null)
                {
                    throw new DOMFormatException('List tag of "'.$sClassId.'" class must have an id attribute', null,
                        null, $oListNode);
                }

                // - Each items
                foreach ($oListNode->GetNodes('./items/item') as $oItemNode)
                {
                    $sItemId = $oItemNode->getAttribute('id');
                    if ($sItemId === null)
                    {
                        throw new DOMFormatException('Item tag of "'.$sItemId.'" list must have an id attribute', null,
                            null, $oItemNode);
                    }

                    $aItem = array(
                        'att_code' => $sItemId,
                        'rank' => $iDefaultItemRank
                    );

                    $oRankNode = $oItemNode->GetOptionalElement('rank');
                    if ($oRankNode !== null)
                    {
                        $aItem['rank'] = $oRankNode->GetText($iDefaultItemRank);
                    }

                    $aListItems[] = $aItem;
                }
                // - Sorting list items by rank
                usort($aListItems, function ($a, $b) {
                    return $a['rank'] > $b['rank'];
                });
                $aClassLists[$sListId] = $aListItems;
            }

            // - Adding class only if it has at least one list
            if (!empty($aClassLists))
            {
                $aClassesLists[$sClassId] = $aClassLists;
            }
        }
        $aPortalConf = $container->getParameter('combodo.portal.instance.conf');
        $aPortalConf['lists']  = $aClassLists;
        $container->setParameter('combodo.portal.instance.conf', $aPortalConf);
    }


}