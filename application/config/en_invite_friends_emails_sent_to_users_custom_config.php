<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Email Config Variables for Invite friend functionality 
|--------------------------------------------------------------------------
| 
*/	

###########Email config defined for send invitaion by male user ##########
//$config['email_cc_invite_friend'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_invite_friend'] = 'catalinbasturescu@gmail.com';
$config['email_from_invite_friend'] = 'pozvani@'.HTTP_HOST;
$config['email_reply_to_invite_friend'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_invite_friend'] = '{user_first_name_last_name_or_company_name} (přes '.HTTP_HOST.')';
//$config['email_subject_invite_friend'] = 'join me on on '.HTTP_HOST;
//$config['email_message_invite_friend_male_sender'] - TO BE IMPLEMENTED

//send invitation MALE
$config['email_subject_invite_friend_male_sender'] = 'Male - Následujte mě na '.HTTP_HOST;

$config['email_message_invite_friend_male_sender'] = '<p>I would like to invite you to join me on Travai.cz.</p>
<p>Simply "<a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">Click here</a> or on the link from below and create your account. Registration is completly free !"</p>			
<p><a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">{referral_url}</a></p>			
<p>Thank you,</p>
<p>{user_first_name_last_name_or_company_name}</p>

<hr>

<p>You\'ve received this email because user "{user_first_name_last_name_or_company_name}" sent an invitation to this address.</p>		
<p>You don\'t know who "{user_first_name_last_name_or_company_name}" is ? Visit his profile page <a style="font-weight:bold;color:black;" target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a></p>															
<p>If that doesn\'t seem right, talk to "{user_first_name_last_name_or_company_name}" or email us at support@xxxxxx.com.</p>
##################################################################################################

<p>{user_first_name_last_name_or_company_name} vás zve, abyste se připojili k němu na Travai !</p>

<p>Připojte se <a target="_blank" href="{referral_url}">Kliknutím ZDE</a> nebo níže pomocí odkazu a vytvořte si svůj účet. Registrace je zdarma !</p>

<a target="_blank" href="{referral_url}">{referral_url}</a>

<hr>
Obdrželi jste tento email, protože náš uživatel {user_first_name_last_name_or_company_name} vám poslal pozvání na tuto emailovou adresu.

Znáte se s {user_first_name_last_name_or_company_name} ? Navštivte jeho profilovou stránku <a target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a>.

Pokud se vám něco nezdá, kontaktujte {user_first_name_last_name_or_company_name} nebo kontaktujte nás.

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.';
#######################################################################################################




###########Email config defined for send invitaion by male user as a reminder ##########
//reminder SEND invite friend MALE
//$config['email_cc_reminder_invite_friend'] = 'catalin.basturescu@gmail.com';
$config['email_bcc_reminder_invite_friend'] = 'catalinbasturescu@gmail.com';
$config['email_from_reminder_invite_friend'] = 'pozvani@'.HTTP_HOST;
$config['email_reply_to_reminder_invite_friend'] = 'no-reply@'.HTTP_HOST;
$config['email_from_name_reminder_invite_friend'] = '{user_first_name_last_name_or_company_name} (přes '.HTTP_HOST.')';

//$config['email_subject_reminder_invite_friend'] = 'reminder - join me on on '.HTTP_HOST;

$config['email_subject_reminder_invite_friend_male_sender'] = 'Male - Nezapomeňte mě následovat na '.HTTP_HOST;

$config['email_message_reminder_invite_friend_male_sender'] = '<p>I have sent you few days ago an invite to join me on Travai.cz. By this I would like to remind you of my invitation.</p>

<p>Simply "<a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">Click here</a> or on the link from below and create your account. Registration is completly free !"</p>			
<p><a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">{referral_url}</a></p>			
<p>Thank you,</p>
<p>{user_first_name_last_name_or_company_name}</p>
<br>
<p>You\'ve received this email because user "{user_first_name_last_name_or_company_name}" sent an invitation to this address.</p>		
<p>You don\'t know who "{user_first_name_last_name_or_company_name}" is ? Visit his profile page <a style="font-weight:bold;color:black;" target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a></p>															
<p>If that doesn\'t seem right, talk to "{user_first_name_last_name_or_company_name}" or email us at support@xxxxxx.com.</p>

<hr>

<p>Před pár dny jsem vám poslal pozvání, abyste se ke mně připojili na Travai.cz. Tímto bych vám chtěl připomenout mé pozvání.</p>

<p>Připojte se <a target="_blank" href="{referral_url}">Kliknutím ZDE</a> nebo níže pomocí odkazu a vytvořte si svůj účet. Registrace je zdarma !</p>

<a target="_blank" href="{referral_url}">{referral_url}</a>

<hr>
Obdrželi jste tento email, protože náš uživatel {user_first_name_last_name_or_company_name} vám poslal pozvání na tuto emailovou adresu.

Znáte se s {user_first_name_last_name_or_company_name} ? Navštivte jeho profilovou stránku <a target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a>.

Pokud se vám něco nezdá, kontaktujte {user_first_name_last_name_or_company_name} nebo kontaktujte nás.

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.';
###########Email config defined for send invitaion by female user ##########
//send invitation feMALE
$config['email_subject_invite_friend_female_sender'] = 'Female - Následujte mě na '.HTTP_HOST;

$config['email_message_invite_friend_female_sender'] = '<p>I would like to invite you to join me on Travai.cz.</p>
<p>Simply "<a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">Click here</a> or on the link from below and create your account. Registration is completly free !"</p>			
<p><a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">{referral_url}</a></p>			
<p>Thank you,</p>
<p>{user_first_name_last_name_or_company_name}</p>
<br>
<p>You\'ve received this email because user "{user_first_name_last_name_or_company_name}" sent an invitation to this address.</p>		
<p>You don\'t know who "{user_first_name_last_name_or_company_name}" is ? Visit her profile page <a style="font-weight:bold;color:black;" target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a></p>															
<p>If that doesn\'t seem right, talk to "{user_first_name_last_name_or_company_name}" or email us at support@xxxxxx.com.</p>
';

###########Email config defined for send invitaion by female user as a reminder ##########

$config['email_subject_reminder_invite_friend_female_sender'] = 'Female - Nezapomeňte mě následovat na '.HTTP_HOST;
$config['email_message_reminder_invite_friend_female_sender'] = '<p>I have sent you few days ago an invite to join me on Travai.cz. By this I would like to remind you of my invitation.</p>
<p>Simply "<a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">Click here</a> or on the link from below and create your account. Registration is completly free !"</p>			
<p><a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">{referral_url}</a></p>			
<p>Thank you,</p>
<p>{user_first_name_last_name_or_company_name}</p>
<br>
<p>You\'ve received this email because user "{user_first_name_last_name_or_company_name}" sent an invitation to this address.</p>		
<p>You don\'t know who "{user_first_name_last_name_or_company_name}" is ? Visit her profile page <a style="font-weight:bold;color:black;" target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a></p>															
<p>If that doesn\'t seem right, talk to "{user_first_name_last_name_or_company_name}" or email us at support@xxxxxx.com.</p>
';


###########Email config defined for send invitaion by company user ##########
############################ invite friend COMPANY

$config['email_subject_invite_friend_company_sender'] = 'Company - Následujte nás na '.HTTP_HOST;

$config['email_message_invite_friend_company_sender'] = '<p>I would like to invite you to join me on Travai.cz.</p>
<p>Simply "<a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">Click here</a> or on the link from below and create your account. Registration is completly free !"</p>			
<p><a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">{referral_url}</a></p>			
<p>Thank you,</p>
<p>{user_first_name_last_name_or_company_name}</p>
<br>
<p>You\'ve received this email because user "{user_first_name_last_name_or_company_name}" sent an invitation to this address.</p>		
<p>You don\'t know who "{user_first_name_last_name_or_company_name}" is ? Visit its profile page <a style="font-weight:bold;color:black;" target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a></p>															
<p>If that doesn\'t seem right, talk to "{user_first_name_last_name_or_company_name}" or email us at support@xxxxxx.com.</p>
';

###########Email config defined for send invitaion by company user as a reminder##########
############################ resend invite friend COMPANY


$config['email_subject_reminder_invite_friend_company_sender'] = 'Company - Nezapomeňte nás následovat na '.HTTP_HOST;

$config['email_message_reminder_invite_friend_company_sender'] = '<p>I have sent you few days ago an invite to join me on Travai.cz. By this I would like to remind you of my invitation.</p>
<p>Simply "<a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">Click here</a> or on the link from below and create your account. Registration is completly free !"</p>			
<p><a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">{referral_url}</a></p>			
<p>Thank you,</p>
<p>{user_first_name_last_name_or_company_name}</p>
<br>
<p>You\'ve received this email because user "{user_first_name_last_name_or_company_name}" sent an invitation to this address.</p>		
<p>You don\'t know who "{user_first_name_last_name_or_company_name}" is ? Visit its profile page <a style="font-weight:bold;color:black;" target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a></p>															
<p>If that doesn\'t seem right, talk to "{user_first_name_last_name_or_company_name}" or email us at support@xxxxxx.com.</p>
';

###############################################################################################




###########Email config defined for send invitaion by company app male user ##########
############################ invite friend COMPANY APP male
$config['email_subject_invite_friend_company_app_male_sender'] = 'app-Male - Nezapomeňte mě následovat na '.HTTP_HOST;

$config['email_message_invite_friend_company_app_male_sender'] = '<p>app-male I would like to invite you to join me on Travai.cz.</p>
<p>Simply "<a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">Click here</a> or on the link from below and create your account. Registration is completly free !"</p>			
<p><a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">{referral_url}</a></p>			
<p>Thank you,</p>
<p>{user_first_name_last_name_or_company_name}</p>

<hr>

<p>You\'ve received this email because user "{user_first_name_last_name_or_company_name}" sent an invitation to this address.</p>		
<p>You don\'t know who "{user_first_name_last_name_or_company_name}" is ? Visit his profile page <a style="font-weight:bold;color:black;" target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a></p>															
<p>If that doesn\'t seem right, talk to "{user_first_name_last_name_or_company_name}" or email us at support@xxxxxx.com.</p>
##################################################################################################

<p>{user_first_name_last_name_or_company_name} vás zve, abyste se připojili k němu na Travai !</p>

<p>Připojte se <a target="_blank" href="{referral_url}">Kliknutím ZDE</a> nebo níže pomocí odkazu a vytvořte si svůj účet. Registrace je zdarma !</p>

<a target="_blank" href="{referral_url}">{referral_url}</a>

<hr>
Obdrželi jste tento email, protože náš uživatel {user_first_name_last_name_or_company_name} vám poslal pozvání na tuto emailovou adresu.

Znáte se s {user_first_name_last_name_or_company_name} ? Navštivte jeho profilovou stránku <a target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a>.

Pokud se vám něco nezdá, kontaktujte {user_first_name_last_name_or_company_name} nebo kontaktujte nás.

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.';

###########Email config defined for send invitaion by company app male user as a reminder##########
############################ resend invite friend COMPANY app male
$config['email_subject_reminder_invite_friend_company_app_male_sender'] = 'app-Male - Nezapomeňte mě následovat na '.HTTP_HOST;
$config['email_message_reminder_invite_friend_company_app_male_sender'] = '<p>app-male I have sent you few days ago an invite to join me on Travai.cz. By this I would like to remind you of my invitation.</p>

<p>Simply "<a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">Click here</a> or on the link from below and create your account. Registration is completly free !"</p>			
<p><a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">{referral_url}</a></p>			
<p>Thank you,</p>
<p>{user_first_name_last_name_or_company_name}</p>
<br>
<p>You\'ve received this email because user "{user_first_name_last_name_or_company_name}" sent an invitation to this address.</p>		
<p>You don\'t know who "{user_first_name_last_name_or_company_name}" is ? Visit his profile page <a style="font-weight:bold;color:black;" target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a></p>															
<p>If that doesn\'t seem right, talk to "{user_first_name_last_name_or_company_name}" or email us at support@xxxxxx.com.</p>

<hr>

<p>Před pár dny jsem vám poslal pozvání, abyste se ke mně připojili na Travai.cz. Tímto bych vám chtěl připomenout mé pozvání.</p>

<p>Připojte se <a target="_blank" href="{referral_url}">Kliknutím ZDE</a> nebo níže pomocí odkazu a vytvořte si svůj účet. Registrace je zdarma !</p>

<a target="_blank" href="{referral_url}">{referral_url}</a>

<hr>
Obdrželi jste tento email, protože náš uživatel {user_first_name_last_name_or_company_name} vám poslal pozvání na tuto emailovou adresu.

Znáte se s {user_first_name_last_name_or_company_name} ? Navštivte jeho profilovou stránku <a target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a>.

Pokud se vám něco nezdá, kontaktujte {user_first_name_last_name_or_company_name} nebo kontaktujte nás.

<hr>
Travai agentura, s.r.o.<br>
Phone CZ: +357 25 558 777 (Mon-Sun 08:00-20:00) · Live Chat<br>
Support 24/7: support@xxxxxx.com<br>
Address: FXTM Tower, 35 Lamprou Konstantara, Kato Polemidia, 4156, Limassol, Cyprus
<hr>
Na tento e-mail neodpovídejte. Je automaticky generován a tato e-mailová adresa není kontrolována.';

###########Email config defined for send invitaion by company app female user ##########
############################ invite friend COMPANY APP female
$config['email_subject_invite_friend_company_app_female_sender'] = 'app-female - Následujte mě na '.HTTP_HOST;

$config['email_message_invite_friend_company_app_female_sender'] = '<p>app-female I would like to invite you to join me on Travai.cz.</p>
<p>Simply "<a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">Click here</a> or on the link from below and create your account. Registration is completly free !"</p>			
<p><a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">{referral_url}</a></p>			
<p>Thank you,</p>
<p>{user_first_name_last_name_or_company_name}</p>
<br>
<p>You\'ve received this email because user "{user_first_name_last_name_or_company_name}" sent an invitation to this address.</p>		
<p>You don\'t know who "{user_first_name_last_name_or_company_name}" is ? Visit her profile page <a style="font-weight:bold;color:black;" target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a></p>															
<p>If that doesn\'t seem right, talk to "{user_first_name_last_name_or_company_name}" or email us at support@xxxxxx.com.</p>';

###########Email config defined for send invitaion by company app female user as a reminder##########
############################ resend invite friend COMPANY app female
$config['email_subject_reminder_invite_friend_company_app_female_sender'] = 'app-female - Nezapomeňte mě následovat na '.HTTP_HOST;
$config['email_message_reminder_invite_friend_company_app_female_sender'] = '<p>app-female I have sent you few days ago an invite to join me on Travai.cz. By this I would like to remind you of my invitation.</p>
<p>Simply "<a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">Click here</a> or on the link from below and create your account. Registration is completly free !"</p>			
<p><a style="font-weight:bold;color:black;" target="_blank" href="{referral_url}">{referral_url}</a></p>			
<p>Thank you,</p>
<p>{user_first_name_last_name_or_company_name}</p>
<br>
<p>You\'ve received this email because user "{user_first_name_last_name_or_company_name}" sent an invitation to this address.</p>		
<p>You don\'t know who "{user_first_name_last_name_or_company_name}" is ? Visit her profile page <a style="font-weight:bold;color:black;" target="_blank" href="{profile_page_url}">{user_first_name_last_name_or_company_name}</a></p>															
<p>If that doesn\'t seem right, talk to "{user_first_name_last_name_or_company_name}" or email us at support@xxxxxx.com.</p>';

?>