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
// Database inconsistencies
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	// Dictionary entries go here
	'Menu:DBToolsMenu' => 'Ferramentas de Banco de Dados',
	'DBTools:Class' => 'Classe',
	'DBTools:Title' => 'Manutenção do Banco de Dados',
	'DBTools:ErrorsFound' => 'Erros Encontrados',
	'DBTools:Indication' => 'Importante: Depois de corrigir erros no banco de dados, você terá que executar a análise novamente, à medida que novas inconsistências serão geradas',
	'DBTools:Disclaimer' => 'Aviso: Faça backup do seu banco de dados antes de executar as correções',
	'DBTools:Error' => 'Erros',
	'DBTools:Count' => 'Quantidade',
	'DBTools:SQLquery' => 'Consulta SQL',
	'DBTools:FixitSQLquery' => 'Correção da consulta SQL (sugestão)',
	'DBTools:SQLresult' => 'Resultado do SQL',
	'DBTools:NoError' => 'Sem problemas no banco de dados',
	'DBTools:HideIds' => 'Lista de erros',
	'DBTools:ShowIds' => 'Visualização detalhada',
	'DBTools:ShowReport' => 'Relatório',
	'DBTools:IntegrityCheck' => 'Verificação de integridade',
	'DBTools:FetchCheck' => 'Verificação de consulta (longa)',
	'DBTools:SelectAnalysisType' => 'Selecione o tipo de análise',
	'DBTools:Analyze' => 'Analisar',
	'DBTools:Details' => 'Exibir detalhes',
	'DBTools:ShowAll' => 'Exibir todos os erros',
	'DBTools:Inconsistencies' => 'Inconsistências no banco de dados',
	'DBTools:DetailedErrorTitle' => '%2$s erro(s) na classe %1$s: %3$s',
	'DBTools:DetailedErrorLimit' => 'List limited to %1$s errors~~',
	'DBAnalyzer-Integrity-OrphanRecord' => 'Item orfão em `%1$s`, ele deveria ter seu registro irmão na tabela `%2$s`',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Chave externa inválida %1$s (coluna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Chave externa ausente %1$s (coluna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Valor inválido par %1$s (coluna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Algumas contas de usuário não possuem perfil',
	'DBAnalyzer-Integrity-HKInvalid' => 'Chave hierárquica quebrada `%1$s`',
	'DBAnalyzer-Fetch-Count-Error' => 'Erro na busca em `%1$s`, %2$d registros buscados / %3$d contados',
	'DBAnalyzer-Integrity-FinalClass' => 'Campo `%2$s`.`%1$s` precisa ter o mesmo valor que `%3$s`.`%1$s`',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Campo `%2$s`.`%1$s` precisa conter uma classe válida',
));

// Database Info
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'DBTools:DatabaseInfo' => 'Informação do banco de dados',
	'DBTools:Base' => 'Banco',
	'DBTools:Size' => 'Tamanho',
));

// Lost attachments
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'DBTools:LostAttachments' => 'Anexos perdidos',
	'DBTools:LostAttachments:Disclaimer' => 'Aqui você procurará no seu banco de dados por anexos perdidos. Isto NÃO é uma ferramenta de recuperação de dados, pois não busca dados apagados',
	'DBTools:LostAttachments:Button:Analyze' => 'Analisar',
	'DBTools:LostAttachments:Button:Restore' => 'Recuperar',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Esta ação não pode ser desfeita, você confirma que quer recuperar os arquivos selecionados?',
	'DBTools:LostAttachments:Button:Busy' => 'Aguarde...',
	'DBTools:LostAttachments:Step:Analyze' => 'Primeiro, vamos procurar por anexos perdidos através da análise do banco de dados',
	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Resultado da análise:',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Ótimo! Tudo parece estar nos seus devidos lugares (Nenhum anexo perdido foi encontrado)',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Alguns anexos (%1$d) parecem estar perdidos. Verifique a lista abaixo e escolha os que você deseja mover',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Nome do arquivo',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Local atual',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Mover para',
	'DBTools:LostAttachments:Step:RestoreResults' => 'Resultado da restauração:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d anexo(s) recuperado(s)',
	'DBTools:LostAttachments:StoredAsInlineImage' => 'Armazenar como imagem embutida',
	'DBTools:LostAttachments:History' => 'Anexo "%1$s" recuperado com as Ferramentas de Banco de Dados'
));
