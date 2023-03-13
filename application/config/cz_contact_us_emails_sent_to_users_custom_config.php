<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Email Config Variables for contact us page email
|--------------------------------------------------------------------------
|
*/
$config['contact_us_page_email_bcc'] = 'catalin.basturescu@gmail.com';
//$config['contact_us_page_email_cc'] = 'catalin.basturescu@gmail.com';
$config['contact_us_page_email_from'] = 'kontakt@'.HTTP_HOST;
$config['contact_us_page_email_from_name'] = 'Travai Kontakt';
$config['contact_us_page_email_subject'] = 'potvrzení přijetí zprávy';
//$config['contact_us_page_email_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['contact_us_page_email_message_first_last_name'] = '<p><b>Jméno a příjmení:</b> {first_last_name}</p>';
$config['contact_us_page_email_message_company_name'] = '<p><b>Společnost:</b> {company_name}</p>';
$config['contact_us_page_number_of_employees'] = '<p><b>Počet zaměstnanců:</b> {number_of_employees}</p>';

$config['contact_us_page_email_message'] = 'Děkujeme za kontaktování,

<p>obdrželi jsme váš dotaz a ozveme se vám co nejdříve s odpovědí, obvykle do 24 až 72 hodin. V případě naléhavých dotazů nás kontaktujte telefonicky.</p>

<p>Přejeme příjemný den</p>
______________________________________________________________________________

<p><b><u>Obsah zprávy</u></b></p>
{first_last_name}
{company_name}
{number_of_employees}

<p><b>Emailová adresa:</b> {contact_email}</p>

<p><b>Telefon:</b> {phone_number}</p>

<p><b>Na co se nás ptáte:</b> {contact_reason}</p>

<p><b>Popis:</b> {description}</p>

<hr>
Travai agentura, s.r.o.<br>
Brno, Vídeňská 297/99
<hr>
Telefon: (+420) 515 910 910 (Po - Ne 8:00 - 18:00)
<br>
Email: podpora@travai.cz
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována. Pokud máte nějaké dotazy, kontaktujte nás emailem nebo telefonicky.';

?>