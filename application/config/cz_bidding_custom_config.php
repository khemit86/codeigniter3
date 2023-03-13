<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

##### Config variables for bid form on project detail page

$config['project_details_page_project_bid_form_delivery_in'] = 'dodání do'; //sp place bid on fixed budget project

$config['project_details_page_bidder_listing_details_delivery_in'] = '<span class="delivery_in_color">dodání do</span>';

$config['project_details_page_bid_form_description_section_heading'] = 'detail nabídky';

$config['project_details_page_project_bid_form_place_bid_btn_txt'] = 'Odeslat';

$config['project_details_page_project_active_bidder_listing_edit_bid_btn_txt'] = 'Upravit nabídku';

$config['project_details_page_project_active_bidder_listing_retract_bid_btn_txt'] = 'Stáhnout nabídku';

$config['project_details_page_project_bid_form_cancel_btn_txt'] = 'Zrušit';

$config['project_details_page_project_bid_form_edit_bid_btn_txt'] = 'Upravit nabídku';


//termín pro rozhodnutí - used on both my projects and project details page - for both sp and po
$config['project_details_page_awarded_bidder_listing_details_awaiting_acceptance_expires_txt'] = 'termín pro rozhodnutí';


//These config are using on project detail page for showing heading of project tab for sp view(like sp login on project detail page and job is awarded/completed/inprogress to hisself the he will see his heading)
$config['project_details_page_awaiting_acceptance_project_tab_sp_view_txt'] = 'Moje nabídka';

$config['project_details_page_inprogress_project_tab_sp_view_txt'] = 'Moje práce';

$config['project_details_page_incomplete_project_tab_sp_view_txt'] = 'CZ-InComplete SP View';

$config['fulltime_project_details_page_active_dispute_tab_employee_view_txt'] = 'CZ-Fulltime-Active Dispute Sp view';

$config['project_details_page_active_dispute_tab_sp_view_txt'] = 'CZ-Active Dispute Sp view';

$config['project_details_page_completed_project_tab_sp_view_txt'] = 'Moje práce';

$config['project_details_page_marked_completed_project_tab_sp_view_txt'] = 'Označeno jako dokončené';

// config are using for project completed listing(project completed outside the portal)
$config['project_details_page_project_marked_as_complete_snippet_txt_sp_view'] = 'označeno jako dokončené';

$config['displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected'] = 'po dohodě';

$config['displayed_text_project_details_page_bidder_listing_details_confidential_option_selected'] = 'neveřejný';

##### Error messages when user trying to POST BID / APPLY NOW in invalid project
####EXPIRED PROJECTS STATUS
$config['project_details_page_sp_view_place_bid_expired_project'] = "Inzerát vypršel. Nabídka nelze odeslat.";// this variable used for when user is on project details page and clicks on - apply now - button // also used for when user has the bid form open and clicks on place bid button too

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_expired_project'] = "Inzerát vypršel. Příloha nelze nahrát.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_expired_project'] = "Inzerát vypršel. Příloha nelze smazat.";

####CANCELLED
$config['project_details_page_sp_view_place_bid_cancelled_project'] = "Inzerát je zrušený. Nabídka nelze poslat.";// cancelled by PO or admin - used for when user is on project details page and clicks on - apply now - button // also used for when user has the bid form open and clicks on place bid button too

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_cancelled_project'] = "Inzerát je zrušený. Příloha nelze nahrát.";

$config['project_details_page_sp_view_place_bid_open_bid_attachment_cancelled_project'] = "Inzerát je zrušený. Příloha nelze otevřít.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_cancelled_project'] = "Inzerát je zrušený. Příloha nelze smazat.";

####DELETED PROJECTS
$config['project_details_page_sp_view_place_bid_deleted_project'] = "Inzerát je smazaný. Nabídka nelze podat.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_deleted_project'] = "Inzerát je smazaný. Příloha nelze nahrát.";

$config['project_details_page_sp_view_place_bid_open_bid_attachment_deleted_project'] = "Inzerát je smazaný. Příloha nelze otevřít.";

$config['project_details_page_sp_view_place_bid_delete_attachment_deleted_project'] = "Inzerát je smazaný. Příloha nelze smazat.";

