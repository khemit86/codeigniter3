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
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_bcc'] = 'catalinbasturescu@gmail.com';
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_from'] = 'security@'.HTTP_HOST;
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_from_name'] = 'travai.cz (Update Email)';

//$config['account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_subject'] = '{user_first_name_last_name}, Update Email(male)';
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_subject'] = 'změna emailu - Update Email - to new email - male';


$config['account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_message'] = '<p>Dear {user_first_name_last_name}(new email),</p>
<p>You successfully changed your account email address. From now on you will have to use {new_email} to connect to your account</p>
<p>This message is to confirm that your account email was changed on {update_email_time} from IP {ip}.</p>
<p>If you did this, you can safely disregard this email.</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your account, please contact us immediately.</p>
<p>Thanks,Security Team</p>

<hr>

<p>Vážený {user_first_name_last_name},</p>
<p>potvrzujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle používáte {new_email} pro přihlášení k Vašemu Travai účtu.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';


######## Email config for update login email functionltiy.Email sent to male user on his old email adress.	
// For male(to old email address)
//$config['account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_bcc'] = 'catalinbasturescu@gmail.com';
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_from'] = 'security@'.HTTP_HOST;
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_from_name'] = 'travai.cz (Update Email)';
//$config['account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_subject'] = '{user_first_name_last_name}, Update Email(male)';
$config['account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_subject'] = 'změna emailu - Update Email - to old email - male';


$config['account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_message'] = '<p>Dear {user_first_name_last_name} (old email),</p>
<p>This message is to confirm that your account email was changed on {update_email_time}. From now on you will not be able to use this email address to login to SITE</p>
<p>If you did this, you can safely disregard this email.</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your account, please contact us immediately.</p>
<p>Thanks,Security Team</p>

<hr>

<p>Vážený {user_first_name_last_name},</p>
<p>potvrzujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle nelze používat tato emailová adresa pro přihlášení k Vašemu Travai účtu.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';

######## Email config for update login email functionltiy.Email sent to app male user on his new email adress.		
// For app male(to new email address)
//$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_bcc'] = 'catalinbasturescu@gmail.com';
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_from'] = 'security@'.HTTP_HOST;
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_from_name'] = 'travai.cz (Update Email)';

//$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_subject'] = '{user_first_name_last_name}, Update Email(male)';
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_subject'] = 'změna emailu - Update Email - to new email - App male';


$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_message'] = '<p>(app male)Dear {user_first_name_last_name}(new email),</p>
<p>You successfully changed your account email address. From now on you will have to use {new_email} to connect to your account</p>
<p>This message is to confirm that your account email was changed on {update_email_time} from IP {ip}.</p>
<p>If you did this, you can safely disregard this email.</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your account, please contact us immediately.</p>
<p>Thanks,Security Team</p>

<hr>

<p>Vážený {user_first_name_last_name},</p>
<p>potvrzujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle používáte {new_email} pro přihlášení k Vašemu Travai účtu.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';


######## Email config for update login email functionltiy.Email sent to app male user on his old email adress.	
// For app male(to old email address)
//$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_bcc'] = 'catalinbasturescu@gmail.com';
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_from'] = 'security@'.HTTP_HOST;
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_from_name'] = 'travai.cz (Update Email)';
//$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_subject'] = '{user_first_name_last_name}, Update Email(male)';
$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_subject'] = 'změna emailu - Update Email - to old email - app male';


$config['account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_message'] = '<p>App Male: Dear {user_first_name_last_name} (old email),</p>
<p>This message is to confirm that your account email was changed on {update_email_time}. From now on you will not be able to use this email address to login to SITE</p>
<p>If you did this, you can safely disregard this email.</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your account, please contact us immediately.</p>
<p>Thanks,Security Team</p>

<hr>

<p>Vážený {user_first_name_last_name},</p>
<p>potvrzujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle nelze používat tato emailová adresa pro přihlášení k Vašemu Travai účtu.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';





