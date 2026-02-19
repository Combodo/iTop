<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('PT BR', 'Brazilian', 'Brazilian', [
	'UI:Login:Title'                  => 'Login no '.ITOP_APPLICATION_SHORT,
	'UI:Login:Logo:AltText'           => ITOP_APPLICATION_SHORT.' logo~~',
	'UI:Login:Welcome'                => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Usuário e/ou senha inválido(s), tente novamente',
	'UI:Login:IdentifyYourself'       => 'Identifique-se antes continuar',
	'UI:Login:UserNamePrompt'         => 'Usuário',
	'UI:Login:PasswordPrompt'         => 'Senha',
	'UI:Login:ForgotPwd'              => 'Esqueceu sua senha?',
	'UI:Login:ForgotPwdForm'          => 'Esqueceu sua senha',
	'UI:Login:ForgotPwdForm+'         => 'O '.ITOP_APPLICATION_SHORT.' pode enviar um e-mail em que você vai encontrar instruções para seguir para redefinir sua conta',
	'UI:Login:ResetPassword'          => 'Enviar agora',
	'UI:Login:ResetPwdFailed'         => 'Falha ao enviar e-mail: %1$s',
	'UI:Login:SeparatorOr'            => 'Ou',

	'UI:ResetPwd-Error-WrongLogin'    => '\'%1$s\' não é um login válido',
	'UI:ResetPwd-Error-NotPossible'   => 'Não é permitida alteração de senha de contas externas',
	'UI:ResetPwd-Error-FixedPwd'      => 'A conta não permite alteração de senha',
	'UI:ResetPwd-Error-NoContact'     => 'A conta não está associada a uma pessoa',
	'UI:ResetPwd-Error-NoEmailAtt'    => 'A conta não está associada a uma pessoa que contém um endereço de e-mail no '.ITOP_APPLICATION_SHORT.'.Por favor, contate o administrador',
	'UI:ResetPwd-Error-NoEmail'       => 'A conta não contém um endereço de e-mail. Por favor, contate o administrador',
	'UI:ResetPwd-Error-Send'          => 'Houve um problema técnico de transporte de e-mail. Por favor, contate o administrador',
	'UI:ResetPwd-EmailSent'           => 'Verifique sua caixa de e-mail e siga as instruções. Se você não receber nenhum e-mail, verifique a caixa de SPAM e o login que você digitou',
	'UI:ResetPwd-EmailSubject'        => 'Alterar a senha',
	'UI:ResetPwd-EmailBody'           => '<body><p>Você solicitou a alteração da senha do '.ITOP_APPLICATION_SHORT.'.</p><p>Por favor, siga este link (passo simples) para <a href="%1$s">digitar a nova senha</a></p>.',
	'UI:ResetPwd-Title'               => 'Alterar senha',
	'UI:ResetPwd-Error-InvalidToken'  => 'Desculpe, a senha já foi alterada, ou você deve ter recebido múltiplos e-mails. Por favor, certifique-se que você acessou o link fornecido no último e-mail recebido',
	'UI:ResetPwd-Error-EnterPassword' => 'Digite a nova senha para a conta \'%1$s\'',
	'UI:ResetPwd-Ready'               => 'A senha foi alterada com sucesso',
	'UI:ResetPwd-Login'               => 'Clique para entrar...',

	'UI:Login:About'                               => '',
	'UI:Login:ChangeYourPassword'                  => 'Alterar sua senha',
	'UI:Login:OldPasswordPrompt'                   => 'Senha antiga',
	'UI:Login:NewPasswordPrompt'                   => 'Nova senha',
	'UI:Login:RetypeNewPasswordPrompt'             => 'Repetir nova senha',
	'UI:Login:IncorrectOldPassword'                => 'Erro: senha antiga incorreta',
	'UI:LogOffMenu'                                => 'Sair',
	'UI:LogOff:ThankYou'                           => 'Obrigado por usar o sistema',
	'UI:LogOff:ClickHereToLoginAgain'              => 'Clique aqui para entrar novamente...',
	'UI:ChangePwdMenu'                             => 'Alterar senha...',
	'UI:Login:PasswordChanged'                     => 'Senha alterada com sucesso',
	'UI:Login:PasswordNotChanged'                  => 'Error: Password is the same!~~',
	'UI:Login:RetypePwdDoesNotMatch'               => '"Nova senha" e "Repetir nova senha" são diferentes. Tente novamente!',
	'UI:Button:Login'                              => 'Login',
	'UI:Login:Error:AccessRestricted'              => 'Acesso restrito. Por favor, contacte o administrador',
	'UI:Login:Error:AccessAdmin'                   => 'Acesso restrito somente para usuários com privilégios administrativos. Por favor, contacte o administrador',
	'UI:Login:Error:WrongOrganizationName'         => 'Organização não encontrada',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Vários contatos têm o mesmo e-mail',
	'UI:Login:Error:NoValidProfiles'               => 'Nenhum perfil válido fornecido',
]);
