<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author Vladimir Kunin <v.b.kunin@gmail.com>
 *
 */
Dict::Add('RU RU', 'Russian', 'Русский', [
	'Menu:DataFeatureRemovalMenu' => 'Управление расширениями',
	'combodo-data-feature-removal/Operation:Main/Title' => 'Управление расширениями',
	'DataFeatureRemoval:Main:Title' => 'Управление расширениями',
	'DataFeatureRemoval:Main:SubTitle' => 'Включение и отключение расширений, установленных в вашем iTop',
	'DataFeatureRemoval:Failure:Title' => 'Ошибки пробного удаления расширений',
	'DataFeatureRemoval:Helper:Title' => 'Проверьте, есть ли данные или зависимости, мешающие добавить/удалить расширение.',
	'DataFeatureRemoval:Features:Title' => 'Расширения',
	'DataFeatureRemoval:Result:Title' => 'Запрошено изменение',
	'DataFeatureRemoval:NoResult:Title' => 'Изменений не запрошено',
	'DataFeatureRemoval:Execution:Title' => 'Выполнения удаления',
	'DataFeatureRemoval:Analysis:Title' => 'Результат анализа',
	'DataFeatureRemoval:Analysis:Subtitle' => 'Просмотрите все элементы, требующие внимания',
	'DataFeatureRemoval:Analysis:SubTitle' => 'Элементов для очистки перед продолжением: %1$s',
	'DataFeatureRemoval:DeletionPlan:Title' => 'План удаления данных',
	'DataFeatureRemoval:DeletionPlan:SubTitle' => 'Строк для очистки перед продолжением: %1$s',
	'DataFeatureRemoval:DoDeletion:Title' => 'Выполнить удаление',
	'DataFeatureRemoval:DoDeletion:SubTitle' => 'Удалить все записи из базы данных',
	'DataFeatureRemoval:DeletionPlan:Error:Issues' => 'Некоторые объекты нужно удалить вручную перед очисткой',
	'DataFeatureRemoval:Table:Analysis:ClassName' => 'Элемент для удаления',
	'DataFeatureRemoval:Table:Analysis:FeatureName' => 'Название расширения',
	'DataFeatureRemoval:Table:Analysis:Module' => 'Название модуля',
	'DataFeatureRemoval:Table:Analysis:Occurrence' => 'Количество',
	'DataFeatureRemoval:CleanupComplete:Title' => 'Всё чисто.',
	'DataFeatureRemoval:CompilComplete' => 'Компиляция выполнена успешно. Очистка не требуется. Можно переходить к установке.',
	'DataFeatureRemoval:Compile:InProgress' => 'Идёт компиляция...',
	'DataFeatureRemoval:Compile:Success' => 'Компиляция выполнена успешно',
	'DataFeatureRemoval:Compile:Error' => 'Ошибка компиляции',
	'DataFeatureRemoval:RunAudit:InProgress' => 'Идёт анализ...',
	'DataFeatureRemoval:RunAudit:Success' => 'Анализ завершён',
	'DataFeatureRemoval:RunAudit:Error' => 'Ошибка при анализе',
	'UI:Button:Analyze' => 'Анализировать',
	'UI:Button:ModifyChoices' => 'Изменить выбор',
	'UI:Button:AnalyzeAndSetup' => 'Анализировать и перейти к установке',
	'UI:Button:PlanDeletion' => 'Продолжить удаление',
	'UI:Button:DoDeletion' => 'Продолжить удаление',
	'UI:Button:BackToMain' => 'Изменить выбор',
	'UI:Button:Setup' => 'Запустить установку',
	'UI:Action:ForceUninstall' => 'Принудительно удалить',
	'UI:Action:MoreInfo' => 'Подробнее',
	'DataFeatureRemoval:Table:Empty' => 'Нет данных для удаления',
	'DataFeatureRemoval:Column:Class' => 'Класс',
	'DataFeatureRemoval:Column:DeleteCount' => 'Записей к удалению',
	'DataFeatureRemoval:Column:UpdateCount' => 'Записей к обновлению',
	'DataFeatureRemoval:Column:IssueCount' => 'Найдено проблем, мешающих автоматической очистке',
	'DataFeatureRemoval:Column:DeletedCount' => 'Удалено записей',
	'DataFeatureRemoval:Column:UpdatedCount' => 'Обновлено записей',
]);