######## Email config for update login email functionltiy.Email sent to female user on her new email adress.
// For female(new email address)
//$config['account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_bcc'] = 'catalinbasturescu@gmail.com';
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_from'] = 'security@'.HTTP_HOST;
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_from_name'] = 'travai.cz (Update Email)';
//$config['account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_subject'] = '{user_first_name_last_name}, Update Email(female)';
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_subject'] = 'změna emailu - Update Email - to new email - female';


$config['account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_message'] = '<p>Dear {user_first_name_last_name},</p>
<p>You successfully changed your iPrace.online account email address. From now on you will have to use {new_email} to connect to your account</p>
<p>This message is to confirm that your account email was changed on {update_email_time} from IP {ip}.</p>
<p>If you did this, you can safely disregard this email.</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your account, please contact us immediately.</p>
<p>Thanks,Security Team</p>

<hr>

<p>Vážená {user_first_name_last_name},</p>
<p>potvrzujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle používáte {new_email} pro přihlášení k Vašemu Travai účtu.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';

######## Email config for update login email functionltiy.Email sent to female user on her old email adress.
// For female(old email address)
//$config['account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_bcc'] = 'catalinbasturescu@gmail.com';
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_from'] = 'security@'.HTTP_HOST;
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_from_name'] = 'travai.cz (Update Email)';
//$config['account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_subject'] = '{user_first_name_last_name}, Update Email(female)';
$config['account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_subject'] = 'změna emailu - Update Email - to old email - female';



$config['account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_message'] = '<p>Dear {user_first_name_last_name},</p>
<p>This message is to confirm that your account email was changed on {update_email_time}. From now on you will not be able to use this email address to login to SITE</p>
<p>If you did this, you can safely disregard this email.</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your account, please contact us immediately.</p>
<p>Thanks,Security Team</p>

<hr>

<p>Vážená {user_first_name_last_name},</p>
<p>potvrzujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle nelze používat tato emailová adresa pro přihlášení k Vašemu Travai účtu.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';


######## Email config for update login email functionltiy.Email sent to app female user on her new email adress.
// For app female(new email address)
//$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_bcc'] = 'catalinbasturescu@gmail.com';
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_from'] = 'security@'.HTTP_HOST;
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_from_name'] = 'travai.cz (Update Email)';
//$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_subject'] = '{user_first_name_last_name}, Update Email(female)';
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_subject'] = 'změna emailu - Update Email - to new email - App female';


$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_message'] = '<p>(App Female)Dear {user_first_name_last_name},</p>
<p>You successfully changed your iPrace.online account email address. From now on you will have to use {new_email} to connect to your account</p>
<p>This message is to confirm that your account email was changed on {update_email_time} from IP {ip}.</p>
<p>If you did this, you can safely disregard this email.</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your account, please contact us immediately.</p>
<p>Thanks,Security Team</p>

<hr>

<p>Vážená {user_first_name_last_name},</p>
<p>potvrzujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle používáte {new_email} pro přihlášení k Vašemu Travai účtu.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';

######## Email config for update login email functionltiy.Email sent to app female user on her old email adress.
// For app female(old email address)
//$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_old_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_old_email_address_bcc'] = 'catalinbasturescu@gmail.com';
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_old_email_address_from'] = 'security@'.HTTP_HOST;
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_old_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_old_email_address_from_name'] = 'travai.cz (Update Email)';
//$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_old_email_address_subject'] = '{user_first_name_last_name}, Update Email(female)';
$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_old_email_address_subject'] = 'změna emailu - Update Email - to old email - app female';



$config['account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_old_email_address_message'] = '<p>(App Female)Dear {user_first_name_last_name},</p>
<p>This message is to confirm that your account email was changed on {update_email_time}. From now on you will not be able to use this email address to login to SITE</p>
<p>If you did this, you can safely disregard this email.</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your account, please contact us immediately.</p>
<p>Thanks,Security Team</p>

<hr>

<p>Vážená {user_first_name_last_name},</p>
<p>potvrzujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle nelze používat tato emailová adresa pro přihlášení k Vašemu Travai účtu.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';



