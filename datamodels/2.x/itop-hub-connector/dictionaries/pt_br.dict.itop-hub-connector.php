<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2023 Combodo SARL
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
	// Dictionary entries go here
	'Menu:iTopHub' => 'iTop Hub',
	'Menu:iTopHub:Register' => 'Conectar ao iTop Hub',
	'Menu:iTopHub:Register+' => 'Vá para o iTop Hub para atualizar sua instância '.ITOP_APPLICATION_SHORT,
	'Menu:iTopHub:Register:Description' => '<p>Obtenha acesso à sua plataforma da comunidade iTop Hub!</br>Encontre todos os conteúdos e informações necessárias, gerencie suas instâncias '.ITOP_APPLICATION_SHORT.' por meio de ferramentas personalizadas & instale mais extensões.</br><br/>Ao conectar-se ao iTop hub a partir desta página, você enviará informações da sua instância '.ITOP_APPLICATION_SHORT.' ao iTop Hub.</p>',
	'Menu:iTopHub:MyExtensions' => 'Extensões Implantadas',
	'Menu:iTopHub:MyExtensions+' => 'Veja a lista de extensões implantadas nesta instância do '.ITOP_APPLICATION_SHORT,
	'Menu:iTopHub:BrowseExtensions' => 'Obter Extensões do iTop Hub',
	'Menu:iTopHub:BrowseExtensions+' => 'Navegue por mais extensões no iTop Hub',
	'Menu:iTopHub:BrowseExtensions:Description' => '<p>Visite a loja do iTop Hub, seu único lugar para encontrar extensões maravilhosas do '.ITOP_APPLICATION_SHORT.' !</br>Encontre as extensões que irão ajudá-lo a personalizar e adaptar o '.ITOP_APPLICATION_SHORT.' aos seus processos.</br><br/>Ao conectar-se ao iTop hub a partir desta página, você enviará informações da sua instância '.ITOP_APPLICATION_SHORT.' ao iTop Hub.</p>',
	'iTopHub:GoBtn' => 'Ir ao iTop Hub',
	'iTopHub:CloseBtn' => 'Fechar',
	'iTopHub:GoBtn:Tooltip' => 'Abrir www.itophub.io',
	'iTopHub:OpenInNewWindow' => 'Abrir o iTop Hub em uma nova janela',
	'iTopHub:AutoSubmit' => 'Não me pergunte novamente. Da próxima vez, vá para o iTop Hub automaticamente',
	'UI:About:RemoteExtensionSource' => 'iTop Hub',
	'iTopHub:Explanation' => 'Ao clicar neste botão, você será redirecionado para o iTop Hub',
	'iTopHub:BackupFreeDiskSpaceIn' => '%1$s de espaço livre em disco em %2$s',
	'iTopHub:FailedToCheckFreeDiskSpace' => 'Falha ao verificar o espaço livre em disco',
	'iTopHub:BackupOk' => 'Backup concluído com sucesso',
	'iTopHub:BackupFailed' => 'Backup falhou!',
	'iTopHub:Landing:Status' => 'Status da implantação',
	'iTopHub:Landing:Install' => 'Implantando extensão(ões)...',
	'iTopHub:CompiledOK' => 'Compilação bem-sucedida',
	'iTopHub:ConfigurationSafelyReverted' => 'Erro detectado durante a implantação!<br/>A configuração do '.ITOP_APPLICATION_SHORT.' NÃO foi modificada',
	'iTopHub:FailAuthent' => 'Autenticação falhou para esta ação',
	'iTopHub:InstalledExtensions' => 'Extensões implantadas nesta instância',
	'iTopHub:ExtensionCategory:Manual' => 'Extensões implantadas manualmente',
	'iTopHub:ExtensionCategory:Manual+' => 'As seguintes extensões foram implantadas copiando-as manualmente no diretório %1$s do '.ITOP_APPLICATION_SHORT.':',
	'iTopHub:ExtensionCategory:Remote' => 'Extensões implantadas através do iTop Hub',
	'iTopHub:ExtensionCategory:Remote+' => 'As seguintes extensões foram implantadas através do iTop Hub:',
	'iTopHub:NoExtensionInThisCategory' => 'Não há extensão nesta categoria',
	'iTopHub:NoExtensionInThisCategory+' => 'Acesse o iTop Hub para encontrar as extensões que ajudarão você a personalizar e adaptar o '.ITOP_APPLICATION_SHORT.' aos seus processos !',
	'iTopHub:ExtensionNotInstalled' => 'Não instalado',
	'iTopHub:GetMoreExtensions' => 'Obter extensões do iTop Hub...',
	'iTopHub:LandingWelcome' => 'Parabéns! As extensões a seguir foram baixadas do iTop Hub e implantadas no seu '.ITOP_APPLICATION_SHORT,
	'iTopHub:GoBackToITopBtn' => 'Voltar para o '.ITOP_APPLICATION_SHORT,
	'iTopHub:Uncompressing' => 'Descompactando extensão(ões)...',
	'iTopHub:InstallationWelcome' => 'Instalação das extensões baixadas do iTop Hub',
	'iTopHub:DBBackupLabel' => 'Backup da instância',
	'iTopHub:DBBackupSentence' => 'Faça um backup do banco de dados e configuração do '.ITOP_APPLICATION_SHORT.' antes de atualizar',
	'iTopHub:DeployBtn' => 'Implantar !',
	'iTopHub:DatabaseBackupProgress' => 'Backup da instância...',
	'iTopHub:InstallationEffect:Install' => 'Versão: %1$s será instalada.',
	'iTopHub:InstallationEffect:NoChange' => 'Versão: %1$s já está instalada. Nenhuma alteração realizada',
	'iTopHub:InstallationEffect:Upgrade' => 'Será <b>atualizada</b> da versão %1$s para a versão %2$s',
	'iTopHub:InstallationEffect:Downgrade' => 'Será <b>REBAIXADA</b> da versão %1$s para a versão %2$s',
	'iTopHub:InstallationProgress:DatabaseBackup' => 'Backup da instância do '.ITOP_APPLICATION_SHORT.'...',
	'iTopHub:InstallationProgress:ExtensionsInstallation' => 'Instalação das extensões',
	'iTopHub:InstallationEffect:MissingDependencies' => 'Esta extensão não pode ser instalada por causa de dependências não atendidas',
	'iTopHub:InstallationEffect:MissingDependencies_Details' => 'A extensão requer o(s) módulo(s): %1$s',
	'iTopHub:InstallationProgress:InstallationSuccessful' => 'Instalação bem-sucedida!',
	'iTopHub:InstallationStatus:Installed_Version' => '%1$s versão: %2$s',
	'iTopHub:InstallationStatus:Installed' => 'Instalado',
	'iTopHub:InstallationStatus:Version_NotInstalled' => 'Versão %1$s <b>NÃO</b> instalada',
));


