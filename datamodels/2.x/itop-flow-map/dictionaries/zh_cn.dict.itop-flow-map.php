<?php

/**
 * Module combodo-flow-map
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('ZH CN', 'Chinese', '简体中文', [

	'Relation:dataflows/Description'    => 'DataFlows between CIs~~',
	'Relation:dataflows/DownStream'     => 'Outbound flows...',
	'Relation:dataflows/DownStream+'    => 'Outbound flows map from',
	'Relation:dataflows/UpStream'       => 'Inbound flows...',
	'Relation:dataflows/UpStream+'      => 'Inbound flows map to',

	'Class:FunctionalCI/Attribute:dataflows' => '数据流',
	'Class:FunctionalCI/Attribute:dataflows+' => '该对象作为源或目标的数据流',
	'FunctionalCI:DataFlow:Title' => '数据流',
	'FunctionalCI:DataFlow:Inbound' => '入站数据流',
	'FunctionalCI:DataFlow:Outbound' => '出站数据流',

	'DataFlow:moreinfo' => '数据流详情',

	'Class:DataFlow' => '数据流',
	'Class:DataFlow+' => 'For application flow for example~~',
	'Class:DataFlow/Name' => '%1$s',
	'Class:DataFlow/Attribute:name' => '名称',
	'Class:DataFlow/Attribute:name_id+' => '已传输的数据',
	'Class:DataFlow/Attribute:source_id' => '数据源',
	'Class:DataFlow/Attribute:source_id+' => '数据流的源头配置项',
	'Class:DataFlow/Attribute:source_impact' => '数据源影响?',
	'Class:DataFlow/Attribute:source_impact+' => '数据源是否影响数据流?',
	'Class:DataFlow/Attribute:source_impact/Value:yes' => '是',
	'Class:DataFlow/Attribute:source_impact/Value:yes+' => '如果数据源失效，数据流将受到影响',
	'Class:DataFlow/Attribute:source_impact/Value:no' => '否',
	'Class:DataFlow/Attribute:source_impact/Value:no+' => '如果数据源失效，数据流不受影响',
	'Class:DataFlow/Attribute:destination_id' => '目标',
	'Class:DataFlow/Attribute:destination_id+' => '数据流的目标配置项',
	'Class:DataFlow/Attribute:destination_impact' => '目标影响',
	'Class:DataFlow/Attribute:destination_impact+' => '目标是否受数据流影响?',
	'Class:DataFlow/Attribute:destination_impact/Value:yes' => '是',
	'Class:DataFlow/Attribute:destination_impact/Value:yes+' => '如果数据流停止，目标将受到影响',
	'Class:DataFlow/Attribute:destination_impact/Value:no' => '否',
	'Class:DataFlow/Attribute:destination_impact/Value:no+' => '如果数据流停止，目标不受影响',
	'Class:DataFlow/Attribute:dataflowtype_id' => '数据流类型',
	'Class:DataFlow/Attribute:dataflowtype_id+' => '数据流的分类',
	'Class:DataFlow/Attribute:status' => '状态',
	'Class:DataFlow/Attribute:status+' => '',
	'Class:DataFlow/Attribute:status/Value:active' => '启用',
	'Class:DataFlow/Attribute:status/Value:inactive' => '停用',
	'Class:DataFlow/Attribute:execution_frequency' => '执行频率',
	'Class:DataFlow/Attribute:execution_frequency+' => '数据流执行的频率',
	'Class:DataFlow/Attribute:execution_frequency/Value:realtime' => '实时',
	'Class:DataFlow/Attribute:execution_frequency/Value:realtime+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:ondemand' => '按需',
	'Class:DataFlow/Attribute:execution_frequency/Value:ondemand+' => '即时执行，不按计划进行',
	'Class:DataFlow/Attribute:execution_frequency/Value:hourly' => '每小时',
	'Class:DataFlow/Attribute:execution_frequency/Value:hourly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:daily' => '每天',
	'Class:DataFlow/Attribute:execution_frequency/Value:daily+'	=> '',
	'Class:DataFlow/Attribute:execution_frequency/Value:weekly' => '每周',
	'Class:DataFlow/Attribute:execution_frequency/Value:weekly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:monthly' => '每月',
	'Class:DataFlow/Attribute:execution_frequency/Value:monthly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:yearly'	=> '每年',
	'Class:DataFlow/Attribute:execution_frequency/Value:yearly+' => '',
	'Class:DataFlow/Attribute:documents_list+' => '例如: 技术规范, 操作手册等.',
	'Class:DataFlow/Attribute:contacts_list+' => '例如: 数据流所有者, 技术支持等.',
	'Class:DataFlow/Error:CheckSource' => 'The source of a data flow cannot be a data flow itself. Choose another source CI than %1$s~~',
	'Class:DataFlow/Error:CheckDestination' => 'The destination of a data flow cannot be a data flow itself. Choose another destination CI than %1$s~~',

	'Class:DataFlowType' => '数据流类型',
	'Class:DataFlowType+' => '数据流的分类',

/*
	'Class:DataFlow/Attribute:source_id_friendlyname'             => 'source_id_friendlyname',
	'Class:DataFlow/Attribute:source_id_friendlyname+'            => 'Full name',
	'Class:DataFlow/Attribute:source_id_finalclass_recall'        => 'source_id->CI sub-class',
	'Class:DataFlow/Attribute:source_id_finalclass_recall+'       => 'Name of the final class',
	'Class:DataFlow/Attribute:source_id_obsolescence_flag'        => 'source_id->Obsolete',
	'Class:DataFlow/Attribute:source_id_obsolescence_flag+'       => 'Computed dynamically on other attributes',
	'Class:DataFlow/Attribute:destination_id_friendlyname'        => 'destination_id_friendlyname',
	'Class:DataFlow/Attribute:destination_id_friendlyname+'       => 'Full name',
	'Class:DataFlow/Attribute:destination_id_finalclass_recall'   => 'destination_id->CI sub-class',
	'Class:DataFlow/Attribute:destination_id_finalclass_recall+'  => 'Name of the final class',
	'Class:DataFlow/Attribute:destination_id_obsolescence_flag'   => 'destination_id->Obsolete',
	'Class:DataFlow/Attribute:destination_id_obsolescence_flag+'  => 'Computed dynamically on other attributes',
*/
]);
