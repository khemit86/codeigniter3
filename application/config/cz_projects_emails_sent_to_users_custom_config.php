<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Email Config Variables for report violation regarding projects(From project detail page)
|--------------------------------------------------------------------------
| 
*/	
//$config['email_cc_report_project_violation'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_report_project_violation'] = 'catalin.basturescu@gmail.com';
//$config['email_reply_to_report_project_violation'] = 'no-reply@'.HTTP_HOST;
$config['email_from_report_project_violation'] = 'no-reply@'.HTTP_HOST;
//$config['email_from_name_project_violation'] = 'Project violation report';
$config['email_from_name_project_violation'] = 'Travai Podpora';
//$config['email_subject_report_project_violation'] = '{user_first_name_last_name_or_company_name} reports violation on project id {project_id}';
//$config['email_subject_report_project_violation'] = 'project violation report';
$config['email_subject_report_project_violation'] = 'Oznámení porušení pravidel';


$config['email_message_report_project_violation'] = 'Děkujeme za kontaktování,

<p>potvrzujeme, že jsme obdrželi informaci o porušení pravidel na inzerátu <a href="{project_url}" target="_blank">{project_title}</a>.</p>

<p>Co nejdříve na tomto zjištění budeme pracovat a v případě potřeby budete kontaktováni.</p>

<p>Přejeme příjemný den</p>
______________________________________________________________________________

<p><b>Obsah informace o porušení pravidel</b></p>

<p><b>Inzerát:</b> <a href="{project_url}" target="_blank">{project_title}</a></p>

<p><b>Potencionální porušení:</b> {project_violation_reason}</p>
 
<p><b>Popis:</b><br>{project_violation_detail}</p>

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