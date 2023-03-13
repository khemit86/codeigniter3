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
$config['welcome_email_bcc_signup_personal_male'] = 'catalin.basturescu@gmail.com';
$config['welcome_email_from_signup_personal_male'] = 'registrace@'.HTTP_HOST;
//$config['welcome_email_reply_to_signup_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['welcome_email_from_name_signup_personal_male'] = 'Travai Registrace';

$config['welcome_email_subject_signup_personal_male'] = 'Vítejte na travai.cz - ověřte Vaši emailovou adresu';
$config['welcome_email_message_signup_personal_male'] = 'Vážený {name},

<p>vítejte na Travai.cz - portál, který pomáhá spojit, komunikovat, spolupracovat a mít příležitosti k osobnímu růstu.</p>

<p>Už jen jeden krok Vás dělí od kontaktu s lidmi a společnostmi z celé země.</p>

<p>Abyste mohl začít používat Travai.cz, musíte ověřit Vaši emailovou adresu.</p>

<p>Pro ověření <a href="{signup_verification_url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>

<p>{signup_verification_url}</p>

<p>Aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>

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

################ Defined the email variables regarding welcome email for female user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: save_user_signup_data */
//updated on 2.11.2019
//$config['welcome_email_cc_signup_personal_female'] = 'catalin.basturescu@gmail.com';
$config['welcome_email_bcc_signup_personal_female'] = 'catalin.basturescu@gmail.com';
$config['welcome_email_from_signup_personal_female'] = 'registrace@'.HTTP_HOST;
//$config['welcome_email_reply_to_signup_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['welcome_email_from_name_signup_personal_female'] = 'Travai Registrace';
$config['welcome_email_subject_signup_personal_female'] = 'Vítejte na travai.cz - ověřte Vaši emailovou adresu';

$config['welcome_email_message_signup_personal_female'] = 'Vážená {name},

<p>vítejte na Travai.cz - portál, který pomáhá spojit, komunikovat, spolupracovat a mít příležitosti k osobnímu růstu.</p>

<p>Už jen jeden krok Vás dělí od kontaktu s lidmi a společnostmi z celé země.</p>

<p>Abyste mohla začít používat Travai.cz, musíte ověřit Vaši emailovou adresu.</p>

<p>Pro ověření <a href="{signup_verification_url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>

<p>{signup_verification_url}</p>

<p>Aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>

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

################ Defined the email variables regarding welcome email for company user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: save_user_signup_data */
//$config['welcome_email_cc_signup_company'] = 'catalin.basturescu@gmail.com';
$config['welcome_email_bcc_signup_company'] = 'catalin.basturescu@gmail.com';
$config['welcome_email_from_signup_company'] = 'registrace@'.HTTP_HOST;
//$config['welcome_email_reply_to_signup_company'] = 'no-reply@'.HTTP_HOST;
$config['welcome_email_from_name_signup_company'] = 'Travai Registrace';
$config['welcome_email_subject_signup_company'] = 'Vítejte na travai.cz - ověřte vaši emailovou adresu';

$config['welcome_email_message_signup_company'] = 'Vážení {company_name},

<p>vítejte na Travai.cz - portál, který pomáhá spojit, komunikovat, spolupracovat a mít příležitosti k osobnímu růstu.</p>

<p>Zjednodušte si cestu získávání nových příležitostí a podpořte růst svého podnikání.</p>

<p>Abyste mohli začít používat Travai.cz, musíte ověřit vaši emailovou adresu.</p>

<p>Pro ověření <a href="{signup_verification_url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>

<p>{signup_verification_url}</p>

<p>Aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>

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


################ Defined the email variables regarding welcome email for male authorized physical person start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: save_user_signup_data */
//$config['welcome_email_cc_signup_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['welcome_email_bcc_signup_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['welcome_email_from_signup_company_app_male'] = 'registrace@'.HTTP_HOST;
//$config['welcome_email_reply_to_signup_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['welcome_email_from_name_signup_company_app_male'] = 'Travai Registrace';
$config['welcome_email_subject_signup_company_app_male'] = 'Vítejte na travai.cz - ověřte Vaši emailovou adresu';
$config['welcome_email_message_signup_company_app_male'] = 'Vážený {name},

<p>vítejte na Travai.cz - portál, který pomáhá spojit, komunikovat, spolupracovat a mít příležitosti k osobnímu růstu.</p>

<p>Už jen jeden krok Vás dělí od kontaktu s lidmi a společnostmi z celé země.</p>