$config['project_details_page_sp_view_update_bid_upload_bid_attachment_deleted_project'] = "Inzerát je smazaný. Příloha nelze nahrát.";

$config['project_details_page_sp_view_update_bid_open_bid_attachment_deleted_project'] = "Inzerát je smazaný. Příloha nelze otevřít.";

$config['project_details_page_sp_view_update_bid_delete_attachment_deleted_project'] = "Inzerát je smazaný. Příloha nelze smazat.";


#############################################################################

$config['project_details_page_sp_view_place_bid_already_posted_bid_same_project'] = "Již jste poslali nabídku na tento projekt.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_already_posted_bid_project'] = "Již jste poslali nabídku na tento projekt. Příloha nelze nahrát.";

###################ERROR MESSAGES REGARDING AWARDED PROJECT
$config['project_details_page_sp_view_place_bid_awarded_different_sp_project'] = "Projekt již byl udělen. Nelze poslat nabídku.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_awarded_different_sp_project'] = "Projekt již byl udělen. Nelze nahrát přílohu.";

//AWARDED TO SAME USER
$config['project_details_page_sp_view_place_bid_awarded_same_sp_project'] = "Projekt vám byl udělen. Nelze poslat nabídku.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_awarded_same_sp_project'] = "Projekt vám byl udělen. Příloha nelze nahrát.";

$config['project_details_page_sp_view_place_bid_in_progress_same_sp_project'] = "Projekt vám byl udělen a práce probíhá. Nelze poslat nabídku.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_in_progress_same_sp_project'] = "Projekt vám byl udělen a práce probíhá. Nelze smazat přílohu.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_in_progress_same_sp_project'] = "Projekt vám byl udělen a práce probíhá. Nelze nahrát přílohu.";

$config['project_details_page_sp_view_place_bid_in_progress_different_sp_project'] = "Status inzerátu byl změněn na probíhající. Nelze poslat nabídku.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_in_progress_different_sp_project'] = "Status inzerátu byl změněn na probíhající. Nelze nahrát přílohu.";


//completed project
$config['project_details_page_sp_view_same_sp_try_place_bid_on_completed_project'] = "Již jste tento projekt dokončili. Nelze poslat nabídku.";

$config['project_details_page_sp_view_same_sp_try_place_bid_upload_bid_attachment_on_completed_project'] = "Již jste tento projekt dokončili. Nelze nahrát přílohu.";

$config['project_details_page_sp_view_same_sp_try_place_bid_delete_bid_attachment_on_completed_project'] = "Již jste tento projekt dokončili. Nelze smazat přílohu.";

$config['project_details_page_sp_view_different_sp_try_place_bid_on_completed_project'] = "Tento projekt je dokončený. Nelze poslat nabídku.";

$config['project_details_page_sp_view_different_sp_try_place_bid_upload_bid_attachment_on_completed_project'] = "Tento projekt je dokončený. Nelze nahrát přílohu.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_already_posted_same_sp_project'] = "Již jste poslali nabídku na tento projekt. Příloha nelze smazat.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_awarded_same_sp_project'] = "Projekt vám byl udělen. Nelze smazat přílohu.";


####### error message when user trying to edit bid in invalid project
$config['project_details_page_sp_view_update_bid_cancelled_project'] = "Inzerát byl zrušen. Nelze upravit nabídku.";

$config['project_details_page_sp_view_update_bid_deleted_project'] = "Inzerát byl smazán. Nelze upravit nabídku.";

$config['project_details_page_sp_view_update_bid_already_retracted_project'] = "Nabídka byla stažena. Nelze upravit nabídku.";

$config['project_details_page_sp_view_update_bid_awarded_same_sp_project'] = "Projekt vám byl udělen. Nelze upravit nabídku.";

$config['project_details_page_sp_view_update_bid_in_progress_same_sp_project'] = "Projekt vám byl udělen a práce probíhá. Nelze upravit nabídku.";

$config['project_details_page_sp_view_same_sp_try_update_bid_on_completed_project'] = "Projekt je dokončený. Nelze upravit nabídku.";

///////////////////attachment related issues
$config['project_details_page_sp_view_update_bid_upload_bid_attachment_awarded_same_sp_project'] = "Projekt vám byl udělen. Příloha nelze nahrát.";