######## Email config for update login email functionltiy.Email sent to company user on his new email adress.
// For company(new email address)
//$config['account_management_company_account_update_email_section_email_sent_to_new_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_company_account_update_email_section_email_sent_to_new_email_address_bcc'] = 'catalinbasturescu@gmail.com';
$config['account_management_company_account_update_email_section_email_sent_to_new_email_address_from'] = 'security@'.HTTP_HOST;
$config['account_management_company_account_update_email_section_email_sent_to_new_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_company_account_update_email_section_email_sent_to_new_email_address_from_name'] = 'travai.cz (Update Email)';
//$config['account_management_company_account_update_email_section_email_sent_to_new_email_address_subject'] = '{company_name}, Update Email(company)';
$config['account_management_company_account_update_email_section_email_sent_to_new_email_address_subject'] = 'změna emailu - Update Email - to new email - company';


$config['account_management_company_account_update_email_section_email_sent_to_new_email_address_message'] = '<p>Dear {company_name},</p>
<p>You successfully changed your account email address. From now on you will have to use {new_email} to connect to your account</p>
<p>This message is to confirm that your account email was changed on {update_email_time} from IP {ip}.</p>
<p>If you did this, you can safely disregard this email.</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your account, please contact us immediately.</p>
<p>Thanks,Security Team</p>

<hr>

<p>Vážená {company_name},</p>
<p>potvrzujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle používáte {new_email} pro přihlášení k vašemu Travai účtu.</p>

<p>Pokud jste tak učinili, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedli nebo se domníváte, že k vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';

######## Email config for update login email functionltiy.Email sent to company user on his old email adress.	
// For company(old email address)
//$config['account_management_company_account_update_email_section_email_sent_to_old_email_address_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_company_account_update_email_section_email_sent_to_old_email_address_bcc'] = 'catalinbasturescu@gmail.com';
$config['account_management_company_account_update_email_section_email_sent_to_old_email_address_from'] = 'security@'.HTTP_HOST;
$config['account_management_company_account_update_email_section_email_sent_to_old_email_address_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_company_account_update_email_section_email_sent_to_old_email_address_from_name'] = 'travai.cz (Update Email)';
//$config['account_management_company_account_update_email_section_email_sent_to_old_email_address_subject'] = '{company_name}, Update Email(company)';
$config['account_management_company_account_update_email_section_email_sent_to_old_email_address_subject'] = 'změna emailu - Update Email - to old email - company';


$config['account_management_company_account_update_email_section_email_sent_to_old_email_address_message'] = '<p>Dear {company_name},</p>
<p>This message is to confirm that your account email was changed on {update_email_time}. From now on you will not be able to use this email address to login to SITE</p>
<p>If you did this, you can safely disregard this email.</p>
<p>If you did not make this change or you believe an unauthorized person has accessed your account, please contact us immediately.</p>
<p>Thanks,Security Team</p>

<hr>

<p>Vážená {company_name},</p>

<p>potvrzujeme, že přihlašovací email k Vašemu účtu byl změněn dne {update_email_time} z IP adresy {ip}.</p>

<p>Od této chvíle nelze používat tato emailová adresa pro přihlášení k vašemu Travai účtu.</p>

<p>Pokud jste tak učinili, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedli nebo se domníváte, že k vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';

/*
|--------------------------------------------------------------------------
| Email Config Variables for update login password functionality
|--------------------------------------------------------------------------
| 
*/

######## Email config for update login password functionltiy.Email sent to male user on his email adress
// For male
//$config['account_management_personal_account_male_user_update_password_section_email_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_male_user_update_password_section_email_bcc'] = 'catalinbasturescu@gmail.com';
$config['account_management_personal_account_male_user_update_password_section_email_from'] = 'security@'.HTTP_HOST;
$config['account_management_personal_account_male_user_update_password_section_email_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_male_user_update_password_section_email_from_name'] = 'travai.cz (update password)';
//$config['account_management_personal_account_male_user_update_password_section_email_subject'] = '{user_first_name_last_name}, změna hesla - Update password(male)';
$config['account_management_personal_account_male_user_update_password_section_email_subject'] = 'změna hesla - Update password(male)';

