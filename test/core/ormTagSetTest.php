<?php
/**
 * Copyright (c) 2010-2018 Combodo SARL
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
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 27/08/2018
 * Time: 17:26
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Exception;
use ormTagSet;

/**
 * Tests of the ormTagSet class
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */class ormTagSetTest extends ItopDataTestCase
{

    /**
     * @throws Exception
     */
    protected function setUp()
    {
        parent::setUp();
    }

    public function testGetTagDataClass()
    {
        $oTagSet = new ormTagSet('Ticket', 'tagfield');
        static::assertEquals($oTagSet->GetTagDataClass(), 'TagSetFieldDataFor_Ticket_tagfield');
    }

    public function testGetValue()
    {
        $this->CreateTagData('Ticket', 'tagfield', 'tag1', 'First');
        $this->CreateTagData('Ticket', 'tagfield', 'tag2', 'Second');

        $oTagSet = new ormTagSet('Ticket', 'tagfield');
        static::assertEquals($oTagSet->GetValue(), array());

        $oTagSet->AddTag('tag1');
        static::assertEquals($oTagSet->GetValue(), array('tag1'));

        $oTagSet->AddTag('tag2');
        static::assertEquals($oTagSet->GetValue(), array('tag1', 'tag2'));
    }

    public function testAddTag()
    {
        $this->CreateTagData('Ticket', 'tagfield', 'tag1', 'First');
        $this->CreateTagData('Ticket', 'tagfield', 'tag2', 'Second');

        $oTagSet = new ormTagSet('Ticket', 'tagfield');

        $oTagSet->AddTag('tag1');
        static::assertEquals($oTagSet->GetValue(), array('tag1'));

        $oTagSet->SetValue(array('tag1', 'tag2'));
        static::assertEquals($oTagSet->GetValue(), array('tag1', 'tag2'));

        $oTagSet->RemoveTag('tag1');
        static::assertEquals($oTagSet->GetValue(), array('tag2'));

        $oTagSet->AddTag('tag1');
        static::assertEquals($oTagSet->GetValue(), array('tag1', 'tag2'));
    }

    public function testEquals()
    {
        $this->CreateTagData('Ticket', 'tagfield', 'tag1', 'First');
        $this->CreateTagData('Ticket', 'tagfield', 'tag2', 'Second');

        $oTagSet1 = new ormTagSet('Ticket', 'tagfield');
        $oTagSet1->AddTag('tag1');
        static::assertTrue($oTagSet1->Equals($oTagSet1));

        $oTagSet2 = new ormTagSet('Ticket', 'tagfield');
        $oTagSet2->SetValue(array('tag1'));

        static::assertTrue($oTagSet1->Equals($oTagSet2));

        $oTagSet1->AddTag('tag2');
        static::assertFalse($oTagSet1->Equals($oTagSet2));
    }

    public function testSetValue()
    {
        $this->CreateTagData('Ticket', 'tagfield', 'tag1', 'First');
        $this->CreateTagData('Ticket', 'tagfield', 'tag2', 'Second');

        $oTagSet = new ormTagSet('Ticket', 'tagfield');

        $oTagSet->SetValue(array('tag1'));
        static::assertEquals($oTagSet->GetValue(), array('tag1'));

        $oTagSet->SetValue(array('tag1', 'tag2'));
        static::assertEquals($oTagSet->GetValue(), array('tag1', 'tag2'));

    }

    public function testRemoveTag()
    {
        $this->CreateTagData('Ticket', 'tagfield', 'tag1', 'First');
        $this->CreateTagData('Ticket', 'tagfield', 'tag2', 'Second');

        $oTagSet = new ormTagSet('Ticket', 'tagfield');
        $oTagSet->RemoveTag('tag_unknown');
        static::assertEquals($oTagSet->GetValue(), array());

        $oTagSet->SetValue(array('tag1'));
        $oTagSet->RemoveTag('tag_unknown');
        static::assertEquals($oTagSet->GetValue(), array('tag1'));

        $oTagSet->SetValue(array('tag1', 'tag2'));
        $oTagSet->RemoveTag('tag1');
        static::assertEquals($oTagSet->GetValue(), array('tag2'));

        $oTagSet->AddTag('tag1');
        static::assertEquals($oTagSet->GetValue(), array('tag1', 'tag2'));

        $oTagSet->RemoveTag('tag1');
        static::assertEquals($oTagSet->GetValue(), array('tag2'));

        $oTagSet->RemoveTag('tag1');
        static::assertEquals($oTagSet->GetValue(), array('tag2'));

        $oTagSet->RemoveTag('tag2');
        static::assertEquals($oTagSet->GetValue(), array());
    }
}
