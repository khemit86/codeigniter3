<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Email Config Variables for update login email functionality
|--------------------------------------------------------------------------
|
*/
######## Email config for update login email functionltiy.Email sent to male user on his new email adress.
// For male(to new email address)
//$config['account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_bcc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_from'] = 'no-reply@'.HTTP_HOST;
//$config['account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_from_name'] = 'Travai Podpora';
//$config['account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_subject'] = '{user_first_name_last_name}, Update Email(male)';
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_subject'] = 'ověření - nový přihlašovací email';


$config['account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_message'] = 'Vážený {user_first_name_last_name},

<p>potvrzujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle používáte {new_email} pro přihlášení k Vašemu Travai účtu.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

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

######## Email config for update login email functionltiy.Email sent to male user on his old email adress.
// For male(to old email address)
//$config['account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_bcc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_from'] = 'no-reply@'.HTTP_HOST;
//$config['account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_from_name'] = 'Travai Podpora';
//$config['account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_subject'] = '{user_first_name_last_name}, Update Email(male)';
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_subject'] = 'upozornění - změna přihlašovacího emailu';


$config['account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_message'] = 'Vážený {user_first_name_last_name},

<p>informujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle nelze používat tato emailová adresa pro přihlášení k Vašemu Travai účtu.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

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


######## Email config for update login email functionltiy.Email sent to app male user on his new email adress.
// For app male(to new email address)
//$config['account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_bcc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_from'] = 'no-reply@'.HTTP_HOST;
//$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_from_name'] = 'Travai Podpora';
//$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_subject'] = '{user_first_name_last_name}, Update Email(male)';
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_subject'] = 'ověření - nový přihlašovací email';


$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_message'] = 'Vážený {user_first_name_last_name},

<p>potvrzujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle používáte {new_email} pro přihlášení k Vašemu Travai účtu.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

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

######## Email config for update login email functionltiy.Email sent to app male user on his old email address.
// For app male(to old email address)
//$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_bcc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_from'] = 'no-reply@'.HTTP_HOST;
//$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_from_name'] = 'Travai Podpora';
//$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_subject'] = '{user_first_name_last_name}, Update Email(male)';
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_subject'] = 'upozornění - změna přihlašovacího emailu';


$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_message'] = 'Vážený {user_first_name_last_name},

<p>informujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle nelze používat tato emailová adresa pro přihlášení k Vašemu Travai účtu.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

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


######## Email config for update login email functionltiy.Email sent to female user on her new email adress.
// For female(new email address)
//$config['account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_bcc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_from'] = 'no-reply@'.HTTP_HOST;
//$config['account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_from_name'] = 'Travai Podpora';
//$config['account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_subject'] = '{user_first_name_last_name}, Update Email(female)';
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_subject'] = 'ověření - nový přihlašovací email';


$config['account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_message'] = 'Vážená {user_first_name_last_name},

<p>potvrzujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle používáte {new_email} pro přihlášení k Vašemu Travai účtu.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

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

######## Email config for update login email functionltiy.Email sent to female user on her old email adress.
// For female(old email address)
//$config['account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_bcc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_from'] = 'no-reply@'.HTTP_HOST;
//$config['account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_from_name'] = 'Travai Podpora';
//$config['account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_subject'] = '{user_first_name_last_name}, Update Email(female)';
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_subject'] = 'upozornění - změna přihlašovacího emailu';


$config['account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_message'] = 'Vážená {user_first_name_last_name},

<p>informujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle nelze používat tato emailová adresa pro přihlášení k Vašemu Travai účtu.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

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


######## Email config for update login email functionltiy.Email sent to app female user on her new email adress.
// For app female(new email address)
//$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_bcc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_from'] = 'no-reply@'.HTTP_HOST;
//$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_from_name'] = 'Travai Podpora';
//$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_subject'] = '{user_first_name_last_name}, Update Email(female)';
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_subject'] = 'ověření - nový přihlašovací email';


$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_message'] = 'Vážená {user_first_name_last_name},

<p>potvrzujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle používáte {new_email} pro přihlášení k Vašemu Travai účtu.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

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

######## Email config for update login email functionltiy.Email sent to app female user on her old email adress.
// For app female(old email address)
//$config['account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_old_email_address_bcc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_old_email_address_from'] = 'no-reply@'.HTTP_HOST;
//$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_old_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_old_email_address_from_name'] = 'Travai Podpora';
//$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_old_email_address_subject'] = '{user_first_name_last_name}, Update Email(female)';
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_old_email_address_subject'] = 'upozornění - změna přihlašovacího emailu';


