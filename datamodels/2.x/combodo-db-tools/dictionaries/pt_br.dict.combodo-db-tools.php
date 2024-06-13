<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 *
 */
Dict::Add('PT BR', 'Brazilian', 'Brazilian', [
	'DBAnalyzer-Fetch-Count-Error' => 'Erro na busca em `%1$s`, %2$d registros buscados / %3$d contados',
	'DBAnalyzer-Integrity-FinalClass' => 'Campo `%2$s`.`%1$s` precisa ter o mesmo valor que `%3$s`.`%1$s`',
	'DBAnalyzer-Integrity-HKInvalid' => 'Chave hierárquica quebrada `%1$s`',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Chave externa inválida %1$s (coluna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Valor inválido par %1$s (coluna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Chave externa ausente %1$s (coluna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-OrphanRecord' => 'Item orfão em `%1$s`, ele deveria ter seu registro irmão na tabela `%2$s`',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Campo `%2$s`.`%1$s` precisa conter uma classe válida',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Algumas contas de usuário não possuem perfil',
	'DBTools:Analyze' => 'Analisar',
	'DBTools:Base' => 'Banco',
	'DBTools:Class' => 'Classe',
	'DBTools:Count' => 'Quantidade',
	'DBTools:DatabaseInfo' => 'Informação do banco de dados',
	'DBTools:DetailedErrorLimit' => 'List limited to %1$s errors~~',
	'DBTools:DetailedErrorTitle' => '%2$s erro(s) na classe %1$s: %3$s',
	'DBTools:Details' => 'Exibir detalhes',
	'DBTools:Disclaimer' => 'Aviso: Faça backup do seu banco de dados antes de executar as correções',
	'DBTools:Error' => 'Erros',
	'DBTools:ErrorsFound' => 'Erros Encontrados',
	'DBTools:FetchCheck' => 'Verificação de consulta (longa)',
	'DBTools:FixitSQLquery' => 'Correção da consulta SQL (sugestão)',
	'DBTools:HideIds' => 'Lista de erros',
	'DBTools:Inconsistencies' => 'Inconsistências no banco de dados',
	'DBTools:Indication' => 'Importante: Depois de corrigir erros no banco de dados, você terá que executar a análise novamente, à medida que novas inconsistências serão geradas',
	'DBTools:IntegrityCheck' => 'Verificação de integridade',
	'DBTools:LostAttachments' => 'Anexos perdidos',
	'DBTools:LostAttachments:Button:Analyze' => 'Analisar',
	'DBTools:LostAttachments:Button:Busy' => 'Aguarde...',
	'DBTools:LostAttachments:Button:Restore' => 'Recuperar',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Esta ação não pode ser desfeita, você confirma que quer recuperar os arquivos selecionados?',
	'DBTools:LostAttachments:Disclaimer' => 'Aqui você procurará no seu banco de dados por anexos perdidos. Isto NÃO é uma ferramenta de recuperação de dados, pois não busca dados apagados',
	'DBTools:LostAttachments:History' => 'Anexo "%1$s" recuperado com as Ferramentas de Banco de Dados',
	'DBTools:LostAttachments:Step:Analyze' => 'Primeiro, vamos procurar por anexos perdidos através da análise do banco de dados',
	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Resultado da análise:',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Local atual',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Nome do arquivo',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Mover para',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Ótimo! Tudo parece estar nos seus devidos lugares (Nenhum anexo perdido foi encontrado)',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Alguns anexos (%1$d) parecem estar perdidos. Verifique a lista abaixo e escolha os que você deseja mover',
	'DBTools:LostAttachments:Step:RestoreResults' => 'Resultado da restauração:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d anexo(s) recuperado(s)',
	'DBTools:LostAttachments:StoredAsInlineImage' => 'Armazenar como imagem embutida',
	'DBTools:NoError' => 'Sem problemas no banco de dados',
	'DBTools:SQLquery' => 'Consulta SQL',
	'DBTools:SQLresult' => 'Resultado do SQL',
	'DBTools:SelectAnalysisType' => 'Selecione o tipo de análise',
	'DBTools:ShowAll' => 'Exibir todos os erros',
	'DBTools:ShowIds' => 'Visualização detalhada',
	'DBTools:ShowReport' => 'Relatório',
	'DBTools:Size' => 'Tamanho',
	'DBTools:Title' => 'Manutenção do Banco de Dados',
	'Menu:DBToolsMenu' => 'Ferramentas de Banco de Dados',
]);