$config['account_management_personal_account_male_user_update_password_section_email_message'] = '<p>Dear {user_first_name_last_name},</p>
<p>You successfully changed your account email address</p>
<p>This message is to confirm that your account password was changed on {update_password_time} from IP {ip}.</p>
<p>If you did this, you can safely disregard this email.</p><p>If you did not make this change or you believe an unauthorized person has accessed your account, please contact us immediately.</p>
<p>Thanks,Security Team</p>

<hr>
<p>Vážený {user_first_name_last_name},</p>
<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo změněno dne {update_password_time} z IP adresy {ip}.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';

######## Email config for update login password functionltiy.Email sent to female user on her email adress.
// For female
//$config['account_management_personal_account_female_user_update_password_section_email_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_female_user_update_password_section_email_bcc'] = 'catalinbasturescu@gmail.com';
$config['account_management_personal_account_female_user_update_password_section_email_from'] = 'security@'.HTTP_HOST;
$config['account_management_personal_account_female_user_update_password_section_email_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_female_user_update_password_section_email_from_name'] = 'travai.cz (Update Password)';
//$config['account_management_personal_account_female_user_update_password_section_email_subject'] = '{user_first_name_last_name}, Update password(female)';
$config['account_management_personal_account_female_user_update_password_section_email_subject'] = 'změna hesla - Update password(female)';

$config['account_management_personal_account_female_user_update_password_section_email_message'] = '<p>Dear {user_first_name_last_name},</p><p>You successfully changed your iPrace.online account email address</p><p>This message is to confirm that your account password was changed on {update_password_time} from IP {ip}.</p><p>If you did this, you can safely disregard this email.</p><p>If you did not make this change or you believe an unauthorized person has accessed your account, please contact us immediately.</p><p>Thanks,Security Team</p>

<hr>
<p>Vážená {user_first_name_last_name},</p>
<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo změněno dne {update_password_time} z IP adresy {ip}.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';


######## Email config for update login password functionltiy.Email sent to app male user on his email adress
// For app male
//$config['account_management_personal_account_company_app_male_user_update_password_section_email_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_male_user_update_password_section_email_bcc'] = 'catalinbasturescu@gmail.com';
$config['account_management_personal_account_company_app_male_user_update_password_section_email_from'] = 'security@'.HTTP_HOST;
$config['account_management_personal_account_company_app_male_user_update_password_section_email_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_company_app_male_user_update_password_section_email_from_name'] = 'travai.cz (update password)';
//$config['account_management_personal_account_company_app_male_user_update_password_section_email_subject'] = '{user_first_name_last_name}, změna hesla - Update password(male)';
$config['account_management_personal_account_company_app_male_user_update_password_section_email_subject'] = 'změna hesla - Update password(app male)';

$config['account_management_personal_account_company_app_male_user_update_password_section_email_message'] = '<p>(app male)Dear {user_first_name_last_name},</p>
<p>You successfully changed your account email address</p>
<p>This message is to confirm that your account password was changed on {update_password_time} from IP {ip}.</p>
<p>If you did this, you can safely disregard this email.</p><p>If you did not make this change or you believe an unauthorized person has accessed your account, please contact us immediately.</p>
<p>Thanks,Security Team</p>

<hr>
<p>Vážený {user_first_name_last_name},</p>
<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo změněno dne {update_password_time} z IP adresy {ip}.</p>

<p>Pokud jste tak učinil, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedl nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';

######## Email config for update login password functionltiy.Email sent to app female user on her email adress.
// For app female
//$config['account_management_personal_account_company_app_female_user_update_password_section_email_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_personal_account_company_app_female_user_update_password_section_email_bcc'] = 'catalinbasturescu@gmail.com';
$config['account_management_personal_account_company_app_female_user_update_password_section_email_from'] = 'security@'.HTTP_HOST;
$config['account_management_personal_account_company_app_female_user_update_password_section_email_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_personal_account_company_app_female_user_update_password_section_email_from_name'] = 'travai.cz (Update Password)';
//$config['account_management_personal_account_company_app_female_user_update_password_section_email_subject'] = '{user_first_name_last_name}, Update password(female)';
$config['account_management_personal_account_company_app_female_user_update_password_section_email_subject'] = 'změna hesla - Update password(app female)';

