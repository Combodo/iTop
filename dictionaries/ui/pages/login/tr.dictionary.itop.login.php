<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('TR TR', 'Turkish', 'Türkçe', [
	'UI:Login:Title' => ITOP_APPLICATION_SHORT.' login~~',
	'UI:Login:Logo:AltText' => ITOP_APPLICATION_SHORT.' logo~~',
	'UI:Login:Welcome' => ITOP_APPLICATION_SHORT.'\'a Hoşgeldiniz!',
	'UI:Login:IncorrectLoginPassword' => 'Hatalı kullanıcı/şifre tekrar deneyiniz.',
	'UI:Login:IdentifyYourself' => 'Devam etmeden önce kendinizi tanıtınız',
	'UI:Login:UserNamePrompt' => 'Kullanıcı Adı',
	'UI:Login:PasswordPrompt' => 'Şifre',
	'UI:Login:ForgotPwd' => 'Şifrenizi mi unuttunuz?',
	'UI:Login:ForgotPwdForm' => 'Şifrenizi mi unuttunuz?',
	'UI:Login:ForgotPwdForm+' => ITOP_APPLICATION_SHORT.', hesabınızı sıfırlamak için izleyeceğiniz talimatları bulacağınız bir e-posta gönderebilir.',
	'UI:Login:ResetPassword' => 'Şimdi gönder!',
	'UI:Login:ResetPwdFailed' => 'Bir e-posta gönderilemedi: %1$s',
	'UI:Login:SeparatorOr' => 'Or~~',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' geçerli bir giriş değil',
	'UI:ResetPwd-Error-NotPossible' => 'Harici hesapların şifre sıfırlama izni yoktur.',
	'UI:ResetPwd-Error-FixedPwd' => 'Hesabın şifre sıfırlama izni yoktur.',
	'UI:ResetPwd-Error-NoContact' => 'Hesap bir kişiyle ilişkili değildir.',
	'UI:ResetPwd-Error-NoEmailAtt' => 'Hesap, bir e-posta özelliğine sahip bir kişiyle ilişkili değildir. Lütfen yöneticinize başvurun.',
	'UI:ResetPwd-Error-NoEmail' => 'Bir e-posta adresi eksik. Lütfen yöneticinize başvurun.',
	'UI:ResetPwd-Error-Send' => 'E-posta ulaştırma teknik sorunu. Lütfen yöneticinize başvurun.',
	'UI:ResetPwd-EmailSent' => 'Lütfen e-posta kutunuzu kontrol edin ve talimatları izleyin...',
	'UI:ResetPwd-EmailSubject' => ITOP_APPLICATION_SHORT.'şifrenizi sıfırlayın',
	'UI:ResetPwd-EmailBody' => '<body><p>'.ITOP_APPLICATION_SHORT.' şifrenizin sıfırlanması talebinde bulundunuz.</p><p> Yeni şifre oluşturmak için lütfen aşağıdaki tek kullanımlık bağlantıyı <a href=\\"%1$s\\">takip ediniz.</a></p>',
	'UI:ResetPwd-Title' => 'Şifre sıfırla',
	'UI:ResetPwd-Error-InvalidToken' => 'Üzgünüz, ya parola zaten sıfırlandı ya da birkaç e-posta aldınız. Lütfen aldığınız en son e-postada verilen bağlantıyı kullandığınızdan emin olun',
	'UI:ResetPwd-Error-EnterPassword' => '\'%1$s\' hesabı için yeni bir şifre girin.',
	'UI:ResetPwd-Ready' => 'Şifre değiştirildi.',
	'UI:ResetPwd-Login' => 'Giriş yapmak için buraya tıklayın...',

	'UI:Login:About' => ITOP_APPLICATION.' Powered by Combodo~~',
	'UI:Login:ChangeYourPassword' => 'Şifre Değiştir',
	'UI:Login:OldPasswordPrompt' => 'Mevcut şifre',
	'UI:Login:NewPasswordPrompt' => 'Yeni şifre',
	'UI:Login:RetypeNewPasswordPrompt' => 'Yeni şifre tekrar',
	'UI:Login:IncorrectOldPassword' => 'Hata: mevcut şifre hatalı',
	'UI:LogOffMenu' => 'Çıkış',
	'UI:LogOff:ThankYou' => ITOP_APPLICATION_SHORT.' Kullanıdığınız için teşekkürler',
	'UI:LogOff:ClickHereToLoginAgain' => 'Tekrar bağlanmak için tıklayınız...',
	'UI:ChangePwdMenu' => 'Şifre değiştir...',
	'UI:Login:PasswordChanged' => 'Şifre başarıyla ayarlandı!',
	'UI:Login:PasswordNotChanged' => 'Error: Password is the same!~~',
	'UI:Login:RetypePwdDoesNotMatch' => 'Yeni şifre eşlenmedi !',
	'UI:Button:Login' => ITOP_APPLICATION_SHORT.'\'a Giriş',
	'UI:Login:Error:AccessRestricted' => ITOP_APPLICATION_SHORT.' erişim sınırlandırıldı. Sistem yöneticisi ile irtibata geçiniz',
	'UI:Login:Error:AccessAdmin' => 'Erişim sistem yönetci hesaplaları ile mümkün. Sistem yöneticisi ile irtibata geçiniz.',
	'UI:Login:Error:WrongOrganizationName' => 'Unknown organization~~',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Multiple contacts have the same e-mail~~',
	'UI:Login:Error:NoValidProfiles' => 'No valid profile provided~~',
]);