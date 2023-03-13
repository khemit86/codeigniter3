<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Email Config Variables for Invite friend functionality 
|--------------------------------------------------------------------------
| 
*/

###########Email config defined for send invitaion by male user ##########

//send invitation MALE
//$config['email_cc_invite_friend'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_invite_friend'] = 'catalin.basturescu@gmail.com';
$config['email_from_invite_friend'] = 'pozvani@'.HTTP_HOST;
//$config['email_reply_to_invite_friend'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_invite_friend'] = '{user_first_name_last_name_or_company_name} (přes '.HTTP_HOST.')';

//$config['email_subject_invite_friend'] = 'join me on on '.HTTP_HOST;
$config['email_subject_invite_friend_male_sender'] = 'Následujte mě na '.HTTP_HOST;

$config['email_message_invite_friend_male_sender'] = '{user_first_name_last_name_or_company_name} vás zve, abyste se připojili k němu na Travai.

<p>Připojte se <a target="_blank" href="{referral_url}">kliknutím ZDE</a> nebo níže pomocí odkazu a vytvořte si svůj účet. Registrace je zdarma!</p>

<a target="_blank" href="{referral_url}">{referral_url}</a>
<br>
<hr>
Obdrželi jste tento email, protože náš uživatel {user_first_name_last_name_or_company_name} vám poslal pozvání na tuto emailovou adresu.

Znáte se s {user_first_name_last_name_or_company_name}? Navštivte jeho profilovou stránku <a target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a>.

Pokud se vám něco nezdá, kontaktujte {user_first_name_last_name_or_company_name} nebo kontaktujte nás.

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

//reminder SEND invite friend MALE
###########Email config defined for send invitaion by male user as a reminder ##########
//$config['email_cc_reminder_invite_friend'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_reminder_invite_friend'] = 'catalin.basturescu@gmail.com';
$config['email_from_reminder_invite_friend'] = 'pozvani@'.HTTP_HOST;
//$config['email_reply_to_reminder_invite_friend'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_reminder_invite_friend'] = '{user_first_name_last_name_or_company_name} (přes '.HTTP_HOST.')';

$config['email_subject_reminder_invite_friend_male_sender'] = 'Nezapomeňte mě následovat na '.HTTP_HOST;

$config['email_message_reminder_invite_friend_male_sender'] = 'Před pár dny jsem vám poslal pozvání, abyste se ke mně připojili na Travai. Tímto bych vám chtěl připomenout mé pozvání.

<p>Připojte se <a target="_blank" href="{referral_url}">kliknutím ZDE</a> nebo níže pomocí odkazu a vytvořte si svůj účet. Registrace je zdarma!</p>

<a target="_blank" href="{referral_url}">{referral_url}</a>
<br>
<hr>
Obdrželi jste tento email, protože náš uživatel {user_first_name_last_name_or_company_name} vám poslal pozvání na tuto emailovou adresu.

Znáte se s {user_first_name_last_name_or_company_name}? Navštivte jeho profilovou stránku <a target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a>.

Pokud se vám něco nezdá, kontaktujte {user_first_name_last_name_or_company_name} nebo kontaktujte nás.

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

##################################################################################################################
###########Email config defined for send invitaion by female user ##########
//send invitation feMALE
$config['email_subject_invite_friend_female_sender'] = 'Následujte mě na '.HTTP_HOST;

$config['email_message_invite_friend_female_sender'] = '{user_first_name_last_name_or_company_name} vás zve, abyste se připojili k ní na Travai.

<p>Připojte se <a target="_blank" href="{referral_url}">kliknutím ZDE</a> nebo níže pomocí odkazu a vytvořte si svůj účet. Registrace je zdarma!</p>

<a target="_blank" href="{referral_url}">{referral_url}</a>
<br>
<hr>
Obdrželi jste tento email, protože náš uživatel {user_first_name_last_name_or_company_name} vám poslala pozvání na tuto emailovou adresu.

