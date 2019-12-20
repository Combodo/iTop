<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2018 Combodo
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(

	'bkp-backup-running' => 'Um backup está sendo executado. Por favor, espere...',
	'bkp-restore-running' => 'Uma restauração está sendo executada. Por favor, espere...',

	'Menu:BackupStatus' => 'Backups agendados',
	'bkp-status-title' => 'Backups agendados',
	'bkp-status-checks' => 'Configurações e verificações',
	'bkp-mysqldump-ok' => 'mysqldump está presente: %1$s',
	'bkp-mysqldump-notfound' => 'mysqldump não pode ser encontrado: %1$s - Por favor, verifique se ele está instalado e no caminho, ou edite o arquivo de configuração para ajustar mysql_bindir.',
	'bkp-mysqldump-issue' => 'mysqldump não pode ser executado (retcode=%1$d): Por favor, certifique-se de que está instalado e no caminho, ou edite o arquivo de configuração para ajustar mysql_bindir.',
	'bkp-missing-dir' => 'O diretório de destino %1$s não foi encontrado',
	'bkp-free-disk-space' => '<b>%1$s livre</b> in %2$s',
	'bkp-dir-not-writeable' => '%1$s não é gravável',
	'bkp-wrong-format-spec' => 'A especificação atual para formatar os nomes dos arquivos está errada. (%1$s). A especificação padrão foi aplicada: %2$s',
	'bkp-name-sample' => 'Os arquivos de backup são nomeados dependendo dos identificadores do banco de dados, data e hora. Exemplo: %1$s',
	'bkp-week-days' => 'Backups ocorrerão <b>cada %1$s a %2$s</b>',
	'bkp-retention' => 'No máximo <b>%1$d arquivos de backup serão mantidos</b> no diretório destino.',
	'bkp-next-to-delete' => 'Será deletado quando ocorrer o próximo backup (veja a configuração de "retention_count")',
	'bkp-table-file' => 'Arquivo',
	'bkp-table-file+' => 'Apenas arquivos com a extensão .zip são considerados arquivos de backup',
	'bkp-table-size' => 'Tamanho',
	'bkp-table-size+' => '',
	'bkp-table-actions' => 'Ações',
	'bkp-table-actions+' => '',
	'bkp-status-backups-auto' => 'Backups agendados',
	'bkp-status-backups-manual' => 'Backups manuais',
	'bkp-status-backups-none' => 'Nenhum backup ainda',
	'bkp-next-backup' => 'O próximo backup ocorrerá em <b>%1$s</b> (%2$s) at %3$s',
	'bkp-button-backup-now' => 'Backup Agora!',
	'bkp-button-restore-now' => 'Restaurar!',
	'bkp-confirm-backup' => 'Por favor, confirme que você solicitou que o backup ocorra agora.',
	'bkp-confirm-restore' => 'Por favor, confirme que você deseja restaurar o backup %1$s.',
	'bkp-wait-backup' => 'Por favor, aguarde o backup concluir...',
	'bkp-wait-restore' => 'Por favor, aguarde a restauração concluir...',
	'bkp-success-restore' => 'Restauração concluída com sucesso.',
));
