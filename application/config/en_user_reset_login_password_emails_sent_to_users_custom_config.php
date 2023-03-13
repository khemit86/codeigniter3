<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Email Config Variables for Reset Login password 
|--------------------------------------------------------------------------
| 
*/		
/////////////////////////////////// Email variables for unverfied user start here ////////////////////

################ Defined the email variables regarding forgot password for unverified user(male) start here

/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_unverified_user_forgot_password_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_forgot_password_personal_male'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_user_forgot_password_personal_male'] = 'security@'.HTTP_HOST;
$config['email_reply_to_unverified_user_forgot_password_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_forgot_password_personal_male'] = 'Travai.cz Password Reset';
$config['email_subject_unverified_user_forgot_password_personal_male'] = '{name}, zde je Váš odkaz pro resetování hesla (unverified male)';
$config['email_message_unverified_user_forgot_password_personal_male'] = '<p>Dear {name},</p>
<p>Recently you made a request to reset your Travai account password.</p>
<p>To continue please ****Click Here**** or paste the following link into your browser:</p>
<p>{reset_password_request_link}</p>
<p>The link will expire in {reset_password_link_expire_time}, so be sure to use it right away.</p>
<p>If you did not make this request, please contact at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>
<p>Similarly, if you need additional help or further details or information about our services, please contact us at info@xxxxx.com or call (+420)xxx.xxx.xxx</p>
<hr>

