<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Email Config Variables for send project invitation to sp
|--------------------------------------------------------------------------
| 
*/	

######Email config for sent project invitations to SP by PO start ###
// for fixed and hourly projects
// $config['project_invitation_email_cc'] = 'catalin.basturescu@gmail.com';
$config['project_invitation_email_bcc'] = 'catalinbasturescu@gmail.com';
$config['project_invitation_email_from'] = 'invitation@'.HTTP_HOST;
$config['project_invitation_email_reply_to'] = 'no-reply@'.HTTP_HOST;
$config['project_invitation_email_from_name'] = '{user_first_name_last_name_or_company_name} (via devserver.info)';
//$config['project_invitation_email_from_name'] = 'Registration manish.Devserver1.info';
$config['project_invitation_email_subject'] = 'Pozvání na projekt - Project Invitation';
$config['project_invitation_email_message'] = '<p>{sp_name},</p>
<p>you have a project inivitation from <a href="{po_profile_url_link}" target="_blank">{po_name}</a></p>

<p>Title: <a href="{project_url_link}">{project_title}</a></p>
<p><a href="{project_url_link}"><strong>Click Here</strong></a> for accept the invitation<p>

<hr>

<p>{sp_name},</p>

<p>bylo pro Vás vytvořené pozvání na projekt "<strong><a href="{project_url_link}">{project_title}</a></strong>" od <a href="{po_profile_url_link}" target="_blank">{po_name}</a></p>

<p>Podívat se a reagovat můžete <a href="{project_url_link}"><strong>zde...</strong></a><p>

<hr>
Travai agentura, s.r.o.<br>Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';



######Email config for sent project invitations to SP by PO start ###
// for fulltime projects
// $config['fulltime_project_invitation_email_cc'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_invitation_email_bcc'] = 'catalinbasturescu@gmail.com';
$config['fulltime_project_invitation_email_from'] = 'invitation@'.HTTP_HOST;
$config['fulltime_project_invitation_email_reply_to'] = 'no-reply@'.HTTP_HOST;
/* $config['fulltime_project_invitation_email_from_name'] = 'manish sharma (via devserver.info)'; */
$config['fulltime_project_invitation_email_from_name'] = '{user_first_name_last_name_or_company_name} (via devserver.info)';
$config['fulltime_project_invitation_email_subject'] = 'Pozvání na pracovní pozici - Fulltime Project Invitation';
$config['fulltime_project_invitation_email_message'] = '<p>{sp_name}</p><p>you have an project iniviation from <a href="{po_profile_url_link}" target="_blank">{po_name}</a></p><p>Title:<a href="{fulltime_project_url_link}">{fulltime_project_title}</a></p><p><a href="{fulltime_project_url_link}"><strong>Click Here</strong></a> for accept the invitation<p>
<hr>
<p>{sp_name},</p>
<p>bylo pro Vás vytvořené pozvání na pracovní pozici "<strong><a href="{fulltime_project_url_link}">{fulltime_project_title}</a></strong>" od <a href="{po_profile_url_link}" target="_blank">{po_name}</a></p>

<p>Podívat se a reagovat můžete <a href="{fulltime_project_url_link}"><strong>zde...</strong></a><p>
<hr>
Travai agentura, s.r.o.<br>Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus';

?>