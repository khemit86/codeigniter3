<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Email Config Variables for contact us page email
|--------------------------------------------------------------------------
|
*/
$config['contact_us_page_email_bcc'] = 'catalin.basturescu@gmail.com';
//$config['contact_us_page_email_cc'] = 'catalin.basturescu@gmail.com';
$config['contact_us_page_email_from'] = 'no-reply@'.HTTP_HOST;
$config['contact_us_page_email_from_name'] = 'Travai Podpora';
$config['contact_us_page_email_subject'] = 'Contact Us';
$config['contact_us_page_email_reply_to'] = 'no-reply@'.HTTP_HOST;

$config['contact_us_page_email_message_first_last_name'] = '<p><b>Name:</b> {first_last_name}</p>';
$config['contact_us_page_email_message_company_name'] = '<p><b>Company name:</b> {company_name}</p>';
$config['contact_us_page_number_of_employees'] = '<p><b>Number of employees:</b> {number_of_employees}</p>';

$config['contact_us_page_email_message'] = 'Hi there
<p>We received your email regarding contact. Thanks for informing us.</p>
<p>This is an automated message. We need some time to process each email that we receive, but we will write back to you personally as soon as we can. Thank you for your understanding!</p>
<p>Kind regards</p>
<u>Details are mention below</u>
{first_last_name}
{company_name}
{number_of_employees}
<p><b>Contact Email:</b> {contact_email}</p>
<p><b>Description:</b><br>{description}</p>
<p><b>Phone Number:</b><br>{phone_number}</p>
<p><b>Contact Reason:</b><br>{contact_reason}</p>
<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.';

?>