<p>Vážený {name},</p>
<p>obdrželi jsme žádost o resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">link KLIKNĚTE ZDE</a> nebo vložte do svého prohlížeče následující odkaz:</p>
<p>{reset_password_request_link}</p>
<p>Platnost odkazu vyprší v {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';


################ Defined the email variables regarding forgot password for unverified user(female) start here
/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_unverified_user_forgot_password_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_forgot_password_personal_female'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_user_forgot_password_personal_female'] = 'security@'.HTTP_HOST;
$config['email_reply_to_unverified_user_forgot_password_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_forgot_password_personal_female'] = 'Travai.cz Password Reset';
$config['email_subject_unverified_user_forgot_password_personal_female'] = '{name}, zde je Váš odkaz pro resetování hesla (unverified female)';
$config['email_message_unverified_user_forgot_password_personal_female'] = '<p>Dear {name},</p>
<p>Recently you made a request to reset your Travai account password.</p>
<p>To continue please ****Click Here**** or paste the following link into your browser:</p>
<p>{reset_password_request_link}</p>
<p>The link will expire in {reset_password_link_expire_time}, so be sure to use it right away.</p>
<p>If you did not make this request, please contact at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>
<p>Similarly, if you need additional help or further details or information about our services, please contact us at info@xxxxx.com or call (+420)xxx.xxx.xxx</p>
<hr>
<p>Vážená {name},</p>
<p>obdrželi jsme žádost o resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">link KLIKNĚTE ZDE</a> nebo vložte do svého prohlížeče následující odkaz:</p>
<p>{reset_password_request_link}</p>
<p>Platnost odkazu vyprší v {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';


################ Defined the email variables regarding forgot password for unverified user(company) start here
/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_unverified_user_forgot_password_company'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_forgot_password_company'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_user_forgot_password_company'] = 'security@'.HTTP_HOST;
$config['email_reply_to_unverified_user_forgot_password_company'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_forgot_passwor_company'] = 'Travai.cz Password Reset';
$config['email_subject_unverified_user_forgot_password_company'] = '{company_name}, zde je Váš odkaz pro resetování hesla (unverified company)';
$config['email_message_unverified_user_forgot_password_company'] = '<p>{company_name},</p>
<p>Recently you made a request to reset your Travai account password.</p>
<p>To continue please ****Click Here**** or paste the following link into your browser:</p>
<p>{reset_password_request_link}</p>
<p>The link will expire in {reset_password_link_expire_time}, so be sure to use it right away.</p>
<p>If you did not make this request, please contact at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>
<p>Similarly, if you need additional help or further details or information about our services, please contact us at info@xxxxx.com or call (+420)xxx.xxx.xxx</p>



<hr>
<p>Vážená {company_name},</p>

<p>obdrželi jsme žádost o resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">link KLIKNĚTE ZDE</a> nebo vložte do svého prohlížeče následující odkaz:</p>
<p>{reset_password_request_link}</p>
<p>Platnost odkazu vyprší v {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedli nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';


################ Defined the email variables regarding forgot password for unverified user(male) start here

/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_unverified_user_forgot_password_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_forgot_password_company_app_male'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_user_forgot_password_company_app_male'] = 'security@'.HTTP_HOST;
$config['email_reply_to_unverified_user_forgot_password_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_forgot_password_company_app_male'] = 'Travai.cz Password Reset';
$config['email_subject_unverified_user_forgot_password_company_app_male'] = '{name}, zde je Váš odkaz pro resetování hesla (unverified app male)';
$config['email_message_unverified_user_forgot_password_company_app_male'] = '<p>(unverified app male)Dear {name},</p>
<p>Recently you made a request to reset your Travai account password.</p>
<p>To continue please ****Click Here**** or paste the following link into your browser:</p>
<p>{reset_password_request_link}</p>
<p>The link will expire in {reset_password_link_expire_time}, so be sure to use it right away.</p>
<p>If you did not make this request, please contact at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>
<p>Similarly, if you need additional help or further details or information about our services, please contact us at info@xxxxx.com or call (+420)xxx.xxx.xxx</p>
<hr>

<p>Vážený {name},</p>
<p>obdrželi jsme žádost o resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">link KLIKNĚTE ZDE</a> nebo vložte do svého prohlížeče následující odkaz:</p>
<p>{reset_password_request_link}</p>
<p>Platnost odkazu vyprší v {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';


################ Defined the email variables regarding forgot password for unverified user(female) start here
/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_unverified_user_forgot_password_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_forgot_password_company_app_female'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_user_forgot_password_company_app_female'] = 'security@'.HTTP_HOST;
$config['email_reply_to_unverified_user_forgot_password_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_forgot_password_company_app_female'] = 'Travai.cz Password Reset';
$config['email_subject_unverified_user_forgot_password_company_app_female'] = '{name}, zde je Váš odkaz pro resetování hesla (unverified app female)';
$config['email_message_unverified_user_forgot_password_company_app_female'] = '<p>(unverified app female)Dear {name},</p>
<p>Recently you made a request to reset your Travai account password.</p>
<p>To continue please ****Click Here**** or paste the following link into your browser:</p>
<p>{reset_password_request_link}</p>
<p>The link will expire in {reset_password_link_expire_time}, so be sure to use it right away.</p>
<p>If you did not make this request, please contact at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>
<p>Similarly, if you need additional help or further details or information about our services, please contact us at info@xxxxx.com or call (+420)xxx.xxx.xxx</p>
<hr>
<p>Vážená {name},</p>
<p>obdrželi jsme žádost o resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">link KLIKNĚTE ZDE</a> nebo vložte do svého prohlížeče následující odkaz:</p>
<p>{reset_password_request_link}</p>
<p>Platnost odkazu vyprší v {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';



################ Defined the email variables regarding reset password for unverified user(male) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_unverified_user_successful_reset_password_confirmation_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_successful_reset_password_confirmation_personal_male'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_user_successful_reset_password_confirmation_personal_male'] = 'security@'.HTTP_HOST;
$config['email_reply_to_unverified_user_successful_reset_password_confirmation_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_successful_reset_password_confirmation_personal_male'] = 'Travai.cz Password Reset';
$config['email_subject_unverified_user_successful_reset_password_confirmation_personal_male'] = 'Vaše heslo Travai účtu bylo úspěšně resetováno (unverified male)';

$config['email_message_unverified_user_successful_reset_password_confirmation_personal_male'] = '<p>Dear {name},</p>
<p>this is to confirm that you successfully reset the password of your Travai account that is pending verification.</p>
<p>Now you have the opportunity to successfully validate your email address and start using your account. In order to validate your email address we will ask you to login to Travai using the email address you used at registration time and the password that you just set</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your pending verification account, please contact us at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>

<hr>

<p>Vážený {name},</p>

<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto žádost neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';

################ Defined the email variables regarding reset password for unverified user(female) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_unverified_user_successful_reset_password_confirmation_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_successful_reset_password_confirmation_personal_female'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_user_successful_reset_password_confirmation_personal_female'] = 'security@'.HTTP_HOST;
$config['email_reply_to_unverified_user_successful_reset_password_confirmation_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_successful_reset_password_confirmation_personal_female'] = 'Travai.cz Password Reset';
$config['email_subject_unverified_user_successful_reset_password_confirmation_personal_female'] = 'Vaše heslo Travai účtu bylo úspěšně resetováno (unverified female)';

$config['email_message_unverified_user_successful_reset_password_confirmation_personal_female'] = '<p>Dear {name},</p>
<p>this is to confirm that you successfully reset the password of your Travai account that is pending verification.</p>
<p>Now you have the opportunity to successfully validate your email address and start using your account. In order to validate your email address we will ask you to login to Travai using the email address you used at registration time and the password that you just set</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your pending verification account, please contact us at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>

<hr>

<p>Vážená {name},</p>

<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>


<p>Pokud jste tuto žádost neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';

################ Defined the email variables regarding reset password for unverified user(company) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_unverified_user_successful_reset_password_confirmation_company'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_successful_reset_password_confirmation_company'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_user_successful_reset_password_confirmation_company'] = 'security@'.HTTP_HOST;
$config['email_reply_to_unverified_user_successful_reset_password_confirmation_company'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_successful_reset_password_confirmation_company'] = 'Travai.cz Password Reset';
$config['email_subject_unverified_user_successful_reset_password_confirmation_company'] = 'Vaše heslo Travai účtu bylo úspěšně resetováno (unverified company)';

$config['email_message_unverified_user_successful_reset_password_confirmation_company'] = '<p>{company_name},</p>
<p>this is to confirm that you successfully reset the password of your Travai account that is pending verification.</p>
<p>Now you have the opportunity to successfully validate your email address and start using your account. In order to validate your email address we will ask you to login to Travai using the email address you used at registration time and the password that you just set</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your pending verification account, please contact us at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>

<hr>

<p>Vážená {company_name},</p>

<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinili, můžete tento e-mail bezpečně ignorovat.</p>


<p>Pokud jste tuto žádost neprovedli nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';

################ Defined the email variables regarding reset password for unverified user(app male) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_unverified_user_successful_reset_password_confirmation_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_successful_reset_password_confirmation_company_app_male'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_user_successful_reset_password_confirmation_company_app_male'] = 'security@'.HTTP_HOST;
$config['email_reply_to_unverified_user_successful_reset_password_confirmation_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_successful_reset_password_confirmation_company_app_male'] = 'Travai.cz Password Reset';
$config['email_subject_unverified_user_successful_reset_password_confirmation_company_app_male'] = 'Vaše heslo Travai účtu bylo úspěšně resetováno (unverified app male)';

$config['email_message_unverified_user_successful_reset_password_confirmation_company_app_male'] = '<p>(unverified app male)Dear {name},</p>
<p>this is to confirm that you successfully reset the password of your Travai account that is pending verification.</p>
<p>Now you have the opportunity to successfully validate your email address and start using your account. In order to validate your email address we will ask you to login to Travai using the email address you used at registration time and the password that you just set</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your pending verification account, please contact us at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>

<hr>

<p>Vážený {name},</p>

<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto žádost neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';

################ Defined the email variables regarding reset password for unverified user(app female) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_unverified_user_successful_reset_password_confirmation_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_successful_reset_password_confirmation_company_app_female'] = 'catalinbasturescu@gmail.com';
$config['email_from_unverified_user_successful_reset_password_confirmation_company_app_female'] = 'security@'.HTTP_HOST;
$config['email_reply_to_unverified_user_successful_reset_password_confirmation_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_successful_reset_password_confirmation_company_app_female'] = 'Travai.cz Password Reset';
$config['email_subject_unverified_user_successful_reset_password_confirmation_company_app_female'] = 'Vaše heslo Travai účtu bylo úspěšně resetováno (unverified app female)';

$config['email_message_unverified_user_successful_reset_password_confirmation_company_app_female'] = '<p>(unverified app female)Dear {name},</p>
<p>this is to confirm that you successfully reset the password of your Travai account that is pending verification.</p>
<p>Now you have the opportunity to successfully validate your email address and start using your account. In order to validate your email address we will ask you to login to Travai using the email address you used at registration time and the password that you just set</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your pending verification account, please contact us at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>

<hr>

<p>Vážená {name},</p>

<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>


<p>Pokud jste tuto žádost neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';

/////////////////////////////////// Email variables for verfied user end here ////////////////////


/////////////////////////////////// Email variables for verfied user start here ////////////////////
################ Defined the email variables regarding forgot password for verified user(male) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_verified_user_forgot_password_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_forgot_password_personal_male'] = 'catalinbasturescu@gmail.com';
$config['email_from_verified_user_forgot_password_personal_male'] = 'security@'.HTTP_HOST;
$config['email_reply_to_verified_user_forgot_password_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_forgot_password_personal_male'] = 'travai.cz Password Reset';
$config['email_subject_verified_user_forgot_password_personal_male'] = '{name}, posíláme odkaz pro resetování hesla (verified male)';

$config['email_message_verified_user_forgot_password_personal_male'] = '<p>Dear {name},</p>
<p>Recently you made a request to reset your Travail account password.</p>
<p>To continue please Click Here or paste the following link into your browser:</p>
<p>{reset_password_request_link}</p>
<p>The link will expire in {reset_password_link_expire_time}, so be sure to use it right away.</p>
<p>If you did not make this request or you believe an unauthorized person has accessed your account, please contact at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>
<p>Similarly, if you need additional help or further details or information about our services, please contact us at info@xxxxx.com or call (+420)xxx.xxx.xxx</p>
<hr>

<p>Vážený {name},</p>
<p>obdrželi jsme žádost o resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">link KLIKNĚTE ZDE</a> nebo vložte do svého prohlížeče následující odkaz:</p>
<p>{reset_password_request_link}</p>
<p>Platnost odkazu vyprší v {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';




################ Defined the email variables regarding forgot password for verified user(female) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_verified_user_forgot_password_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_forgot_password_personal_female'] = 'catalinbasturescu@gmail.com';
$config['email_from_verified_user_forgot_password_personal_female'] = 'security@'.HTTP_HOST;
$config['email_reply_to_verified_user_forgot_password_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_forgot_password_personal_female'] = 'Travai.cz Password Reset';
$config['email_subject_verified_user_forgot_password_personal_female'] = '{name}, posíláme odkaz pro resetování hesla (verified female)';

$config['email_message_verified_user_forgot_password_personal_female'] = '<p>Dear {name},</p>
<p>Recently you made a request to reset your Travail account password.</p>
<p>To continue please Click Here or paste the following link into your browser:</p>
<p>{reset_password_request_link}</p>
<p>The link will expire in {reset_password_link_expire_time}, so be sure to use it right away.</p>
<p>If you did not make this request or you believe an unauthorized person has accessed your account, please contact at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>
<p>Similarly, if you need additional help or further details or information about our services, please contact us at info@xxxxx.com or call (+420)xxx.xxx.xxx</p>
<hr>
<p>Vážená {name},</p>

<p>obdrželi jsme žádost o resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">link KLIKNĚTE ZDE</a> nebo vložte do svého prohlížeče následující odkaz:</p>
<p>{reset_password_request_link}</p>
<p>Platnost odkazu vyprší v {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';
################ Defined the email variables regarding forgot password for verified user(company) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_verified_user_forgot_password_company'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_forgot_password_company'] = 'catalinbasturescu@gmail.com';
$config['email_from_verified_user_forgot_password_company'] = 'security@'.HTTP_HOST;
$config['email_reply_to_verified_user_forgot_password_company'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_forgot_password_company'] = 'Travai.cz Password Reset';
$config['email_subject_verified_user_forgot_password_company'] = '{company_name}, posíláme odkaz pro resetování hesla (verified company)';

$config['email_message_verified_user_forgot_password_company'] = '<p>{company_name},</p>
<p>Recently you made a request to reset your Travail account password.</p>
<p>To continue please Click Here or paste the following link into your browser:</p>
<p>{reset_password_request_link}</p>
<p>The link will expire in {reset_password_link_expire_time}, so be sure to use it right away.</p>
<p>If you did not make this request or you believe an unauthorized person has accessed your account, please contact at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>
<p>Similarly, if you need additional help or further details or information about our services, please contact us at info@xxxxx.com or call (+420)xxx.xxx.xxx</p>
<hr>

<p>Vážená {company_name},</p>

<p>obdrželi jsme žádost o resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">link KLIKNĚTE ZDE</a> nebo vložte do svého prohlížeče následující odkaz:</p>
<p>{reset_password_request_link}</p>
<p>Platnost odkazu vyprší v {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedli nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';

################ Defined the email variables regarding forgot password for verified user(male) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_verified_user_forgot_password_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_forgot_password_company_app_male'] = 'catalinbasturescu@gmail.com';
$config['email_from_verified_user_forgot_password_company_app_male'] = 'security@'.HTTP_HOST;
$config['email_reply_to_verified_user_forgot_password_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_forgot_password_company_app_male'] = 'travai.cz Password Reset';
$config['email_subject_verified_user_forgot_password_company_app_male'] = '{name}, posíláme odkaz pro resetování hesla (app verified male)';

$config['email_message_verified_user_forgot_password_company_app_male'] = '<p>(app verified male)Dear {name},</p>
<p>Recently you made a request to reset your Travail account password.</p>
<p>To continue please Click Here or paste the following link into your browser:</p>
<p>{reset_password_request_link}</p>
<p>The link will expire in {reset_password_link_expire_time}, so be sure to use it right away.</p>
<p>If you did not make this request or you believe an unauthorized person has accessed your account, please contact at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>
<p>Similarly, if you need additional help or further details or information about our services, please contact us at info@xxxxx.com or call (+420)xxx.xxx.xxx</p>
<hr>

<p>Vážený {name},</p>
<p>obdrželi jsme žádost o resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">link KLIKNĚTE ZDE</a> nebo vložte do svého prohlížeče následující odkaz:</p>
<p>{reset_password_request_link}</p>
<p>Platnost odkazu vyprší v {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';




################ Defined the email variables regarding forgot password for verified user(female) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_verified_user_forgot_password_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_forgot_password_company_app_female'] = 'catalinbasturescu@gmail.com';
$config['email_from_verified_user_forgot_password_company_app_female'] = 'security@'.HTTP_HOST;
$config['email_reply_to_verified_user_forgot_password_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_forgot_password_company_app_female'] = 'Travai.cz Password Reset(app female verified)';
$config['email_subject_verified_user_forgot_password_company_app_female'] = '{name}, posíláme odkaz pro resetování hesla (verified female)';

$config['email_message_verified_user_forgot_password_company_app_female'] = '<p>Dear {name},</p>
<p>Recently you made a request to reset your Travail account password.</p>
<p>To continue please Click Here or paste the following link into your browser:</p>
<p>{reset_password_request_link}</p>
<p>The link will expire in {reset_password_link_expire_time}, so be sure to use it right away.</p>
<p>If you did not make this request or you believe an unauthorized person has accessed your account, please contact at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>
<p>Similarly, if you need additional help or further details or information about our services, please contact us at info@xxxxx.com or call (+420)xxx.xxx.xxx</p>
<hr>
<p>Vážená {name},</p>

<p>obdrželi jsme žádost o resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">link KLIKNĚTE ZDE</a> nebo vložte do svého prohlížeče následující odkaz:</p>
<p>{reset_password_request_link}</p>
<p>Platnost odkazu vyprší v {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';



################ Defined the email variables regarding reset password email for verified user(male) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_verified_user_successful_reset_password_confirmation_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_successful_reset_password_confirmation_personal_male'] = 'catalinbasturescu@gmail.com';
$config['email_from_verified_user_successful_reset_password_confirmation_personal_male'] = 'security@'.HTTP_HOST;
$config['email_reply_to_verified_user_successful_reset_password_confirmation_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_successful_reset_password_confirmation_personal_male'] = 'Travai.cz Password Reset';
$config['email_subject_verified_user_successful_reset_password_confirmation_personal_male'] = 'Vaše heslo Travai účtu bylo úspěšně resetováno (male verified)';

$config['email_message_verified_user_successful_reset_password_confirmation_personal_male'] = '<p>Dear {name},</p>
<p>The password for your iPrace.online account has been successfully reset.</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your account, go to your account management page (DYNAMIC LINK TO BE ADDED LATER ON) to reset your password without delay. Following this, review and update your account security settings.</p>
<p>If you need additional help, please contact us at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>

<hr>

<p>Vážený {name},</p>
<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>


<p>Pokud jste tuto žádost neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';
################ Defined the email variables regarding reset password email for verified user(female) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_verified_user_successful_reset_password_confirmation_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_successful_reset_password_confirmation_personal_female'] = 'catalinbasturescu@gmail.com';
$config['email_from_verified_user_successful_reset_password_confirmation_personal_female'] = 'security@'.HTTP_HOST;
$config['email_reply_to_verified_user_successful_reset_password_confirmation_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_successful_reset_password_confirmation_personal_female'] = 'Travai.cz Password Reset';
$config['email_subject_verified_user_successful_reset_password_confirmation_personal_female'] = 'Vaše heslo Travai účtu bylo úspěšně resetováno (female verified)';
$config['email_message_verified_user_successful_reset_password_confirmation_personal_female'] = '<p>Dear {name},</p>
<p>The password for your iPrace.online account has been successfully reset.</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your account, go to your account management page (DYNAMIC LINK TO BE ADDED LATER ON) to reset your password without delay. Following this, review and update your account security settings.</p>
<p>If you need additional help, please contact us at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>
<hr>

<p>Vážená {name},</p>
<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>


<p>Pokud jste tuto žádost neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';
################ Defined the email variables regarding reset password email for verified user(company) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_verified_user_successful_reset_password_confirmation_company'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_successful_reset_password_confirmation_company'] = 'catalinbasturescu@gmail.com';
$config['email_from_verified_user_successful_reset_password_confirmation_company'] = 'security@'.HTTP_HOST;
$config['email_reply_to_verified_user_successful_reset_password_confirmation_company'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_successful_reset_password_confirmation_company'] = 'Travai.cz Password Reset';
$config['email_subject_verified_user_successful_reset_password_confirmation_company'] = 'Vaše heslo Travai účtu bylo úspěšně resetováno (company verified)';
$config['email_message_verified_user_successful_reset_password_confirmation_company'] = '<p>{company_name},</p>
<p>The password for your iPrace.online account has been successfully reset.</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your account, go to your account management page (DYNAMIC LINK TO BE ADDED LATER ON) to reset your password without delay. Following this, review and update your account security settings.</p>
<p>If you need additional help, please contact us at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>

<hr>
<p>Vážená {company_name},</p>
<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinili, můžete tento e-mail bezpečně ignorovat.</p>


<p>Pokud jste tuto žádost neprovedli nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';

################ Defined the email variables regarding reset password email for verified user(app male) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_verified_user_successful_reset_password_confirmation_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_successful_reset_password_confirmation_company_app_male'] = 'catalinbasturescu@gmail.com';
$config['email_from_verified_user_successful_reset_password_confirmation_company_app_male'] = 'security@'.HTTP_HOST;
$config['email_reply_to_verified_user_successful_reset_password_confirmation_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_successful_reset_password_confirmation_company_app_male'] = 'Travai.cz Password Reset';
$config['email_subject_verified_user_successful_reset_password_confirmation_company_app_male'] = 'Vaše heslo Travai účtu bylo úspěšně resetováno (male app verified)';

$config['email_message_verified_user_successful_reset_password_confirmation_company_app_male'] = '<p>(male app verified)Dear {name},</p>
<p>The password for your iPrace.online account has been successfully reset.</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your account, go to your account management page (DYNAMIC LINK TO BE ADDED LATER ON) to reset your password without delay. Following this, review and update your account security settings.</p>
<p>If you need additional help, please contact us at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>

<hr>

<p>Vážený {name},</p>
<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>


<p>Pokud jste tuto žádost neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';
################ Defined the email variables regarding reset password email for verified user(app female) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_verified_user_successful_reset_password_confirmation_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_successful_reset_password_confirmation_company_app_female'] = 'catalinbasturescu@gmail.com';
$config['email_from_verified_user_successful_reset_password_confirmation_company_app_female'] = 'security@'.HTTP_HOST;
$config['email_reply_to_verified_user_successful_reset_password_confirmation_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_successful_reset_password_confirmation_company_app_female'] = 'Travai.cz Password Reset';
$config['email_subject_verified_user_successful_reset_password_confirmation_company_app_female'] = 'Vaše heslo Travai účtu bylo úspěšně resetováno (female app verified)';
$config['email_message_verified_user_successful_reset_password_confirmation_company_app_female'] = '<p>(female app verified)Dear {name},</p>
<p>The password for your iPrace.online account has been successfully reset.</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your account, go to your account management page (DYNAMIC LINK TO BE ADDED LATER ON) to reset your password without delay. Following this, review and update your account security settings.</p>
<p>If you need additional help, please contact us at help@xxxxx.com or call (+420)xxx.xxx.xxx, as soon as possible.</p>
<hr>

<p>Vážená {name},</p>
<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>


<p>Pokud jste tuto žádost neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';
/////////////////////////////////// Email variables for verfied user end here ////////////////////	
?>