Znáte se s {user_first_name_last_name_or_company_name}? Navštivte její profilovou stránku <a target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a>.

Pokud se vám něco nezdá, kontaktujte {user_first_name_last_name_or_company_name} nebo kontaktujte nás.

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

###########Email config defined for send invitaion by female user as a reminder ##########
############invite send reminder female sender

$config['email_subject_reminder_invite_friend_female_sender'] = 'Nezapomeňte mě následovat na '.HTTP_HOST;

$config['email_message_reminder_invite_friend_female_sender'] = 'Před pár dny jsem vám poslala pozvání, abyste se ke mně připojili na Travai. Tímto bych vám chtěla připomenout mé pozvání.

<p>Připojte se <a target="_blank" href="{referral_url}">kliknutím ZDE</a> nebo níže pomocí odkazu a vytvořte si svůj účet. Registrace je zdarma!</p>

<a target="_blank" href="{referral_url}">{referral_url}</a>
<br>
<hr>
Obdrželi jste tento email, protože náš uživatel {user_first_name_last_name_or_company_name} vám poslala pozvání na tuto emailovou adresu.

Znáte se s {user_first_name_last_name_or_company_name}? Navštivte její profilovou stránku <a target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a>.

Pokud se vám něco nezdá, kontaktujte {user_first_name_last_name_or_company_name} nebo kontaktujte nás.

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

###########Email config defined for send invitaion by company user ##########
############################ invite friend COMPANY
$config['email_subject_invite_friend_company_sender'] = 'Následujte nás na '.HTTP_HOST;

$config['email_message_invite_friend_company_sender'] = '{user_first_name_last_name_or_company_name} vás zvou, abyste se k nim připojili na Travai.

<p>Připojte se <a target="_blank" href="{referral_url}">kliknutím ZDE</a> nebo níže pomocí odkazu a vytvořte si svůj účet. Registrace je zdarma!</p>

<a target="_blank" href="{referral_url}">{referral_url}</a>
<br>
<hr>
Obdrželi jste tento email, protože náš uživatel {user_first_name_last_name_or_company_name} vám poslali pozvání na tuto emailovou adresu.

Znáte se s {user_first_name_last_name_or_company_name}? Navštivte jejich profilovou stránku <a target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a>.

Pokud se vám něco nezdá, kontaktujte {user_first_name_last_name_or_company_name} nebo kontaktujte nás.

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

###########Email config defined for send invitaion by company user as a reminder##########
############################ resend invite friend COMPANY
$config['email_subject_reminder_invite_friend_company_sender'] = 'Nezapomeňte nás následovat na '.HTTP_HOST;

$config['email_message_reminder_invite_friend_company_sender'] = 'Před pár dny jsme vám poslali pozvání, abyste se k nám připojili na Travai. Tímto bychom vám chtěli připomenout naše pozvání.

<p>Připojte se <a target="_blank" href="{referral_url}">kliknutím ZDE</a> nebo níže pomocí odkazu a vytvořte si svůj účet. Registrace je zdarma!</p>

<a target="_blank" href="{referral_url}">{referral_url}</a>
<br>
<hr>
Obdrželi jste tento email, protože náš uživatel {user_first_name_last_name_or_company_name} vám poslali pozvání na tuto emailovou adresu.

Znáte se s {user_first_name_last_name_or_company_name}? Navštivte jejich profilovou stránku <a target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a>.

Pokud se vám něco nezdá, kontaktujte {user_first_name_last_name_or_company_name} nebo kontaktujte nás.

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

###############################################################################################	


###########Email config defined for send invitaion by company app male user ##########
############################ invite friend COMPANY APP male
$config['email_subject_invite_friend_company_app_male_sender'] = 'Následujte mě na '.HTTP_HOST;
$config['email_message_invite_friend_company_app_male_sender'] = '{user_first_name_last_name_or_company_name} vás zve, abyste se připojili k němu na Travai.

