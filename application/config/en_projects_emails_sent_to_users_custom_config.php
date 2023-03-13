<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Email Config Variables for report violation regarding projects(From project detail page)
|--------------------------------------------------------------------------
| 
*/
//$config['email_cc_report_project_violation'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_report_project_violation'] = 'catalin.basturescu@gmail.com';
$config['email_from_report_project_violation'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_project_violation'] = 'Project violation report';
$config['email_reply_to_report_project_violation'] = 'no-reply@'.HTTP_HOST;
$config['email_subject_report_project_violation'] = 'project violation report';


$config['email_message_report_project_violation'] = 'Hi there

<p>We received your email regarding violation on <a href="{project_url}" target="_blank">{project_title}</a>. Thanks for informing us.</p>

<p>This is an automated message. We need some time to process each email that we receive, but we will write back to you personally as soon as we can. Thank you for your understanding!</p>

<p>Kind regards</p>
 
 <u>Details of your report</u>
 <p><b>Violation reported by:</b> <a href="{user_profile_page_url}" target="_blank">{user_first_name_last_name_or_company_name}</a><br></p>
 <p><b>Project Title:</b> <a href="{project_url}" target="_blank">{project_title}</a></p>
 <p><b>Reason:</b> {project_violation_reason}</p>
 <p><b>Details:</b><br>{project_violation_detail}</p>
 <hr>
	Travai agentura, s.r.o.<br>
	Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
	Support 24/7: support@xxxxxx.com<br>
	Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
	<hr>
	Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.';

?>