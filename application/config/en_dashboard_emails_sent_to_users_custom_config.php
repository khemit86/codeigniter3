<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Email Config Variables for send feedback functionality from dasboard left navigation menu 
|--------------------------------------------------------------------------
| 
*/
//$config['email_cc_send_feedback'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_send_feedback'] = 'catalinbasturescu@gmail.com';
$config['email_from_send_feedback'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_send_feedback'] = 'Feedback submit';
$config['email_reply_to_send_feedback'] = 'no-reply@'.HTTP_HOST;

$config['email_subject_send_feedback_male_sender'] = 'Male : New feedback submitted by {user_first_name_last_name}';
$config['email_message_send_feedback_male_sender'] = '<p>Hello male <b>{user_first_name_last_name}</b></p>

<p>We received your email regarding feedback. Thanks for informing us.</p>

<p>This is an automated message. We need some time to process each email that we receive, but we will write back to you personally as soon as we can. Thank you for your understanding!</p>

<p>Kind regards</p>
 
 <u>Details of your report</u>
 <p><b>Feedback Given by:</b> <a href="{user_profile_page_url}" target="_blank">{user_first_name_last_name}</a><br></p>
 <p><b>Details:</b><br>{feedback_message}</p>
 <hr>
	Travai agentura, s.r.o.<br>
	Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
	Support 24/7: support@xxxxxx.com<br>
	Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
	<hr>
	Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.';

###############################################################

$config['email_subject_send_feedback_female_sender'] = 'Female : New feedback submitted by {user_first_name_last_name}';
$config['email_message_send_feedback_female_sender'] = '<p>Hello female <b>{user_first_name_last_name}</b></p>

<p>We received your email regarding feedback. Thanks for informing us.</p>

<p>This is an automated message. We need some time to process each email that we receive, but we will write back to you personally as soon as we can. Thank you for your understanding!</p>

<p>Kind regards</p>
 
 <u>Details of your report</u>
 <p><b>Feedback Given by:</b> <a href="{user_profile_page_url}" target="_blank">{user_first_name_last_name}</a><br></p>
 <p><b>Details:</b><br>{feedback_message}</p>
 <hr>
	Travai agentura, s.r.o.<br>
	Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
	Support 24/7: support@xxxxxx.com<br>
	Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
	<hr>
	Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.';

###############################################################

$config['email_subject_send_feedback_company_sender'] = 'Company : New feedback submitted by {user_company_name}';
$config['email_message_send_feedback_company_sender'] = '<p>Hello Company <b>{user_company_name}</b></p>

<p>We received your email regarding feedback. Thanks for informing us.</p>

<p>This is an automated message. We need some time to process each email that we receive, but we will write back to you personally as soon as we can. Thank you for your understanding!</p>

<p>Kind regards</p>
 
 <u>Details of your report</u>
 <p><b>Feedback Given by:</b> <a href="{user_profile_page_url}" target="_blank">{user_company_name}</a><br></p>
 <p><b>Details:</b><br>{feedback_message}</p>
 <hr>
	Travai agentura, s.r.o.<br>
	Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
	Support 24/7: support@xxxxxx.com<br>
	Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
	<hr>
	Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.';

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
Telefon: +420 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

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
Telefon: +420 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

?>