$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_old_email_address_message'] = 'Vážená {user_first_name_last_name},

<p>informujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle nelze používat tato emailová adresa pro přihlášení k Vašemu Travai účtu.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

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

######## Email config for update login email functionltiy.Email sent to company user on his new email adress.
// For company(new email address)
//$config['account_management_company_account_update_email_section_email_sent_to_new_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_company_account_update_email_section_email_sent_to_new_email_address_bcc'] = 'catalin.basturescu@gmail.com';
$config['account_management_company_account_update_email_section_email_sent_to_new_email_address_from'] = 'no-reply@'.HTTP_HOST;
//$config['account_management_company_account_update_email_section_email_sent_to_new_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_company_account_update_email_section_email_sent_to_new_email_address_from_name'] = 'Travai Podpora';
//$config['account_management_company_account_update_email_section_email_sent_to_new_email_address_subject'] = '{company_name}, Update Email(company)';
$config['account_management_company_account_update_email_section_email_sent_to_new_email_address_subject'] = 'ověření - nový přihlašovací email';

$config['account_management_company_account_update_email_section_email_sent_to_new_email_address_message'] = 'Vážení {company_name},

<p>potvrzujeme, že přihlašovací email k vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle používáte {new_email} pro přihlášení k vašemu Travai účtu.</p>

<p>Pokud jste tak učinili, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedli nebo se domníváte, že k vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

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

######## Email config for update login email functionltiy.Email sent to company user on his old email adress.
// For company(old email address)
//$config['account_management_company_account_update_email_section_email_sent_to_old_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_company_account_update_email_section_email_sent_to_old_email_address_bcc'] = 'catalin.basturescu@gmail.com';
$config['account_management_company_account_update_email_section_email_sent_to_old_email_address_from'] = 'no-reply@'.HTTP_HOST;
//$config['account_management_company_account_update_email_section_email_sent_to_old_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_company_account_update_email_section_email_sent_to_old_email_address_from_name'] = 'Travai Podpora';
//$config['account_management_company_account_update_email_section_email_sent_to_old_email_address_subject'] = '{company_name}, Update Email(company)';
$config['account_management_company_account_update_email_section_email_sent_to_old_email_address_subject'] = 'upozornění - změna přihlašovacího emailu';

$config['account_management_company_account_update_email_section_email_sent_to_old_email_address_message'] = 'Vážení {company_name},

<p>informujeme, že přihlašovací email k vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle nelze používat tato emailová adresa pro přihlášení k vašemu Travai účtu.</p>

<p>Pokud jste tak učinili, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedli nebo se domníváte, že k vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

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

/*
|--------------------------------------------------------------------------
| Email Config Variables for update login password functionality
|--------------------------------------------------------------------------
| 
*/

######## Email config for update login password functionltiy.Email sent to male user on his email adress
// email config for update password
// For male
//$config['account_management_personal_account_male_user_update_password_section_email_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_male_user_update_password_section_email_bcc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_male_user_update_password_section_email_from'] = 'no-reply@'.HTTP_HOST;
//$config['account_management_personal_account_male_user_update_password_section_email_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_male_user_update_password_section_email_from_name'] = 'Travai Podpora';
//$config['account_management_personal_account_male_user_update_password_section_email_subject'] = '{user_first_name_last_name}, změna hesla - Update password(male)';
$config['account_management_personal_account_male_user_update_password_section_email_subject'] = 'upozornění - změna přihlašovacího hesla';

$config['account_management_personal_account_male_user_update_password_section_email_message'] = 'Vážený {user_first_name_last_name},

<p>informujeme, že přihlašovací heslo k Vašemu účtu bylo změněno dne {update_password_time} z IP adresy {ip}.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

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

######## Email config for update login password functionltiy.Email sent to female user on her email adress.
// For female
//$config['account_management_personal_account_female_user_update_password_section_email_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_female_user_update_password_section_email_bcc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_female_user_update_password_section_email_from'] = 'no-reply@'.HTTP_HOST;
//$config['account_management_personal_account_female_user_update_password_section_email_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_female_user_update_password_section_email_from_name'] = 'Travai Podpora';
//$config['account_management_personal_account_female_user_update_password_section_email_subject'] = '{user_first_name_last_name}, Update password(female)';
$config['account_management_personal_account_female_user_update_password_section_email_subject'] = 'upozornění - změna přihlašovacího hesla';

$config['account_management_personal_account_female_user_update_password_section_email_message'] = 'Vážená {user_first_name_last_name},

<p>informujeme, že přihlašovací heslo k Vašemu účtu bylo změněno dne {update_password_time} z IP adresy {ip}.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

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

