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
$config['email_bcc_unverified_user_forgot_password_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_user_forgot_password_personal_male'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_unverified_user_forgot_password_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_forgot_password_personal_male'] = 'Travai Podpora';
$config['email_subject_unverified_user_forgot_password_personal_male'] = 'Odkaz pro resetování hesla';

$config['email_message_unverified_user_forgot_password_personal_male'] = 'Vážený {name},

<p>obdrželi jsme žádost pro resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">klikněte zde</a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>
<p>{reset_password_request_link}</p>

<p>Platnost odkazu vyprší za {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Vídeňská 297/99
<br>
Brno-střed, Štýřice
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

################ Defined the email variables regarding forgot password for unverified user(female) start here
/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_unverified_user_forgot_password_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_forgot_password_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_user_forgot_password_personal_female'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_unverified_user_forgot_password_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_forgot_password_personal_female'] = 'Travai Podpora';
$config['email_subject_unverified_user_forgot_password_personal_female'] = 'Odkaz pro resetování hesla';

$config['email_message_unverified_user_forgot_password_personal_female'] = 'Vážená {name},

<p>obdrželi jsme žádost pro resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">klikněte zde</a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>
<p>{reset_password_request_link}</p>

<p>Platnost odkazu vyprší za {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Vídeňská 297/99
<br>
Brno-střed, Štýřice
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';


################ Defined the email variables regarding forgot password for unverified user(company) start here
/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_unverified_user_forgot_password_company'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_forgot_password_company'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_user_forgot_password_company'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_unverified_user_forgot_password_company'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_forgot_passwor_company'] = 'Travai Podpora';
$config['email_subject_unverified_user_forgot_password_company'] = 'Odkaz pro resetování hesla';

$config['email_message_unverified_user_forgot_password_company'] = 'Vážení {company_name},

<p>obdrželi jsme žádost pro resetování hesla vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">klikněte zde</a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>
<p>{reset_password_request_link}</p>
<p>Platnost odkazu vyprší za {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedli nebo se domníváte, že k vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Vídeňská 297/99
<br>
Brno-střed, Štýřice
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';


################ Defined the email variables regarding forgot password for unverified app(male) start here

/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_unverified_user_forgot_password_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_forgot_password_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_user_forgot_password_company_app_male'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_unverified_user_forgot_password_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_forgot_password_company_app_male'] = 'Travai Podpora';
$config['email_subject_unverified_user_forgot_password_company_app_male'] = 'Odkaz pro resetování hesla';

$config['email_message_unverified_user_forgot_password_company_app_male'] = 'Vážený {name},

<p>obdrželi jsme žádost pro resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">klikněte zde</a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>
<p>{reset_password_request_link}</p>
<p>Platnost odkazu vyprší za {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Vídeňská 297/99
<br>
Brno-střed, Štýřice
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

################ Defined the email variables regarding forgot password for unverified app(female) start here
/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_unverified_user_forgot_password_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_forgot_password_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_user_forgot_password_company_app_female'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_unverified_user_forgot_password_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_forgot_password_company_app_female'] = 'Travai Podpora';
$config['email_subject_unverified_user_forgot_password_company_app_female'] = 'Odkaz pro resetování hesla';

$config['email_message_unverified_user_forgot_password_company_app_female'] = 'Vážená {name},

<p>obdrželi jsme žádost pro resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">klikněte zde</a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>
<p>{reset_password_request_link}</p>
<p>Platnost odkazu vyprší za {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Vídeňská 297/99
<br>
Brno-střed, Štýřice
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

################ Defined the email variables regarding reset password for unverified user(male) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_unverified_user_successful_reset_password_confirmation_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_successful_reset_password_confirmation_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_user_successful_reset_password_confirmation_personal_male'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_unverified_user_successful_reset_password_confirmation_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_successful_reset_password_confirmation_personal_male'] = 'Travai Podpora';
$config['email_subject_unverified_user_successful_reset_password_confirmation_personal_male'] = 'Heslo Travai účtu bylo resetováno';

$config['email_message_unverified_user_successful_reset_password_confirmation_personal_male'] = 'Vážený {name},

<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto žádost neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Vídeňská 297/99
<br>
Brno-střed, Štýřice
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

################ Defined the email variables regarding reset password for unverified user(female) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_unverified_user_successful_reset_password_confirmation_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_successful_reset_password_confirmation_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_user_successful_reset_password_confirmation_personal_female'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_unverified_user_successful_reset_password_confirmation_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_successful_reset_password_confirmation_personal_female'] = 'Travai Podpora';
$config['email_subject_unverified_user_successful_reset_password_confirmation_personal_female'] = 'Heslo Travai účtu bylo resetováno';

$config['email_message_unverified_user_successful_reset_password_confirmation_personal_female'] = 'Vážená {name},

<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto žádost neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Brno, Vídeňská 297/99
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

################ Defined the email variables regarding reset password for unverified user(company) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_unverified_user_successful_reset_password_confirmation_company'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_successful_reset_password_confirmation_company'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_user_successful_reset_password_confirmation_company'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_unverified_user_successful_reset_password_confirmation_company'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_successful_reset_password_confirmation_company'] = 'Travai Podpora';
$config['email_subject_unverified_user_successful_reset_password_confirmation_company'] = 'Heslo Travai účtu bylo resetováno';

$config['email_message_unverified_user_successful_reset_password_confirmation_company'] = 'Vážení {company_name},

<p>potvrzujeme, že přihlašovací heslo k vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinili, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto žádost neprovedli nebo se domníváte, že k vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Brno, Vídeňská 297/99
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

################ Defined the email variables regarding reset password for unverified user(app male) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_unverified_user_successful_reset_password_confirmation_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_successful_reset_password_confirmation_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_user_successful_reset_password_confirmation_company_app_male'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_unverified_user_successful_reset_password_confirmation_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_successful_reset_password_confirmation_company_app_male'] = 'Travai Podpora';
$config['email_subject_unverified_user_successful_reset_password_confirmation_company_app_male'] = 'Heslo Travai účtu bylo resetováno';

$config['email_message_unverified_user_successful_reset_password_confirmation_company_app_male'] = 'Vážený {name},

<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto žádost neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Brno, Vídeňská 297/99
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

################ Defined the email variables regarding reset password for unverified user(app female) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_unverified_user_successful_reset_password_confirmation_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_successful_reset_password_confirmation_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_user_successful_reset_password_confirmation_company_app_female'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_unverified_user_successful_reset_password_confirmation_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_successful_reset_password_confirmation_company_app_female'] = 'Travai Podpora';
$config['email_subject_unverified_user_successful_reset_password_confirmation_company_app_female'] = 'Heslo Travai účtu bylo resetováno';

$config['email_message_unverified_user_successful_reset_password_confirmation_company_app_female'] = 'Vážená {name},

<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto žádost neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Brno, Vídeňská 297/99
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

/////////////////////////////////// Email variables for verfied user end here ////////////////////


/////////////////////////////////// Email variables for verfied user start here ////////////////////
################ Defined the email variables regarding forgot password for verified user(male) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_verified_user_forgot_password_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_forgot_password_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_from_verified_user_forgot_password_personal_male'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_verified_user_forgot_password_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_forgot_password_personal_male'] = 'Travai Podpora';
$config['email_subject_verified_user_forgot_password_personal_male'] = 'Odkaz pro resetování hesla';

$config['email_message_verified_user_forgot_password_personal_male'] = 'Vážený {name},

<p>obdrželi jsme žádost pro resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">klikněte zde</a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>
<p>{reset_password_request_link}</p>

<p>Platnost odkazu vyprší za {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Vídeňská 297/99
<br>
Brno-střed, Štýřice
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';




################ Defined the email variables regarding forgot password for verified user(female) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_verified_user_forgot_password_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_forgot_password_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_from_verified_user_forgot_password_personal_female'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_verified_user_forgot_password_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_forgot_password_personal_female'] = 'Travai Podpora';
$config['email_subject_verified_user_forgot_password_personal_female'] = 'Odkaz pro resetování hesla';

$config['email_message_verified_user_forgot_password_personal_female'] = 'Vážená {name},

<p>obdrželi jsme žádost pro resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">klikněte zde</a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>
<p>{reset_password_request_link}</p>

<p>Platnost odkazu vyprší za {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Vídeňská 297/99
<br>
Brno-střed, Štýřice
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

################ Defined the email variables regarding forgot password for verified user(company) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_verified_user_forgot_password_company'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_forgot_password_company'] = 'catalin.basturescu@gmail.com';
$config['email_from_verified_user_forgot_password_company'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_verified_user_forgot_password_company'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_forgot_password_company'] = 'Travai Podpora';
$config['email_subject_verified_user_forgot_password_company'] = 'Odkaz pro resetování hesla';

$config['email_message_verified_user_forgot_password_company'] = 'Vážení {company_name},

<p>obdrželi jsme žádost pro resetování hesla vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">klikněte zde</a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>
<p>{reset_password_request_link}</p>
<p>Platnost odkazu vyprší za {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedli nebo se domníváte, že k vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Vídeňská 297/99
<br>
Brno-střed, Štýřice
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

################ Defined the email variables regarding forgot password for verified user(app male) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_verified_user_forgot_password_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_forgot_password_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_from_verified_user_forgot_password_company_app_male'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_verified_user_forgot_password_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_forgot_password_company_app_male'] = 'Travai Podpora';
$config['email_subject_verified_user_forgot_password_company_app_male'] = 'Odkaz pro resetování hesla';

$config['email_message_verified_user_forgot_password_company_app_male'] = 'Vážený {name},

<p>obdrželi jsme žádost pro resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">klikněte zde</a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>
<p>{reset_password_request_link}</p>

<p>Platnost odkazu vyprší za {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Vídeňská 297/99
<br>
Brno-střed, Štýřice
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';


################ Defined the email variables regarding forgot password for verified user(app female) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: recover_password_ajax */
//$config['email_cc_verified_user_forgot_password_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_forgot_password_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_from_verified_user_forgot_password_company_app_female'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_verified_user_forgot_password_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_forgot_password_company_app_female'] = 'Travai Podpora';
$config['email_subject_verified_user_forgot_password_company_app_female'] = 'Odkaz pro resetování hesla';

$config['email_message_verified_user_forgot_password_company_app_female'] = 'Vážená {name},

<p>obdrželi jsme žádost pro resetování hesla Vašeho Travai účtu, dne {reset_password_request_time} z IP adresy {reset_password_request_source_ip}.</p>

<p>Chcete-li pokračovat, <a href="{reset_password_request_link}">klikněte zde</a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>
<p>{reset_password_request_link}</p>

<p>Platnost odkazu vyprší za {reset_password_link_expire_time}, proto jej použijte co nejdříve.</p>

<p>Pokud jste tuto žádost neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Vídeňská 297/99
<br>
Brno-střed, Štýřice
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

################ Defined the email variables regarding reset password email for verified user(male) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_verified_user_successful_reset_password_confirmation_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_successful_reset_password_confirmation_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_from_verified_user_successful_reset_password_confirmation_personal_male'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_verified_user_successful_reset_password_confirmation_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_successful_reset_password_confirmation_personal_male'] = 'Travai Podpora';
$config['email_subject_verified_user_successful_reset_password_confirmation_personal_male'] = 'Heslo Travai účtu bylo resetováno';

$config['email_message_verified_user_successful_reset_password_confirmation_personal_male'] = 'Vážený {name},

<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto žádost neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Vídeňská 297/99
<br>
Brno-střed, Štýřice
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

################ Defined the email variables regarding reset password email for verified user(female) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_verified_user_successful_reset_password_confirmation_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_successful_reset_password_confirmation_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_from_verified_user_successful_reset_password_confirmation_personal_female'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_verified_user_successful_reset_password_confirmation_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_successful_reset_password_confirmation_personal_female'] = 'Travai Podpora';
$config['email_subject_verified_user_successful_reset_password_confirmation_personal_female'] = 'Heslo Travai účtu bylo resetováno';

$config['email_message_verified_user_successful_reset_password_confirmation_personal_female'] = 'Vážená {name},

<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto žádost neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Vídeňská 297/99
<br>
Brno-střed, Štýřice
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

################ Defined the email variables regarding reset password email for verified user(company) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_verified_user_successful_reset_password_confirmation_company'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_successful_reset_password_confirmation_company'] = 'catalin.basturescu@gmail.com';
$config['email_from_verified_user_successful_reset_password_confirmation_company'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_verified_user_successful_reset_password_confirmation_company'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_successful_reset_password_confirmation_company'] = 'Travai Podpora';
$config['email_subject_verified_user_successful_reset_password_confirmation_company'] = 'Heslo Travai účtu bylo resetováno';

$config['email_message_verified_user_successful_reset_password_confirmation_company'] = 'Vážení {company_name},

<p>potvrzujeme, že přihlašovací heslo k vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinili, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto žádost neprovedli nebo se domníváte, že k vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Vídeňská 297/99
<br>
Brno-střed, Štýřice
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

################ Defined the email variables regarding reset password email for verified user(app male) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_verified_user_successful_reset_password_confirmation_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_successful_reset_password_confirmation_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_from_verified_user_successful_reset_password_confirmation_company_app_male'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_verified_user_successful_reset_password_confirmation_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_successful_reset_password_confirmation_company_app_male'] = 'Travai Podpora';
$config['email_subject_verified_user_successful_reset_password_confirmation_company_app_male'] = 'Heslo Travai účtu bylo resetováno';

$config['email_message_verified_user_successful_reset_password_confirmation_company_app_male'] = 'Vážený {name},

<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto žádost neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Vídeňská 297/99
<br>
Brno-střed, Štýřice
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

################ Defined the email variables regarding reset password email for verified user(app female) start here
/* Filename: application\modules\forgot_password\controllers\Fogot_password.php */
/* Controller: Forgot_password Method name: reset_password_ajax */
//$config['email_cc_verified_user_successful_reset_password_confirmation_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_verified_user_successful_reset_password_confirmation_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_from_verified_user_successful_reset_password_confirmation_company_app_female'] = 'no-reply@'.HTTP_HOST;
//$config['email_reply_to_verified_user_successful_reset_password_confirmation_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_verified_user_successful_reset_password_confirmation_company_app_female'] = 'Travai Podpora';
$config['email_subject_verified_user_successful_reset_password_confirmation_company_app_female'] = 'Heslo Travai účtu bylo resetováno';

$config['email_message_verified_user_successful_reset_password_confirmation_company_app_female'] = 'Vážená {name},

<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo resetováno dne {successful_reset_password_time} z IP adresy {successful_reset_password_source_ip}.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto žádost neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým</p>

<hr>
Travai agentura, s.r.o.
<br>
Vídeňská 297/99
<br>
Brno-střed, Štýřice
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';
/////////////////////////////////// Email variables for verfied user end here ////////////////////

?>