<p>Abyste mohl začít používat Travai.cz, musíte ověřit Vaši emailovou adresu.</p>

<p>Pro ověření <a href="{signup_verification_url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>

<p>{signup_verification_url}</p>

<p>Aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>

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

################ Defined the email variables regarding welcome email for female authorized physical person start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: save_user_signup_data */
//$config['welcome_email_cc_signup_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['welcome_email_bcc_signup_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['welcome_email_from_signup_company_app_female'] = 'registrace@'.HTTP_HOST;
//$config['welcome_email_reply_to_signup_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['welcome_email_from_name_signup_company_app_female'] = 'Travai Registrace';
$config['welcome_email_subject_signup_company_app_female'] = 'Vítejte na travai.cz - ověřte Vaši emailovou adresu';

$config['welcome_email_message_signup_company_app_female'] = 'Vážená {name},

<p>vítejte na Travai.cz - portál, který pomáhá spojit, komunikovat, spolupracovat a mít příležitosti k osobnímu růstu.</p>

<p>Už jen jeden krok Vás dělí od kontaktu s lidmi a společnostmi z celé země.</p>

<p>Abyste mohla začít používat Travai.cz, musíte ověřit Vaši emailovou adresu.</p>

<p>Pro ověření <a href="{signup_verification_url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku:</p>

<p>{signup_verification_url}</p>