$config['project_details_page_sp_view_update_bid_upload_bid_attachment_in_progress_same_sp_project'] = "Projekt vám byl udělen a práce probíhá. Nelze nahrát přílohu.";

$config['project_details_page_sp_view_same_sp_try_update_bid_upload_bid_attachment_on_completed_project'] = "Již jste tento projekt dokončili. Nelze nahrát přílohu.";

$config['project_details_page_sp_view_update_bid_already_retracted_upload_bid_attachment_same_sp_project'] = "Nabídka byla stažena. Nelze nahrát přílohu.";

$config['project_details_page_sp_view_update_bid_already_retracted_open_bid_attachment_same_sp_project'] = "Nabídka byla stažena. Nelze otevřít přílohu.";

//cancelled project
$config['project_details_page_sp_view_update_bid_upload_bid_attachment_cancelled_project'] = "Projekt je zrušený. Nelze nahrát přílohu.";

$config['project_details_page_sp_view_update_bid_open_bid_attachment_cancelled_project'] = "Projekt je zrušený. Nelze otevřít přílohu.";

$config['project_details_page_sp_view_update_bid_delete_bid_attachment_cancelled_project'] = "Projekt je zrušený. Nelze smazat přílohu.";

$config['project_details_page_sp_view_update_bid_delete_bid_attachment_awarded_same_sp_project'] = "Projekt vám byl udělen. Příloha nelze smazat.";

$config['project_details_page_sp_view_update_bid_delete_bid_attachment_in_progress_same_sp_project'] = "Projekt vám byl udělen a práce probíhá. Nelze smazat přílohu.";


$config['retract_bid_confirmation_project_modal_body'] = 'Opravdu chcete stáhnout <span class="display-inline-block">nabídku ?</span>';

$config['retract_bid_confirmation_project_modal_retract_btn_txt'] = 'Stáhnout';

################# error message when trying to retract bid
// when some unauthrized user(PO/unauthrizedSP) trying to retract bid
$config['project_details_page_sp_view_retract_bid_cancelled_project'] = "Inzerát byl zrušen. Nelze stáhnout nabídku.";

$config['project_details_page_sp_view_retract_bid_awarded_same_sp_project'] = "Projekt vám byl udělen. Nemůžete stáhnout nabídku.";

$config['project_details_page_sp_view_retract_bid_in_progress_same_sp_project'] = "Projekt vám byl udělen a práce probíhá. Nelze stáhnout nabídku.";

$config['project_details_page_sp_view_same_sp_try_retract_bid_on_completed_project'] = "Projekt je dokončený. Nelze stáhnout nabídku.";

$config['project_details_page_sp_view_retract_bid_deleted_project'] = "Inzerát byl smazán. Nelze stáhnout nabídku.";

$config['project_details_page_sp_view_retract_bid_already_retracted_project'] = "Nabídku jste již stáhli.";

################# error message when trying to award bid
// when some unauthrized user(PO/unauthrizedSP) trying to award bid
$config['project_details_page_po_view_award_bid_cancelled_project'] = "Inzerát byl zrušen. Projekt nelze udělit.";

$config['project_details_page_po_view_award_bid_deleted_project'] = "Inzerát byl smazán. Projekt nelze udělit.";

$config['project_details_page_po_view_award_bid_already_awarded_same_sp_project'] = 'Inzerát již byl udělený pro <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>.';

$config['project_details_page_po_view_award_bid_in_progress_same_sp_project'] = "Projekt již probíhá s tímto poskytovatelem. Nelze znovu udělit.";

$config['project_details_page_po_view_same_sp_try_award_bid_on_completed_project'] = "Projekt je dokončený. Nelze udělit poskytovateli.";

$config['project_details_page_po_view_award_bid_already_retracted_project'] = "Nabídka byla stažena. Inzerát nelze udělit.";

// variable for confirmation confirmation popup for decline bid for service provider
$config['decline_award_confirmation_project_modal_body_sp_view'] = 'Opravdu chcete odmítnout udělení projektu?';

################# error message when trying to decline the awarded bid
// when some unauthorized user(PO/unauthrizedSP) trying to decline bid
$config['project_details_page_sp_view_decline_award_cancelled_project'] = "Inzerát byl zrušen. Nelze odmítnout nabídku.";

