<?php
/**
 * Copyright (C) 2013-2019 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 *
 *
 */

/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 24/01/19
 * Time: 17:28
 */

namespace Combodo\iTop\Portal\Brick;

use UserRights;
use ModuleDesign;
use Combodo\iTop\Portal\Helper\ApplicationHelper;

/**
 * Class BrickCollection
 *
 * @package Combodo\iTop\Portal\Brick
 * @since 2.7.0
 */
class BrickCollection
{
    /** @var array|null Lazily computed */
    private $aAllowedBricksData;
    /** @var \ModuleDesign */
    private $oModuleDesign;

    public function __construct(ModuleDesign $oModuleDesign)
    {
        $this->oModuleDesign = $oModuleDesign;
    }

    public function __call($method, $arguments)
    {
        return $this->GetBrickProperty($method);
    }

    /**
     * @param string $sId
     *
     * @return mixed
     * @throws PropertyNotFoundException
     */
    private function GetBrickProperty($sId)
    {
        if (array_key_exists($sId, $this->getBricks())) {
            return $this->getBricks()[$sId];
        }

        throw new PropertyNotFoundException(
            "The property '$sId' do not exists in BricksCollection with keys: ".array_keys($this->getBricks())
        );
    }

    public function getBricks()
    {
        if (! isset($this->aAllowedBricksData)) {
            $this->LazyLoad();
        }

        return $this->aAllowedBricksData;
    }

    public function getBrickById($id)
    {
        foreach ($this->getBricks()['bricks'] as $brick) {
            if ($brick->GetId() === $id)
            {
                return $brick;
            }
        }

        throw new BrickNotFoundException('Brick with id = "'.$id.'" was not found among loaded bricks.');
    }

    private function LazyLoad()
    {
        $aRawBrickList = $this->GetRawBrickList();

        $this->aAllowedBricksData = array(
            'bricks' => array(),
            'bricks_total_width' => 0,
            'bricks_home_count' => 0,
            'bricks_navigation_menu_count' => 0
        );

        foreach ($aRawBrickList as $oBrick) {
            ApplicationHelper::LoadBrickSecurity($oBrick);

            if ($oBrick->GetActive() && $oBrick->IsGrantedForProfiles(UserRights::ListProfiles()))
            {
                $this->aAllowedBricksData['bricks'][] = $oBrick;
                $this->aAllowedBricksData['bricks_total_width'] += $oBrick->GetWidth();
                if ($oBrick->GetVisibleHome())
                {
                    $this->aAllowedBricksData['bricks_home_count']++;
                }
                if ($oBrick->GetVisibleNavigationMenu())
                {
                    $this->aAllowedBricksData['bricks_navigation_menu_count']++;
                }
            }
        }

        // - Sorting bricks by rank
        $this->aAllowedBricksData['bricks_ordering'] = array();
        //   - Home
        $this->aAllowedBricksData['bricks_ordering']['home'] = $this->aAllowedBricksData['bricks'];
        usort($this->aAllowedBricksData['bricks_ordering']['home'], function (PortalBrick $a, PortalBrick $b) {
            return $a->GetRankHome() > $b->GetRankHome();
        });
        //    - Navigation menu
        $this->aAllowedBricksData['bricks_ordering']['navigation_menu'] = $this->aAllowedBricksData['bricks'];
        usort($this->aAllowedBricksData['bricks_ordering']['navigation_menu'], function (PortalBrick $a, PortalBrick $b) {
            return $a->GetRankNavigationMenu() > $b->GetRankNavigationMenu();
        });
    }

    private function GetRawBrickList()
    {
        $aBricks = array();
        foreach ($this->oModuleDesign->GetNodes('/module_design/bricks/brick') as $oBrickNode)
        {
            $sBrickClass = $oBrickNode->getAttribute('xsi:type');
            try
            {
                if (class_exists($sBrickClass))
                {
                    /** @var \Combodo\iTop\Portal\Brick\PortalBrick $oBrick */
                    $oBrick = new $sBrickClass();
                    $oBrick->LoadFromXml($oBrickNode);

                    $aBricks[] = $oBrick;
                }
                else
                {
                    throw new DOMFormatException('Unknown brick class "'.$sBrickClass.'" from xsi:type attribute', null,
                        null, $oBrickNode);
                }
            }
            catch (DOMFormatException $e)
            {
                throw new Exception('Could not create brick ('.$sBrickClass.') from XML because of a DOM problem : '.$e->getMessage());
            }
            catch (Exception $e)
            {
                throw new Exception('Could not create brick ('.$sBrickClass.') from XML : '.$oBrickNode->Dump().' '.$e->getMessage());
            }
        }

        return $aBricks;
    }

}