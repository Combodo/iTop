<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', [
	'UI:Login:Title'                  => 'Inicio de Sesión',
	'UI:Login:Logo:AltText'           => ITOP_APPLICATION_SHORT.' logo~~',
	'UI:Login:Welcome'                => 'Bienvenido a '.ITOP_APPLICATION_SHORT,
	'UI:Login:IncorrectLoginPassword' => 'Usuario/Contraseña incorrecto, por favor intente otra vez.',
	'UI:Login:IdentifyYourself'       => 'Identifiquese antes de continuar',
	'UI:Login:UserNamePrompt'         => 'Usuario   ',
	'UI:Login:PasswordPrompt'         => 'Contraseña',
	'UI:Login:ForgotPwd'              => '¿Olvidó su contraseña?',
	'UI:Login:ForgotPwdForm'          => 'Olvido de Contraseña',
	'UI:Login:ForgotPwdForm+'         => ITOP_APPLICATION_SHORT.' puede enviarle un correo en el cual encontrará las instrucciones a seguir para restablecer su contraseña.',
	'UI:Login:ResetPassword'          => 'Enviar Ahora',
	'UI:Login:ResetPwdFailed'         => 'Error al enviar correo-e: %1$s',
	'UI:Login:SeparatorOr'            => 'O',

	'UI:ResetPwd-Error-WrongLogin'    => '\'%1$s\' no es un usuario válido',
	'UI:ResetPwd-Error-NotPossible'   => 'Cuentas externas no permiten restablecimiento de contraseña.',
	'UI:ResetPwd-Error-FixedPwd'      => 'La cuenta no permite restablecimiento de contraseña.',
	'UI:ResetPwd-Error-NoContact'     => 'La cuenta no está asociada a una persona.',
	'UI:ResetPwd-Error-NoEmailAtt'    => 'La cuenta no está asociada a una persona con correo electrónico. Por favor contacte al administrador.',
	'UI:ResetPwd-Error-NoEmail'       => 'Falta dirección de correo electrónico. Por favor contacte al administrador.',
	'UI:ResetPwd-Error-Send'          => 'Falla al envar un correo. Por favor contacte al administrador.',
	'UI:ResetPwd-EmailSent'           => 'Por favor verifique su buzón de correo y siga las instrucciones. Si no recibe el mensaje, por favor verifique la cuenta proporcionada.',
	'UI:ResetPwd-EmailSubject'        => 'Restablecer contraseña de '.ITOP_APPLICATION_SHORT,
	'UI:ResetPwd-EmailBody'           => '<body><p>Ha solicitado restablecer su contraseña en '.ITOP_APPLICATION_SHORT.'.</p><p>Por favor de click en la siguiente liga: <a href="%1$s">proporcione una nueva contraseña</a></p>.',
	'UI:ResetPwd-Title'               => 'Restablecer Contraseña',
	'UI:ResetPwd-Error-InvalidToken'  => 'Lo siento, tal vez su contraseña ya ha sido cambiada, o ha recibido varios correos electrónicos. Por favor asegurese de haber dado click a la liga del último correo recibido.',
	'UI:ResetPwd-Error-EnterPassword' => 'Contraseña Nueva para \'%1$s\'.',
	'UI:ResetPwd-Ready'               => 'La contraseña ha sido cambiada.',
	'UI:ResetPwd-Login'               => 'Click aquí para conectarse ',

	'UI:Login:About'                               => 'Acerca de',
	'UI:Login:ChangeYourPassword'                  => 'Cambie su Contraseña',
	'UI:Login:OldPasswordPrompt'                   => 'Contraseña Actual',
	'UI:Login:NewPasswordPrompt'                   => 'Contraseña Nueva',
	'UI:Login:RetypeNewPasswordPrompt'             => 'Confirme Contraseña Nueva',
	'UI:Login:IncorrectOldPassword'                => 'Error: la Contraseña Anterior es Incorrecta',
	'UI:LogOffMenu'                                => 'Cerrar Sesión',
	'UI:LogOff:ThankYou'                           => 'Gracias por usar '.ITOP_APPLICATION_SHORT,
	'UI:LogOff:ClickHereToLoginAgain'              => 'Click aquí para conectarse nuevamente',
	'UI:ChangePwdMenu'                             => 'Cambiar Contraseña',
	'UI:Login:PasswordChanged'                     => '¡Contraseña Exitosamente Cambiada!',
	'UI:Login:PasswordNotChanged'                  => 'Error: ¡La contraseña es la misma!',
	'UI:Login:RetypePwdDoesNotMatch'               => '¡La Nueva Contraseña y su Confirmación No Coinciden!',
	'UI:Button:Login'                              => 'Entrar',
	'UI:Login:Error:AccessRestricted'              => 'El acceso a '.ITOP_APPLICATION_SHORT.' está restringido. Por favor contacte al Administrador de '.ITOP_APPLICATION_SHORT.'.',
	'UI:Login:Error:AccessAdmin'                   => 'Acceso restringido a usuarios con privilegio de administrador. Por favor contacte al Administrador de '.ITOP_APPLICATION_SHORT.'.',
	'UI:Login:Error:WrongOrganizationName'         => 'Organización desconocida',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Varios contactos tienen la misma dirección de correo electrónico',
	'UI:Login:Error:NoValidProfiles'               => 'Perfil inválido',
]);