$config['project_details_page_sp_view_decline_award_deleted_project'] = "Inzerát byl smazán. Nelze odmítnout nabídku.";

$config['project_details_page_sp_view_decline_award_in_progress_project'] = "Tuto nabídku jste již přijali a práce probíhá. Nelze odmítnout nabídku.";

$config['project_details_page_sp_view_same_sp_try_decline_award_on_completed_project'] = "Tento projekt je dokončený. Nelze odmítnout udělení.";

$config['project_details_page_sp_view_decline_award_award_already_declined_or_expired_project'] = "Stav vaší nabídky byl změněn. Udělení nelze odmítnout.";

$config['project_details_page_sp_view_decline_award_already_retracted_project'] = "Nabídka byla stažena. Nelze odmítnout udělení.";

//variable for confirmation confirmation popup for accept bid for service provider
$config['accept_award_confirmation_project_modal_body_sp_view'] = 'Pokračováním souhlasíte s udělením projektu a zahájíte práci.';

################# error message when trying to accept the awarded bid
// when some unauthorized user(PO/unauthrizedSP) trying to accept bid
$config['project_details_page_sp_view_accept_award_cancelled_project'] = "Inzerát byl zrušený. Nelze přijmout nabídku.";

$config['project_details_page_sp_view_accept_award_deleted_project'] = "Inzerát byl smazán. Nelze přijmout nabídku.";

$config['project_details_page_sp_view_accept_award_already_retracted_project'] = "Nabídka byla stažena. Nelze přijmout udělení.";

$config['project_details_page_sp_view_accept_award_award_already_declined_or_expired_project'] = "Stav vaší nabídky byl změněn. Nelze příjmout nabídku.";

$config['project_details_page_sp_view_validation_project_accept_award_award_acceptance_deadline_already_expired_message'] = "Nelze provést tuto volbu. Termín pro přijetí expiroval."; // when service provider trying to accept the awarded bid after awarded expiration time is past

$config['project_details_page_sp_view_accept_award_in_progress_project'] = "Udělení jste již přijali a práce probíhá. Nelze znovu přijmout udělení.";

$config['project_details_page_sp_view_same_sp_try_accept_award_on_completed_project'] = "Tento projekt je dokončený. Nelze přijmout udělení.";

// when some unauthorized user(PO/unauthrizedSP) trying post bid or update bid
$config['project_details_page_bid_description_minimum_length_words_limit'] = 5;
$config['project_details_page_bid_description_minimum_length_character_limit'] = 10; //minimum limit of character of project bid description on project bid form
$config['project_details_page_bid_description_maximum_length_character_limit'] = 2000; //maximum limit of character of project bid description on bid form

// variable to manage bid description character limit on project_details_page_bidder_listing section
$config['project_details_page_bidder_listing_bid_description_character_limit_mobile'] = 250; // bid description character limit for mobile device
$config['project_details_page_bidder_listing_bid_description_character_limit_tablet'] = 250; // bid description character limit for tablet device
$config['project_details_page_bidder_listing_bid_description_character_limit_desktop'] = 250; // bid description character limit for dekstop device

//Config for upload file button text for place bid/update bid
$config['project_details_page_bid_form_upload_file_button_txt'] = 'Nahrát soubor';


// validation message for posy/update bid form
//for fixed/hourly project



// validation message for bid form - both fixed budegt and hourly rate prjects
$config['project_details_page_bid_description_validation_project_bid_form_message'] = 'popis nabídky je povinný';

$config['project_details_page_biding_form_drop_down_options'] = array('to_be_agreed'=>'po dohodě','confidential'=>'neveřejný');

$config['project_details_page_custom_bid_attachment_allowed_file_extensions'] = '"png","PNG","gif","GIF","jpeg","JPEG","jpg","JPG","pdf","application/PDF","txt","xls","xlsx","doc","docx"';

$config['project_details_page_bid_attachment_allowed_files_validation_project_bid_form_message'] = "Nahrávat je možné po jednom souboru.";

$config['project_details_page_bid_attachment_invalid_file_extension_validation_project_bid_form_message'] = "Typ souboru, který chcete nehrát, není podporován.";



$config['project_details_page_bid_posted_confirmation_project_bid_form_message'] = 'nabídka byla odeslána';

