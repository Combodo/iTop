<?php

/**
 * Module combodo-flow-map
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
/**
 * @author Vladimir Kunin <v.b.kunin@gmail.com>
 *
 */

Dict::Add('RU RU', 'Russian', 'Русский', [

	'Relation:dataflows/Description'    => 'Потоки данных между КЕ',
	'Relation:dataflows/DownStream'     => 'Исходящие потоки...',
	'Relation:dataflows/DownStream+'    => 'Карта исходящих потоков от',
	'Relation:dataflows/UpStream'       => 'Входящие потоки...',
	'Relation:dataflows/UpStream+'      => 'Карта входящих потоков к',

	'Class:FunctionalCI/Attribute:dataflows' => 'Потоки данных',
	'Class:FunctionalCI/Attribute:dataflows+' => 'Потоки данных, для которых этот объект является источником или назначением',
	'FunctionalCI:DataFlow:Title' => 'Потоки данных',
	'FunctionalCI:DataFlow:Inbound' => 'Входящие потоки',
	'FunctionalCI:DataFlow:Outbound' => 'Исходящие потоки',

	'DataFlow:moreinfo' => 'Особенности потока',

	'Class:DataFlow' => 'Поток',
	'Class:DataFlow+' => 'Например, для потока приложения',
	'Class:DataFlow/ComplementaryName' => '%1$s - %2$s',
	'Class:DataFlow/Attribute:name' => 'Название',
	'Class:DataFlow/Attribute:name+' => 'Идентифицирует передаваемый поток данных',
	'Class:DataFlow/Attribute:source_id' => 'Источник',
	'Class:DataFlow/Attribute:source_id+' => 'КЕ-источник потока',
	'Class:DataFlow/Attribute:source_impact' => 'Источник влияет?',
	'Class:DataFlow/Attribute:source_impact+' => 'Влияет ли источник на поток?',
	'Class:DataFlow/Attribute:source_impact/Value:yes' => 'да',
	'Class:DataFlow/Attribute:source_impact/Value:yes+' => 'Если источник выходит из строя, поток нарушается',
	'Class:DataFlow/Attribute:source_impact/Value:no' => 'нет',
	'Class:DataFlow/Attribute:source_impact/Value:no+' => 'Если источник выходит из строя, поток не нарушается',
	'Class:DataFlow/Attribute:destination_id' => 'Назначение',
	'Class:DataFlow/Attribute:destination_id+' => 'КЕ-назначение потока',
	'Class:DataFlow/Attribute:destination_impact' => 'Назначение подвержено влиянию',
	'Class:DataFlow/Attribute:destination_impact+' => 'Подвержено ли назначение влиянию потока?',
	'Class:DataFlow/Attribute:destination_impact/Value:yes' => 'да',
	'Class:DataFlow/Attribute:destination_impact/Value:yes+' => 'Если поток останавливается, назначение подвержено влиянию',
	'Class:DataFlow/Attribute:destination_impact/Value:no' => 'нет',
	'Class:DataFlow/Attribute:destination_impact/Value:no+' => 'Если поток останавливается, назначение не подвержено влиянию',
	'Class:DataFlow/Attribute:dataflowtype_id' => 'Тип потока',
	'Class:DataFlow/Attribute:dataflowtype_id+' => 'Values defined in a typology of Data Flow Typel~~',
	'Class:DataFlow/Attribute:dataflowprotocol_id' => 'Протокол потока',
	'Class:DataFlow/Attribute:dataflowprotocol_id+' => 'Values defined in a typology of Data Flow Protocol~~',
	'Class:DataFlow/Attribute:documentation_url' => 'Ссылка на документацию',
	'Class:DataFlow/Attribute:documentation_url+' => 'Ссылка на документацию потока данных',
	'Class:DataFlow/Attribute:last_change_date' => 'Дата последнего изменения',
	'Class:DataFlow/Attribute:last_change_date+' => 'Дата последнего изменения программного обеспечения или конфигурации потока данных',
	'Class:DataFlow/Attribute:status' => 'Статус',
	'Class:DataFlow/Attribute:status+' => '',
	'Class:DataFlow/Attribute:status/Value:active' => 'активен',
	'Class:DataFlow/Attribute:status/Value:inactive' => 'неактивен',
	'Class:DataFlow/Attribute:execution_frequency' => 'Периодичность выполнения',
	'Class:DataFlow/Attribute:execution_frequency+' => 'Как часто выполняется поток данных',
	'Class:DataFlow/Attribute:execution_frequency/Value:realtime' => 'в реальном времени',
	'Class:DataFlow/Attribute:execution_frequency/Value:realtime+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:ondemand' => 'по запросу',
	'Class:DataFlow/Attribute:execution_frequency/Value:ondemand+' => 'по требованию, без расписания',
	'Class:DataFlow/Attribute:execution_frequency/Value:hourly' => 'ежечасно',
	'Class:DataFlow/Attribute:execution_frequency/Value:hourly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:daily' => 'ежедневно',
	'Class:DataFlow/Attribute:execution_frequency/Value:daily+'	=> '',
	'Class:DataFlow/Attribute:execution_frequency/Value:weekly' => 'еженедельно',
	'Class:DataFlow/Attribute:execution_frequency/Value:weekly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:monthly' => 'ежемесячно',
	'Class:DataFlow/Attribute:execution_frequency/Value:monthly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:yearly'	=> 'ежегодно',
	'Class:DataFlow/Attribute:execution_frequency/Value:yearly+' => '',
	'Class:DataFlow/Attribute:documents_list+' => 'Например: технические спецификации, регламенты и т. д.',
	'Class:DataFlow/Attribute:contacts_list+' => 'Например: владелец потока, техническая поддержка и т. д.',
	'Class:DataFlow/Error:CheckSource' => 'Источником потока данных не может быть другой поток данных. Выберите другую КЕ-источник, отличную от %1$s',
	'Class:DataFlow/Error:CheckDestination' => 'Назначением потока данных не может быть другой поток данных. Выберите другую КЕ-назначение, отличную от %1$s',

	'Class:DataFlowType' => 'Тип потока данных',
	'Class:DataFlowType+' => 'Типология потоков данных',

	'Class:DataFlowProtocol' => 'Протокол потока данных',
	'Class:DataFlowProtocol+' => 'Типология протоколов потоков данных',

]);