<p>Připojte se <a target="_blank" href="{referral_url}">Kliknutím ZDE</a> nebo níže pomocí odkazu a vytvořte si svůj účet. Registrace je zdarma!</p>

<a target="_blank" href="{referral_url}">{referral_url}</a>
<br>
<hr>
Obdrželi jste tento email, protože náš uživatel {user_first_name_last_name_or_company_name} vám poslal pozvání na tuto emailovou adresu.

Znáte se s {user_first_name_last_name_or_company_name}? Navštivte jeho profilovou stránku <a target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a>.

Pokud se vám něco nezdá, kontaktujte {user_first_name_last_name_or_company_name} nebo kontaktujte nás.

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

###########Email config defined for send invitaion by company app male user as a reminder##########
############################ resend invite friend COMPANY app male
$config['email_subject_reminder_invite_friend_company_app_male_sender'] = 'Nezapomeňte mě následovat na '.HTTP_HOST;
$config['email_message_reminder_invite_friend_company_app_male_sender'] = 'Před pár dny jsem vám poslal pozvání, abyste se ke mně připojili na Travai. Tímto bych vám chtěl připomenout mé pozvání.

<p>Připojte se <a target="_blank" href="{referral_url}">Kliknutím ZDE</a> nebo níže pomocí odkazu a vytvořte si svůj účet. Registrace je zdarma!</p>

<a target="_blank" href="{referral_url}">{referral_url}</a>
<br>
<hr>
Obdrželi jste tento email, protože náš uživatel {user_first_name_last_name_or_company_name} vám poslal pozvání na tuto emailovou adresu.

Znáte se s {user_first_name_last_name_or_company_name}? Navštivte jeho profilovou stránku <a target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a>.

Pokud se vám něco nezdá, kontaktujte {user_first_name_last_name_or_company_name} nebo kontaktujte nás.

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

###########Email config defined for send invitaion by company app female user ##########
############################ invite friend COMPANY APP female
$config['email_subject_invite_friend_company_app_female_sender'] = 'Následujte mě na '.HTTP_HOST;
$config['email_message_invite_friend_company_app_female_sender'] = '{user_first_name_last_name_or_company_name} vás zve, abyste se připojili k ní na Travai.

<p>Připojte se <a target="_blank" href="{referral_url}">kliknutím ZDE</a> nebo níže pomocí odkazu a vytvořte si svůj účet. Registrace je zdarma!</p>

<a target="_blank" href="{referral_url}">{referral_url}</a>
<br>
<hr>
Obdrželi jste tento email, protože náš uživatel {user_first_name_last_name_or_company_name} vám poslala pozvání na tuto emailovou adresu.

Znáte se s {user_first_name_last_name_or_company_name}? Navštivte její profilovou stránku <a target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a>.

Pokud se vám něco nezdá, kontaktujte {user_first_name_last_name_or_company_name} nebo kontaktujte nás.

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

###########Email config defined for send invitaion by company app female user as a reminder##########
############################ resend invite friend COMPANY app female
$config['email_subject_reminder_invite_friend_company_app_female_sender'] = 'Nezapomeňte mě následovat na '.HTTP_HOST;
$config['email_message_reminder_invite_friend_company_app_female_sender'] = 'Před pár dny jsem vám poslala pozvání, abyste se ke mně připojili na Travai. Tímto bych vám chtěla připomenout mé pozvání.

<p>Připojte se <a target="_blank" href="{referral_url}">kliknutím ZDE</a> nebo níže pomocí odkazu a vytvořte si svůj účet. Registrace je zdarma!</p>

<a target="_blank" href="{referral_url}">{referral_url}</a>
<br>
<hr>
Obdrželi jste tento email, protože náš uživatel {user_first_name_last_name_or_company_name} vám poslala pozvání na tuto emailovou adresu.

Znáte se s {user_first_name_last_name_or_company_name}? Navštivte její profilovou stránku <a target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a>.

Pokud se vám něco nezdá, kontaktujte {user_first_name_last_name_or_company_name} nebo kontaktujte nás.

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