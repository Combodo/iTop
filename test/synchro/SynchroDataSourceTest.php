<?php

namespace Combodo\iTop\Test\UnitTest\Synchro;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use CMDBSource;
use MetaModel;
use SynchroExecution;

class SynchroDataSourceTest extends ItopDataTestCase
{
	// Need database COMMIT in order to create the FULLTEXT INDEX of MySQL
	const USE_TRANSACTION = false;
        
        protected $sSynchroTrace = 'none';


        public function testSynchroReplicaStatus()
	{
                $aParams = array(
                    'name' => 'Test synchro replica status '.time(),
                    'description' => 'unit test - created automatically',
                    'status' => 'production',
                    'scope_class' => 'OSFamily',
                    'scope_restriction' => '',
                    'full_load_periodicity' => '10',
                    'delete_policy' => 'update',
                    'delete_policy_update' => 'name:test_obsolescence'
                );
                
		// Create the data source
		$oSynchroDataSource = $this->createObject('SynchroDataSource', $aParams);
                
                //Create a replica from trigger
                $sTable = $oSynchroDataSource->GetDataTable();
	
                $sSQL = "INSERT INTO `$sTable` (`primary_key`, `name`) VALUES ('test', 'test');";
                CMDBSource::Query($sSQL);
                
                $oSynchroReplica = MetaModel::GetObjectByColumn('SynchroReplica', 'sync_source_id', $oSynchroDataSource->GetKey());
                $this->assertEquals('new', $oSynchroReplica->Get('status'));
                
                //wait for the full load periodicity to be passed
                sleep($aParams['full_load_periodicity'] + 5);
                
                //Remove log for this synchro
                $oConfig = MetaModel::GetConfig();
                $this->sSynchroTrace = $oConfig->Get('synchro_trace');
                $oConfig->Set('synchro_trace', 'none');
                MetaModel::LoadConfig($oConfig);
                
                //Launch synchro execution
                $oSynchroExec = new SynchroExecution($oSynchroDataSource);
                $oSynchroLog = $oSynchroExec->Process();
                $this->assertEquals('completed', $oSynchroLog->Get('status'));
                
                $this->ReloadObject($oSynchroReplica);
                $this->assertEquals('obsolete', $oSynchroReplica->Get('status'));
	
                //Update replica from trigger
                $sSQL = "UPDATE `$sTable` SET `name` = 'test_2' WHERE `primary_key` = 'test';";
                CMDBSource::Query($sSQL);
                
                $this->ReloadObject($oSynchroReplica);
                $this->assertEquals('new', $oSynchroReplica->Get('status'));
                
                //Launch synchro execution
                $oSynchroExec = new SynchroExecution($oSynchroDataSource);
                $oSynchroLog = $oSynchroExec->Process();
                $this->assertEquals('completed', $oSynchroLog->Get('status'));
	}
        
        protected function tearDown() {
            //Reset synchro_trace
            $oConfig = MetaModel::GetConfig();
            $oConfig->Set('synchro_trace', $this->sSynchroTrace);
            MetaModel::LoadConfig($oConfig);
            
            parent::tearDown();
        }
}