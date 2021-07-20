<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
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
	'Menu:DBToolsMenu' => 'Ferramentas de Base de Dados',
	'DBTools:Class' => 'Classe',
	'DBTools:Title' => 'Manutenção da Base de Dados',
	'DBTools:ErrorsFound' => 'Erros Encontrados',
	'DBTools:Indication' => 'Important: after fixing errors in the database you\'ll have to run the analysis again as new inconsistencies will be generated~~',
	'DBTools:Disclaimer' => 'DISCLAIMER: BACKUP YOUR DATABASE BEFORE RUNNING THE FIXES~~',
	'DBTools:Error' => 'Erros',
	'DBTools:Count' => 'Quantidade',
	'DBTools:SQLquery' => 'Query SQL',
	'DBTools:FixitSQLquery' => 'Query SQL para correção (sugestão)',
	'DBTools:SQLresult' => 'Resultado do SQL',
	'DBTools:NoError' => 'Sem problemas na base de dados',
	'DBTools:HideIds' => 'Lista de erros',
	'DBTools:ShowIds' => 'Visualização detalhada',
	'DBTools:ShowReport' => 'Relatório',
	'DBTools:IntegrityCheck' => 'Verificação de integridade',
	'DBTools:FetchCheck' => 'Verificação de Busca (longo)',
	'DBTools:SelectAnalysisType' => 'Select analysis type~~',

	'DBTools:Analyze' => 'Analisar',
	'DBTools:Details' => 'Mostrar detalhes',
	'DBTools:ShowAll' => 'Mostrar todos erros',

	'DBTools:Inconsistencies' => 'Inconsistências na base de dados',
	'DBTools:DetailedErrorTitle' => '%2$s error(s) in class %1$s: %3$s~~',

	'DBAnalyzer-Integrity-OrphanRecord' => 'Item orfão em `%1$s`, ele deveria ter seu registro irmão na tabela `%2$s`',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Chave externa inválida %1$s (coluna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Chave externa ausente %1$s (coluna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Valor inválido par %1$s (coluna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Algumas contas de usuário não possuem perfil',
	'DBAnalyzer-Fetch-Count-Error' => 'Erro na busca em `%1$s`, %2$d registros buscados / %3$d contados',
	'DBAnalyzer-Integrity-FinalClass' => 'Campo `%2$s`.`%1$s` precisa ter o mesmo valor que `%3$s`.`%1$s`',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Campo `%2$s`.`%1$s` precisa conter uma classe válida',
));

// Database Info
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'DBTools:DatabaseInfo' => 'Informação da base de dados',
	'DBTools:Base' => 'Base',
	'DBTools:Size' => 'Tamanho',
));

// Lost attachments
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'DBTools:LostAttachments' => 'Anexos perdidos',
	'DBTools:LostAttachments:Disclaimer' => 'Aqui você procurará na sua base de dados por anexos perdidos. Isto NÃO é uma ferramenta de recuperação de dados, pois não busca dados apagados.',

	'DBTools:LostAttachments:Button:Analyze' => 'Analisar',
	'DBTools:LostAttachments:Button:Restore' => 'Recuperar',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Esta ação não pode ser desfeita, você confirma que quer recuperar os arquivos selecionados?',
	'DBTools:LostAttachments:Button:Busy' => 'Aguarde...',

	'DBTools:LostAttachments:Step:Analyze' => 'Primeiro, procure anexos perdidos pela análise da base de dados.',

	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Resultados da análise:',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Ótimo! Tudo parece estar nos seus devidos lugares.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Alguns anexos (%1$d) parecem estar perdidos. Verifique a lista abaixo e escolha os que você deseja mover.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Nome do arquivo',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Local atual',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Mover para',

	'DBTools:LostAttachments:Step:RestoreResults' => 'Resultados:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d de anexos recuperados.',

	'DBTools:LostAttachments:StoredAsInlineImage' => 'Armazenar como imagem embedada.',
	'DBTools:LostAttachments:History' => 'Anexo "%1$s" recuperada com as Ferramentas de Base de Dados'
));
