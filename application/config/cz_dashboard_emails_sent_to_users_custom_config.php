<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Email Config Variables for send feedback functionality from dasboard left navigation menu
|--------------------------------------------------------------------------
|
*/
//$config['email_cc_send_feedback'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_send_feedback'] = 'catalin.basturescu@gmail.com';
$config['email_from_send_feedback'] = 'no-reply@'.HTTP_HOST;
//$config['email_from_name_send_feedback'] = 'Feedback submit';
$config['email_from_name_send_feedback'] = 'Travai Podpora';

//$config['email_reply_to_send_feedback'] = 'no-reply@'.HTTP_HOST;
//$config['email_subject_send_feedback'] = 'New feedback submitted by {user_first_name_last_name_or_company_name}';

$config['email_subject_send_feedback_male_sender'] = 'Zpětná vazba';
$config['email_message_send_feedback_male_sender'] = 'Vážený {user_first_name_last_name},

<p>obdrželi jsme Vaši zprávu se zpětnou vazbou. Děkujeme, že jste nás kontaktoval.</p>

<p>Toto je automatická zpráva. Potřebujeme nějaký čas pro zpracování a v případě dotazu Vás co nejdříve kontaktujeme.</p>

<p>Přejeme příjemný den</p>
______________________________________________________________________________

<p><b>Zpětná vazba vytvořená uživatelem:</b> <a href="{user_profile_page_url}" target="_blank">{user_first_name_last_name}</a></p>

<p><b>Obsah zpětné vazby:</b><br>{feedback_message}</p>

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

#################################################

$config['email_subject_send_feedback_company_app_male_sender'] = 'Zpětná vazba';
$config['email_message_send_feedback_company_app_male_sender'] = 'Vážený {user_first_name_last_name},

<p>obdrželi jsme Vaši zprávu se zpětnou vazbou. Děkujeme, že jste nás kontaktoval.</p>

<p>Toto je automatická zpráva. Potřebujeme nějaký čas pro zpracování a v případě dotazu Vás co nejdříve kontaktujeme.</p>

<p>Přejeme příjemný den</p>
______________________________________________________________________________

<p><b>Zpětná vazba vytvořená uživatelem:</b> <a href="{user_profile_page_url}" target="_blank">{user_first_name_last_name}</a></p>

<p><b>Obsah zpětné vazby:</b><br>{feedback_message}</p>

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

///////////////////////////////////////////////////////////////////////////////////////////////

$config['email_subject_send_feedback_female_sender'] = 'Zpětná vazba';
$config['email_message_send_feedback_female_sender'] = 'Vážená {user_first_name_last_name},

<p>obdrželi jsme Vaši zprávu se zpětnou vazbou. Děkujeme, že jste nás kontaktovala.</p>

<p>Toto je automatická zpráva. Potřebujeme nějaký čas pro zpracování a v případě dotazu Vás co nejdříve kontaktujeme.</p>

<p>Přejeme příjemný den</p>
______________________________________________________________________________

<p><b>Zpětná vazba vytvořená uživatelem:</b> <a href="{user_profile_page_url}" target="_blank">{user_first_name_last_name}</a></p>

<p><b>Obsah zpětné vazby:</b><br>{feedback_message}</p>

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

####################################################################################

$config['email_subject_send_feedback_company_app_female_sender'] = 'Zpětná vazba';
$config['email_message_send_feedback_company_app_female_sender'] = 'Vážená {user_first_name_last_name},

<p>obdrželi jsme Vaši zprávu se zpětnou vazbou. Děkujeme, že jste nás kontaktovala.</p>

<p>Toto je automatická zpráva. Potřebujeme nějaký čas pro zpracování a v případě dotazu Vás co nejdříve kontaktujeme.</p>

<p>Přejeme příjemný den</p>
______________________________________________________________________________

<p><b>Zpětná vazba vytvořená uživatelem:</b> <a href="{user_profile_page_url}" target="_blank">{user_first_name_last_name}</a></p>

<p><b>Obsah zpětné vazby:</b><br>{feedback_message}</p>

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

/////////////////////////////////////////////////////////////////////////////////////////////

$config['email_subject_send_feedback_company_sender'] = 'Zpětná vazba';
$config['email_message_send_feedback_company_sender'] = 'Vážení {user_company_name},

<p>obdrželi jsme vaši zprávu se zpětnou vazbou. Děkujeme, že jste nás kontaktovali.</p>

<p>Toto je automatická zpráva. Potřebujeme nějaký čas pro zpracování a v případě dotazu vás co nejdříve kontaktujeme.</p>

<p>Přejeme příjemný den</p>
______________________________________________________________________________

<p><b>Zpětná vazba vytvořená uživatelem:</b> <a href="{user_profile_page_url}" target="_blank">{user_company_name}</a></p>

<p><b>Obsah zpětné vazby:</b><br>{feedback_message}</p>

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