######## Email config for update login password functionltiy.Email sent to app male user on his email adress
// email config for update password
// For app male
//$config['account_management_personal_account_company_app_male_user_update_password_section_email_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_male_user_update_password_section_email_bcc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_male_user_update_password_section_email_from'] = 'no-reply@'.HTTP_HOST;
//$config['account_management_personal_account_company_app_male_user_update_password_section_email_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_company_app_male_user_update_password_section_email_from_name'] = 'Travai Podpora';
//$config['account_management_personal_account_company_app_male_user_update_password_section_email_subject'] = '{user_first_name_last_name}, změna hesla - Update password(male)';
$config['account_management_personal_account_company_app_male_user_update_password_section_email_subject'] = 'upozornění - změna přihlašovacího hesla';

$config['account_management_personal_account_company_app_male_user_update_password_section_email_message'] = 'Vážený {user_first_name_last_name},

<p>informujeme, že přihlašovací heslo k Vašemu účtu bylo změněno dne {update_password_time} z IP adresy {ip}.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

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

######## Email config for update login password functionltiy.Email sent to app female user on her email adress.
// For app female
//$config['account_management_personal_account_company_app_female_user_update_password_section_email_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_female_user_update_password_section_email_bcc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_female_user_update_password_section_email_from'] = 'no-reply@'.HTTP_HOST;
//$config['account_management_personal_account_company_app_female_user_update_password_section_email_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_company_app_female_user_update_password_section_email_from_name'] = 'Travai Podpora';
//$config['account_management_personal_account_company_app_female_user_update_password_section_email_subject'] = '{user_first_name_last_name}, Update password(female)';
$config['account_management_personal_account_company_app_female_user_update_password_section_email_subject'] = 'upozornění - změna přihlašovacího hesla';

$config['account_management_personal_account_company_app_female_user_update_password_section_email_message'] = 'Vážená {user_first_name_last_name},

<p>informujeme, že přihlašovací heslo k Vašemu účtu bylo změněno dne {update_password_time} z IP adresy {ip}.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

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


######## Email config for update login password functionltiy.Email sent to company user on his email adress.
// Company
//$config['account_management_company_account_update_password_section_email_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_company_account_update_password_section_email_bcc'] = 'catalin.basturescu@gmail.com';
$config['account_management_company_account_update_password_section_email_from'] = 'no-reply@'.HTTP_HOST;
//$config['account_management_company_account_update_password_section_email_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_company_account_update_password_section_email_from_name'] = 'Travai Podpora';
//$config['account_management_company_account_update_password_section_email_subject'] = '{company_name}, Update password(company)';
$config['account_management_company_account_update_password_section_email_subject'] = 'upozornění - změna přihlašovacího hesla';


$config['account_management_company_account_update_password_section_email_message'] = 'Vážení {company_name},

<p>informujeme, že přihlašovací heslo k vašemu účtu bylo změněno dne {update_password_time} z IP adresy {ip}.</p>

<p>Pokud jste tak učinili, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedli nebo se domníváte, že k vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

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

// Email config for close account start//
//$config['user_close_account_request_email_cc'] = 'catalin.basturescu@gmail.com';
$config['user_close_account_request_email_bcc'] = 'catalin.basturescu@gmail.com';
$config['user_close_account_request_email_from'] = 'no-reply@'.HTTP_HOST;
//$config['user_close_account_request_email_from_name'] = 'Close Account';
$config['user_close_account_request_email_from_name'] = 'Travai Podpora';
//to be chnaged with podpora@
//$config['user_close_account_request_email_reply_to'] = 'no-reply@'.HTTP_HOST;
//$config['user_close_account_request_email_subject'] = 'close account';
$config['user_close_account_request_email_subject'] = 'zavření účtu';
$config['user_close_account_request_email_message'] = 'Děkujeme, že nás informujete.

<p>Vaše žádost o zavření účtu k nám dorazila.</p>
<hr>
<p>Tento email je automaticky generován. Dejte nám nějaký čas ke zpracování. Co nejdříve vás budeme kontaktovat.</p>

<br>
<b><u>Kopie vaší žádosti</u></b>

<p><b>Žádost k zavření účtu od uživatele:</b> <a href="{user_profile_page_url}" target="_blank">{user_first_name_last_name_or_company_name}</a><br></p>

<p><b>Žádost o zavření účtu jste odeslali dne</b> {close_account_request_sent_time}</p>

<p><b>Důvod zavření účtu:</b> {close_account_reason}</p>

<p><b>Zpětná vazba:</b><br>{close_account_reason_feedback}</p>

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
// Email config for close account end//

?>