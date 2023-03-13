<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Email Config Variables for send project invitation to sp
|--------------------------------------------------------------------------
| 
*/	

######Email config for sent project invitations to SP by PO start ###

// for fixed and hourly projects
//$config['project_invitation_email_cc'] = 'catalin.basturescu@gmail.com';
$config['project_invitation_email_bcc'] = 'catalin.basturescu@gmail.com';
$config['project_invitation_email_from'] = 'no-reply@'.HTTP_HOST;
//$config['project_invitation_email_reply_to'] = 'no-reply@'.HTTP_HOST;

$config['project_invitation_email_from_name'] = '{user_first_name_last_name_or_company_name} (přes travai.cz)'; //THIS MUST BE UPDATED WITH DYNAMICAL INSERTION OF DOMAIN NAME

//$config['project_invitation_email_from_name'] = 'Registration manish.Devserver1.info';
$config['project_invitation_email_subject'] = 'Pozvání na projekt';
$config['project_invitation_email_message'] = '{sp_name},

<p>bylo pro Vás vytvořené pozvání na projekt "<strong><a href="{project_url_link}">{project_title}</a></strong>" od <a href="{po_profile_url_link}" target="_blank">{po_name}</a>.</p>

<p>Přejeme příjemný den</p>

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

######Email config for sent project invitations to SP by PO start ###
// for fulltime projects
//$config['fulltime_project_invitation_email_cc'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_invitation_email_bcc'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_invitation_email_from'] = 'no-reply@'.HTTP_HOST;
//$config['fulltime_project_invitation_email_reply_to'] = 'no-reply@'.HTTP_HOST;
/* $config['fulltime_project_invitation_email_from_name'] = 'manish sharma (via devserver.info)'; */
$config['fulltime_project_invitation_email_from_name'] = '{user_first_name_last_name_or_company_name} (přes travai.cz)';
$config['fulltime_project_invitation_email_subject'] = 'Pozvání na pracovní pozici';
$config['fulltime_project_invitation_email_message'] = '{sp_name},

<p>bylo pro Vás vytvořené pozvání na pracovní pozici "<strong><a href="{fulltime_project_url_link}">{fulltime_project_title}</a></strong>" od <a href="{po_profile_url_link}" target="_blank">{po_name}</a>.</p>

<p>Přejeme příjemný den</p>

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

#########End ##########

?>