$config['project_details_page_bid_updated_confirmation_project_bid_form_message'] = 'nabídka byla upravena';

$config['project_details_page_retract_bid_confirmation_project_bid_form_message'] = 'nabídka byla stažena';

################ config for download the bid attachments from active bids/awarded bids/in progress bids
$config['project_details_page_bid_form_bid_attachment_not_exist_validation_message_bidder_view_project'] = "Došlo k chybě. Smažte přílohu a nahrajte znovu.";

//config is using when attachment is not exists and project is in open for bidding status and po trying to open attachment from bidder list
$config['project_details_page_open_for_bidding_project_bidder_list_open_bid_attachment_not_exist_validation_message_po_view_project'] = "Příloha nabídky nelze otevřít.";

//config is using when attachment is not exists and project is in open for bidding status and sp trying to open attachment from bidder list
$config['project_details_page_open_for_bidding_project_bidder_list_open_bid_attachment_not_exist_validation_message_bidder_view_project'] = "Příloha nabídky neexistuje. Nahrajte přílohu znovu.";

//this config is using when po/sp trying to open bid attachment from awarded bidder list project status is awarded and attachment not exists
$config['project_details_page_awarded_project_awarded_open_bid_attachment_not_exist_validation_message_bidder_list_sp_po_view_project'] = "Příloha inzerátu neexistuje.";

//this config is using when po/sp trying to open bid attachment from in progress bidder list project status is in progress and attachment not exists
$config['project_details_page_in_progress_project_open_bid_attachment_not_exist_validation_message_bidder_list_awarded_sp_po_view_project'] = "Příloha inzerátu neexistuje.";

//this config is using when po/sp trying to open bid attachment from incomplete bidder list project status is incomplete and attachment not exists
$config['project_details_page_incomplete_project_open_bid_attachment_not_exist_validation_message_bidder_list_awarded_sp_po_view_project'] = "Příloha inzerátu neexistuje.";

//this config is using when po/sp trying to open bid attachment from completed bidder list project status is complete and attachment not exists
$config['project_details_page_completed_project_open_bid_attachment_not_exist_validation_message_bidder_list_awarded_sp_po_view_project'] = "Příloha nabídky neexistuje.";

//this config is using as common for sp/po when they are trying to open bid atatchments from bidding list and in the background project hasbeen cancelled
$config['project_details_page_cancelled_project_bidder_list_open_bid_attachment_not_exist_validation_message_sp_po_view'] = "Příloha inzerátu neexistuje.";


$config['project_details_page_deleted_project_bidder_list_open_bid_attachment_not_exist_validation_message_sp_po_view'] = "Projekt je smazán. Příloha nelze otevřít.";//config message is common for both po/sp view when they are trying to open attachments from open bidding list and in the background admin deleted the project

// Config is used when po trying to open the bid attachment from bidder list and project status is expired
$config['project_details_page_expired_project_bidder_list_open_bid_attachment_not_exist_validation_message_po_view_project'] = "Příloha nabídky neexistuje.";

// Config is used when sp trying to open the bid attachment from bidder list and project status is expired
$config['project_details_page_expired_project_bidder_list_open_bid_attachment_not_exist_validation_message_bidder_view_project'] = "Příloha nabídky neexistuje. Nahrajte přílohu znovu.";

######## These config variables using on those files where in progress bidding listing is showing
$config['in_progress_bidding_listing_project_start_date'] = '<span class="in_progress_project_start_date_color">zahájení práce</span>';

$config['completed_bidding_listing_project_start_date'] = '<span class="completed_project_start_date_color">zahájení práce</span>';

$config['completed_bidding_listing_project_completion_date'] = '<span class="completed_bidding_completion_date_color">dokončená práce</span>';

$config['fixed_or_hourly_project_value'] = '<span class="fixed_or_hourly_project_value_color">hodnota projektu</span>';

$config['completed_bidding_listing_project_value'] = '<span class="completed_bidding_project_value_color">hodnota projektu</span>';


//Complete Project Manually functionality
//button text for manual project complete request send by PO
$config['project_details_page_request_project_mark_as_complete_po_view_btn_txt'] = 'Odeslat žádost';

$config['project_details_page_no_mark_project_complete_request_msg_po_view'] = 'ještě nebyla odeslána žádná žádost pro označení projektu jako dokončený';