<p>Aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>

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
$config['email_bcc_account_verified_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_from_account_verified_personal_male'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_account_verified_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_account_verified_personal_male'] = 'Travai Registrace';
$config['email_subject_account_verified_personal_male'] = 'Vítejte na travai.cz';

$config['email_message_account_verified_personal_male'] = 'Vážený {name},

<p>Váš účet je aktivován a připravený k používání.</p>

<p>Jsme rádi, že jste s námi. Spustili jsme Travai.cz abychom pomohli vytvářet skutečně kvalitní dohody a obchody na lepší úrovni.</p>

<p>Od této chvíle můžete začít tvořit Váš profil, vložte svoji fotku nebo logo, nadpis, popis, vzdělání a certifikáty. Vložte své portofolio a zveřejněte své služby. Můžete vytvářet inzeráty, hledat projekty a pracovní pozice, posílat nabídky nebo žádosti.</p>

<p>V případě dotazů nebo potřeby získání informací o našich službách, navštivte stránku "<a href="{faq_page_url}">Často kladené otázky (F.A.Q.)</a>" nebo nás kontaktujte přímo e-mailem nebo telefonicky.</p>


<p><strong><a href="{site_url}">Pokračovat na portál Travai.cz</a></strong></p>

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

################ Defined the email variables regarding verified female user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: signup_successful_verification */
//$config['email_cc_account_verified_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_account_verified_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_from_account_verified_personal_female'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_account_verified_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_account_verified_personal_female'] = 'Travai Registrace';
$config['email_subject_account_verified_personal_female'] = 'Vítejte na travai.cz';

$config['email_message_account_verified_personal_female'] = 'Vážená {name},

<p>Váš účet je aktivován a připravený k používání.</p>

<p>Jsme rádi, že jste s námi. Spustili jsme Travai.cz abychom pomohli vytvářet skutečně kvalitní dohody a obchody na lepší úrovni.</p>

<p>Od této chvíle můžete začít tvořit Váš profil, vložte svoji fotku nebo logo, nadpis, popis, vzdělání a certifikáty. Vložte své portofolio a zveřejněte své služby. Můžete vytvářet inzeráty, hledat projekty a pracovní pozice, posílat nabídky nebo žádosti.</p>

<p>V případě dotazů nebo potřeby získání informací o našich službách, navštivte stránku "<a href="{faq_page_url}">Často kladené otázky (F.A.Q.)</a>" nebo nás kontaktujte přímo e-mailem nebo telefonicky.</p>

<p><strong><a href="{site_url}">Pokračovat na portál Travai.cz</a></strong></p>

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

################ Defined the email variables regarding verified company user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: signup_successful_verification */
//$config['email_cc_account_verified_company'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_account_verified_company'] = 'catalin.basturescu@gmail.com';
$config['email_from_account_verified_company'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_account_verified_company'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_account_verified_company'] = 'Travai Registrace';
$config['email_subject_account_verified_company'] = 'Vítejte na travai.cz';
$config['email_message_account_verified_company'] = 'Vážení {company_name},

<p>Váš účet je aktivován a připravený k používání.</p>

<p>Jsme rádi, že jste s námi. Spustili jsme Travai.cz abychom pomohli vytvářet skutečně kvalitní dohody a obchody na lepší úrovni.</p>

<p>Od této chvíle můžete začít tvořit váš profil, vložte své logo, nadpis, popis společnosti, vizi, poslání a cíl společnosti. Vložte své portofolio a zveřejněte své služby. Můžete vytvářet inzeráty, hledat projekty a partnery, posílat nabídky.</p>

<p>V případě dotazů nebo potřeby získání informací o našich službách, navštivte stránku "<a href="{faq_page_url}">Často kladené otázky (F.A.Q.)</a>" nebo nás kontaktujte přímo e-mailem nebo telefonicky.</p>

<p><strong><a href="{site_url}">Pokračovat na portál Travai.cz</a></strong></p>

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

################ Defined the email variables regarding verified accounts(company) end here

################ Defined the email variables regarding verified male app user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: signup_successful_verification */
//$config['email_cc_account_verified_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_account_verified_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_from_account_verified_company_app_male'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_account_verified_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_account_verified_company_app_male'] = 'Travai Registrace';
$config['email_subject_account_verified_company_app_male'] = 'Vítejte na travai.cz';

$config['email_message_account_verified_company_app_male'] = 'Vážený {name},

<p>Váš účet je aktivován a připravený k používání.</p>

<p>Jsme rádi, že jste s námi. Spustili jsme Travai.cz abychom pomohli vytvářet skutečně kvalitní dohody a obchody na lepší úrovni.</p>

<p>Od této chvíle můžete začít tvořit Váš profil, vložte svoji fotku nebo logo, nadpis, popis, vzdělání a certifikáty. Vložte své portofolio a zveřejněte své služby. Můžete vytvářet inzeráty, hledat projekty a pracovní pozice, posílat nabídky nebo žádosti.</p>

<p>V případě dotazů nebo potřeby získání informací o našich službách, navštivte stránku "<a href="{faq_page_url}">Často kladené otázky (F.A.Q.)</a>" nebo nás kontaktujte přímo e-mailem nebo telefonicky.</p>

<p><strong><a href="{site_url}">Pokračovat na portál Travai.cz</a></strong></p>

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

################ Defined the email variables regarding verified female app user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: signup_successful_verification */
//$config['email_cc_account_verified_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_account_verified_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_from_account_verified_company_app_female'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_account_verified_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_account_verified_company_app_female'] = 'Travai Registrace';
$config['email_subject_account_verified_company_app_female'] = 'Vítejte na travai.cz';

$config['email_message_account_verified_company_app_female'] = 'Vážená {name},

<p>Váš účet je aktivován a připravený k používání.</p>

<p>Jsme rádi, že jste s námi. Spustili jsme Travai.cz abychom pomohli vytvářet skutečně kvalitní dohody a obchody na lepší úrovni.</p>

<p>Od této chvíle můžete začít tvořit Váš profil, vložte svoji fotku nebo logo, nadpis, popis, vzdělání a certifikáty. Vložte své portofolio a zveřejněte své služby. Můžete vytvářet inzeráty, hledat projekty a pracovní pozice, posílat nabídky nebo žádosti.</p>

<p>V případě dotazů nebo potřeby získání informací o našich službách, navštivte stránku "<a href="{faq_page_url}">Často kladené otázky (F.A.Q.)</a>" nebo nás kontaktujte přímo e-mailem nebo telefonicky.</p>

<p><strong><a href="{site_url}">Pokračovat na portál Travai.cz</a></strong></p>

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

/*
|--------------------------------------------------------------------------
| Email Config Variables for new verification code during mannual request on signup verify process
|--------------------------------------------------------------------------
|
*/
################ Defined the email variables regarding mannual request code for male user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: send_verification_code_manually_generated_by_user */
//updated on 02.11.2019
//$config['email_cc_unverified_account_manual_request_verification_code_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_manual_request_verification_code_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_account_manual_request_verification_code_personal_male'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_unverified_account_manual_request_verification_code_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_manual_request_verification_code_personal_male'] = 'Travai Registrace';
$config['email_subject_unverified_account_manual_request_verification_code_personal_male'] = 'Váš nový ověřovací kód';

$config['email_message_unverified_account_manual_request_verification_code_personal_male'] = 'Vážený {name},

<p>právě jste požádal o vytvoření nového ověřovacího kódu pro ověření Vašeho účtu.</p>

<p>Pro ověření <a href="{url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>

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

################ Defined the email variables regarding manual request code for female user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: send_verification_code_manually_generated_by_user */
//$config['email_cc_unverified_account_manual_request_verification_code_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_manual_request_verification_code_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_account_manual_request_verification_code_personal_female'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_unverified_account_manual_request_verification_code_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_manual_request_verification_code_personal_female'] = 'Travai Registrace';
$config['email_subject_unverified_account_manual_request_verification_code_personal_female'] = 'Váš nový ověřovací kód';

$config['email_message_unverified_account_manual_request_verification_code_personal_female'] = 'Vážená {name},

<p>právě jste požádala o vytvoření nového ověřovacího kódu pro ověření Vašeho účtu.</p>

<p>Pro ověření <a href="{url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>

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

################ Defined the email variables regarding manual request code for company user start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: send_verification_code_manually_generated_by_user */
//updated on 02.11.2019
//$config['email_cc_unverified_account_manual_request_verification_code_company'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_manual_request_verification_code_company'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_account_manual_request_verification_code_company'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_unverified_account_manual_request_verification_code_company'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_manual_request_verification_code_company'] = 'Travai Registrace';
$config['email_subject_unverified_account_manual_request_verification_code_company'] = 'Váš nový ověřovací kód';

$config['email_message_unverified_account_manual_request_verification_code_compnay'] = 'Vážení {company_name},

<p>právě jste požádali o vytvoření nového ověřovacího kódu pro ověření vašeho účtu.</p>

<p>Pro ověření <a href="{url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>

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

################ Defined the email variables regarding manual request code for male authorized physical person start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: send_verification_code_manually_generated_by_user */
//updated on 02.11.2019
//$config['email_cc_unverified_account_manual_request_verification_code_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_manual_request_verification_code_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_account_manual_request_verification_code_company_app_male'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_unverified_account_manual_request_verification_code_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_manual_request_verification_code_company_app_male'] = 'Travai Registrace';
$config['email_subject_unverified_account_manual_request_verification_code_company_app_male'] = 'Váš nový ověřovací kód';

$config['email_message_unverified_account_manual_request_verification_code_personal_company_app_male'] = 'Vážený {name},

<p>právě jste požádal o vytvoření nového ověřovacího kódu pro ověření Vašeho účtu.</p>

<p>Pro ověření <a href="{url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>

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

################ Defined the email variables regarding manual request code for female authorized physical person start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: user Method name: send_verification_code_manually_generated_by_user */
//$config['email_cc_unverified_account_manual_request_verification_code_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_manual_request_verification_code_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_account_manual_request_verification_code_company_app_female'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_unverified_account_manual_request_verification_code_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_manual_request_verification_code_company_app_female'] = 'Travai Registrace';
$config['email_subject_unverified_account_manual_request_verification_code_company_app_female'] = 'Váš nový ověřovací kód';

$config['email_message_unverified_account_manual_request_verification_code_company_app_female'] = 'Vážená {name},

<p>právě jste požádala o vytvoření nového ověřovacího kódu pro ověření Vašeho účtu.</p>

<p>Pro ověření <a href="{url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>

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
$config['email_bcc_unverified_user_account_delete_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_user_account_delete_personal_male'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_unverified_user_account_delete_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_account_delete_personal_male'] = 'Travai Registrace';
$config['email_subject_unverified_user_account_delete_personal_male'] = 'účet odstraněn';

$config['email_message_unverified_user_account_delete_personal_male'] = 'Vážený {name},

<p>Váš email a účet byl z databáze Travai.cz odstraněn kvůli chybějícímu ověření.</p>

<p>Kdykoli v budoucnu se můžete registrovat znovu.</p>

<p>Děkujeme a doufáme, že se brzy vrátíte.</p>

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

################ Defined the email variables regarding unverfierd female user for delete account start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: cron_remove_unverified_users_from_users_pending_verification_table */
/* Filename: application\modules\signin\controllers\Sigin_model.php */
/* Controller: user Method name: remove_unverified_user */
//$config['email_cc_unverified_user_account_delete_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_account_delete_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_user_account_delete_personal_female'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_unverified_user_account_delete_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_account_delete_personal_female'] = 'Travai Registrace';
$config['email_subject_unverified_user_account_delete_personal_female'] = 'účet odstraněn';

$config['email_message_unverified_user_account_delete_personal_female'] = 'Vážená {name},

<p>Váš email a účet byl z databáze Travai.cz odstraněn kvůli chybějícímu ověření.</p>

<p>Kdykoli v budoucnu se můžete registrovat znovu.</p>

<p>Děkujeme a doufáme, že se brzy vrátíte.</p>

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

################ Defined the email variables regarding unverfierd company user for delete account start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: cron_remove_unverified_users_from_users_pending_verification_table */
/* Filename: application\modules\signin\controllers\Sigin_model.php */
/* Controller: user Method name: remove_unverified_user */
//$config['email_cc_unverified_user_account_delete_company'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_account_delete_company'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_user_account_delete_company'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_unverified_user_account_delete_company'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_account_delete_company'] = 'Travai Registrace';
$config['email_subject_unverified_user_account_delete_company'] = 'účet odstraněn';

$config['email_message_unverified_user_account_delete_company'] = 'Vážení {company_name},

<p>váš účet byl z databáze Travai.cz odstraněn kvůli chybějícímu ověření.</p>

<p>Kdykoli v budoucnu se můžete zaregistrovat znovu.</p>

<p>Děkujeme a doufáme, že se brzy vrátíte.</p>

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

################ Defined the email variables regarding unverfierd accounts(company) for delete account end here


################ Defined the email variables regarding unverfierd male app for delete account start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: cron_remove_unverified_users_from_users_pending_verification_table */
/* Filename: application\modules\signin\controllers\Sigin_model.php */
/* Controller: user Method name: remove_unverified_user */
//$config['email_cc_unverified_user_account_delete_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_account_delete_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_user_account_delete_company_app_male'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_unverified_user_account_delete_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_account_delete_company_app_male'] = 'Travai Registrace';
$config['email_subject_unverified_user_account_delete_company_app_male'] = 'účet odstraněn';

$config['email_message_unverified_user_account_delete_company_app_male'] = 'Vážený {name},

<p>Váš email a účet byl z databáze Travai.cz odstraněn kvůli chybějícímu ověření.</p>

<p>Kdykoli v budoucnu se můžete registrovat znovu.</p>

<p>Děkujeme a doufáme, že se brzy vrátíte.</p>

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

################ Defined the email variables regarding unverfierd female app for delete account start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: cron_remove_unverified_users_from_users_pending_verification_table */
/* Filename: application\modules\signin\controllers\Sigin_model.php */
/* Controller: user Method name: remove_unverified_user */
//$config['email_cc_unverified_user_account_delete_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_user_account_delete_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_user_account_delete_company_app_female'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_unverified_user_account_delete_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_user_account_delete_company_app_female'] = 'Travai Registrace';
$config['email_subject_unverified_user_account_delete_company_app_female'] = 'účet odstraněn';

$config['email_message_unverified_user_account_delete_company_app_female'] = 'Vážená {name},

<p>Váš email a účet byl z databáze Travai.cz odstraněn kvůli chybějícímu ověření.</p>

<p>Kdykoli v budoucnu se můžete registrovat znovu.</p>

<p>Děkujeme a doufáme, že se brzy vrátíte.</p>

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

/*
|--------------------------------------------------------------------------
| Email Config Variables for send reminder to the unverfied account
|--------------------------------------------------------------------------
|
*/
################ Defined the email variables regarding unverfied male user for send reminders start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: cron_send_reminder_verification_code_to_not_verified_users_accounts */
//$config['email_cc_unverified_account_reminder_verification_code_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_reminder_verification_code_personal_male'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_account_reminder_verification_code_personal_male'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_unverified_account_reminder_verification_code_personal_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_reminder_verification_code_personal_male'] = 'Travai Registrace';
$config['email_subject_unverified_account_reminder_verification_code_personal_male'] = 'Připomenutí: potvrďte svoji registraci na travai.cz';

$config['email_message_unverified_account_reminder_verification_code_personal_male'] = 'Vážený {name},

<p>tento email Vám byl odeslán při registraci na Travai.cz dne {account_registration_date} v {account_registration_time} a doposud nebyla ověřena Vaše emailová adresa.</p>

<p>Pro ověření <a href="{signup_verification_url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{signup_verification_url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn. Stále máte {account_expiration_time_left}, abyste potvrdil svoji emailovou adresu.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>

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

################ Defined the email variables regarding unverfierd female user for send reminders start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: cron_send_reminder_verification_code_to_not_verified_users_accounts */
//$config['email_cc_unverified_account_reminder_verification_code_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_reminder_verification_code_personal_female'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_account_reminder_verification_code_personal_female'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_unverified_account_reminder_verification_code_personal_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_reminder_verification_code_personal_female'] = 'Travai Registrace';
$config['email_subject_unverified_account_reminder_verification_code_personal_female'] = 'Připomenutí: potvrďte svoji registraci na travai.cz';

$config['email_message_unverified_account_reminder_verification_code_personal_female'] = 'Vážená {name},

<p>tento email Vám byl odeslán při registraci na Travai.cz dne {account_registration_date} v {account_registration_time} a doposud nebyla ověřena Vaše emailová adresa.</p>

<p>Pro ověření <a href="{signup_verification_url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{signup_verification_url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn. Stále máte {account_expiration_time_left}, abyste potvrdila svoji emailovou adresu.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>

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

################ Defined the email variables regarding unverfierd company user for send reminders start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: cron_send_reminder_verification_code_to_not_verified_users_accounts */
//$config['email_cc_unverified_account_reminder_verification_code_company'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_reminder_verification_code_company'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_account_reminder_verification_code_company'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_unverified_account_reminder_verification_code_company'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_reminder_verification_code_company'] = 'Travai Registrace';
$config['email_subject_unverified_account_reminder_verification_code_company'] = 'Připomenutí: potvrďte svoji registraci na travai.cz';

$config['email_message_unverified_account_reminder_verification_code_company'] = 'Vážení {company_name},

<p>tento email vám byl odeslán při registraci na Travai.cz dne {account_registration_date} v {account_registration_time} a doposud nebyla ověřena vaše emailová adresa.</p>

<p>Pro ověření <a href="{signup_verification_url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{signup_verification_url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn. Stále máte {account_expiration_time_left}, abyste potvrdili svoji emailovou adresu.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>

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
################ Defined the email variables regarding unverfierd accounts(company) for send reminders end here

################ Defined the email variables regarding unverfied male authorized physical person for send reminders start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: cron_send_reminder_verification_code_to_not_verified_users_accounts */
//$config['email_cc_unverified_account_reminder_verification_code_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_reminder_verification_code_company_app_male'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_account_reminder_verification_code_company_app_male'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_unverified_account_reminder_verification_code_company_app_male'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_reminder_verification_code_company_app_male'] = 'Travai Registrace';
$config['email_subject_unverified_account_reminder_verification_code_company_app_male'] = 'Připomenutí: potvrďte svoji registraci na travai.cz';

$config['email_message_unverified_account_reminder_verification_code_company_app_male'] = 'Vážený {name},

<p>tento email Vám byl odeslán při registraci na Travai.cz dne {account_registration_date} v {account_registration_time} a doposud nebyla ověřena Vaše emailová adresa.</p>

<p>Pro ověření <a href="{signup_verification_url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{signup_verification_url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn. Stále máte {account_expiration_time_left}, abyste potvrdil svoji emailovou adresu.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>

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

################ Defined the email variables regarding unverfierd female authorized physical person for send reminders start here
/* Filename: application\modules\cron\controllers\Cron.php */
/* Controller: user Method name: cron_send_reminder_verification_code_to_not_verified_users_accounts */
//$config['email_cc_unverified_account_reminder_verification_code_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_unverified_account_reminder_verification_code_company_app_female'] = 'catalin.basturescu@gmail.com';
$config['email_from_unverified_account_reminder_verification_code_company_app_female'] = 'registrace@'.HTTP_HOST;
//$config['email_reply_to_unverified_account_reminder_verification_code_company_app_female'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_unverified_account_reminder_verification_code_company_app_female'] = 'Travai Registrace';
$config['email_subject_unverified_account_reminder_verification_code_company_app_female'] = 'Připomenutí: potvrďte svoji registraci na travai.cz';

$config['email_message_unverified_account_reminder_verification_code_company_app_female'] = 'Vážená {name},

<p>tento email Vám byl odeslán při registraci na Travai.cz dne {account_registration_date} v {account_registration_time} a doposud nebyla ověřena Vaše emailová adresa.</p>

<p>Pro ověření <a href="{signup_verification_url}"><strong>klikněte zde</strong></a> nebo zkopírujte a vložte níže uvedený odkaz do prohlížeče adresního řádku.</p>
<p>{signup_verification_url}</p>

<p>Nový aktivační kód pro použití přímo na Travai stránce je: <strong>{activation_code}</strong></p>

<p>Pro ověření účtu, zbývá {account_expiration_interval}. Po uplynutí času bude účet z našeho systému odstraněn. Stále máte {account_expiration_time_left}, abyste potvrdila svoji emailovou adresu.</p>

<p>Nevíte si rady? Pokud se při registraci setkáte s potížemi, kontaktujte nás.</p>

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

?>