$config['account_management_personal_account_company_app_female_user_update_password_section_email_message'] = '<p>(company app)Dear {user_first_name_last_name},</p><p>You successfully changed your iPrace.online account email address</p><p>This message is to confirm that your account password was changed on {update_password_time} from IP {ip}.</p><p>If you did this, you can safely disregard this email.</p><p>If you did not make this change or you believe an unauthorized person has accessed your account, please contact us immediately.</p><p>Thanks,Security Team</p>

<hr>
<p>Vážená {user_first_name_last_name},</p>
<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo změněno dne {update_password_time} z IP adresy {ip}.</p>

<p>Pokud jste tak učinila, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedla nebo se domníváte, že k Vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';

######## Email config for update login password functionltiy.Email sent to company user on his email adress.
// Company
//$config['account_management_company_account_update_password_section_email_cc'] = 'catalin.basturescu@gmail.com';
$config['account_management_company_account_update_password_section_email_bcc'] = 'catalinbasturescu@gmail.com';
$config['account_management_company_account_update_password_section_email_from'] = 'security@'.HTTP_HOST;
$config['account_management_company_account_update_password_section_email_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['account_management_company_account_update_password_section_email_from_name'] = 'travai.cz (Update password)';
//$config['account_management_company_account_update_password_section_email_subject'] = '{company_name}, Update password(company)';
$config['account_management_company_account_update_password_section_email_subject'] = 'změna hesla - Update password(company)';


$config['account_management_company_account_update_password_section_email_message'] = '<p>Dear {company_name},</p><p>You successfully changed your iPrace.online account email address</p><p>This message is to confirm that your account password was changed on {update_password_time} from IP {ip}.</p><p>If you did this, you can safely disregard this email.</p><p>If you did not make this change or you believe an unauthorized person has accessed your account, please contact us immediately.</p><p>Thanks,Security Team</p>

<hr>
<p>Vážená {company_name},</p>
<p>potvrzujeme, že přihlašovací heslo k Vašemu účtu bylo změněno dne {update_password_time} z IP adresy {ip}.</p>

<p>Pokud jste tak učinili, můžete tento e-mail bezpečně ignorovat.</p>

<p>Pokud jste tuto změnu neprovedli nebo se domníváte, že k vašemu účtu přistoupila neoprávněná osoba, okamžitě nás kontaktujte.</p>

<p>Travai bezpečnostní tým.</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';

// Email config for close account start//
//$config['user_close_account_request_email_cc'] = 'catalin.basturescu@gmail.com';
$config['user_close_account_request_email_bcc'] = 'catalinbasturescu@gmail.com';
$config['user_close_account_request_email_from'] = 'no-reply@'.HTTP_HOST;
$config['user_close_account_request_email_from_name'] = 'Close Account';
$config['user_close_account_request_email_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['user_close_account_request_email_subject'] = 'close account';
$config['user_close_account_request_email_message'] = 'Hi there
<p>We received your email regarding close account. Thanks for informing us.</p>
<p>This is an automated message. We need some time to process each email that we receive, but we will write back to you personally as soon as we can. Thank you for your understanding!</p>
<p>Kind regards</p>
 <u>Details of your close account</u>
 <p><b>Close account request send by:</b> <a href="{user_profile_page_url}" target="_blank">{user_first_name_last_name_or_company_name}</a><br></p>
 <p><b>Close Account Reason:</b> {close_account_reason}</p>
 <p><b>Reason Description:</b><br>{close_account_reason_feedback}</p>
 <p>You submitted request to close your account on {close_account_request_sent_time}</p>
 <hr>
	Travai agentura, s.r.o.<br>
	Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
	Support 24/7: support@xxxxxx.com<br>
	Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
	<hr>
	Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.';
// Email config for close account end//

//end section for Zavření účtu / Close Account