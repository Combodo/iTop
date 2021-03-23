<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2021 Combodo SARL
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
	'iTopUpdate:UI:PageTitle' => 'Atualização da Aplicação',
    'itop-core-update:UI:SelectUpdateFile' => 'Atualização da Aplicação',
    'itop-core-update:UI:ConfirmUpdate' => 'Atualização da Aplicação',
    'itop-core-update:UI:UpdateCoreFiles' => 'Atualização da Aplicação',
	'iTopUpdate:UI:MaintenanceModeActive' => 'A aplicação encontrasse em manutenção, nenhum usuário pode acessar a aplicação. Você precisa rodar o setup ou restaurar os arquivos da aplicação para voltar ao modo normal.',
	'itop-core-update:UI:UpdateDone' => 'Atualização da Aplicação',

	'itop-core-update/Operation:SelectUpdateFile/Title' => 'Atualização da Aplicação',
	'itop-core-update/Operation:ConfirmUpdate/Title' => 'Confirma Atualização da Aplicação',
	'itop-core-update/Operation:UpdateCoreFiles/Title' => 'Aplicação em atualização',
	'itop-core-update/Operation:UpdateDone/Title' => 'Atualização da Aplicação Finalizada',

	'iTopUpdate:UI:SelectUpdateFile' => 'Escolha o arquivo atualização para enviar',
	'iTopUpdate:UI:CheckUpdate' => 'Verificando arquivo de atualização',
	'iTopUpdate:UI:ConfirmInstallFile' => 'Você está para instalar %1$s',
	'iTopUpdate:UI:DoUpdate' => 'Atualizar',
	'iTopUpdate:UI:CurrentVersion' => 'Versão atual',
	'iTopUpdate:UI:NewVersion' => 'Nova versão',
    'iTopUpdate:UI:Back' => 'Voltar',
    'iTopUpdate:UI:Cancel' => 'Cancelar',
    'iTopUpdate:UI:Continue' => 'Continuar',
	'iTopUpdate:UI:RunSetup' => 'Rodar setup',
    'iTopUpdate:UI:WithDBBackup' => 'Backup da base de dados',
    'iTopUpdate:UI:WithFilesBackup' => 'Backup dos arquivos da aplicação',
    'iTopUpdate:UI:WithoutBackup' => 'Backup não planejado',
    'iTopUpdate:UI:Backup' => 'Backup gerado antes da atualização',
	'iTopUpdate:UI:DoFilesArchive' => 'Arquivar arquivos da aplicação',
	'iTopUpdate:UI:UploadArchive' => 'Escolha um pacote para enviar',
	'iTopUpdate:UI:ServerFile' => 'Caminho para o pacote já no servidor',
	'iTopUpdate:UI:WarningReadOnlyDuringUpdate' => 'Durante a atualização, a aplicação ficará em modo leitura.',

    'iTopUpdate:UI:Status' => 'Status',
    'iTopUpdate:UI:Action' => 'Atualizar',
    'iTopUpdate:UI:History' => 'Versões anteriores',
    'iTopUpdate:UI:Progress' => 'Progresso da atualização',

    'iTopUpdate:UI:DoBackup:Label' => 'Backup de arquivos e base de dados',
    'iTopUpdate:UI:DoBackup:Warning' => 'Backup não recomendado devido ao espaço em disco limitado',

    'iTopUpdate:UI:DiskFreeSpace' => 'Espaço em disco disponível',
    'iTopUpdate:UI:ItopDiskSpace' => 'Espaço em disco do iTop',
    'iTopUpdate:UI:DBDiskSpace' => 'Espaço em disco da base de dados',
	'iTopUpdate:UI:FileUploadMaxSize' => 'Tamanho máximo de envio de arquivos',

	'iTopUpdate:UI:PostMaxSize' => 'PHP ini post_max_size: %1$s',
	'iTopUpdate:UI:UploadMaxFileSize' => 'PHP ini upload_max_filesize: %1$s',

    'iTopUpdate:UI:CanCoreUpdate:Loading' => 'Verificando arquivos de sistema',
    'iTopUpdate:UI:CanCoreUpdate:Error' => 'Falha ao verificar arquivos de sistema (%1$s)',
    'iTopUpdate:UI:CanCoreUpdate:ErrorFileNotExist' => 'Falha ao verificar arquivos de sistema (arquivo não existe %1$s)',
    'iTopUpdate:UI:CanCoreUpdate:Failed' => 'Falha ao verificar arquivos de sistema',
    'iTopUpdate:UI:CanCoreUpdate:Yes' => 'Aplicação pode ser atualizada',
	'iTopUpdate:UI:CanCoreUpdate:No' => 'Aplicação não pode ser atualizada: %1$s',
	'iTopUpdate:UI:CanCoreUpdate:Warning' => 'Atenção: a atualização da aplicação pode falhar: %1$s',
	'iTopUpdate:UI:CannotUpdateUseSetup' => 'You must use the <a href="%1$s">setup</a> to update the application.<br />Some modified files were detected, a partial update cannot be executed.~~',

	// Setup Messages
    'iTopUpdate:UI:SetupMessage:Ready' => 'Pronto para começar',
	'iTopUpdate:UI:SetupMessage:EnterMaintenance' => 'Entrando em modo manutenção',
	'iTopUpdate:UI:SetupMessage:Backup' => 'Backup da base de dados',
	'iTopUpdate:UI:SetupMessage:FilesArchive' => 'Arquivar arquivos da aplicação',
    'iTopUpdate:UI:SetupMessage:CopyFiles' => 'Copiar nova versão de arquivos',
	'iTopUpdate:UI:SetupMessage:CheckCompile' => 'Verificar atualização da aplicação',
	'iTopUpdate:UI:SetupMessage:Compile' => 'Atualizar aplicação e base de dados',
	'iTopUpdate:UI:SetupMessage:UpdateDatabase' => 'Atualizar base de dados',
	'iTopUpdate:UI:SetupMessage:ExitMaintenance' => 'Saindo do modo manutenção',
    'iTopUpdate:UI:SetupMessage:UpdateDone' => 'Atualização completa',

	// Errors
	'iTopUpdate:Error:MissingFunction' => 'Impossível começar a atualização, função ausente',
	'iTopUpdate:Error:MissingFile' => 'Faltando arquivo: %1$s',
	'iTopUpdate:Error:CorruptedFile' => 'Arquivo %1$s está corrompido',
    'iTopUpdate:Error:BadFileFormat' => 'O arquivo de atualização não é um ZIP',
    'iTopUpdate:Error:BadFileContent' => 'O arquivo de atualização não é um arquivo da aplicação',
    'iTopUpdate:Error:BadItopProduct' => 'O arquivo de atualização não é compatível com a aplicação',
	'iTopUpdate:Error:Copy' => 'Erro, falha ao copiar de \'%1$s\' para \'%2$s\'',
    'iTopUpdate:Error:FileNotFound' => 'Arquivo não encontrado',
    'iTopUpdate:Error:NoFile' => 'Nenhum arquivo fornecido',
	'iTopUpdate:Error:InvalidToken' => 'Token inválido',
	'iTopUpdate:Error:UpdateFailed' => 'Atualização falhou',
	'iTopUpdate:Error:FileUploadMaxSizeTooSmall' => 'O tamanho máximo de envio de arquivos me parece muito pequeno para a atualização. Favor alterar as configurações do PHP.',

	'iTopUpdate:UI:RestoreArchive' => 'Você pode restaurar sua aplicação com o arquivo \'%1$s\'',
	'iTopUpdate:UI:RestoreBackup' => 'Você pode restaurar sua base de dados com \'%1$s\'',
	'iTopUpdate:UI:UpdateDone' => 'Atualizado com sucesso',
	'Menu:iTopUpdate' => 'Atualização da Aplicação',
	'Menu:iTopUpdate+' => 'Atualização da Aplicação',

    // Missing itop entries
    'Class:ModuleInstallation/Attribute:installed' => 'Instalado em',
    'Class:ModuleInstallation/Attribute:name' => 'Noome',
    'Class:ModuleInstallation/Attribute:version' => 'Versão',
    'Class:ModuleInstallation/Attribute:comment' => 'Comentário',
));


