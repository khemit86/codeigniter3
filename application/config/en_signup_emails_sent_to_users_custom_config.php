<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Email Config Variables for Signup 
|--------------------------------------------------------------------------
| 
*/	
/*
|--------------------------------------------------------------------------
| Email Config Variables for welcome email 
|--------------------------------------------------------------------------
| 
*/

################ Defined the email variables regarding welcome email for male user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: save_user_signup_data */
//$config['welcome_email_cc_signup_personal_male'] = 'catalin.basturescu@gmail.com';
$config['welcome_email_bcc_signup_personal_male'] = 'catalinbasturescu@gmail.com';
//$config['welcome_email_from_signup_personal_male'] = 'registration@'.HTTP_HOST;
$config['welcome_email_from_signup_personal_male'] = 'registrace@'.HTTP_HOST;
$config['welcome_email_reply_to_signup_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['welcome_email_from_name_signup_personal_male'] = 'Registration '.HTTP_HOST;
$config['welcome_email_subject_signup_personal_male'] = 'Vítejte na travai.cz - ověřte Vaši emailovou adresu(male)';
$config['welcome_email_message_signup_personal_male'] = '<p><strong>Dear {name},</strong></p>
<p>Welcome</p>
<p>You&#39;re just one small step away from getting hundreds of job offers from people and companies all over the country looking for skilled professionals like you!</p><p>Remember if you do not verify your account after {account_expiration_interval} it will be removed from our system.</p>
<p>In order to be able to start using your iPrace.online account you need to verify your email address. You can either copy and paste the verification code here below into the verification page.</p>
<p>Your activation code is: {activation_code}</p>
<p>Or <a href="{signup_verification_url}"><strong>Click Here</strong></a> to verify your email and activate your account</p>
<p>Or copy and paste the link below in your browser&#39;s address bar</p>
<p>{signup_verification_url}</p>
<p><u><em><strong>Thanks for being with iPrace.online!</strong></em></u></p>
<hr>

<p><strong>Vážený {name},</strong></p>
<p>vítejte na Travai.cz - portál, který pomáhá spojit, komunikovat, spolupracovat a mít příležitosti k osobnímu růstu.</p>

<p>Už jen jeden krok Vás dělí od kontaktu s lidmi a společnostmi z celé země.</p>

<p>Abyste mohl začít používat Travai.cz, musíte ověřit Vaši emailovou adresu.</p>

<p>Pro ověření své emailové adresy <a href="{signup_verification_url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>

<p>{signup_verification_url}</p>

<p>Aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

################ Defined the email variables regarding welcome email for female user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: save_user_signup_data */
//$config['welcome_email_cc_signup_personal_female'] = 'catalin.basturescu@gmail.com';
$config['welcome_email_bcc_signup_personal_female'] = 'catalinbasturescu@gmail.com';
//$config['welcome_email_from_signup_personal_female'] = 'registration@'.HTTP_HOST;
$config['welcome_email_from_signup_personal_female'] = 'registrace@'.HTTP_HOST;
$config['welcome_email_reply_to_signup_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['welcome_email_from_name_signup_personal_female'] = 'Registration '.HTTP_HOST;
$config['welcome_email_subject_signup_personal_female'] = 'Vítejte na travai.cz - ověřte Vaši emailovou adresu(female)';
$config['welcome_email_message_signup_personal_female'] = '<p>Female-- <strong>Dear {name},</strong></p>
<p>Welcome and thank you for registering on iPrace.online.</p>
<p>You&#39;re just one small step away from getting hundreds of job offers from people and companies all over the country looking for skilled professionals like you!</p>
<p>Remember if you do not verify your account after {account_expiration_interval} it will be removed from our system.</p>
<p>In order to be able to start using your iPrace.online account you need to verify your email address. You can either copy and paste the verification code here below into the verification page.</p><p>Your activation code is: {activation_code}</p>
<p>Or <a href="{signup_verification_url}"><strong>Click Here</strong></a> to verify your email and activate your account</p>
<p>Or copy and paste the link below in your browser address bar</p>
<p>{signup_verification_url}</p>
<p><u><em><strong>Thanks</strong></em></u></p>
<hr>
<p><strong>Vážená {name},</strong></p>

<p>vítejte na Travai.cz - portál, který pomáhá spojit, komunikovat, spolupracovat a mít příležitosti k osobnímu růstu.</p>

<p>Už jen jeden krok Vás dělí od kontaktu s lidmi a společnostmi z celé země.</p>

<p>Abyste mohla začít používat Travai.cz, musíte ověřit Vaši emailovou adresu.</p>

<p>Pro ověření své emailové adresy <a href="{signup_verification_url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>

<p>{signup_verification_url}</p>

<p>Aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

################ Defined the email variables regarding welcome email for company user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: save_user_signup_data */
//$config['welcome_email_cc_signup_company'] = 'catalin.basturescu@gmail.com';
$config['welcome_email_bcc_signup_company'] = 'catalinbasturescu@gmail.com';
//$config['welcome_email_from_signup_company'] = 'registration@'.HTTP_HOST;
$config['welcome_email_from_signup_company'] = 'registrace@'.HTTP_HOST;
$config['welcome_email_reply_to_signup_company'] = 'no-reply@'.HTTP_HOST;
$config['welcome_email_from_name_signup_company'] = 'Registration '.HTTP_HOST;
$config['welcome_email_subject_signup_company'] = 'Vítejte na travai.cz - ověřte vaši emailovou adresu(company)';
$config['welcome_email_message_signup_company'] = '<p>company-<strong>{company_name},</strong></p>
<p>Welcome and thank you for registering on iPrace.online.</p>
<p>You&#39;re just one small step away from getting hundreds of job offers from people and companies all over the country looking for skilled professionals like you!</p>
<p>Remember if you do not verify your account after {account_expiration_interval} it will be removed from our system.</p>
<p>In order to be able to start using your iPrace.online account you need to verify your email address. You can either copy and paste the verification code here below into the verification page.</p>
<p>Your activation code is: {activation_code}</p><p>Or <a href="{signup_verification_url}"><strong>Click Here</strong></a> to verify your email and activate your account</p>
<p>Or copy and paste the link below in your browser&#39;s address bar</p>
<p>{signup_verification_url}</p>
<p><u><em><strong>Thanks for being with iPrace.online!</strong></em></u></p><p>iPrace.online Support Team</p>
<hr>
<p><strong>Vážená {company_name},</strong></p>

<p>vítejte na Travai.cz - portál, který pomáhá spojit, komunikovat, spolupracovat a mít příležitosti k osobnímu růstu.</p>

<p>Zjednodušte si cestu získávání nových příležitostí a podpořte růst svého podnikání.</p>

<p>Abyste mohli začít používat Travai.cz, musíte ověřit vaši emailovou adresu.</p>

<p>Pro ověření své emailové adresy <a href="{signup_verification_url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>

<p>{signup_verification_url}</p>

<p>Aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

################ Defined the email variables regarding welcome email for male authorized physical person start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: save_user_signup_data */
//$config['welcome_email_cc_signup_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['welcome_email_bcc_signup_company_app_male'] = 'catalinbasturescu@gmail.com';
//$config['welcome_email_from_signup_company_app_male'] = 'registration@'.HTTP_HOST;
$config['welcome_email_from_signup_company_app_male'] = 'registrace@'.HTTP_HOST;
$config['welcome_email_reply_to_signup_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['welcome_email_from_name_signup_company_app_male'] = 'Registration '.HTTP_HOST;
$config['welcome_email_subject_signup_company_app_male'] = 'Vítejte na travai.cz - ověřte Vaši emailovou adresu(app male)';
$config['welcome_email_message_signup_company_app_male'] = '<p>App male: <strong>(app male)Dear {name},</strong></p>
<p>Welcome</p>
<p>You&#39;re just one small step away from getting hundreds of job offers from people and companies all over the country looking for skilled professionals like you!</p><p>Remember if you do not verify your account after {account_expiration_interval} it will be removed from our system.</p>
<p>In order to be able to start using your iPrace.online account you need to verify your email address. You can either copy and paste the verification code here below into the verification page.</p>
<p>Your activation code is: {activation_code}</p>
<p>Or <a href="{signup_verification_url}"><strong>Click Here</strong></a> to verify your email and activate your account</p>
<p>Or copy and paste the link below in your browser&#39;s address bar</p>
<p>{signup_verification_url}</p>
<p><u><em><strong>Thanks for being with iPrace.online!</strong></em></u></p>
<hr>

<p><strong>Vážený {name},</strong></p>
<p>vítejte na Travai.cz - portál, který pomáhá spojit, komunikovat, spolupracovat a mít příležitosti k osobnímu růstu.</p>

<p>Už jen jeden krok Vás dělí od kontaktu s lidmi a společnostmi z celé země.</p>

<p>Abyste mohl začít používat Travai.cz, musíte ověřit Vaši emailovou adresu.</p>

<p>Pro ověření své emailové adresy <a href="{signup_verification_url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>

<p>{signup_verification_url}</p>

<p>Aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

################ Defined the email variables regarding welcome email for female authorized physical person start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: save_user_signup_data */
//$config['welcome_email_cc_signup_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['welcome_email_bcc_signup_company_app_female'] = 'catalinbasturescu@gmail.com';
//$config['welcome_email_from_signup_company_app_female'] = 'registration@'.HTTP_HOST;
$config['welcome_email_from_signup_company_app_female'] = 'registrace@'.HTTP_HOST;
$config['welcome_email_reply_to_signup_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['welcome_email_from_name_signup_company_app_female'] = 'Registration '.HTTP_HOST;
$config['welcome_email_subject_signup_company_app_female'] = 'Vítejte na travai.cz - ověřte Vaši emailovou adresu(app female)';
$config['welcome_email_message_signup_company_app_female'] = '<p><strong>Dear {name},(app female)</strong></p>
<p>Welcome and thank you for registering on iPrace.online.</p>
<p>You&#39;re just one small step away from getting hundreds of job offers from people and companies all over the country looking for skilled professionals like you!</p>
<p>Remember if you do not verify your account after {account_expiration_interval} it will be removed from our system.</p>
<p>In order to be able to start using your iPrace.online account you need to verify your email address. You can either copy and paste the verification code here below into the verification page.</p><p>Your activation code is: {activation_code}</p>
<p>Or <a href="{signup_verification_url}"><strong>Click Here</strong></a> to verify your email and activate your account</p>
<p>Or copy and paste the link below in your browser&#39;s address bar</p>
<p>{signup_verification_url}</p>
<p><u><em><strong>Thanks</strong></em></u></p>
<hr>
<p><strong>Vážená {name},</strong></p>

<p>vítejte na Travai.cz - portál, který pomáhá spojit, komunikovat, spolupracovat a mít příležitosti k osobnímu růstu.</p>

<p>Už jen jeden krok Vás dělí od kontaktu s lidmi a společnostmi z celé země.</p>

<p>Abyste mohla začít používat Travai.cz, musíte ověřit Vaši emailovou adresu.</p>

<p>Pro ověření své emailové adresy <a href="{signup_verification_url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>

<p>{signup_verification_url}</p>

<p>Aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

/*
|--------------------------------------------------------------------------
| Email Config Variables for verfied the account
|--------------------------------------------------------------------------
|
*/
################ Defined the email variables regarding verified male user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: signup_successful_verification */
//$config['email_cc_account_verified_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_account_verified_personal_male'] = 'catalinbasturescu@gmail.com';
$config['email_from_account_verified_personal_male'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_account_verified_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_account_verified_personal_male'] = 'Registration '.HTTP_HOST;
$config['email_subject_account_verified_personal_male'] = 'Vítejte na travai.cz - Váš účet je aktivován-(male)';
$config['email_message_account_verified_personal_male'] = '<p>Male:- Dear {name},</p>
<p>Welcome to iPrace.online - the largest services portal in Czech Republic.</p>
<p>Your account has been activated and is ready for you to use.</p>
<hr>
<p>Vážený {name},</p>

<p>Váš účet je aktivován a připravený k používání.</p>

<p>Jsme rádi, že jste s námi. Spustili jsme Travai.cz abychom pomohli vytvářet skutečně kvalitní dohody a obchody na lepší úrovni.</p>

<p>Od této chvíle můžete začít tvořit váš profil, vložte svoji fotku nebo logo, nadpis, popis, vzdělání a certifikáty. Vložte své portofolio a zveřejněte své služby. Můžete vytvářet inzeráty, hledat projekty a pracovní pozice, posílat nabídky nebo žádosti.</p>

<p>V případě dotazů nebo potřeby jakýchkoli informací o našich službách, navštivte stránku "Nápovědy" nebo nás kontaktujte přímo e-mailem nebo telefonicky.</p>

<p><strong><a href="{site_url}">Pokračovat na portál Travai.cz</a></strong></p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

################ Defined the email variables regarding verified female user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: signup_successful_verification */
//$config['email_cc_account_verified_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_account_verified_personal_female'] = 'catalinbasturescu@gmail.com';
$config['email_from_account_verified_personal_female'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_account_verified_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_account_verified_personal_female'] = 'Registration '.HTTP_HOST;
$config['email_subject_account_verified_personal_female'] = 'Vítejte na travai.cz - Váš účet je aktivován-(female)';
$config['email_message_account_verified_personal_female'] = '<p>Female- Dear {name},</p>
<p>Welcome to iPrace.online - the largest services portal in Czech Republic.</p>
<p>Your account has been activated and is ready for you to use.</p>
<hr>
<p>Vážená {name},</p>

<p>Váš účet je aktivován a připravený k používání.</p>

<p>Jsme rádi, že jste s námi. Spustili jsme Travai.cz abychom pomohli vytvářet skutečně kvalitní dohody a obchody na lepší úrovni.</p>

<p>Od této chvíle můžete začít tvořit váš profil, vložte svoji fotku nebo logo, nadpis, popis, vzdělání a certifikáty. Vložte své portofolio a zveřejněte své služby. Můžete vytvářet inzeráty, hledat projekty a pracovní pozice, posílat nabídky nebo žádosti.</p>

<p>V případě dotazů nebo potřeby jakýchkoli informací o našich službách, navštivte stránku "Nápovědy" nebo nás kontaktujte přímo e-mailem nebo telefonicky.</p>

<p><strong><a href="{site_url}">Pokračovat na portál Travai.cz</a></strong></p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

################ Defined the email variables regarding verified company user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: signup_successful_verification */
//$config['email_cc_account_verified_company'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_account_verified_company'] = 'catalinbasturescu@gmail.com';
$config['email_from_account_verified_company'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_account_verified_company'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_account_verified_company'] = 'Registration '.HTTP_HOST;
$config['email_subject_account_verified_company'] = 'Vítejte na travai.cz - Váš účet je aktivován (company)';
$config['email_message_account_verified_company'] = '<p>Company:- {company_name},</p>
<p>Welcome to iPrace.online - the largest services portal in Czech Republic.</p>
<p>Your account has been activated and is ready for you to use.</p>
<hr>
<p>Vážená {company_name},</p>

<p>Váš účet je aktivován a připravený k používání.</p>

<p>Jsme rádi, že jste s námi. Spustili jsme Travai.cz abychom pomohli vytvářet skutečně kvalitní dohody a obchody na lepší úrovni.</p>

<p>Od této chvíle můžete začít tvořit váš profil, vložte své logo, nadpis, popis společnosti, vizi, poslání a cíl společnosti. Vložte své portofolio a zveřejněte své služby. Můžete vytvářet inzeráty, hledat projekty a partnery, posílat nabídky.</p>

<p>V případě dotazů nebo potřeby jakýchkoli informací o našich službách, navštivte stránku "Nápovědy" nebo nás kontaktujte přímo e-mailem nebo telefonicky.</p>

<p><strong><a href="{site_url}">Pokračovat na portál Travai.cz</a></strong></p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;
################ Defined the email variables regarding verified accounts(company) end here

################ Defined the email variables regarding verified male app start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: signup_successful_verification */
//$config['email_cc_account_verified_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_account_verified_company_app_male'] = 'catalinbasturescu@gmail.com';
$config['email_from_account_verified_company_app_male'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_account_verified_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_account_verified_company_app_male'] = 'Registration '.HTTP_HOST;
$config['email_subject_account_verified_company_app_male'] = 'Vítejte na travai.cz - Váš účet je aktivován-(app male)';
$config['email_message_account_verified_company_app_male'] = '<p>(app male)Dear {name},</p>
<p>Welcome to iPrace.online - the largest services portal in Czech Republic.</p>
<p>Your account has been activated and is ready for you to use.</p>
<hr>
<p>Vážený {name},</p>

<p>Váš účet je aktivován a připravený k používání.</p>

<p>Jsme rádi, že jste s námi. Spustili jsme Travai.cz abychom pomohli vytvářet skutečně kvalitní dohody a obchody na lepší úrovni.</p>

<p>Od této chvíle můžete začít tvořit váš profil, vložte svoji fotku nebo logo, nadpis, popis, vzdělání a certifikáty. Vložte své portofolio a zveřejněte své služby. Můžete vytvářet inzeráty, hledat projekty a pracovní pozice, posílat nabídky nebo žádosti.</p>

<p>V případě dotazů nebo potřeby jakýchkoli informací o našich službách, navštivte stránku "Nápovědy" nebo nás kontaktujte přímo e-mailem nebo telefonicky.</p>

<p><strong><a href="{site_url}">Pokračovat na portál Travai.cz</a></strong></p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

################ Defined the email variables regarding verified female app start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: signup_successful_verification */
//$config['email_cc_account_verified_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_account_verified_company_app_female'] = 'catalinbasturescu@gmail.com';
$config['email_from_account_verified_company_app_female'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_account_verified_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_account_verified_company_app_female'] = 'Registration '.HTTP_HOST;
$config['email_subject_account_verified_company_app_female'] = 'Vítejte na travai.cz - Váš účet je aktivován-(app female)';
$config['email_message_account_verified_company_app_female'] = '<p>(app female)Dear {name},</p>
<p>Welcome to iPrace.online - the largest services portal in Czech Republic.</p>
<p>Your account has been activated and is ready for you to use.</p>
<hr>
<p>Vážená {name},</p>

<p>Váš účet je aktivován a připravený k používání.</p>

<p>Jsme rádi, že jste s námi. Spustili jsme Travai.cz abychom pomohli vytvářet skutečně kvalitní dohody a obchody na lepší úrovni.</p>

<p>Od této chvíle můžete začít tvořit váš profil, vložte svoji fotku nebo logo, nadpis, popis, vzdělání a certifikáty. Vložte své portofolio a zveřejněte své služby. Můžete vytvářet inzeráty, hledat projekty a pracovní pozice, posílat nabídky nebo žádosti.</p>

<p>V případě dotazů nebo potřeby jakýchkoli informací o našich službách, navštivte stránku "Nápovědy" nebo nás kontaktujte přímo e-mailem nebo telefonicky.</p>

<p><strong><a href="{site_url}">Pokračovat na portál Travai.cz</a></strong></p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;


/*
|--------------------------------------------------------------------------
| Email Config Variables for new verification code during mannual request on signup verify process
|--------------------------------------------------------------------------
|
*/
################ Defined the email variables regarding mannual request code for male user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: send_verification_code_manually_generated_by_user */
//$config['email_cc_unverified_account_manual_request_verification_code_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_manual_request_verification_code_personal_male'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_account_manual_request_verification_code_personal_male'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_unverified_account_manual_request_verification_code_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_manual_request_verification_code_personal_male'] = 'Registration '.HTTP_HOST;
$config['email_subject_unverified_account_manual_request_verification_code_personal_male'] = 'Váš nový ověřovací kód (male)';
$config['email_message_unverified_account_manual_request_verification_code_personal_male'] = '<p>Male: <strong>Hello {name},</strong></p>
<p>You just requested a new verification code to be generated for your email address.</p>
<p>You can either copy and paste the verification code here below into the verification page.</p>
<p>Your new activation code is: {activation_code}</p>or <p> <a href="{url}"><strong>Click Here</strong></a> to confirm your email address and activate your iPrace.online account or copy/paste the link below in your browser</p><p>{url}</p>
<p>Remember : you have {account_expiration_interval} after registration to confirm your email address and get access to your Travai account, otherwise your account will be removed from our database.</p>
<p>You still have {account_expiration_time_left} to confirm your email.</p>

<hr>

<p><strong>Vážený {name},</strong></p>
<p>právě jste požádal o vytvoření nového ověřovacího kódu pro ověření Vašeho účtu.</p>

<p>Pro ověření své emailové adresy <a href="{url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;
################ Defined the email variables regarding manual request code for female user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: send_verification_code_manually_generated_by_user */
//$config['email_cc_unverified_account_manual_request_verification_code_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_manual_request_verification_code_personal_female'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_account_manual_request_verification_code_personal_female'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_unverified_account_manual_request_verification_code_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_manual_request_verification_code_personal_female'] = 'Registration '.HTTP_HOST;
$config['email_subject_unverified_account_manual_request_verification_code_personal_female'] = 'Váš nový ověřovací kód (female)';
$config['email_message_unverified_account_manual_request_verification_code_personal_female'] = '<p>Female- <strong>Hello {name},</strong></p>
<p>You just requested a new verification code to be generated for your email address.</p>
<p>You can either copy and paste the verification code here below into the verification page.</p>
<p>Your new activation code is: {activation_code}</p> or
<p><a href="{url}"><strong>Click Here</strong></a> to confirm your email address and activate your iPrace.online account or copy/paste the link below in your browser</p>
<p>{url}</p><p>Remember : you have {account_expiration_interval} after registration to confirm your email address and get access to your Travai account, otherwise your account will be removed from our database. You still have {account_expiration_time_left} to confirm your email.</p>
<hr>

<p><strong>Vážená {name},</strong></p>
<p>právě jste požádala o vytvoření nového ověřovacího kódu pro ověření Vašeho účtu.</p>

<p>Pro ověření své emailové adresy <a href="{url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

################ Defined the email variables regarding manual request code for company user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: send_verification_code_manually_generated_by_user */
//$config['email_cc_unverified_account_manual_request_verification_code_company'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_manual_request_verification_code_company'] = 'catalinbasturescu@gmail.com';
//$config['email_from_unverified_account_manual_request_verification_code_company'] = 'registration@'.HTTP_HOST;
$config['email_from_unverified_account_manual_request_verification_code_company'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_unverified_account_manual_request_verification_code_company'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_manual_request_verification_code_company'] = 'Registration '.HTTP_HOST;
$config['email_subject_unverified_account_manual_request_verification_code_company'] = 'Váš nový ověřovací kód (company)';
$config['email_message_unverified_account_manual_request_verification_code_compnay'] = '<p>Company- <strong>Hello {company_name},</strong></p>
<p>You just requested a new verification code to be generated for your email address.</p>
<p>You can either copy and paste the verification code here below into the verification page.</p>
<p>Your new activation code is: {activation_code}</p> or
<p> <a href="{url}"><strong>Click Here</strong></a> to confirm your email address and activate your iPrace.online account or copy/paste the link below in your browser</p>
<p>{url}</p>
<p>Remember : you have {account_expiration_interval} after registration to confirm your email address and get access to your Travai account, otherwise your account will be removed from our database. You still have {account_expiration_time_left} to confirm your email.</p>

<hr>

<p><strong>Vážená {company_name},</strong></p>

<p>právě jste požádali o vytvoření nového ověřovacího kódu pro ověření vašeho účtu.</p>

<p>Pro ověření své emailové adresy <a href="{url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;


################ Defined the email variables regarding manual request code for male authorized physical person start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: send_verification_code_manually_generated_by_user */
//$config['email_cc_unverified_account_manual_request_verification_code_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_manual_request_verification_code_company_app_male'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_account_manual_request_verification_code_company_app_male'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_unverified_account_manual_request_verification_code_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_manual_request_verification_code_company_app_male'] = 'Registration '.HTTP_HOST;
$config['email_subject_unverified_account_manual_request_verification_code_company_app_male'] = 'Váš nový ověřovací kód (app male)';
$config['email_message_unverified_account_manual_request_verification_code_company_app_male'] = '<p><strong>(app male)Hello {name},</strong></p>
<p>You just requested a new verification code to be generated for your email address.</p>
<p>You can either copy and paste the verification code here below into the verification page.</p>
<p>Your new activation code is: {activation_code}</p>or <p> <a href="{url}"><strong>Click Here</strong></a> to confirm your email address and activate your iPrace.online account or copy/paste the link below in your browser</p><p>{url}</p>
<p>Remember : you have {account_expiration_interval} after registration to confirm your email address and get access to your Travai account, otherwise your account will be removed from our database.</p>
<p>You still have {account_expiration_time_left} to confirm your email.</p>

<hr>

<p><strong>Vážený {name},</strong></p>
<p>právě jste požádal o vytvoření nového ověřovacího kódu pro ověření Vašeho účtu.</p>

<p>Pro ověření své emailové adresy <a href="{url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;
################ Defined the email variables regarding manual request code for female authorized physical person start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: send_verification_code_manually_generated_by_user */
//$config['email_cc_unverified_account_manual_request_verification_code_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_manual_request_verification_code_company_app_female'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_account_manual_request_verification_code_company_app_female'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_unverified_account_manual_request_verification_code_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_manual_request_verification_code_company_app_female'] = 'Registration '.HTTP_HOST;
$config['email_subject_unverified_account_manual_request_verification_code_company_app_female'] = 'Váš nový ověřovací kód (app female)';
$config['email_message_unverified_account_manual_request_verification_code_company_app_female'] = '<p><strong>(app female)Hello {name},</strong></p>
<p>You just requested a new verification code to be generated for your email address.</p>
<p>You can either copy and paste the verification code here below into the verification page.</p>
<p>Your new activation code is: {activation_code}</p> or
<p><a href="{url}"><strong>Click Here</strong></a> to confirm your email address and activate your iPrace.online account or copy/paste the link below in your browser</p>
<p>{url}</p><p>Remember : you have {account_expiration_interval} after registration to confirm your email address and get access to your Travai account, otherwise your account will be removed from our database. You still have {account_expiration_time_left} to confirm your email.</p>
<hr>

<p><strong>Vážená {name},</strong></p>
<p>právě jste požádala o vytvoření nového ověřovacího kódu pro ověření Vašeho účtu.</p>

<p>Pro ověření své emailové adresy <a href="{url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

/*
|--------------------------------------------------------------------------
| Email Config Variables for delete the unverfied account
|--------------------------------------------------------------------------
|
*/
################ Defined the email variables regarding unverfierd male user for delete account start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: cron_remove_unverified_users_from_users_pending_verification_table */
/* Filename: application\modules\signin\controllers\Sigin_model.php */
/* Controller: user Method name: remove_unverified_user */
//$config['email_cc_unverified_user_account_delete_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_account_delete_personal_male'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_user_account_delete_personal_male'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_unverified_user_account_delete_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_account_delete_personal_male'] = 'Account Delete '.HTTP_HOST;
$config['email_subject_unverified_user_account_delete_personal_male'] = 'Váš účet byl odstraněn-(male)';
$config['email_message_unverified_user_account_delete_personal_male'] = '<p>Male: Dear {name},</p> 
<p>your account was removed from iPrace.online database due to missing validation.</p>
<p>You can register yourself again anytime in the future.</p>
<p>Hope to see you back, soon.</p>
<p>Best Regards.</p>

<hr>

<p><strong>Vážený {name},</strong></p>

<p>Váš email a účet byl z databáze Travai.cz odstraněn kvůli chybějícímu ověření.</p>

<p>Kdykoli v budoucnu se můžete registrovat znovu.</p>

<p>Děkujeme a doufáme, že se brzy vrátíte.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

################ Defined the email variables regarding unverfierd female user for delete account start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: cron_remove_unverified_users_from_users_pending_verification_table */
/* Filename: application\modules\signin\controllers\Sigin_model.php */
/* Controller: user Method name: remove_unverified_user */
//$config['email_cc_unverified_user_account_delete_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_account_delete_personal_female'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_user_account_delete_personal_female'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_unverified_user_account_delete_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_account_delete_personal_female'] = 'Account Delete '.HTTP_HOST;
$config['email_subject_unverified_user_account_delete_personal_female'] = 'Váš účet byl odstraněn (female)';
$config['email_message_unverified_user_account_delete_personal_female'] = '<p>Female- Dear {name},</p>
<p>your account was removed from iPrace.online database due to missing validation.</p>
<p>You can register yourself again anytime in the future.</p>
<p>Hope to see you back, soon.</p>
<p>Best Regards.</p>
<hr>

<p><strong>Vážená {name},</strong></p>

<p>Váš email a účet byl z databáze Travai.cz odstraněn kvůli chybějícímu ověření.</p>

<p>Kdykoli v budoucnu se můžete registrovat znovu.</p>

<p>Děkujeme a doufáme, že se brzy vrátíte.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

################ Defined the email variables regarding unverfierd company user for delete account start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: cron_remove_unverified_users_from_users_pending_verification_table */
/* Filename: application\modules\signin\controllers\Sigin_model.php */
/* Controller: user Method name: remove_unverified_user */
//$config['email_cc_unverified_user_account_delete_company'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_account_delete_company'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_user_account_delete_company'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_unverified_user_account_delete_company'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_account_delete_company'] = 'Account Delete '.HTTP_HOST;
$config['email_subject_unverified_user_account_delete_company'] = 'Váš účet byl odstraněn (company)';
$config['email_message_unverified_user_account_delete_company'] = '<p>Company- {company_name},</p>
<p>your account was removed from iPrace.online database due to missing validation.</p>
<p>You can register yourself again anytime in the future.</p>
<p>Hope to see you back, soon.</p>
<p>Best Regards.</p>

<hr>

<p><strong>Vážená {company_name},</strong></p>

<p>Váš účet byl z databáze Travai.cz odstraněn kvůli chybějícímu ověření.</p>

<p>Kdykoli v budoucnu se můžete zaregistrovat znovu.</p>

<p>Děkujeme a doufáme, že se brzy vrátíte.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

################ Defined the email variables regarding unverfierd accounts(company) for delete account end here

################ Defined the email variables regarding unverfierd male app for delete account start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: cron_remove_unverified_users_from_users_pending_verification_table */
/* Filename: application\modules\signin\controllers\Sigin_model.php */
/* Controller: user Method name: remove_unverified_user */
//$config['email_cc_unverified_user_account_delete_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_account_delete_company_app_male'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_user_account_delete_company_app_male'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_unverified_user_account_delete_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_account_delete_company_app_male'] = 'Account Delete '.HTTP_HOST;
$config['email_subject_unverified_user_account_delete_company_app_male'] = 'Váš účet byl odstraněn-(app male)';
$config['email_message_unverified_user_account_delete_company_app_male'] = '<p>(app male)Dear {name},</p> 
<p>your account was removed from iPrace.online database due to missing validation.</p>
<p>You can register yourself again anytime in the future.</p>
<p>Hope to see you back, soon.</p>
<p>Best Regards.</p>

<hr>

<p><strong>Vážený {name},</strong></p>

<p>Váš email a účet byl z databáze Travai.cz odstraněn kvůli chybějícímu ověření.</p>

<p>Kdykoli v budoucnu se můžete registrovat znovu.</p>

<p>Děkujeme a doufáme, že se brzy vrátíte.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

################ Defined the email variables regarding unverfierd female app for delete account start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: cron_remove_unverified_users_from_users_pending_verification_table */
/* Filename: application\modules\signin\controllers\Sigin_model.php */
/* Controller: user Method name: remove_unverified_user */
//$config['email_cc_unverified_user_account_delete_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_account_delete_company_app_female'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_user_account_delete_company_app_female'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_unverified_user_account_delete_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_account_delete_company_app_female'] = 'Account Delete '.HTTP_HOST;
$config['email_subject_unverified_user_account_delete_company_app_female'] = 'Váš účet byl odstraněn (app female)';
$config['email_message_unverified_user_account_delete_company_app_female'] = '<p>(app female)Dear {name},</p>
<p>your account was removed from iPrace.online database due to missing validation.</p>
<p>You can register yourself again anytime in the future.</p>
<p>Hope to see you back, soon.</p>
<p>Best Regards.</p>
<hr>

<p><strong>Vážená {name},</strong></p>

<p>Váš email a účet byl z databáze Travai.cz odstraněn kvůli chybějícímu ověření.</p>

<p>Kdykoli v budoucnu se můžete registrovat znovu.</p>

<p>Děkujeme a doufáme, že se brzy vrátíte.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

/*
|--------------------------------------------------------------------------
| Email Config Variables for send reminder to the unverfied account
|--------------------------------------------------------------------------
|
*/
################ Defined the email variables regarding unverfied male user for send reminders start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: ReminderUnverifiedUser */
// $config['email_cc_unverified_account_reminder_verification_code_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_reminder_verification_code_personal_male'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_account_reminder_verification_code_personal_male'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_unverified_account_reminder_verification_code_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_reminder_verification_code_personal_male'] = 'Reminder '.HTTP_HOST;
$config['email_subject_unverified_account_reminder_verification_code_personal_male'] = 'Připomenutí: potvrďte svoji registraci na travai.cz (male)';
$config['email_message_unverified_account_reminder_verification_code_personal_male'] = '<p>Male: <strong> Hello {name},</strong></p>
<p>You are receiving this email as you used to register at iPrace.online on {account_registration_date} at {account_registration_time}, and you still did not confirm your email address.</p>
<p>Your just one small step away from getting hundreds of job offers from people and companies all over the country looking for skilled professionals like you!</p>
<p>Your activation code is: {activation_code}</p>
<p>Remember : you have {account_expiration_interval} after registration to confirm your email address and get access to your Travai account, otherwise your account will be removed from our database. You still have {account_expiration_time_left} to confirm your email.</p>
<p> Please <a href="{signup_verification_url}"><strong>Click Here</strong></a> to confirm your email address and activate your iPrace.online account or copy/paste the link below in your browser</p>
<p>{signup_verification_url}</p>

<hr>

<p><strong>Vážený {name},</strong></p>

<p>tento email Vám byl odeslán při registraci na Travai.cz dne {account_registration_date} v {account_registration_time} a doposud nebyla potvrzena Vaše emailová adresa.</p>

<p>Pro ověření své emailové adresy <a href="{signup_verification_url}"><strong>klikněte zde</strong></a>nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{signup_verification_url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn. Stále máte {account_expiration_time_left}, abyste potvrdil svoji emailovou adresu.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

################ Defined the email variables regarding unverfierd female user for send reminders start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: ReminderUnverifiedUser */
// $config['email_cc_unverified_account_reminder_verification_code_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_reminder_verification_code_personal_female'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_account_reminder_verification_code_personal_female'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_unverified_account_reminder_verification_code_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_reminder_verification_code_personal_female'] = 'Reminder '.HTTP_HOST;
$config['email_subject_unverified_account_reminder_verification_code_personal_female'] = 'Připomenutí: potvrďte svoji registraci na travai.cz (female)';
$config['email_message_unverified_account_reminder_verification_code_personal_female'] = '<p>Female- <strong> Hello {name},</strong></p>
<p>You are receiving this email as you used to register at iPrace.online on {account_registration_date} at {account_registration_time}, and you still did not confirm your email address.</p>
<p>Your just one small step away from getting hundreds of job offers from people and companies all over the country looking for skilled professionals like you!</p>
<p>Your activation code is: {activation_code}</p>
<p>Remember : you have {account_expiration_interval} after registration to confirm your email address and get access to your Travai account, otherwise your account will be removed from our database. You still have {account_expiration_time_left} to confirm your email.</p>
<p> Please <a href="{signup_verification_url}"><strong>Click Here</strong></a> to confirm your email address and activate your iPrace.online account or copy/paste the link below in your browser</p>
<p>{signup_verification_url}</p>

<hr>

<p><strong>Vážená {name},</strong></p>

<p>tento email Vám byl odeslán při registraci na Travai.cz dne {account_registration_date} v {account_registration_time} a doposud nebyla potvrzena Vaše emailová adresa.</p>

<p>Pro ověření své emailové adresy <a href="{signup_verification_url}"><strong>klikněte zde</strong></a>nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{signup_verification_url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn. Stále máte {account_expiration_time_left}, abyste potvrdila svoji emailovou adresu.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

################ Defined the email variables regarding unverfierd company user for send reminders start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: ReminderUnverifiedUser */
// $config['email_cc_unverified_account_reminder_verification_code_company'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_reminder_verification_code_company'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_account_reminder_verification_code_company'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_unverified_account_reminder_verification_code_company'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_reminder_verification_code_company'] = 'Reminder '.HTTP_HOST;
$config['email_subject_unverified_account_reminder_verification_code_company'] = 'Připomenutí: potvrďte svoji registraci na travai.cz (company)';
$config['email_message_unverified_account_reminder_verification_code_company'] = '<p>company-<strong> Hello {company_name},</strong></p>
<p>You are receiving this email as you used to register at iPrace.online on {account_registration_date} at {account_registration_time}, and you still did not confirm your email address.</p>
<p>Your just one small step away from getting hundreds of job offers from people and companies all over the country looking for skilled professionals like you!</p>
<p>Your activation code is: {activation_code}</p>
<p>Remember : you have {account_expiration_interval} after registration to confirm your email address and get access to your Travai account, otherwise your account will be removed from our database. You still have {account_expiration_time_left} to confirm your email.</p>
<p> Please <a href="{signup_verification_url}"><strong>Click Here</strong></a> to confirm your email address and activate your iPrace.online account or copy/paste the link below in your browser</p>
<p>{signup_verification_url}</p>

<hr>

<p><strong>Vážená {company_name},</strong></p>

<p>tento email vám byl odeslán při registraci na Travai.cz dne {account_registration_date} v {account_registration_time} a doposud nebyla potvrzena vaše emailová adresa.</p>

<p>Pro ověření své emailové adresy <a href="{signup_verification_url}"><strong>klikněte zde</strong></a>nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{signup_verification_url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn. Stále máte {account_expiration_time_left}, abyste potvrdili svoji emailovou adresu.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

################ Defined the email variables regarding unverfierd accounts(company) for send reminders end here

################ Defined the email variables regarding unverfied male user for send reminders start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: ReminderUnverifiedUser */
// $config['email_cc_unverified_account_reminder_verification_code_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_reminder_verification_code_company_app_male'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_account_reminder_verification_code_company_app_male'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_unverified_account_reminder_verification_code_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_reminder_verification_code_company_app_male'] = 'Reminder '.HTTP_HOST;
$config['email_subject_unverified_account_reminder_verification_code_company_app_male'] = 'Připomenutí: potvrďte svoji registraci na travai.cz (app male)';
$config['email_message_unverified_account_reminder_verification_code_company_app_male'] = '<p><strong>(app male) Hello {name},</strong></p>
<p>You are receiving this email as you used to register at iPrace.online on {account_registration_date} at {account_registration_time}, and you still did not confirm your email address.</p>
<p>Your just one small step away from getting hundreds of job offers from people and companies all over the country looking for skilled professionals like you!</p>
<p>Your activation code is: {activation_code}</p>
<p>Remember : you have {account_expiration_interval} after registration to confirm your email address and get access to your Travai account, otherwise your account will be removed from our database. You still have {account_expiration_time_left} to confirm your email.</p>
<p> Please <a href="{signup_verification_url}"><strong>Click Here</strong></a> to confirm your email address and activate your iPrace.online account or copy/paste the link below in your browser</p>
<p>{signup_verification_url}</p>

<hr>

<p><strong>Vážený {name},</strong></p>

<p>tento email Vám byl odeslán při registraci na Travai.cz dne {account_registration_date} v {account_registration_time} a doposud nebyla potvrzena Vaše emailová adresa.</p>

<p>Pro ověření své emailové adresy <a href="{signup_verification_url}"><strong>klikněte zde</strong></a>nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{signup_verification_url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn. Stále máte {account_expiration_time_left}, abyste potvrdil svoji emailovou adresu.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

################ Defined the email variables regarding unverfierd female user for send reminders start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: ReminderUnverifiedUser */
// $config['email_cc_unverified_account_reminder_verification_code_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_reminder_verification_code_company_app_female'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_account_reminder_verification_code_company_app_female'] = 'registrace@'.HTTP_HOST;
$config['email_reply_to_unverified_account_reminder_verification_code_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_reminder_verification_code_company_app_female'] = 'Reminder '.HTTP_HOST;
$config['email_subject_unverified_account_reminder_verification_code_company_app_female'] = 'Připomenutí: potvrďte svoji registraci na travai.cz (app female)';
$config['email_message_unverified_account_reminder_verification_code_company_app_female'] = '<p><strong>(app female) Hello {name},</strong></p>
<p>You are receiving this email as you used to register at iPrace.online on {account_registration_date} at {account_registration_time}, and you still did not confirm your email address.</p>
<p>Your just one small step away from getting hundreds of job offers from people and companies all over the country looking for skilled professionals like you!</p>
<p>Your activation code is: {activation_code}</p>
<p>Remember : you have {account_expiration_interval} after registration to confirm your email address and get access to your Travai account, otherwise your account will be removed from our database. You still have {account_expiration_time_left} to confirm your email.</p>
<p> Please <a href="{signup_verification_url}"><strong>Click Here</strong></a> to confirm your email address and activate your iPrace.online account or copy/paste the link below in your browser</p>
<p>{signup_verification_url}</p>

<hr>

<p><strong>Vážená {name},</strong></p>

<p>tento email Vám byl odeslán při registraci na Travai.cz dne {account_registration_date} v {account_registration_time} a doposud nebyla potvrzena Vaše emailová adresa.</p>

<p>Pro ověření své emailové adresy <a href="{signup_verification_url}"><strong>klikněte zde</strong></a>nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{signup_verification_url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn. Stále máte {account_expiration_time_left}, abyste potvrdila svoji emailovou adresu.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.'
;

?>