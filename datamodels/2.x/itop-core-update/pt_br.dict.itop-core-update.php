<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2022 Combodo SARL
 * @license	http://opensource.org/licenses/AGPL-3.0
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
 */
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'iTopUpdate:UI:PageTitle' => 'Atualização do Aplicativo',
  'itop-core-update:UI:SelectUpdateFile' => 'Atualização do Aplicativo',
  'itop-core-update:UI:ConfirmUpdate' => 'Atualização do Aplicativo',
  'itop-core-update:UI:UpdateCoreFiles' => 'Atualização do Aplicativo',
	'iTopUpdate:UI:MaintenanceModeActive' => 'O aplicativo está atualmente em manutenção, nenhum usuário pode acessar o aplicativo. Você precisa executar o instalador ou restaurar o arquivo do aplicativo para retornar ao modo normal.',
	'itop-core-update:UI:UpdateDone' => 'Atualização do Aplicativo',

	'itop-core-update/Operation:SelectUpdateFile/Title' => 'Atualização do Aplicativo',
	'itop-core-update/Operation:ConfirmUpdate/Title' => 'Confirmar Atualização do Aplicativo',
	'itop-core-update/Operation:UpdateCoreFiles/Title' => 'Atualizando o Aplicativo',
	'itop-core-update/Operation:UpdateDone/Title' => 'Atualização do Aplicativo Concluída',

	'iTopUpdate:UI:SelectUpdateFile' => 'Carregue um arquivo de atualização',
	'iTopUpdate:UI:CheckUpdate' => 'Verificar arquivo de atualização',
	'iTopUpdate:UI:ConfirmInstallFile' => 'Você está prestes a instalar o %1$s',
	'iTopUpdate:UI:DoUpdate' => 'Atualizar',
	'iTopUpdate:UI:CurrentVersion' => 'Versão instalada atual',
	'iTopUpdate:UI:NewVersion' => 'Nova versão',
  'iTopUpdate:UI:Back' => 'Voltar',
  'iTopUpdate:UI:Cancel' => 'Cancelar',
  'iTopUpdate:UI:Continue' => 'Continuar',
	'iTopUpdate:UI:RunSetup' => 'Executar instalação',
  'iTopUpdate:UI:WithDBBackup' => 'Backup do banco de dados',
  'iTopUpdate:UI:WithFilesBackup' => 'Fazer backup dos arquivos do aplicativo',
  'iTopUpdate:UI:WithoutBackup' => 'Não fazer backup',
  'iTopUpdate:UI:Backup' => 'Backup gerado antes da atualização',
	'iTopUpdate:UI:DoFilesArchive' => 'Arquivar arquivos do aplicativo',
	'iTopUpdate:UI:UploadArchive' => 'Selecione um pacote para carregar',
	'iTopUpdate:UI:ServerFile' => 'Caminho de um pacote existente no servidor',
	'iTopUpdate:UI:WarningReadOnlyDuringUpdate' => 'Durante a atualização, o aplicativo estará em modo somente-leitura',

  'iTopUpdate:UI:Status' => 'Status',
  'iTopUpdate:UI:Action' => 'Atualizar',
  'iTopUpdate:UI:History' => 'Histórico de Versões',
  'iTopUpdate:UI:Progress' => 'Progresso da atualização',

  'iTopUpdate:UI:DoBackup:Label' => 'Fazer backup de arquivos e banco de dados',
  'iTopUpdate:UI:DoBackup:Warning' => 'Fazer backup não é recomendado devido ao espaço limitado disponível em disco',

  'iTopUpdate:UI:DiskFreeSpace' => 'Espaço livre em disco',
  'iTopUpdate:UI:ItopDiskSpace' => 'Espaço em disco do iTop',
  'iTopUpdate:UI:DBDiskSpace' => 'Espaço em disco do banco de dados',
	'iTopUpdate:UI:FileUploadMaxSize' => 'Tamanho máximo de upload de arquivo',

	'iTopUpdate:UI:PostMaxSize' => 'PHP ini value post_max_size: %1$s~~',
	'iTopUpdate:UI:UploadMaxFileSize' => 'PHP ini value upload_max_filesize: %1$s~~',

  'iTopUpdate:UI:CanCoreUpdate:Loading' => 'Verificando arquivos do aplicativo',
  'iTopUpdate:UI:CanCoreUpdate:Error' => 'A verificação de arquivos do aplicativo falhou (%1$s)~~',
  'iTopUpdate:UI:CanCoreUpdate:ErrorFileNotExist' => 'A verificação de arquivos do aplicativo falhou (Arquivo %1$s não existe)~~',
  'iTopUpdate:UI:CanCoreUpdate:Failed' => 'A verificação de arquivos do sistema falhou',
  'iTopUpdate:UI:CanCoreUpdate:Yes' => 'O aplicativo pode ser atualizado',
	'iTopUpdate:UI:CanCoreUpdate:No' => 'O aplicativo não pode ser atualizado: %1$s~~',
	'iTopUpdate:UI:CanCoreUpdate:Warning' => 'Aviso: a atualização do aplicativo pode falhar: %1$s~~',
	'iTopUpdate:UI:CannotUpdateUseSetup' => '<b>Alguns arquivos modificados foram detectados</b>, uma atualização parcial não pode ser executada.</br>Siga as instruções (<a target="_blank" href="%2$s">link</a>) de forma a atualizar manualmente o seu iTop. Você deve executar o Setup (<a href="%1$s">link</a>) para atualizar o aplicativo.',
	'iTopUpdate:UI:CheckInProgress'=>'Por favor, aguarde a verificação de integridade',

	// Setup Messages
  'iTopUpdate:UI:SetupMessage:Ready' => 'Pronto para iniciar',
	'iTopUpdate:UI:SetupMessage:EnterMaintenance' => 'Entrando no modo de manutenção',
	'iTopUpdate:UI:SetupMessage:Backup' => 'Backup do banco de dados',
	'iTopUpdate:UI:SetupMessage:FilesArchive' => 'Arquivar arquivos do aplicativo',
  'iTopUpdate:UI:SetupMessage:CopyFiles' => 'Copiar novas versões de arquivos',
	'iTopUpdate:UI:SetupMessage:CheckCompile' => 'Verificar atualização do aplicativo',
	'iTopUpdate:UI:SetupMessage:Compile' => 'Atualizar aplicativo e banco de dados',
	'iTopUpdate:UI:SetupMessage:UpdateDatabase' => 'Atualizar banco de dados',
	'iTopUpdate:UI:SetupMessage:ExitMaintenance' => 'Saindo do modo de manutenção',
  'iTopUpdate:UI:SetupMessage:UpdateDone' => 'Atualização concluída',

	// Errors
	'iTopUpdate:Error:MissingFunction' => 'Impossível iniciar a atualização, função ausente',
	'iTopUpdate:Error:MissingFile' => 'Arquivo ausente: %1$s~~',
	'iTopUpdate:Error:CorruptedFile' => 'Arquivo %1$s está corrompido',
  'iTopUpdate:Error:BadFileFormat' => 'Arquivo de atualização não é um arquivo zip',
  'iTopUpdate:Error:BadFileContent' => 'Arquivo de atualização não é um arquivo da aplicação',
  'iTopUpdate:Error:BadItopProduct' => 'Arquivo de atualização não é compatível com seu aplicativo',
	'iTopUpdate:Error:Copy' => 'Erro, não foi possível copiar \'%1$s\' para \'%2$s\'~~',
  'iTopUpdate:Error:FileNotFound' => 'Arquivo não encontrado',
  'iTopUpdate:Error:NoFile' => 'Arquivo não provido',
	'iTopUpdate:Error:InvalidToken' => 'Token inválido',
	'iTopUpdate:Error:UpdateFailed' => 'Atualização falhou',
	'iTopUpdate:Error:FileUploadMaxSizeTooSmall' => 'O tamanho máximo de upload parece ser insuficiente para a atualização. Por favor, altere a configuração do PHP.',

	'iTopUpdate:UI:RestoreArchive' => 'Você pode restaurar seu aplicativo à partir do arquivo \'%1$s\'~~',
	'iTopUpdate:UI:RestoreBackup' => 'Você pode restaurar o banco de dados à partir de \'%1$s\'',
	'iTopUpdate:UI:UpdateDone' => 'Atualização bem-sucedida',
	'Menu:iTopUpdate' => 'Atualização do Aplicativo',
	'Menu:iTopUpdate+' => 'Atualização do Aplicativo',

  // Missing itop entries
  'Class:ModuleInstallation/Attribute:installed' => 'Instalado em',
  'Class:ModuleInstallation/Attribute:name' => 'Nome',
  'Class:ModuleInstallation/Attribute:version' => 'Versão',
  'Class:ModuleInstallation/Attribute:comment' => 'Comentários',
));
