<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2023 Combodo
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'bkp-backup-running' => 'Um backup está sendo executado. Por favor, espere...',
	'bkp-restore-running' => 'Uma restauração está sendo executada. Por favor, espere...',
	'Menu:BackupStatus' => 'Backups agendados',
	'bkp-status-title' => 'Backups Agendados',
	'bkp-status-checks' => 'Configurações e verificações',
	'bkp-mysqldump-ok' => 'mysqldump está presente: %1$s',
	'bkp-mysqldump-notfound' => 'mysqldump não foi encontrado: %1$s - Por favor, verifique se ele está instalado e no caminho, ou edite o arquivo de configuração do '.ITOP_APPLICATION_SHORT.' para ajustar o parâmetro mysql_bindir',
	'bkp-mysqldump-issue' => 'falha durante a execução do mysqldump (retcode=%1$d): Por favor, certifique-se de que o mysqldump está instalado e o caminho está correto, ou edite o arquivo de configuração do '.ITOP_APPLICATION_SHORT.' para ajustar o parâmetro mysql_bindir',
	'bkp-missing-dir' => 'O diretório de destino <code>%1$s</code> não foi encontrado',
	'bkp-free-disk-space' => '<b>%1$s livre</b> em <code>%2$s</code>',
	'bkp-dir-not-writeable' => 'O diretório de destino <code>%1$s</code> não é gravável',
	'bkp-wrong-format-spec' => 'A especificação atual para formatar os nomes dos arquivos está errada. (%1$s). A especificação padrão foi aplicada: %2$s',
	'bkp-name-sample' => 'Os arquivos de backup são nomeados de acordo com os identificadores do banco de dados, data e hora. Exemplo: %1$s',
	'bkp-week-days' => 'Backups ocorrerão a <b>cada %1$s às %2$s</b>',
	'bkp-retention' => 'No máximo <b>%1$d arquivos de backup serão mantidos</b> no diretório de destino',
	'bkp-next-to-delete' => 'Será deletado quando ocorrer o próximo backup (consulte o parâmetro "retention_count" do arquivo de configuração do '.ITOP_APPLICATION_SHORT.')',
	'bkp-table-file' => 'Arquivo',
	'bkp-table-file+' => 'Apenas arquivos com a extensão .zip são considerados arquivos de backup',
	'bkp-table-size' => 'Tamanho',
	'bkp-table-size+' => '',
	'bkp-table-actions' => 'Ações',
	'bkp-table-actions+' => '',
	'bkp-status-backups-auto' => 'Backups agendados',
	'bkp-status-backups-manual' => 'Backups manuais',
	'bkp-status-backups-none' => 'Nenhum backup ainda',
	'bkp-next-backup' => 'O próximo backup ocorrerá em <b>%1$s</b> (%2$s) às %3$s',
	'bkp-next-backup-unknown' => 'O próximo backup <b>não está agendado</b> ainda',
	'bkp-button-backup-now' => 'Backup Agora!',
	'bkp-button-restore-now' => 'Restaurar!',
	'bkp-confirm-backup' => 'Por favor, confirme que você solicitou que o backup ocorra agora',
	'bkp-confirm-restore' => 'Por favor, confirme que você deseja restaurar o backup %1$s',
	'bkp-wait-backup' => 'Por favor, aguarde a conclusão do backup...',
	'bkp-wait-restore' => 'Por favor, aguarde a conclusão da restauração...',
	'bkp-success-restore' => 'Restauração concluída com sucesso',
));