$config['project_details_page_po_view_create_mark_project_complete_invalid_request'] = "Žádost není platná.";

$config['project_details_page_po_view_create_mark_project_complete_request_resent_time_expired'] = "Žádost nelze odeslat. Čas do poslání další žádosti {next_mark_project_as_complete_request_send_available_time}";

$config['create_mark_project_complete_request_confirmation_project_modal_body'] = 'Pokračováním odešlete žádost <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>, pro označení projektu jako dokončený.';

$config['decline_mark_project_complete_request_confirmation_project_modal_body'] = 'Pokračováním nesouhlasíte s žádostí od <a class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a> pro označení projektu jako dokončený.';

$config['project_details_page_sp_view_decline_mark_project_complete_invalid_request'] = "Došlo k chybě. Žádost nelze odmítnout.";

$config['project_details_page_sp_view_accept_mark_project_complete_invalid_request'] = "Došlo k chybě. Žádost nelze přijmout.";

// config for accept the project complete request
$config['accept_mark_project_complete_request_confirmation_project_modal_body'] = 'Pokračováním souhlasíte s žádostí od <a class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a> pro označení projektu jako dokončený.';


// config for project mark complete request section on project detail page
$config['project_details_page_mark_complete_project_request_listing_requested_on_txt_po_view'] = 'Odeslání žádosti:';

$config['project_details_page_mark_complete_project_request_listing_request_received_on_txt_sp_view'] = 'Přijetí žádosti:';

$config['project_details_page_mark_complete_project_request_listing_request_expires_on_txt'] = 'Expirace žádosti:';

$config['project_details_page_mark_complete_project_request_listing_request_expired_on_txt'] = 'Expirováno:';

$config['project_details_page_mark_complete_project_request_listing_request_declined_on_txt'] = 'Zamítnuto:';

$config['project_details_page_mark_complete_project_request_listing_request_accepted_on_txt'] = 'Odsouhlaseno:';

$config['project_details_page_mark_complete_project_request_listing_waiting_for_acceptance_txt_po_view'] = 'Čeká na reakci';

$config['project_details_page_mark_complete_project_request_listing_request_declined_txt'] = 'Žádost zamítnuta';

$config['project_details_page_mark_complete_project_request_listing_request_accepted_txt'] = 'Žádost povolena';

$config['project_details_page_mark_complete_project_request_listing_time_left_send_next_request_txt_po_view'] = 'Čas do poslání další žádosti';

################## config for error message for project mark complete section ######
$config['project_details_page_po_view_po_try_create_mark_project_complete_request_already_accepted_by_sp_message'] = 'Projekt byl změněn jako dokončený. Stránka bude aktualizována.';

$config['project_details_page_sp_view_sp_try_accept_mark_project_complete_request_already_accepted_by_sp_message'] = 'Tuto žádost jste už přijali. Stránka bude aktualizována.';

$config['project_details_page_sp_view_sp_try_accept_mark_project_complete_request_already_declined_by_sp_message'] = 'Tuto žádost jste už odmítli. Stránka bude aktualizována.';

$config['project_details_page_sp_view_sp_try_decline_mark_project_complete_request_already_accepted_by_sp_message'] = 'Tuto žádost jste už přijali. Stránka bude aktualizována.';

$config['project_details_page_sp_view_sp_try_decline_mark_project_complete_request_already_declined_by_sp_message'] = 'Tuto žádost jste už odmítli. Stránka bude aktualizována.';

$config['project_details_page_po_view_po_try_create_mark_project_complete_request_project_already_completed_via_portal_message']= 'Projekt je dokončený přes portál. Nelze vytvořit žádost pro označení projektu jako dokončený.';

$config['project_details_page_sp_view_sp_try_accept_mark_project_complete_request_project_already_completed_via_portal_message'] = 'Projekt je dokončený přes portál. Nelze přijmout žádost.';

$config['project_details_page_sp_view_sp_try_decline_mark_project_complete_request_project_already_completed_via_portal_message'] = 'Projekt je dokončený přes portál. Nelze odmítnout žádost.';

$config['project_details_page_sp_view_sp_try_accept_mark_project_complete_request_already_expired_message'] = 'Nelze provést tuto volbu. Platnost pro rozhodnutí vypršela.';

?>