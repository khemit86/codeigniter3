<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation menu name
$config['projects_left_nav_projects_disputes'] = 'Správa Řešení Sporů';


//page heading
//$config['projects_disputes_page_headline_title'] = 'Projects Disputes Management';
$config['projects_disputes_page_headline_title'] = 'Správa Řešení Sporů';

//Meta Tag
$config['projects_disputes_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa Řešení Sporů';
//Description Meta Tag
$config['projects_disputes_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa Řešení Sporů';

//url
//$config['projects_disputes_page_url'] = 'projects-disputes';
$config['projects_disputes_page_url'] = 'sprava-reseni-sporu';




//$config['project_detail_page_payment_tab_open_dispute_txt'] = 'CZ-open dispute';
$config['project_detail_page_payment_tab_open_dispute_txt'] = 'zahájit spor';

//$config['user_projects_disputes_management_page_open_dispute_button_txt'] = 'CZ:Open Dispute';
$config['user_projects_disputes_management_page_open_dispute_button_txt'] = 'Zahájit spor';



//project owner checkbox
//$config['projects_disputes_page_project_owner'] = 'Zaměstnavatel / Project Owner';
$config['projects_disputes_page_project_owner'] = 'Zadavatel & Zaměstnavatel';

//$config['projects_disputes_page_project_owner_new_dispute'] = 'New Dispute';
$config['projects_disputes_page_project_owner_new_dispute'] = 'Nový spor';

//$config['projects_disputes_page_project_owner_active_dispute'] = 'Active Dispute';
$config['projects_disputes_page_project_owner_active_dispute'] = 'Probíhající spory';

//$config['projects_disputes_page_project_owner_close_dispute'] = 'Close Dispute';
$config['projects_disputes_page_project_owner_close_dispute'] = 'Ukončené spory';


//service provider checkbox
//$config['projects_disputes_page_checkbox_service_provider'] = 'Poskytovatel služeb / Service provider';
$config['projects_disputes_page_checkbox_service_provider'] = 'Poskytovatel & Zaměstnanec';

//$config['projects_disputes_page_checkbox_service_provider_new_dispute'] = 'New Dispute';
$config['projects_disputes_page_checkbox_service_provider_new_dispute'] = 'Nový spor';

//$config['projects_disputes_page_checkbox_service_provider_active_dispute'] = 'Active Dispute';
$config['projects_disputes_page_checkbox_service_provider_active_dispute'] = 'Probíhající spory';

//$config['projects_disputes_page_checkbox_service_provider_close_dispute'] = 'Close Dispute';
$config['projects_disputes_page_checkbox_service_provider_close_dispute'] = 'Ukončené spory';


// meta title tag for dispute detail page
//$config['project_dispute_details_page_title_meta_tag'] = 'dispute {dispute_initiator_user_name} vs. {disputee_user_name} on project - {project_title}';
$config['project_dispute_details_page_title_meta_tag'] = 'spor {dispute_initiator_user_name} proti {disputee_user_name} na {project_title}';

//$config['project_dispute_details_page_description_meta_tag'] = 'dispute {dispute_initiator_user_name} vs. {disputee_user_name} on project - {project_title}';
$config['project_dispute_details_page_description_meta_tag'] = 'spor {dispute_initiator_user_name} proti {disputee_user_name} na {project_title}';


################ Url Routing Variables ###########
//for dispute page
//$config['project_dispute_details_page_url'] = 'disputes-detail';
$config['project_dispute_details_page_url'] = 'spor';


$config['project_dispute_details_page_initiate_dispute_btn_txt']= 'Initiate Dispute';
$config['project_dispute_details_page_post_message_btn_txt']= 'Post Message';
$config['project_dispute_details_page_reply_answer_back_btn_txt']= 'Reply Answer Back';

//$config['project_dispute_details_page_go_to_dispute_detail_page_btn_txt']= 'Go to Dispute Detail page';
$config['project_dispute_details_page_go_to_dispute_detail_page_btn_txt']= 'Přejít na probíhající spor';


/// Config regarding project dispute page(dispute over view page)

####config variables for paging regarding new/active/closed tabs on project dispute page###
//$config['user_projects_disputes_management_page_start_new_disputes_listing_limit'] = 1;


//$config['user_projects_disputes_management_page_paging_url'] = 'page';
$config['user_projects_disputes_management_page_paging_url'] = 'strk';

//$config['user_projects_disputes_management_page_dispute_id_txt'] = 'CZ:Dispute Id:';
$config['user_projects_disputes_management_page_dispute_id_txt'] = 'ID sporu:';

//$config['user_projects_disputes_management_page_project_title_txt'] = 'CZ:Project Title:';
$config['user_projects_disputes_management_page_project_title_txt'] = 'Projekt:';

//$config['user_projects_disputes_management_page_fulltime_project_title_txt'] = 'CZ:Fulltime Project Title:';
$config['user_projects_disputes_management_page_fulltime_project_title_txt'] = 'Pracovní pozice:';

//$config['user_projects_disputes_management_page_disputed_amount_txt'] = 'CZ:Disputed Amount:';
$config['user_projects_disputes_management_page_disputed_amount_txt'] = 'Částka:';

//$config['user_projects_disputes_management_page_total_related_service_fees_txt'] = 'CZ:Total related service fees:';
$config['user_projects_disputes_management_page_total_related_service_fees_txt'] = 'Poplatek:';

//$config['user_projects_disputes_management_page_dispute_start_date'] = 'CZ:Dispute Start date:';
$config['user_projects_disputes_management_page_dispute_start_date'] = 'Zahájení sporu:';

//$config['user_projects_disputes_management_page_user_negotiation_phase_txt'] = 'CZ:Negotiation stage';
$config['user_projects_disputes_management_page_user_negotiation_phase_txt'] = 'fáze vyjednávání';

//$config['user_projects_disputes_management_page_negotiation_stage_end_date_txt'] = 'CZ:Negotiation stage end date:';
$config['user_projects_disputes_management_page_negotiation_stage_end_date_txt'] = 'Konec vyjednávání:';


//$config['user_projects_disputes_management_page_admin_moderation_phase_txt'] = 'CZ:Admin Moderation';
$config['user_projects_disputes_management_page_admin_moderation_phase_txt'] = 'Čeká k posouzení';



//$config['user_projects_disputes_management_page_dispute_initiated_by_txt'] = 'CZ:Dispute initiated by:';
$config['user_projects_disputes_management_page_dispute_initiated_by_txt'] = 'Zahajovatel sporu:';


//$config['user_projects_disputes_management_page_dispute_close_date'] = 'CZ:Dispute Close date:';
$config['user_projects_disputes_management_page_dispute_close_date'] = 'Ukončení sporu:';



//$config['user_projects_disputes_management_page_select_project_dropdown_option_txt'] = 'CZ:Select Project';
$config['user_projects_disputes_management_page_select_project_dropdown_option_txt'] = 'vybrat inzerát';


// Initial view message when no active/closed disputes exists
//$config['user_projects_disputes_management_page_no_active_disputes_message'] = "CZ:You do not have any active disputes";
$config['user_projects_disputes_management_page_no_active_disputes_message'] = "nemáte žádné probíhající spory";

//$config['user_projects_disputes_management_page_no_closed_disputes_message'] = "CZ:You do not have any closed disputes";
$config['user_projects_disputes_management_page_no_closed_disputes_message'] = "nemáte žádné ukončené spory";



// Initial view message for po view on dispute page (new dispute tab)
//$config['user_projects_disputes_management_page_no_published_project_message_po_view'] = "PO:CZ:You do not currently have any published project";
$config['user_projects_disputes_management_page_no_published_project_message_po_view'] = "momentálně není žádný inzerát otevřený";


//$config['user_projects_disputes_management_page_no_financial_activity_on_published_project_message_po_view_singular'] = "PO:CZ:You do not any finalcial activity on project";
$config['user_projects_disputes_management_page_no_financial_activity_on_published_project_message_po_view_singular'] = "momentálně nemáte žádné aktivní platby na inzerátu";


//$config['user_projects_disputes_management_page_no_financial_activity_on_published_projects_message_po_view_plural'] = "PO:CZ:You do not any finalcial activity on projects";
$config['user_projects_disputes_management_page_no_financial_activity_on_published_projects_message_po_view_plural'] = "momentálně nemáte žádné aktivní platby na inzerátech";

// Initial view message for sp view on dispute page (new dispute tab)
//$config['user_projects_disputes_management_page_no_project_message_sp_view'] = "SP:CZ:You do not currently have any activity on any project.";
$config['user_projects_disputes_management_page_no_project_message_sp_view'] = "nemáte žádné probíhající práce";


//$config['user_projects_disputes_management_page_no_financial_activity_on_project_message_sp_view_singular'] = "SP:CZ:You do not currently have any financial activity on any project";
$config['user_projects_disputes_management_page_no_financial_activity_on_project_message_sp_view_singular'] = "momentálně není vytvořená rezervace platby na žádný projekt";

//$config['user_projects_disputes_management_page_no_financial_activity_on_projects_message_sp_view_plural'] = "SP:CZ:You do not currently have any financial activity on any projects";
$config['user_projects_disputes_management_page_no_financial_activity_on_projects_message_sp_view_plural'] = "momentálně nejsou vytvořené rezervace plateb na žádné projekty";


############## conflict message and checks ######### start

//when cancelled the dispute
// For fixed/hourly project
$config['project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_when_negotiation_time_expired_dispute_on_autodecided_dispute'] = "You cannot cancel the dispute.Project negotiation time expired. The dispute has been automatically closed";
$config['project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_when_negotiation_time_expired_dispute_on_admin_arbitration'] = "You cannot cancel the dispute.Project negotiation time expired. The dispute is under admin review.";
$config['project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_already_closed_dispute'] = "You cannot cancelled the dispute.Project dispute is already closed";


// for fulltime project
$config['fulltime_project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_when_negotiation_time_expired_dispute_on_autodecided_dispute'] = "Fulltime:You cannot cancel the dispute.Project negotiation time expired. The dispute has been automatically closed";
$config['fulltime_project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_when_negotiation_time_expired_dispute_on_admin_arbitration'] = "Fulltime:You cannot cancel the dispute.Project negotiation time expired. The dispute is under admin review.";
$config['fulltime_project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_already_closed_dispute'] = "Fulltime:You cannot cancelled the dispute.Project dispute is already closed";


// when trying to post messsage
// for fixed/hourly project
$config['project_dispute_details_page_user_try_to_post_message_when_negotiation_time_expired_dispute_on_autodecided_dispute'] = "You cannot post message.Project negotiation time expired.The dispute has been automatically closed";
$config['project_dispute_details_page_user_try_to_post_message_when_negotiation_time_expired_dispute_on_admin_arbitration'] = "You cannot post message.Project negotiation time expired. The dispute is under admin review.";
$config['project_dispute_details_page_user_try_post_message_on_already_closed_dispute'] = "You cannot post the message.Dsipute is closed";


// For Fulltime Project
$config['fulltime_project_dispute_details_page_user_try_to_post_message_when_negotiation_time_expired_dispute_on_autodecided_dispute'] = "Fulltime:You cannot post message.Project negotiation time expired.The dispute has been automatically closed";
$config['fulltime_project_dispute_details_page_user_try_to_post_message_when_negotiation_time_expired_dispute_on_admin_arbitration'] = "Fulltime:You cannot post message.Project negotiation time expired. The dispute is under admin review.";
$config['fulltime_project_dispute_details_page_user_try_post_message_on_already_closed_dispute'] = "Fulltime:You cannot post the message.Dispute is closed";




//when trying to make counter offer
$config['project_dispute_details_page_user_try_to_make_counter_offer_on_already_closed_dispute'] = "You cannot make counter offer.Project dispute already closed";
$config['project_dispute_details_page_user_try_to_make_counter_offer_when_negotiation_time_expired_dispute_on_autodecided_dispute'] = "You cannot make counter offer.Project negotation time expired.The dispute has been automatically closed";
$config['project_dispute_details_page_user_try_to_make_counter_offer_when_negotiation_time_expired_dispute_on_admin_arbitration'] = "You cannot make counter offer.Project negotation time expired.The dispute is under admin review.";
$config['project_dispute_details_page_user_try_to_make_counter_offer_when_already_received_counter_offer_in_background'] = "You cannot make counter offer Because status is changed of counter offer";

// For fulltime project
$config['fulltime_project_dispute_details_page_user_try_to_make_counter_offer_on_already_closed_dispute'] = "Fulltime:You cannot make counter offer.Project dispute already closed";
$config['fulltime_project_dispute_details_page_user_try_to_make_counter_offer_when_negotiation_time_expired_dispute_on_autodecided_dispute'] = "Fulltime:You cannot make counter offer.Project negotation time expired.The dispute has been automatically closed";
$config['fulltime_project_dispute_details_page_user_try_to_make_counter_offer_when_negotiation_time_expired_dispute_on_admin_arbitration'] = "Fulltime:You cannot make counter offer.Project negotation time expired.The dispute is under admin review.";
$config['fulltime_project_dispute_details_page_user_try_to_make_counter_offer_when_already_received_counter_offer_in_background'] = "Fulltime:You cannot make counter offer Because status is changed of counter offer";

// when trying to accept counter offer
// For fixed/hourly project 
$config['project_dispute_details_page_user_try_accept_offer_dispute_already_closed_dispute'] = "You cannot accept the offer .Project dispute alrady closed";
$config['project_dispute_details_page_user_try_to_accept_counter_offer_when_negotiation_time_expired_dispute_on_autodecided_dispute'] = "You cannot accept counter offer.Project negotation time expired.The dispute has been automatically closed";
$config['project_dispute_details_page_user_try_to_accept_counter_offer_when_negotiation_time_expired_dispute_on_admin_arbitration'] = "You cannot accept counter offer.Project negotation time expired.The dispute is under admin review.";

// For fulltime project
$config['fulltime_project_dispute_details_page_user_try_accept_offer_dispute_already_closed_dispute'] = "Fulltime:You cannot accept the offer .Project dispute alrady closed";
$config['fulltime_project_dispute_details_page_user_try_to_accept_counter_offer_when_negotiation_time_expired_dispute_on_autodecided_dispute'] = "Fulltime:You cannot accept counter offer.Project negotation time expired.The dispute has been automatically closed";
$config['fulltime_project_dispute_details_page_user_try_to_accept_counter_offer_when_negotiation_time_expired_dispute_on_admin_arbitration'] = "Fulltime:You cannot accept counter offer.Project negotation time expired.The dispute is under admin review.";


//when trying to upload attachment
$config['project_dispute_details_page_user_try_to_upload_attachment_when_negotiation_time_expired_dispute_on_autodecided_dispute'] = "You cannot upload attachment.Project negotiation time expired. The dispute has been automatically closed.";

$config['project_dispute_details_page_user_try_to_upload_attachment_when_negotiation_time_expired_dispute_on_admin_arbitration'] = "You cannot upload attachment.Project negotiation time expired. The dispute is under admin review..";
$config['project_dispute_details_page_user_try_to_upload_attchment_on_already_closed_dispute'] = "You cannot upload attachment.Project dispute already closed";
############## conflict message and checks ######### end



$config['project_dispute_details_page_dispute_closed_txt']= 'Dispute has been closed';
$config['project_dispute_details_page_dispute_resolved_txt']= 'Dispute has been resolved';



// Different dispute phase show on project dispute detail page
$config['project_dispute_details_page_dispute_initiation_phase']= 'Initiate Dispute';
$config['project_dispute_details_page_dispute_under_negotiation_phase']= 'Dispute Under Negotatiation';
$config['project_dispute_details_page_dispute_under_admin_review_phase']= 'Dispute Under Admin Review';
$config['project_dispute_details_page_dispute_closed_phase']= 'Dispute Closed';
$config['project_dispute_details_page_dispute_resolved_phase']= 'Dispute Resolved';

// when dispute is in under initiation phase

//For hourly/fixed project
$config['project_dispute_details_page_dispute_initiation_phase_rules_heading'] = 'Dispute Initiation Phase - Disputes Rules';

// For Fulltime project
$config['fulltime_project_dispute_details_page_dispute_initiation_phase_rules_heading'] = 'FULLTIME-Dispute Initiation Phase - Disputes Rules -CZ';


/* $config['project_dispute_details_page_dispute_initiation_phase_rules_txt'] = "(Initiation)There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary,"; */

$config['project_dispute_details_page_automatic_decided_dispute_initiation_phase_rules_txt'] = "<p>We know that even the most professional SPs and POs sometimes can have disagreements, and that is the reason we have put the dispute system in place. However, most of the disputes are result of simple misunderstandings. In most cases, issues can be easily resolved with some good communication and are resolved without reaching arbitration phase.</p>
<p>Our dispute resolution system is designed to allow both parties to resolve the issue amongst themselves.</p>
<p>By continuing, you will initiate dispute against {other_party_first_name_last_name_or_company_name} for <span class='touch_line_break'>{project_disputed_amount}</span>. Your dispute will be moved to next phase, where we will allow you time to try to work with {other_party_first_name_last_name_or_company_name} and jointly reach an agreement. If you will not be able to reach an agreement, at the end of the negotiation period your dispute will be automatically closed and the disputed amount equally split between you and {other_party_first_name_last_name_or_company_name}</p>";

// For Fulltime project
$config['fulltime_project_dispute_details_page_automatic_decided_dispute_initiation_phase_rules_txt'] = "<p>FULLTIME:We know that even the most professional Employees and Employers sometimes can have disagreements, and that is the reason we have put the dispute system in place. However, most of the disputes are result of simple misunderstandings. In most cases, issues can be easily resolved with some good communication and are resolved without reaching arbitration phase.</p>
<p>Our dispute resolution system is designed to allow both parties to resolve the issue amongst themselves.</p>
<p>By continuing, you will initiate dispute against {other_party_first_name_last_name_or_company_name} for <span class='touch_line_break'>{project_disputed_amount}</span>. Your dispute will be moved to next phase, where we will allow you time to try to work with {other_party_first_name_last_name_or_company_name} and jointly reach an agreement. If you will not be able to reach an agreement, at the end of the negotiation period your dispute will be automatically closed and the disputed amount equally split between you and {other_party_first_name_last_name_or_company_name}</p>";




$config['project_dispute_details_page_admin_arbitration_dispute_initiation_phase_dispute_stage_rules_txt'] = '<p>We know that even the most professional SPs and POs sometimes can have disagreements, and that is the reason we have put the dispute system in place. However, most of the disputes are result of simple misunderstandings. In most cases, issues can be easily resolved with some good communication and are resolved without reaching arbitration phase.Our dispute resolution system is designed to allow both parties to resolve the issue amongst themselves.</p>
<p>By continuing, you will initiate dispute against {other_party_first_name_last_name_or_company_name} for <span class="touch_line_break">{project_disputed_amount}</span>.</p><p>Your dispute will be moved to next phase, where we will allow you time to try to work with {other_party_first_name_last_name_or_company_name} and jointly reach an agreement. If you will not be able to reach an agreement, at the end of the negotiation period your dispute moved to admin arbitration who will take a final decision in favor of one of the 2 parties involved in the dispute</p>';

//For fulltime project
$config['fulltime_project_dispute_details_page_admin_arbitration_dispute_initiation_phase_dispute_stage_rules_txt'] = '<p>FULLTIME:We know that even the most professional Employees and Employers sometimes can have disagreements, and that is the reason we have put the dispute system in place. However, most of the disputes are result of simple misunderstandings. In most cases, issues can be easily resolved with some good communication and are resolved without reaching arbitration phase.Our dispute resolution system is designed to allow both parties to resolve the issue amongst themselves.</p>
<p>By continuing, you will initiate dispute against {other_party_first_name_last_name_or_company_name} for <span class="touch_line_break">{project_disputed_amount}</span>.</p><p>Your dispute will be moved to next phase, where we will allow you time to try to work with {other_party_first_name_last_name_or_company_name} and jointly reach an agreement. If you will not be able to reach an agreement, at the end of the negotiation period your dispute moved to admin arbitration who will take a final decision in favor of one of the 2 parties involved in the dispute</p>';


/* $config['project_dispute_details_page_dispute_initiation_phase_dispute_automatic_rules_txt']
$config['project_dispute_details_page_dispute_initiation_phase_dispute_admin moderation_stage_rules_txt'] */



###### config for initiate dispute confirmation popup #####
// modal title
$config['project_dispute_details_page_dispute_initiate_confirmation_project_modal_title'] = 'Initiate the Dispute-CZ';
$config['fulltime_project_dispute_details_page_dispute_initiate_confirmation_project_modal_title'] = 'Fulltime:Initiate the Dispute-CZ';
//modal body
$config['project_dispute_details_page_dispute_initiate_confirmation_project_modal_body'] = 'Are you sure you want to initiate dispute on project <a href="#" class="default_popup_blue_text">{project_title}</a> against user <a href="{user_profile_url_link}" class="default_popup_blue_text" href="#">{user_first_name_last_name_or_company_name}</a>, for <span class="touch_line_break">{project_disputed_amount}</span>-CZ?';

$config['fulltime_project_dispute_details_page_dispute_initiate_confirmation_project_modal_body'] = 'Fulltime:Are you sure you want to initiate dispute on project <a href="#" class="default_popup_blue_text">{fulltime_project_title}</a> against user <a href="{user_profile_url_link}" class="default_popup_blue_text" href="#">{user_first_name_last_name_or_company_name}</a>, for <span class="touch_line_break">{fulltime_project_disputed_amount}-CZ</span>?';
// modal button
$config['project_dispute_details_page_dispute_initiate_confirmation_project_modal_dispute_initiate_btn_txt'] = 'Initiate the dispute-CZ';
$config['fulltime_project_dispute_details_page_dispute_initiate_confirmation_project_modal_dispute_initiate_btn_txt'] = 'Initiate the dispute fulltime-CZ';

###### config for cancel dispute confirmation popup #####
// for fixed/hourly project
$config['project_dispute_details_page_dispute_cancel_confirmation_project_modal_title'] = 'Cancel the Dispute';
// for fulltime project
$config['fulltime_project_dispute_details_page_dispute_cancel_confirmation_project_modal_title'] = 'Fulltime:Cancel the Dispute';



/* 
$config['fixed_budget_project_dispute_details_page_dispute_cancel_by_po_confirmation_project_modal_body'] = 'Are you sure you want to cancel dispute on project <a href="{project_url_link}" target="_blank">{project_title}</a> against <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>?
<br><br><span class="touch_line_break">{fixed_budget_project_disputed_amount}</span> will be released to <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> and service fees of <span class="touch_line_break">{fixed_budget_project_disputed_amount_service_fees}</span> will be charged by the business.'; */

// When dispute cancelled by po
// for fixed project 
$config['fixed_budget_project_dispute_details_page_dispute_cancel_by_po_confirmation_project_modal_body'] = 'FIXED-cancel by po-Are you sure you want to mark this issue as resolved ?<br><br>Once you mark this case as resolved, you will not be able to re-open it again and entire disputed amount <span class="touch_line_break">{fixed_budget_project_disputed_amount}</span> will be transfered to <a href="#" class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a><br><br>
If you are expecting a refund, return or replacement from the seller, you must wait until that has been completed before you close this case.';

// for hourly 

$config['hourly_rate_based_project_dispute_details_page_dispute_cancel_by_po_confirmation_project_modal_body'] = 'HOURLY- cancel by po-Are you sure you want to mark this issue as resolved ?<br><br>Once you mark this case as resolved, you will not be able to re-open it again and entire disputed amount <span class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span> will be transfered to <a href="#" class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a><br><br>
If you are expecting a refund, return or replacement from the seller, you must wait until that has been completed before you close this case.';

// for fulltime
$config['fulltime_project_dispute_details_page_dispute_cancel_by_po_confirmation_project_modal_body'] = 'Fulltime- cancel by po-Are you sure you want to mark this issue as resolved ?<br><br>Once you mark this case as resolved, you will not be able to re-open it again and entire disputed amount <span class="touch_line_break">{fulltime_project_disputed_amount}</span> will be transfered to <a href="#" class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a><br><br>
If you are expecting a refund, return or replacement from the seller, you must wait until that has been completed before you close this case.';

// When dispute cancelled by sp
// for fixed project 

$config['fixed_budget_project_dispute_details_page_dispute_cancel_by_sp_confirmation_project_modal_body'] = 'FIXED- cancel by sp-Are you sure you want to mark this issue as resolved ?<br><br>Once you mark this case as resolved, you will not be able to re-open it again and entire disputed amount <span class="touch_line_break">{fixed_budget_project_disputed_amount}</span> will be transfered to <a href="#" class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a><br><br>
If you are expecting a refund, return or replacement from the seller, you must wait until that has been completed before you close this case.';

// for hourly project

$config['hourly_rate_based_project_dispute_details_page_dispute_cancel_by_sp_confirmation_project_modal_body'] = 'HOURLY-cancel by sp- Are you sure you want to mark this issue as resolved ?<br><br>Once you mark this case as resolved, you will not be able to re-open it again and entire disputed amount <span class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span> will be transfered to <a href="#" class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a><br><br>
If you are expecting a refund, return or replacement from the seller, you must wait until that has been completed before you close this case.';

// for fulltime project
$config['fulltime_project_dispute_details_page_dispute_cancel_by_sp_confirmation_project_modal_body'] = 'Fulltime-cancel by sp- Are you sure you want to mark this issue as resolved ?<br><br>Once you mark this case as resolved, you will not be able to re-open it again and entire disputed amount <span class="touch_line_break">{fulltime_project_disputed_amount}</span> will be transfered to <a href="#" class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a><br><br>
If you are expecting a refund, return or replacement from the seller, you must wait until that has been completed before you close this case.';

// For fixed/hourly project
$config['project_dispute_details_page_dispute_cancel_confirmation_project_modal_dispute_cancel_btn_txt'] = 'Cancel Dispute';

// For fulltime project
$config['_fulltime_project_dispute_details_page_dispute_cancel_confirmation_project_modal_dispute_cancel_btn_txt'] = 'fulltime Cancel Dispute';


###### config for accept counter offer dispute confirmation popup #####
// for fixed/hourly project
$config['project_dispute_details_page_accept_counter_offer_confirmation_project_modal_title'] = 'Accept Offer';
// for fulltime project
$config['fulltime_project_dispute_details_page_accept_counter_offer_confirmation_project_modal_title'] = 'Fulltime:Accept Offer';

// For Fixed Type Project
$config['fixed_budget_project_dispute_details_page_accept_counter_offer_by_po_confirmation_project_modal_body'] = 'FIXED:Are you make sure to accept counter offer?<br><br>Once you will accept the counter offer from <a href="#" class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a> in value of {fixed_budget_project_counter_offer_value}. This {fixed_budget_project_counter_offer_value} will be transferred to <a href="#" class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a> and associated service fees {service_fees_charged_from_po} will be charged. The remaining {fixed_budget_project_remaining_amount} will be returned to your balance together with the associated services fees {service_fees_return_to_po}';


$config['fixed_budget_project_dispute_details_page_accept_counter_offer_by_sp_confirmation_project_modal_body'] = 'FIXED:Are you make sure to accept counter offer?<br><br>Once you will accept the counter offer from <a href="#" class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a> in value of {fixed_budget_project_counter_offer_value}. This {fixed_budget_project_counter_offer_value} will be transferred to your account';

// For Hourly rate Project
$config['hourly_rate_based_project_dispute_details_page_accept_counter_offer_by_po_confirmation_project_modal_body'] = 'HOURLY:Are you make sure to accept counter offer?<br><br>Once you will accept the counter offer from <a href="#" class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a> in value of {hourly_rate_based_project_counter_offer_value}. This {hourly_rate_based_project_counter_offer_value} will be transferred to <a href="#" class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a> and associated service fees {service_fees_charged_from_po} will be charged. The remaining {hourly_rate_based_project_remaining_amount} will be returned to your balance together with the associated services fees {service_fees_return_to_po}';


$config['hourly_rate_based_project_dispute_details_page_accept_counter_offer_by_sp_confirmation_project_modal_body'] = 'HOURLY:Are you make sure to accept counter offer?<br><br>Once you will accept the counter offer from <a href="#" class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a> in value of {hourly_rate_based_project_counter_offer_value}. This {hourly_rate_based_project_counter_offer_value} will be transferred to your account';

// For fulltime Project
$config['fulltime_project_dispute_details_page_accept_counter_offer_by_employer_confirmation_project_modal_body'] = 'Fulltime:Are you make sure to accept counter offer?<br><br>Once you will accept the counter offer from <a href="#" class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a> in value of {fulltime_project_counter_offer_value}. This {fulltime_project_counter_offer_value} will be transferred to <a href="#" class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a> and associated service fees {service_fees_charged_from_po} will be charged. The remaining {fulltime_project_remaining_amount} will be returned to your balance together with the associated services fees {service_fees_return_to_po}';


$config['fulltime_project_dispute_details_page_accept_counter_offer_by_employee_confirmation_project_modal_body'] = 'Fulltime:Are you make sure to accept counter offer?<br><br>Once you will accept the counter offer from <a href="#" class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a> in value of {fulltime_project_counter_offer_value}. This {fulltime_project_counter_offer_value} will be transferred to your account';


/* $config['fixed_budget_project_dispute_message_posted_activity_log_displayed_message_sent_to_poster'] = 'You posted a message on message board of dispute case {dispute_reference_id} on project <a href="{project_url_link}" target="_blank">{project_title}</a>. We encourage you to work with <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> and try to resolve your issue. Otherwise, this dispute will be escalated to admin arbitration process on {dispute_negotiation_end_date}. Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.'; */

// when dispute is in under negotiation phase
//CATALIN - 12.04.2020 - THIS IS WRONG - MUST BE VARIABLES CREATED BASED ON TO WHOM THEMESSAGE IS DISPLAYED TO (INITIATOR / DISPUTEE) AND DECISSION TYPE (AUTODECIDED / ADMIN ARBITRATION)
/* $config['project_dispute_details_page_dispute_under_negotiation_phase_rules_heading'] = 'Under Negotiation Rules'; */ //TO BE REMOVED
###
// For fixed/hourly
$config['project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_initiator_view_autodecided_dispute'] = 'Under Negotiation Rules _initiator_view_autodecided_dispute';
$config['project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_initiator_view_admin_moderated_dispute'] = 'Under Negotiation Rules _initiator_view_admin_moderated_dispute';

// For fulltime

$config['fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_initiator_view_autodecided_dispute'] = 'Fulltime:Under Negotiation Rules _initiator_view_autodecided_dispute';
$config['fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_initiator_view_admin_moderated_dispute'] = 'Fulltime:Under Negotiation Rules _initiator_view_admin_moderated_dispute';

// For fixed/hourly
$config['project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_disputee_view_autodecided_dispute'] = 'Under Negotiation Rules _disputee_view_autodecided_dispute';
$config['project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_disputee_view_admin_moderated_dispute'] = 'Under Negotiation Rules - disputee_view_admin_moderated_dispute';

// For fulltime
$config['fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_disputee_view_autodecided_dispute'] = 'Fulltime:Under Negotiation Rules _disputee_view_autodecided_dispute';
$config['fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_disputee_view_admin_moderated_dispute'] = 'Fulltime:Under Negotiation Rules - disputee_view_admin_moderated_dispute';



/* $config['project_dispute_details_page_dispute_under_negotiation_phase_rules_txt'] = "(Under Negotiation Negotiation)There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary,"; */ //TO BE DELETED
###
// for fixed/hourly project
$config['project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_initiator_view_autodecided_dispute'] = "You are currently during dispute negotiation period, which will end on {dispute_negotiation_end_date}. During this period you have the opportunity to negotiate and amiabily reach an agreement with {other_party_first_name_last_name_or_company_name}. There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before expiration of NEGOTIATION PERIOD. If you can’t reach any agreement with {other_party_first_name_last_name_or_company_name} by {dispute_negotiation_end_date}, your case will be automatically closed, and the disputed amount will be spliy 50% - 50% between you and {other_party_first_name_last_name_or_company_name}. Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.";

// for fulltime project
$config['fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_initiator_view_autodecided_dispute'] = "Fulltime:You are currently during dispute negotiation period, which will end on {dispute_negotiation_end_date}. During this period you have the opportunity to negotiate and amiabily reach an agreement with {other_party_first_name_last_name_or_company_name}. There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before expiration of NEGOTIATION PERIOD. If you can’t reach any agreement with {other_party_first_name_last_name_or_company_name} by {dispute_negotiation_end_date}, your case will be automatically closed, and the disputed amount will be spliy 50% - 50% between you and {other_party_first_name_last_name_or_company_name}. Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.";

// for fixed/hourly project
$config['project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_initiator_view_admin_moderated_dispute'] = "You are currently during dispute negotiation period, which will end on {dispute_negotiation_end_date}. During this period you have the opportunity to negotiate and amiabily reach an agreement with {other_party_first_name_last_name_or_company_name}. There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage. If you can’t reach any agreement with {other_party_first_name_last_name_or_company_name} by {dispute_negotiation_end_date}, your case will be automatically escalated to admin arbitration to decide the outcome (usually within 10 working days). There is a dispute fee of {admin_dispute_arbitration_fee}%, applied to the entire value of your dispute ({project_disputed_amount}), that will be charged in case the dispue is moved to admin arbitration service. Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.";

// for fulltime project
$config['fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_initiator_view_admin_moderated_dispute'] = "Fulltime:You are currently during dispute negotiation period, which will end on {dispute_negotiation_end_date}. During this period you have the opportunity to negotiate and amiabily reach an agreement with {other_party_first_name_last_name_or_company_name}. There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage. If you can’t reach any agreement with {other_party_first_name_last_name_or_company_name} by {dispute_negotiation_end_date}, your case will be automatically escalated to admin arbitration to decide the outcome (usually within 10 working days). There is a dispute fee of {admin_dispute_arbitration_fee}%, applied to the entire value of your dispute ({fulltime_project_disputed_amount}), that will be charged in case the dispue is moved to admin arbitration service. Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.";

// for fixed/hourly project
$config['project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_disputee_view_autodecided_dispute'] = "You are currently during dispute negotiation period, which will end on {dispute_negotiation_end_date}. During this period you have the opportunity to negotiate and amiabily reach an agreement with {other_party_first_name_last_name_or_company_name}. There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before expiration of NEGOTIATION PERIOD. If you can’t reach any agreement with {other_party_first_name_last_name_or_company_name} by {dispute_negotiation_end_date}, your case will be automatically closed, and the disputed amount will be spliT 50% - 50% between you and {other_party_first_name_last_name_or_company_name}. Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.";

//for fulltime project
$config['fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_disputee_view_autodecided_dispute'] = "Fulltime:You are currently during dispute negotiation period, which will end on {dispute_negotiation_end_date}. During this period you have the opportunity to negotiate and amiabily reach an agreement with {other_party_first_name_last_name_or_company_name}. There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before expiration of NEGOTIATION PERIOD. If you can’t reach any agreement with {other_party_first_name_last_name_or_company_name} by {dispute_negotiation_end_date}, your case will be automatically closed, and the disputed amount will be spliT 50% - 50% between you and {other_party_first_name_last_name_or_company_name}. Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.";

// for fixed/hourly project
$config['project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_disputee_view_admin_moderated_dispute'] = "You are currently during dispute negotiation period, which will end on {dispute_negotiation_end_date}. During this period you have the opportunity to negotiate and amiabily reach an agreement with {other_party_first_name_last_name_or_company_name}. There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage. If you can’t reach any agreement with {other_party_first_name_last_name_or_company_name} by {dispute_negotiation_end_date}, your case will be automatically escalated to admin arbitration to decide the outcome (usually within 10 working days). There is a dispute fee of {admin_dispute_arbitration_fee}%, applied to the entire value of your dispute ({project_disputed_amount}), that will be charged in case the dispue is moved to admin arbitration service. Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.";

// for fulltime
$config['fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_disputee_view_admin_moderated_dispute'] = "Fulltime:You are currently during dispute negotiation period, which will end on {dispute_negotiation_end_date}. During this period you have the opportunity to negotiate and amiabily reach an agreement with {other_party_first_name_last_name_or_company_name}. There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage. If you can’t reach any agreement with {other_party_first_name_last_name_or_company_name} by {dispute_negotiation_end_date}, your case will be automatically escalated to admin arbitration to decide the outcome (usually within 10 working days). There is a dispute fee of {admin_dispute_arbitration_fee}%, applied to the entire value of your dispute ({fulltime_project_disputed_amount}), that will be charged in case the dispue is moved to admin arbitration service. Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.";


// config for attachment regarding dispute project for validations 
$config['custom_project_dispute_details_page_attachment_allowed_file_extensions'] = '"png","PNG","gif","GIF","jpeg","JPEG","jpg","JPG","pdf","application/PDF","txt","xls","xlxs","doc","docx","zip","rar"'; //exclusion list : 
$config['plugin_project_dispute_details_page_attachment_allowed_file_extensions'] = 'image/*,.pdf,.xls, .xlsx, .doc, .docx, .txt, .zip'; //exclusion list : 




$config['project_dispute_details_page_attachment_maximum_size_validation_message'] = "Maximální povolená velikost je {project_dispute_attachment_max_file_size_mb} MB";

$config['project_dispute_details_page_attachment_extension_validation_message'] = "Typ obrázku, který chcete nehrát, není podporován!";



$config['project_dispute_details_page_attachment_not_exist_validation_message'] = "attachment not exists";

// for fixed/hourly project
$config['project_dispute_details_page_dispute_description_validation_project_dispute_form_message'] = 'project dispute description is required';

//for fulltime project

$config['project_dispute_details_page_dispute_description_validation_fulltime_project_dispute_form_message'] = 'fulltime project dispute description is required';

// validation message regarding counter offer
//$config['project_dispute_details_page_counter_offer_validation_fulltime_project_dispute_form_message'] = "counter offer field can not be empty[fulltime]";
$config['project_dispute_details_page_counter_offer_validation_project_dispute_form_message'] = "counter offer field can not be empty";



$config['project_dispute_details_page_minimum_maximum_counter_offer_validation_project_dispute_form_message'] = "Enter any amount between {counter_offer_min_amount} and {counter_offer_max_amount}";

$config['project_dispute_details_page_counter_offer_no_range_available_validation_project_dispute_form_message'] = "You can enter the amount only {dispute_counter_offer_amount_no_range_available}";



/* $config['project_dispute_details_page_maximum_counter_offer_validation_project_dispute_form_message'] = 'You cant make the counter offer more than or equal to {disputed_amount} '.CURRENCY;
$config['project_dispute_details_page_maximum_counter_offer_validation_fulltime_project_dispute_form_message'] = 'You cant make the counter offer more than or equal to fulltime {disputed_amount} '.CURRENCY; */



// Dispute reason when decision is automatic 

// for fixed/hourly project
$config['project_dispute_details_page_when_dispute_project_decided_automatic_po_view_reason_txt'] = 'Automatic decided - PO view -dispute case was set as automatically closed. Total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, therefore 50% of that amount <span  class="touch_line_break">{project_50%_disputed_amount}</span> was transferred back to you, together with associated service fees in total amount of <span  class="touch_line_break">{project_disputed_amount_service_fees}</span> (50% from initial service fee value of <span  class="touch_line_break">{project_50%_disputed_amount_service_fees}</span>). The other 50% amount was transferred to {other_party_first_name_last_name_or_company_name}';

$config['project_dispute_details_page_when_dispute_project_decided_automatic_sp_view_reason_txt'] = 'Automatic decided - Sp view -dispute case was set as automatically closed. Total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, therefore 50% of that amount <span  class="touch_line_break">{project_50%_disputed_amount}</span> was transferred to you account';

// for fulltime project
$config['fulltime_project_dispute_details_page_when_dispute_project_decided_automatic_employer_view_reason_txt'] = 'Fulltime:Automatic decided - PO view -dispute case was set as automatically closed. Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore 50% of that amount <span  class="touch_line_break">{fulltime_project_50%_disputed_amount}</span> was transferred back to you, together with associated service fees in total amount of <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> (50% from initial service fee value of <span  class="touch_line_break">{fulltime_project_50%_disputed_amount_service_fees}</span>). The other 50% amount was transferred to {other_party_first_name_last_name_or_company_name}';

$config['fulltime_project_dispute_details_page_when_dispute_project_decided_automatic_employee_view_reason_txt'] = 'Fulltime:Automatic decided - Sp view -dispute case was set as automatically closed. Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore 50% of that amount <span  class="touch_line_break">{fulltime_project_50%_disputed_amount}</span> was transferred to you account';



// Dispute reason when Admin decide winner to sp

// for fixed/hourly project
// For Sp view
$config['project_dispute_details_page_when_dispute_project_decided_admin_arbitration_sp_winner_sp_view_reason_txt'] = 'Admin Arbitartion - Sp view - SP Winner -dispute case was closed by admin. You won the dipsute case.Total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, therefore <span  class="touch_line_break">{project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to you account';
// For Po Viw

$config['project_dispute_details_page_when_dispute_project_decided_admin_arbitration_male_sp_winner_po_view_reason_txt'] = 'Admin Arbitartion - PO view - SP Winner - Male SP -dispute case was closed by admin. You lost the dipsute case against {sp_first_name_last_name}.Total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, therefore <span  class="touch_line_break">{project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to {sp_first_name_last_name}.Bussiness service fees <span  class="touch_line_break">{project_disputed_amount_service_fees}</span> and dispute arbitartion fees <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged from you';

$config['project_dispute_details_page_when_dispute_project_decided_admin_arbitration_female_sp_winner_po_view_reason_txt'] = 'Admin Arbitartion - PO view - SP Winner - Female SP -dispute case was closed by admin. You lost the dipsute case against {sp_first_name_last_name}.Total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, therefore <span  class="touch_line_break">{project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to {sp_first_name_last_name}.Bussiness service fees <span  class="touch_line_break">{project_disputed_amount_service_fees}</span> and dispute arbitartion fees <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged from you';

$config['project_dispute_details_page_when_dispute_project_decided_admin_arbitration_company_sp_winner_po_view_reason_txt'] = 'Admin Arbitartion - PO view - SP Winner - Company SP -dispute case was closed by admin. You lost the dipsute case against {sp_company_name}.Total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, therefore <span  class="touch_line_break">{project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to {sp_company_name}.Bussiness service fees <span  class="touch_line_break">{project_disputed_amount_service_fees}</span> and dispute arbitartion fees <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged from you';

// For fulltime project

// For Sp view
$config['fulltime_project_dispute_details_page_when_dispute_project_decided_admin_arbitration_employee_winner_employee_view_reason_txt'] = 'fulltime:Admin Arbitartion - Sp view - SP Winner -dispute case was closed by admin. You won the dipsute case.Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to you account';
// For Po Viw

$config['fulltime_project_dispute_details_page_when_dispute_project_decided_admin_arbitration_male_employee_winner_employer_view_reason_txt'] = 'Fulltime:Admin Arbitartion - PO view - SP Winner - Male SP -dispute case was closed by admin. You lost the dipsute case against {sp_first_name_last_name}.Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to {sp_first_name_last_name}.Bussiness service fees <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> and dispute arbitartion fees <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged from you';

$config['fulltime_project_dispute_details_page_when_dispute_project_decided_admin_arbitration_female_employee_winner_employer_view_reason_txt'] = 'Fulltime:Admin Arbitartion - PO view - SP Winner - Female SP -dispute case was closed by admin. You lost the dipsute case against {sp_first_name_last_name}.Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to {sp_first_name_last_name}.Bussiness service fees <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> and dispute arbitartion fees <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged from you';

$config['fulltime_project_dispute_details_page_when_dispute_project_decided_admin_arbitration_company_employee_winner_employer_view_reason_txt'] = 'Fulltime:Admin Arbitartion - PO view - SP Winner - Company SP -dispute case was closed by admin. You lost the dipsute case against {sp_company_name}.Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to {sp_company_name}.Bussiness service fees <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> and dispute arbitartion fees <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged from you';


// Dispute reason when Admin decide winner to po
// for fixed/hourly project
// Po View
$config['project_dispute_details_page_when_dispute_project_decided_admin_arbitration_po_winner_po_view_reason_txt'] = 'Admin Arbitartion - PO view - PO Winner -dispute case was closed by admin. You won the dipsute case.Total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, therefore <span  class="touch_line_break">{project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to your account .Bussiness service fees <span  class="touch_line_break">{project_disputed_amount_service_fees}</span> and dispute arbitartion fees <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged from you';

// SP View

$config['project_dispute_details_page_when_dispute_project_decided_admin_arbitration_male_po_winner_sp_view_reason_txt'] = 'Admin Arbitartion - SP view - PO Winner - Male PO -dispute case was closed by admin. You lost the dipsute case against {po_first_name_last_name}.Total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, therefore <span  class="touch_line_break">{project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to {po_first_name_last_name}';


$config['project_dispute_details_page_when_dispute_project_decided_admin_arbitration_female_po_winner_sp_view_reason_txt'] = 'Admin Arbitartion - SP view - PO Winner - FeMale PO -dispute case was closed by admin. You lost the dipsute case against {po_first_name_last_name}.Total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, therefore <span  class="touch_line_break">{project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to {po_first_name_last_name}';


$config['project_dispute_details_page_when_dispute_project_decided_admin_arbitration_company_po_winner_sp_view_reason_txt'] = 'Admin Arbitartion - SP view - PO Winner - Company PO -dispute case was closed by admin. You lost the dipsute case against {po_company_name}.Total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, therefore <span  class="touch_line_break">{project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to {po_company_name}';

// for fulltime project

// Po View
$config['fulltime_project_dispute_details_page_when_dispute_project_decided_admin_arbitration_employer_winner_employer_view_reason_txt'] = 'Fulltime:Admin Arbitartion - PO view - PO Winner -dispute case was closed by admin. You won the dipsute case.Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to your account .Bussiness service fees <span  class="touch_line_break">{project_disputed_amount_service_fees}</span> and dispute arbitartion fees <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged from you';

// SP View

$config['fulltime_project_dispute_details_page_when_dispute_project_decided_admin_arbitration_male_employer_winner_employee_view_reason_txt'] = 'Fulltime:Admin Arbitartion - SP view - PO Winner - Male PO -dispute case was closed by admin. You lost the dipsute case against {po_first_name_last_name}.Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to {po_first_name_last_name}';


$config['fulltime_project_dispute_details_page_when_dispute_project_decided_admin_arbitration_female_employer_winner_employee_view_reason_txt'] = 'Fulltime:Admin Arbitartion - SP view - PO Winner - FeMale PO -dispute case was closed by admin. You lost the dipsute case against {po_first_name_last_name}.Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to {po_first_name_last_name}';


$config['fulltime_project_dispute_details_page_when_dispute_project_decided_admin_arbitration_company_employer_winner_employee_view_reason_txt'] = 'Fulltime:Admin Arbitartion - SP view - PO Winner - Company PO -dispute case was closed by admin. You lost the dipsute case against {po_company_name}.Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to {po_company_name}';


################################################

//Dispute reason when Po initiated the dispute and after that cancelled the dispute by po
// For fixed/hourly project
$config['project_dispute_details_page_dispute_cancelled_by_po_initiator_po_view_reason_txt'] = 'cancelled by initiator PO - PO view - You cancelled this dispute on {dispute_end_date} and agreed to pay the disputed amount of <span  class="touch_line_break">{project_disputed_amount}</span> to <span  class="touch_line_break">{other_party_first_name_last_name_or_company_name}</span>';

$config['project_dispute_details_page_dispute_cancelled_by_male_po_initiator_sp_view_reason_txt'] = 'cancelled by initiator PO - SP view - Male:{po_first_name_last_name} cancelled this dispute on {dispute_end_date} and agreed to pay you the disputed amount of <span  class="touch_line_break">{project_disputed_amount}</span>';
$config['project_dispute_details_page_dispute_cancelled_by_female_po_initiator_sp_view_reason_txt'] = 'cancelled by initiator PO - SP view - Female:{po_first_name_last_name} cancelled this dispute on {dispute_end_date} and agreed to pay you the disputed amount of <span  class="touch_line_break">{project_disputed_amount}</span>';
$config['project_dispute_details_page_dispute_cancelled_by_company_po_initiator_sp_view_reason_txt'] = 'cancelled by initiator PO - SP view -Company:{po_company_name} cancelled this dispute on {dispute_end_date} and agreed to pay you the disputed amount of <span  class="touch_line_break">{project_disputed_amount}</span>';

// For fulltime project
$config['fulltime_project_dispute_details_page_dispute_cancelled_by_employer_initiator_employer_view_reason_txt'] = 'Fulltime:cancelled by initiator PO - PO view - You cancelled this dispute on {dispute_end_date} and agreed to pay the disputed amount of <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> to <span  class="touch_line_break">{other_party_first_name_last_name_or_company_name}</span>';

$config['fulltime_project_dispute_details_page_dispute_cancelled_by_male_employer_initiator_employee_view_reason_txt'] = 'Fulltime:cancelled by initiator PO - SP view - Male:{po_first_name_last_name} cancelled this dispute on {dispute_end_date} and agreed to pay you the disputed amount of <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>';
$config['fulltime_project_dispute_details_page_dispute_cancelled_by_female_employer_initiator_employee_view_reason_txt'] = 'Fulltime:cancelled by initiator PO - SP view - Female:{po_first_name_last_name} cancelled this dispute on {dispute_end_date} and agreed to pay you the disputed amount of <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>';
$config['fulltime_project_dispute_details_page_dispute_cancelled_by_company_employer_initiator_employee_view_reason_txt'] = 'Fulltime:cancelled by initiator PO - SP view -Company:{po_company_name} cancelled this dispute on {dispute_end_date} and agreed to pay you the disputed amount of <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>';


//Dispute reson when sp initiated the dispute and after that cancelled the dispute by sp
// for Fixed/hourly project
$config['project_dispute_details_page_dispute_cancelled_by_sp_initiator_sp_view_reason_txt'] = 'cancelled by initiator SP - SP view - You cancelled this dispute on {dispute_end_date} and agreed to pay the disputed amount of <span  class="touch_line_break">{project_disputed_amount}</span> to {other_party_first_name_last_name_or_company_name}';

$config['project_dispute_details_page_dispute_cancelled_by_male_sp_initiator_po_view_reason_txt'] = 'cancelled by initiator SP - PO view -Male:{sp_first_name_last_name} cancelled this dispute on {dispute_end_date} and agreed to return to you the disputed amount of <span  class="touch_line_break">{project_disputed_amount}</span> and associated business fees <span  class="touch_line_break">{project_disputed_amount_service_fees}</span>';

$config['project_dispute_details_page_dispute_cancelled_by_female_sp_initiator_po_view_reason_txt'] = 'cancelled by initiator SP - PO view -Female:{sp_first_name_last_name} cancelled this dispute on {dispute_end_date} and agreed to return to you the disputed amount of <span  class="touch_line_break">{project_disputed_amount}</span> and associated business fees {project_disputed_amount_service_fees}';
$config['project_dispute_details_page_dispute_cancelled_by_company_sp_initiator_po_view_reason_txt'] = 'cancelled by initiator SP - PO view -Company:{sp_company_name} cancelled this dispute on {dispute_end_date} and agreed to return to you the disputed amount of <span  class="touch_line_break">{project_disputed_amount}</span> and associated business fees <span  class="touch_line_break">{project_disputed_amount_service_fees}</span>';

// for Fulltime project
$config['fulltime_project_dispute_details_page_dispute_cancelled_by_employee_initiator_employee_view_reason_txt'] = 'Fulltime:cancelled by initiator SP - SP view - You cancelled this dispute on {dispute_end_date} and agreed to pay the disputed amount of <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> to {other_party_first_name_last_name_or_company_name}';

$config['fulltime_project_dispute_details_page_dispute_cancelled_by_male_employee_initiator_employer_view_reason_txt'] = 'Fulltime:cancelled by initiator SP - PO view -Male:{sp_first_name_last_name} cancelled this dispute on {dispute_end_date} and agreed to return to you the disputed amount of <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> and associated business fees <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span>';

$config['fulltime_project_dispute_details_page_dispute_cancelled_by_female_employee_initiator_employer_view_reason_txt'] = 'Fulltime:cancelled by initiator SP - PO view -Female:{sp_first_name_last_name} cancelled this dispute on {dispute_end_date} and agreed to return to you the disputed amount of <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> and associated business fees <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span>';

$config['fulltime_project_dispute_details_page_dispute_cancelled_by_company_employee_initiator_employer_view_reason_txt'] = 'Fulltime:cancelled by initiator SP - PO view -Company:{sp_company_name} cancelled this dispute on {dispute_end_date} and agreed to return to you the disputed amount of <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> and associated business fees <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span>';

// Disputed reason when po accept the counter offer

// For Po
// For fixed/hourly project
$config['project_dispute_details_page_dispute_counter_offer_accepted_by_po_po_view_reason_txt'] = 'Accepted By Po:Po View ->You accepted the counter offer <span  class="touch_line_break">{project_counter_offer_value}</span> received from {sp_first_name_last_name_or_company_name} of this dispute on {dispute_end_date} therefore <span  class="touch_line_break">{project_counter_offer_value}</span> will be transferred to {sp_first_name_last_name_or_company_name} and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';

// For fulltime project
$config['fulltime_project_dispute_details_page_dispute_counter_offer_accepted_by_employer_employer_view_reason_txt'] = 'Fulltime:Accepted By Po:Po View ->You accepted the counter offer <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> received from {sp_first_name_last_name_or_company_name} of this dispute on {dispute_end_date} therefore <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> will be transferred to {sp_first_name_last_name_or_company_name} and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{fulltime_project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';

// For SP
// For fixed/hourly project
$config['project_dispute_details_page_dispute_counter_offer_accepted_by_male_po_sp_view_reason_txt'] = 'Accepted By Po[Male]: SP View -> {po_first_name_last_name} accepted you counter offer <span  class="touch_line_break">{project_counter_offer_value}</span> of this dispute on {dispute_end_date} and agreed to return you <span  class="touch_line_break">{project_counter_offer_value}</span>';

$config['project_dispute_details_page_dispute_counter_offer_accepted_by_female_po_sp_view_reason_txt'] = 'Accepted By Po[Female]: SP View -> {po_first_name_last_name} accepted you counter offer <span  class="touch_line_break">{project_counter_offer_value}</span> of this dispute on {dispute_end_date} and agreed to return you <span  class="touch_line_break">{project_counter_offer_value}</span>';

$config['project_dispute_details_page_dispute_counter_offer_accepted_by_company_po_sp_view_reason_txt'] = 'Accepted By Po[Company]: SP View -> {po_company_name} accepted you counter offer <span  class="touch_line_break">{project_counter_offer_value}</span> of this dispute on {dispute_end_date} and agreed to return you <span  class="touch_line_break">{project_counter_offer_value}</span>';

// For fulltime project
$config['fulltime_project_dispute_details_page_dispute_counter_offer_accepted_by_male_po_sp_view_reason_txt'] = 'Fulltime:Accepted By Po[Male]: SP View -> {po_first_name_last_name} accepted you counter offer <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> of this dispute on {dispute_end_date} and agreed to return you <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span>';

$config['fulltime_project_dispute_details_page_dispute_counter_offer_accepted_by_female_po_sp_view_reason_txt'] = 'Fulltime:Accepted By Po[Female]: SP View -> {po_first_name_last_name} accepted you counter offer <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> of this dispute on {dispute_end_date} and agreed to return you <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span>';

$config['fulltime_project_dispute_details_page_dispute_counter_offer_accepted_by_company_po_sp_view_reason_txt'] = 'Fulltime:Accepted By Po[Company]: SP View -> {po_company_name} accepted you counter offer <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> of this dispute on {dispute_end_date} and agreed to return you <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span>';

// Disputed reason when sp accept the counter offer

// For SP
// for fixed/hourly project
$config['project_dispute_details_page_dispute_counter_offer_accepted_by_sp_sp_view_reason_txt'] = 'Accepted By SP: SP View -> you accepted you counter offer <span  class="touch_line_break">{project_counter_offer_value}</span> created by {po_first_name_last_name_or_company_name} of this dispute on {dispute_end_date} and agreed to return you <span  class="touch_line_break">{project_counter_offer_value}</span>';

// For fulltime
$config['fulltime_project_dispute_details_page_dispute_counter_offer_accepted_by_employee_employee_view_reason_txt'] = 'Fulltime:Accepted By SP: SP View -> you accepted you counter offer <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> created by {po_first_name_last_name_or_company_name} of this dispute on {dispute_end_date} and agreed to return you <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span>';

// For Po
// For fixed/hourly
$config['project_dispute_details_page_dispute_counter_offer_accepted_by_male_sp_po_view_reason_txt'] = 'Accepted By Sp[Male]: PO View -> {sp_first_name_last_name} accepted you counter offer <span  class="touch_line_break">{project_counter_offer_value}</span> of this dispute on {dispute_end_date} therefore <span  class="touch_line_break">{project_counter_offer_value}</span> will be transferred to {sp_first_name_last_name} and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';



$config['project_dispute_details_page_dispute_counter_offer_accepted_by_female_sp_po_view_reason_txt'] = 'Accepted By Sp[FEMale]: PO View -> {sp_first_name_last_name} accepted you counter offer <span  class="touch_line_break">{project_counter_offer_value}</span> of this dispute on {dispute_end_date} therefore <span  class="touch_line_break">{project_counter_offer_value}</span> will be transferred to {sp_first_name_last_name} and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';

$config['project_dispute_details_page_dispute_counter_offer_accepted_by_company_sp_po_view_reason_txt'] = 'Accepted By Sp[Comapny]: PO View -> {sp_company_name} accepted you counter offer <span  class="touch_line_break">{project_counter_offer_value}</span> of this dispute on {dispute_end_date} therefore <span  class="touch_line_break">{project_counter_offer_value}</span> will be transferred to {sp_company_name} and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';

// For fulltime project
$config['fulltime_project_dispute_details_page_dispute_counter_offer_accepted_by_male_employee_employer_view_reason_txt'] = 'Fulltime:Accepted By Sp[Male]: PO View -> {sp_first_name_last_name} accepted you counter offer <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> of this dispute on {dispute_end_date} therefore <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> will be transferred to {sp_first_name_last_name} and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{fulltime_project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';

$config['fulltime_project_dispute_details_page_dispute_counter_offer_accepted_by_female_employee_employer_view_reason_txt'] = 'Fulltime:Accepted By Sp[FEMale]: PO View -> {sp_first_name_last_name} accepted you counter offer <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> of this dispute on {dispute_end_date} therefore <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> will be transferred to {sp_first_name_last_name} and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{fulltime_project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';

$config['fulltime_project_dispute_details_page_dispute_counter_offer_accepted_by_company_employee_employer_view_reason_txt'] = 'Fulltime:Accepted By Sp[Comapny]: PO View -> {sp_company_name} accepted you counter offer <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> of this dispute on {dispute_end_date} therefore <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> will be transferred to {sp_company_name} and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{fulltime_project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';


/// Define Config regarding checks for options avaiable on project detail page payemnts tabs (release escrow,create escrow etc) when dispute is open.

// When sp trying to create escrow request on disputed project
$config['project_details_page_sp_tries_create_escrow_request_on_active_disputed_project'] = "CZ:You cannot make escrow request on disputed project";
$config['fulltime_project_details_page_employee_tries_create_escrow_request_on_active_disputed_fulltime_project'] = "CZ:Fulltime->You cannot make escrow request on disputed project";

// When po trying to create requested escrow for SP
$config['project_details_page_po_tries_create_requested_escrow_on_active_disputed_project'] = "CZ:You cannot create escrow on disputed project";
$config['fulltime_project_details_page_employer_tries_create_requested_escrow_on_active_disputed_fulltime_project'] = "CZ:Fulltime->You cannot create escrow on disputed project";

// When Po trying to create escrow for sp
$config['project_details_page_po_tries_create_escrow_on_active_disputed_project'] = "CZ:You cannot create escrow on disputed project";
$config['fulltime_project_details_page_employer_tries_create_escrow_on_active_disputed_fulltime_project'] = "CZ:Fulltime->You cannot create escrow on disputed project";

// When Po trying to release escrow
$config['project_details_page_po_tries_release_escrow_on_active_disputed_project'] = "CZ:You cannot release escrow on disputed project";
$config['fulltime_project_details_page_employer_tries_release_escrow_on_active_disputed_fulltime_project'] = "CZ:Fulltime->You cannot create release escrow on disputed project";

// Activity log messages when dispute initiated
// When po initiated the dispute.

// For Fixed budget project 
$config['fixed_budget_project_dispute_initiated_by_po_message_sent_to_po_user_activity_log_displayed_message'] = 'Fixed-you opened a dispute on the active escrow amount on project <a href="{project_url_link}" target="_blank">{project_title}</a> against <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>. Disputed amount is <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, and the associated service fees in amount of <span  class="touch_line_break">{fixed_budget_project_disputed_amount_service_fees}</span>.';

$config['fixed_budget_project_dispute_initiated_by_male_po_message_sent_to_sp_user_activity_log_displayed_message'] = 'Fixed- Initiated By Male PO:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>';

$config['fixed_budget_project_dispute_initiated_by_female_po_message_sent_to_sp_user_activity_log_displayed_message'] = 'Fixed-Initiated By Female PO:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>';

$config['fixed_budget_project_dispute_initiated_by_company_po_message_sent_to_sp_user_activity_log_displayed_message'] = 'Fixed-Initiated By Company PO:<a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>';

// For Hourly rate based project
$config['hourly_rate_based_project_dispute_initiated_by_po_message_sent_to_po_user_activity_log_displayed_message'] = 'Hourly-you opened a dispute on the active escrow amount on project <a href="{project_url_link}" target="_blank">{project_title}</a> against <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>. Disputed amount is <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, and the associated service fees in amount of <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_service_fees}</span>.';

$config['hourly_rate_based_project_dispute_initiated_by_male_po_message_sent_to_sp_user_activity_log_displayed_message'] = 'Hourly-Initiated By Male PO:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>';

$config['hourly_rate_based_project_dispute_initiated_by_female_po_message_sent_to_sp_user_activity_log_displayed_message'] = 'Hourly-Initiated By Female PO:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>';

$config['hourly_rate_based_project_dispute_initiated_by_company_po_message_sent_to_sp_user_activity_log_displayed_message'] = 'Hourly-Initiated By Company PO:<a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>';

// For Fulltime project 
$config['fulltime_project_dispute_initiated_by_employer_message_sent_to_employer_user_activity_log_displayed_message'] = 'Fulltime-you opened a dispute on the active escrow amount on project <a href="{project_url_link}" target="_blank">{project_title}</a> against <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>. Disputed amount is <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, and the associated service fees in amount of <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span>.';

$config['fulltime_project_dispute_initiated_by_male_employer_message_sent_to_employee_user_activity_log_displayed_message'] = 'Fulltime- Initiated By Male :<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>';

$config['fulltime_project_dispute_initiated_by_female_employer_message_sent_to_employer_user_activity_log_displayed_message'] = 'Fulltime-Initiated By Female PO:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>';

$config['fulltime_project_dispute_initiated_by_company_employer_message_sent_to_employer_user_activity_log_displayed_message'] = 'Fulltime-Initiated By Company PO:<a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>';



// When sp initiated the dispute.

// for fixed budget project
$config['fixed_budget_project_dispute_initiated_by_sp_message_sent_to_sp_user_activity_log_displayed_message'] = 'Fixed-you opened a dispute on the active escrow amount on project <a href="{project_url_link}" target="_blank">{project_title}</a> against <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>. Disputed amount is <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>';

$config['fixed_budget_project_dispute_initiated_by_male_sp_message_sent_to_po_user_activity_log_displayed_message'] = 'Fixed-Initiated By Male SP:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, and the associated service fees in amount of <span  class="touch_line_break">{fixed_budget_project_disputed_amount_service_fees}</span>.';

$config['fixed_budget_project_dispute_initiated_by_female_sp_message_sent_to_po_user_activity_log_displayed_message'] = 'Fixed-Initiated By Female SP:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, and the associated service fees in amount of <span  class="touch_line_break">{fixed_budget_project_disputed_amount_service_fees}</span>.';

$config['fixed_budget_project_dispute_initiated_by_company_sp_message_sent_to_po_user_activity_log_displayed_message'] = 'Fixed-Initiated By Company SP:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, and the associated service fees in amount of <span  class="touch_line_break">{fixed_budget_project_disputed_amount_service_fees}</span>.';

// for hourly rate based project

$config['hourly_rate_based_project_dispute_initiated_by_sp_message_sent_to_sp_user_activity_log_displayed_message'] = 'Hourly-you opened a dispute on the active escrow amount on project <a href="{project_url_link}" target="_blank">{project_title}</a> against <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>. Disputed amount is <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>';

$config['hourly_rate_based_project_dispute_initiated_by_male_sp_message_sent_to_po_user_activity_log_displayed_message'] = 'Hourly-Initiated By Male SP:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, and the associated service fees in amount of <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_service_fees}</span>.';

$config['hourly_rate_based_project_dispute_initiated_by_female_sp_message_sent_to_po_user_activity_log_displayed_message'] = 'Hourly-Initiated By Female SP:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, and the associated service fees in amount of <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_service_fees}</span>.';

$config['hourly_rate_based_project_dispute_initiated_by_company_sp_message_sent_to_po_user_activity_log_displayed_message'] = 'Hourly-Initiated By Company SP:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, and the associated service fees in amount of <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_service_fees}</span>.';

// for fulltime project
$config['fulltime_project_dispute_initiated_by_employee_message_sent_to_employee_user_activity_log_displayed_message'] = 'Fulltime-you opened a dispute on the active escrow amount on project <a href="{project_url_link}" target="_blank">{project_title}</a> against <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>. Disputed amount is <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>';

$config['fulltime_project_dispute_initiated_by_male_employee_message_sent_to_employer_user_activity_log_displayed_message'] = 'Fulltime-Initiated By Male SP:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, and the associated service fees in amount of <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span>.';

$config['fulltime_project_dispute_initiated_by_female_employee_message_sent_to__employer_user_activity_log_displayed_message'] = 'Fulltime-Initiated By Female SP:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, and the associated service fees in amount of <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span>.';

$config['fulltime_project_dispute_initiated_by_company_employee_message_sent_to_employer_user_activity_log_displayed_message'] = 'Fulltime-Initiated By Company SP:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> opened a dispute on the active escrow amount on project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Disputed amount is <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, and the associated service fees in amount of <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span>.';


############ log message when po/sp exchange message during dispute

// for fixed budget
$config['fixed_budget_project_dispute_message_posted_activity_log_displayed_message_sent_to_poster'] = 'Fixed-Poster:You posted a message on message board of dispute case {dispute_reference_id} on project <a href="{project_url_link}" target="_blank">{project_title}</a>. We encourage you to work with <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> and try to resolve your issue. Otherwise, this dispute will be escalated to admin arbitration process on {dispute_negotiation_end_date}. Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.';
$config['fixed_budget_project_dispute_message_posted_activity_log_displayed_message_sent_to_other_party'] = ' Fixed-Reciever:You received a new message from <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> dispute case {dispute_reference_id} opened on project <a href="{project_url_link}" target="_blank">{project_title}</a>. We encourage you to respond to <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> as soon as possible to resolve your issue. Otherwise, this dispute will be escalated to admin arbitration process on {dispute_negotiation_end_date}. Once a case has been moved to admin arbitration process and decission has been taken, the dispute is closed and canott be reopened.';

// for hourly rate based project
$config['hourly_rate_based_project_dispute_message_posted_activity_log_displayed_message_sent_to_poster'] = 'Hourly-Poster:You posted a message on message board of dispute case {dispute_reference_id} on project <a href="{project_url_link}" target="_blank">{project_title}</a>. We encourage you to work with <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> and try to resolve your issue. Otherwise, this dispute will be escalated to admin arbitration process on {dispute_negotiation_end_date}. Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.';
$config['hourly_rate_based_project_dispute_message_posted_activity_log_displayed_message_sent_to_other_party'] = 'Hourly-Reciever:You received a new message from <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> dispute case {dispute_reference_id} opened on project <a href="{project_url_link}" target="_blank">{project_title}</a>. We encourage you to respond to <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> as soon as possible to resolve your issue. Otherwise, this dispute will be escalated to admin arbitration process on {dispute_negotiation_end_date}. Once a case has been moved to admin arbitration process and decission has been taken, the dispute is closed and canott be reopened.';


// for fulltime project
$config['fulltime_project_dispute_message_posted_activity_log_displayed_message_sent_to_poster'] = 'Fulltime-Poster:You posted a message on message board of dispute case {dispute_reference_id} on project <a href="{project_url_link}" target="_blank">{project_title}</a>. We encourage you to work with <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> and try to resolve your issue. Otherwise, this dispute will be escalated to admin arbitration process on {dispute_negotiation_end_date}. Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.';
$config['fulltime_project_dispute_message_posted_activity_log_displayed_message_sent_to_other_party'] = 'Fulltime-Reciever:You received a new message from <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> dispute case {dispute_reference_id} opened on project <a href="{project_url_link}" target="_blank">{project_title}</a>. We encourage you to respond to <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> as soon as possible to resolve your issue. Otherwise, this dispute will be escalated to admin arbitration process on {dispute_negotiation_end_date}. Once a case has been moved to admin arbitration process and decission has been taken, the dispute is closed and canott be reopened.';


// Activity log message when po cancelled the dispute so project will be completed

// For fixed type project 
// send to po when project is completed
$config['fixed_budget_project_message_sent_to_po_when_po_cancelled_dispute_project_completed_user_activity_log_displayed_message'] = 'FIXED:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

//send to sp when project is completed


$config['fixed_budget_project_message_sent_to_sp_when_po_male_cancelled_dispute_project_completed_user_activity_log_displayed_message'] = 'FIXED:Male PO:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a>';

$config['fixed_budget_project_message_sent_to_sp_when_po_female_cancelled_dispute_project_completed_user_activity_log_displayed_message'] = 'FIXED:Female PO:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a>';


$config['fixed_budget_project_message_sent_to_sp_when_po_company_cancelled_dispute_project_completed_user_activity_log_displayed_message'] = 'FIXED:Company PO:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{po_profile_url_link}" target="_blank">{user_company_name}</a>';


// For hourly type project 
// send to po when project is completed
$config['hourly_rate_based_project_message_sent_to_po_when_po_cancelled_dispute_project_completed_user_activity_log_displayed_message'] = 'HOURLY:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

//send to sp when project is completed


$config['hourly_rate_based_project_message_sent_to_sp_when_po_male_cancelled_dispute_project_completed_user_activity_log_displayed_message'] = 'HOURLY:Male PO:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a>';

$config['hourly_rate_based_project_message_sent_to_sp_when_po_female_cancelled_dispute_project_completed_user_activity_log_displayed_message'] = 'HOURLY:Female PO:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a>';


$config['hourly_rate_based_project_message_sent_to_sp_when_po_company_cancelled_dispute_project_completed_user_activity_log_displayed_message'] = 'HOURLY:Company PO:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{po_profile_url_link}" target="_blank">{user_company_name}</a>';



// Activity log message when sp/po accept offer and project will completed

// for fixed budget project
$config['fixed_budget_project_message_sent_to_po_when_user_accept_counter_offer_project_completed_user_activity_log_displayed_message'] = 'FIXED:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

$config['fixed_budget_project_message_sent_to_sp_when_user_accept_counter_offer_project_completed_user_activity_log_displayed_message'] = 'FIXED:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

// for hourly rate based project

$config['hourly_rate_based_project_message_sent_to_po_when_user_accept_counter_offer_project_completed_user_activity_log_displayed_message'] = 'HOURLY:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

$config['hourly_rate_based_project_message_sent_to_sp_when_user_accept_counter_offer_project_completed_user_activity_log_displayed_message'] = 'HOURLY:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';
// Activity log message when po accept counter offer of sp

// For fixed Budget 
// for po
$config['fixed_budget_project_message_sent_to_po_when_po_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'FIXED:Dispute case {dispute_reference_id} was resolved. You accepted the counter offer received from <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>, in value of <span  class="touch_line_break">{fixed_budget_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fixed_budget_project_counter_offer_value}</span> will be transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{fixed_budget_project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';

// for sp when po is male
$config['fixed_budget_project_message_sent_to_sp_when_male_po_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'FIXED-Male PO:Dispute case {dispute_reference_id} was resolved. <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your counter offer , in value of <span  class="touch_line_break">{fixed_budget_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fixed_budget_project_counter_offer_value}</span> tranfer to your account balance';

// for sp when po is female
$config['fixed_budget_project_message_sent_to_sp_when_female_po_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'FIXED-Female PO:Dispute case {dispute_reference_id} was resolved. <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your counter offer , in value of <span  class="touch_line_break">{fixed_budget_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fixed_budget_project_counter_offer_value}</span> tranfer to your account balance';

// for sp when po is company
$config['fixed_budget_project_message_sent_to_sp_when_company_po_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'FIXED-Company PO:Dispute case {dispute_reference_id} was resolved. <a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> accepted your counter offer , in value of <span  class="touch_line_break">{fixed_budget_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fixed_budget_project_counter_offer_value}</span> tranfer to your account balance';



// For hourly rate based project
// for po
$config['hourly_rate_based_project_message_sent_to_po_when_po_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'HOURLY:Dispute case {dispute_reference_id} was resolved. You accepted the counter offer received from <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>, in value of <span  class="touch_line_break">{hourly_rate_based_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{hourly_rate_based_project_counter_offer_value}</span> will be transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{hourly_rate_based_project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';

// for sp when po is male
$config['hourly_rate_based_project_message_sent_to_sp_when_male_po_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'HOURLY-Male PO:Dispute case {dispute_reference_id} was resolved. <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your counter offer , in value of <span  class="touch_line_break">{hourly_rate_based_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{hourly_rate_based_project_counter_offer_value}</span> tranfer to your account balance';

// for sp when po is female
$config['hourly_rate_based_project_message_sent_to_sp_when_female_po_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'HOURLY-Female PO:Dispute case {dispute_reference_id} was resolved. <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your counter offer , in value of <span  class="touch_line_break">{hourly_rate_based_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{hourly_rate_based_project_counter_offer_value}</span> tranfer to your account balance';

// for sp when po is company
$config['hourly_rate_based_project_message_sent_to_sp_when_company_po_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'HOURLY-Company PO:Dispute case {dispute_reference_id} was resolved. <a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> accepted your counter offer , in value of <span  class="touch_line_break">{hourly_rate_based_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{hourly_rate_based_project_counter_offer_value}</span> tranfer to your account balance';

// For fulltime project
// for po
$config['fulltime_project_message_sent_to_employer_when_employer_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'Fulltime:Dispute case {dispute_reference_id} was resolved. You accepted the counter offer received from <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>, in value of <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> will be transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{fulltime_project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';

// for sp when po is male
$config['fulltime_project_message_sent_to_employee_when_male_employer_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'Fulltime-Male PO:Dispute case {dispute_reference_id} was resolved. <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your counter offer , in value of <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> tranfer to your account balance';

// for sp when po is female
$config['fulltime_project_message_sent_to_employee_when_female_employer_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'Fulltime-Female PO:Dispute case {dispute_reference_id} was resolved. <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your counter offer , in value of <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> tranfer to your account balance';

// for sp when po is company
$config['fulltime_project_message_sent_to_employee_when_company_employer_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'Fulltime-Company PO:Dispute case {dispute_reference_id} was resolved. <a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> accepted your counter offer , in value of <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> tranfer to your account balance';

// Activity log message when sp accept counter offer of po

// For fixed based project 
// for sp
$config['fixed_budget_project_message_sent_to_sp_when_sp_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'FIXED:Dispute case {dispute_reference_id} was resolved. You accepted the counter offer received from <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>, in value of <span  class="touch_line_break">{fixed_budget_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fixed_budget_project_counter_offer_value}</span> will be transferred to your account';

// For po when sp is male

$config['fixed_budget_project_message_sent_to_po_when_male_sp_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'FIXED-Male SP:->Dispute case {dispute_reference_id} was resolved. <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your counter offer <span  class="touch_line_break">{fixed_budget_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fixed_budget_project_counter_offer_value}</span> will be transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{fixed_budget_project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';

// For po when sp is female

$config['fixed_budget_project_message_sent_to_po_when_female_sp_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'FIXED-Female SP:->Dispute case {dispute_reference_id} was resolved. <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your counter offer <span  class="touch_line_break">{fixed_budget_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fixed_budget_project_counter_offer_value}</span> will be transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{fixed_budget_project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';

// For po when sp is company

$config['fixed_budget_project_message_sent_to_po_when_company_sp_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'FIXED-Company SP:->Dispute case {dispute_reference_id} was resolved. <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> accepted your counter offer <span  class="touch_line_break">{fixed_budget_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fixed_budget_project_counter_offer_value}</span> will be transferred to <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{fixed_budget_project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';


// For hourly rate based project 
// for sp
$config['hourly_rate_based_project_message_sent_to_sp_when_sp_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'HOURLY:Dispute case {dispute_reference_id} was resolved. You accepted the counter offer received from <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>, in value of <span  class="touch_line_break">{hourly_rate_based_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{hourly_rate_based_project_counter_offer_value}</span> will be transferred to your account';

// For po when sp is male

$config['hourly_rate_based_project_message_sent_to_po_when_male_sp_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'HOURLY-Male SP:->Dispute case {dispute_reference_id} was resolved. <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your counter offer <span  class="touch_line_break">{hourly_rate_based_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{hourly_rate_based_project_counter_offer_value}</span> will be transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{hourly_rate_based_project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';

// For po when sp is female

$config['hourly_rate_based_project_message_sent_to_po_when_female_sp_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'HOURLY-Female SP:->Dispute case {dispute_reference_id} was resolved. <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your counter offer <span  class="touch_line_break">{hourly_rate_based_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{hourly_rate_based_project_counter_offer_value}</span> will be transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{hourly_rate_based_project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';

// For po when sp is company

$config['hourly_rate_based_project_message_sent_to_po_when_company_sp_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'HOURLY-Company SP:->Dispute case {dispute_reference_id} was resolved. <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> accepted your counter offer <span  class="touch_line_break">{hourly_rate_based_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{hourly_rate_based_project_counter_offer_value}</span> will be transferred to <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{hourly_rate_based_project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';


// For fulltime project 
// for sp
$config['fulltime_project_message_sent_to_employee_when_employee_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'Fulltime:Dispute case {dispute_reference_id} was resolved. You accepted the counter offer received from <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>, in value of <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> will be transferred to your account';

// For po when sp is male

$config['fulltime_project_message_sent_to_employer_when_male_employee_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'Fulltime-Male SP:->Dispute case {dispute_reference_id} was resolved. <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your counter offer <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> will be transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{fulltime_project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';

// For po when sp is female

$config['fulltime_project_message_sent_to_employer_when_female_employee_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'Fulltime-Female SP:->Dispute case {dispute_reference_id} was resolved. <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your counter offer <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> will be transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{fulltime_project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';

// For po when sp is company

$config['fulltime_project_message_sent_to_employer_when_company_employee_accept_counter_offer_dispute_project_user_activity_log_displayed_message'] = 'Fulltime-Company SP:->Dispute case {dispute_reference_id} was resolved. <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> accepted your counter offer <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span>. Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> will be transferred to <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> and associated service fees <span  class="touch_line_break">{service_fees_charged_from_po}</span> will be charged. The remaining <span  class="touch_line_break">{fulltime_project_remaining_amount}</span> will be returned to your balance together with the associated services fees <span  class="touch_line_break">{service_fees_return_to_po}</span>';


// Activity log message when dispute decided automatically so project will be completed

// For fixed type project
$config['fixed_budget_project_message_sent_to_po_when_dispute_project_decided_automatic_project_completed_user_activity_log_displayed_message'] = 'FIXED:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';


$config['fixed_budget_project_message_sent_to_sp_when_dispute_project_decided_automatic_project_completed_user_activity_log_displayed_message'] = 'FIXED:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

// For hourly rate based project
$config['hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_automatic_project_completed_user_activity_log_displayed_message'] = 'HOURLY:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';


$config['hourly_rate_based_project_message_sent_to_sp_when_dispute_project_decided_automatic_project_completed_user_activity_log_displayed_message'] = 'HOURLY:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';


// Activity Log message when dispute decided automatically

// For fixed budget project
$config['fixed_budget_project_message_sent_to_sp_when_dispute_project_decided_automatic_user_activity_log_displayed_message'] = 'FIXED:-SP VIEW-dispute case {dispute_reference_id} was set as automatically resolved. Total disputed amount was <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, therefore 50% of that amount <span  class="touch_line_break">{fixed_budget_project_50%_disputed_amount}</span> was transferred to you account';


$config['fixed_budget_project_message_sent_to_po_when_dispute_project_decided_automatic_user_activity_log_displayed_message'] = 'FIXED:-PO VIEW-dispute case {dispute_reference_id} was set as automatically resolved. Total disputed amount was <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, therefore 50% of that amount <span  class="touch_line_break">{fixed_budget_project_50%_disputed_amount}</span> was transferred back to you, together with associated service fees in total amount of <span  class="touch_line_break">{fixed_budget_project_disputed_amount_service_fees}</span> (50% from initial service fee value of <span  class="touch_line_break">{fixed_budget_project_50%_disputed_amount_service_fees}</span>). The other 50% amount was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> ';

// For hourly rate based project
$config['hourly_rate_based_project_message_sent_to_sp_when_dispute_project_decided_automatic_user_activity_log_displayed_message'] = 'HOURLY:-SP VIEW-dispute case {dispute_reference_id} was set as automatically resolved. Total disputed amount was <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, therefore 50% of that amount <span  class="touch_line_break">{hourly_rate_based_project_50%_disputed_amount}</span> was transferred to you account';
$config['hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_automatic_user_activity_log_displayed_message'] = 'HOURLY:-PO VIEW-dispute case {dispute_reference_id} was set as automatically resolved. Total disputed amount was <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, therefore 50% of that amount <span  class="touch_line_break">{hourly_rate_based_project_50%_disputed_amount}</span> was transferred back to you, together with associated service fees in total amount of <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_service_fees}</span> (50% from initial service fee value of <span  class="touch_line_break">{hourly_rate_based_project_50%_disputed_amount_service_fees}</span>). The other 50% amount was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> ';

// For fulltime project
$config['fulltime_project_message_sent_to_employee_when_dispute_project_decided_automatic_user_activity_log_displayed_message'] = 'Fulltime:-SP VIEW-dispute case {dispute_reference_id} was set as automatically resolved. Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore 50% of that amount <span  class="touch_line_break">{fulltime_project_50%_disputed_amount}</span> was transferred to you account';
$config['fulltime_project_message_sent_to_employer_when_dispute_project_decided_automatic_user_activity_log_displayed_message'] = 'Fulltime:-PO VIEW-dispute case {dispute_reference_id} was set as automatically resolved. Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore 50% of that amount <span  class="touch_line_break">{fulltime_project_50%_disputed_amount}</span> was transferred back to you, together with associated service fees in total amount of <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> (50% from initial service fee value of <span  class="touch_line_break">{fulltime_project_50%_disputed_amount_service_fees}</span>). The other 50% amount was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

// Activity log message when po cancelled the dispute


// For Fixed Budget
//For po
$config['fixed_budget_project_message_sent_to_po_when_po_cancelled_dispute_project_user_activity_log_displayed_message'] = 'FIXED:You marked dispute case {dispute_reference_id} as resolved. amount <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span> was transfered to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> and associated service fees <span  class="touch_line_break">{fixed_budget_project_disputed_amount_service_fees}</span> charged by business.';

//for sp
$config['fixed_budget_project_message_sent_to_sp_when_po_male_cancelled_dispute_project_user_activity_log_displayed_message'] = 'FIXED:Male PO:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> marked dispute case {dispute_reference_id} as resolved. entire amount of <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span> was transferred to your account balance';
$config['fixed_budget_project_message_sent_to_sp_when_po_female_cancelled_dispute_project_user_activity_log_displayed_message'] = 'FIXED:Female PO:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> marked dispute case {dispute_reference_id} as resolved. entire amount of <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span> was transferred to your account balance';

$config['fixed_budget_project_message_sent_to_sp_when_po_company_cancelled_dispute_project_user_activity_log_displayed_message'] = 'FIXED:Company PO<a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> marked dispute case {dispute_reference_id} as resolved. entire amount of <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span> was transferred to your account balance';

// For Hourly Rate Project
//For po
$config['hourly_rate_based_project_message_sent_to_po_when_po_cancelled_dispute_project_user_activity_log_displayed_message'] = 'HOURLY:You marked dispute case {dispute_reference_id} as resolved. amount <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span> was transfered to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> and associated service fees <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_service_fees}</span> charged by business.';

//for sp
$config['hourly_rate_based_project_message_sent_to_sp_when_po_male_cancelled_dispute_project_user_activity_log_displayed_message'] = 'HOURLY:Male PO:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> marked dispute case {dispute_reference_id} as resolved. entire amount of <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span> was transferred to your account balance';
$config['hourly_rate_based_project_message_sent_to_sp_when_po_female_cancelled_dispute_project_user_activity_log_displayed_message'] = 'HOURLY:Female PO:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> marked dispute case {dispute_reference_id} as resolved. entire amount of <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span> was transferred to your account balance';

$config['hourly_rate_based_project_message_sent_to_sp_when_po_company_cancelled_dispute_project_user_activity_log_displayed_message'] = 'HOURLY:Company PO<a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> marked dispute case {dispute_reference_id} as resolved. entire amount of <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span> was transferred to your account balance';

// For fulltime Project
//For po
$config['fulltime_project_message_sent_to_employer_when_employer_cancelled_dispute_project_user_activity_log_displayed_message'] = 'Fulltime:You marked dispute case {dispute_reference_id} as resolved. amount <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> was transfered to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> and associated service fees <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> charged by business.';

//for sp
$config['fulltime_project_message_sent_to_employee_when_employer_male_cancelled_dispute_project_user_activity_log_displayed_message'] = 'Fulltime:Male PO:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> marked dispute case {dispute_reference_id} as resolved. entire amount of <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> was transferred to your account balance';
$config['fulltime_project_message_sent_to_employee_when_employer_female_cancelled_dispute_project_user_activity_log_displayed_message'] = 'Fulltime:Female PO:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> marked dispute case {dispute_reference_id} as resolved. entire amount of <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> was transferred to your account balance';

$config['fulltime_project_message_sent_to_employee_when_employer_company_cancelled_dispute_project_user_activity_log_displayed_message'] = 'Fulltime:Company PO<a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> marked dispute case {dispute_reference_id} as resolved. entire amount of <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> was transferred to your account balance';


// Activity log message when sp cancelled the dispute


// For fixed budget project

//For sp
$config['fixed_budget_project_message_sent_to_sp_when_sp_cancelled_dispute_project_user_activity_log_displayed_message'] = 'FIXED-You marked dispute case {dispute_reference_id} as resolved. amount <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span> was transfered to <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';

//for po


$config['fixed_budget_project_message_sent_to_po_when_sp_male_cancelled_dispute_project_user_activity_log_displayed_message'] = 'FIXED-Male SP:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> marked dispute case {dispute_reference_id} as resolved.Entire amount of <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span> with associated service fees <span  class="touch_line_break">{fixed_budget_project_disputed_amount_service_fees}</span> was transferred to your account balance.';



$config['fixed_budget_project_message_sent_to_po_when_sp_female_cancelled_dispute_project_user_activity_log_displayed_message'] = 'FIXED-Female SP:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> marked dispute case {dispute_reference_id} as resolved.Entire amount of <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span> with associated service fees <span  class="touch_line_break">{fixed_budget_project_disputed_amount_service_fees}</span> was transferred to your account balance.';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_cancelled_dispute_project_user_activity_log_displayed_message'] = 'FIXED-Company SP:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> marked dispute case {dispute_reference_id} as resolved. Entire amount of <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span> with associated service fees <span  class="touch_line_break">{fixed_budget_project_disputed_amount_service_fees}</span> was transferred to your account balance.';

// for hourly rate based project

//For sp
$config['hourly_rate_based_project_message_sent_to_sp_when_sp_cancelled_dispute_project_user_activity_log_displayed_message'] = 'HOURLY-You marked dispute case {dispute_reference_id} as resolved. amount <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span> was transfered to <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';

//for po
$config['hourly_rate_based_project_message_sent_to_po_when_sp_male_cancelled_dispute_project_user_activity_log_displayed_message'] = 'HOURLY-Male SP:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> marked dispute case {dispute_reference_id} as resolved.Entire amount of <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span> with associated service fees <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_service_fees}</span> was transferred to your account balance.';

$config['hourly_rate_based_project_message_sent_to_po_when_sp_female_cancelled_dispute_project_user_activity_log_displayed_message'] = 'HOURLY-Female SP:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> marked dispute case {dispute_reference_id} as resolved.Entire amount of <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span> with associated service fees <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_service_fees}</span> was transferred to your account balance.';

$config['hourly_rate_based_project_message_sent_to_po_when_sp_company_cancelled_dispute_project_user_activity_log_displayed_message'] = 'HOURLY-Company SP:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> marked dispute case {dispute_reference_id} as resolved. Entire amount of <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span> with associated service fees <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_service_fees}</span> was transferred to your account balance.';

// for fulltime project

//For sp
$config['fulltime_project_message_sent_to_employee_when_employee_cancelled_dispute_project_user_activity_log_displayed_message'] = 'Fulltime-You marked dispute case {dispute_reference_id} as resolved. amount <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> was transfered to <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';

//for po
$config['fulltime_project_message_sent_to_employer_when_employee_male_cancelled_dispute_project_user_activity_log_displayed_message'] = 'Fulltime-Male SP:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> marked dispute case {dispute_reference_id} as resolved.Entire amount of <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> with associated service fees <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> was transferred to your account balance.';

$config['fulltime_project_message_sent_to_employer_when_employee_female_cancelled_dispute_project_user_activity_log_displayed_message'] = 'Fulltime-Female SP:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> marked dispute case {dispute_reference_id} as resolved.Entire amount of <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> with associated service fees <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> was transferred to your account balance.';

$config['fulltime_project_message_sent_to_employer_when_employee_company_cancelled_dispute_project_user_activity_log_displayed_message'] = 'Fulltime-Company SP:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> marked dispute case {dispute_reference_id} as resolved. Entire amount of <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> with associated service fees <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> was transferred to your account balance.';



// Activity log message when user created the counter offer regarding dispute

// For fixed Project

$config['fixed_budget_project_dispute_make_counter_offer_activity_log_displayed_message_sent_to_creator'] = 'FIXED:You created the counter offer <span  class="touch_line_break">{counter_amount_value}</span> for dispute case {dispute_reference_id} opened on project <a href="{project_url_link}" target="_blank">{project_title}</a> for <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

$config['fixed_budget_project_dispute_make_counter_offer_activity_log_displayed_message_sent_to_other_party'] = 'FIXED:You received a new counter offer of <span  class="touch_line_break">{counter_amount_value}</span> from <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> dispute case {dispute_reference_id} opened on project <a href="{project_url_link}" target="_blank">{project_title}</a>';

// For hourly Project

$config['hourly_rate_based_project_dispute_make_counter_offer_activity_log_displayed_message_sent_to_creator'] = 'HOURLY:You created the counter offer <span  class="touch_line_break">{counter_amount_value}</span> for dispute case {dispute_reference_id} opened on project <a href="{project_url_link}" target="_blank">{project_title}</a> for <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

$config['hourly_rate_based_project_dispute_make_counter_offer_activity_log_displayed_message_sent_to_other_party'] = 'HOURLY:You received a new counter offer of <span  class="touch_line_break">{counter_amount_value}</span> from <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> dispute case {dispute_reference_id} opened on project <a href="{project_url_link}" target="_blank">{project_title}</a>';

// For fulltime Project
$config['fulltime_project_dispute_make_counter_offer_activity_log_displayed_message_sent_to_creator'] = 'Fulltime:You created the counter offer <span  class="touch_line_break">{counter_amount_value}</span> for dispute case {dispute_reference_id} opened on project <a href="{project_url_link}" target="_blank">{project_title}</a> for <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

$config['fulltime_project_dispute_make_counter_offer_activity_log_displayed_message_sent_to_other_party'] = 'Fulltime:You received a new counter offer of <span  class="touch_line_break">{counter_amount_value}</span> from <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> dispute case {dispute_reference_id} opened on project <a href="{project_url_link}" target="_blank">{project_title}</a>';
// Activity Log message when dipsute is decided by admin arbitartion and Po is winner of dispute


// For Fixed budget project
$config['fixed_budget_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_po_winner_user_activity_log_displayed_message'] = 
'FIXED:dispute case {dispute_reference_id} was closed by admin.You won the dispute against <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>. Total disputed amount was <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fixed_budget_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred back to you, together with associated service fees <span  class="touch_line_break">{fixed_budget_project_disputed_amount_service_fees}</span> and <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged by admin as dsipute arbitartion fees.';

$config['fixed_budget_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_male_po_winner_user_activity_log_displayed_message'] = 
'FIXED-Male PO:dispute case {dispute_reference_id} was closed by admin.You loss the dispute against <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> therefore <span  class="touch_line_break">{fixed_budget_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a>';

$config['fixed_budget_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_female_po_winner_user_activity_log_displayed_message'] = 
'FIXED-Female PO:dispute case {dispute_reference_id} was closed by admin.You loss the dispute against <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> therefore <span  class="touch_line_break">{fixed_budget_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a>';

$config['fixed_budget_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_company_po_winner_user_activity_log_displayed_message'] = 
'FIXED-Company PO:dispute case {dispute_reference_id} was closed by admin.You loss the dispute against <a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> therefore <span  class="touch_line_break">{fixed_budget_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{po_profile_url_link}" target="_blank">{user_company_name}</a>';


// For Hourly project
$config['hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_po_winner_user_activity_log_displayed_message'] = 
'Hourly:dispute case {dispute_reference_id} was closed by admin.You won the dispute against <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>. Total disputed amount was <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred back to you, together with associated service fees <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_service_fees}</span> and <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged by admin as dsipute arbitartion fees.';

$config['hourly_rate_based_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_male_po_winner_user_activity_log_displayed_message'] = 
'Hourly-Male PO:dispute case {dispute_reference_id} was closed by admin.You loss the dispute against <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> therefore <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a>';

$config['hourly_rate_based_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_female_po_winner_user_activity_log_displayed_message'] = 
'Hourly-Female PO:dispute case {dispute_reference_id} was closed by admin.You loss the dispute against <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> therefore <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a>';

$config['hourly_rate_based_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_company_po_winner_user_activity_log_displayed_message'] = 
'Hourly-Company PO:dispute case {dispute_reference_id} was closed by admin.You loss the dispute against <a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> therefore <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{po_profile_url_link}" target="_blank">{user_company_name}</a>';


// For fulltime project
$config['fulltime_project_message_sent_to_employer_when_dispute_project_decided_admin_arbitration_employer_winner_user_activity_log_displayed_message'] = 
'Fuultime:dispute case {dispute_reference_id} was closed by admin.You won the dispute against <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>. Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred back to you, together with associated service fees <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> and <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged by admin as dsipute arbitartion fees.';

$config['fulltime_project_message_sent_to_employee_when_dispute_project_decided_admin_arbitration_male_employer_winner_user_activity_log_displayed_message'] = 
'Fuultime-Male PO:dispute case {dispute_reference_id} was closed by admin.You loss the dispute against <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> therefore <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a>';

$config['fulltime_project_message_sent_to_employee_when_dispute_project_decided_admin_arbitration_female_employer_winner_user_activity_log_displayed_message'] = 
'Fuultime-Female PO:dispute case {dispute_reference_id} was closed by admin.You loss the dispute against <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> therefore <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a>';

$config['fulltime_project_message_sent_to_employee_when_dispute_project_decided_admin_arbitration_company_employer_winner_user_activity_log_displayed_message'] = 
'Fuultime-Company PO:dispute case {dispute_reference_id} was closed by admin.You loss the dispute against <a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> therefore <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{po_profile_url_link}" target="_blank">{user_company_name}</a>';




// Activity Log message when dipsute is decided by admin arbitartion and Sp is winner of dispute and project is completed

// send to sp when project is completed

$config['fixed_budget_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_project_completed_sp_winner_user_activity_log_displayed_message'] = 'congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

//send to po when project is completed
$config['fixed_budget_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_project_completed_male_sp_winner_user_activity_log_displayed_message'] = 'Male:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a>';

$config['fixed_budget_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_project_completed_female_sp_winner_user_activity_log_displayed_message'] = 'FeMale:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a>';


$config['fixed_budget_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_project_completed_company_sp_winner_user_activity_log_displayed_message'] = 'Male:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a>';

// For hourly budget

$config['hourly_rate_based_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_project_completed_sp_winner_user_activity_log_displayed_message'] = 'HOURLY:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

//send to po when project is completed
$config['hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_project_completed_male_sp_winner_user_activity_log_displayed_message'] = 'HOURLY:Male SP:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a>';

$config['hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_project_completed_female_sp_winner_user_activity_log_displayed_message'] = 'HOURLY:Female SP:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a>';


$config['hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_project_completed_company_sp_winner_user_activity_log_displayed_message'] = 'HOURLY:Company SP:congratulations, your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" s now completed with <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a>';

// Activity Log message when dipsute is decided by admin arbitartion and Sp is winner of dispute


// For fixed
// for Sp
$config['fixed_budget_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_sp_winner_user_activity_log_displayed_message'] = 'FIXED:dispute case {dispute_reference_id} was closed by admin.You won the dispute against <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> therefore <span  class="touch_line_break">{fixed_budget_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to your account';

// For po when sp is male 
$config['fixed_budget_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_male_sp_winner_user_activity_log_displayed_message'] = 'FIXED:Male sp:dispute case {dispute_reference_id} was closed by admin.You lost the dispute against <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a>. Total disputed amount was <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fixed_budget_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a>.Bussiness service fees <span  class="touch_line_break">{fixed_budget_project_disputed_amount_service_fees}</span> and dispute arbitartion fees. <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged from you';

// For po when sp is female 
$config['fixed_budget_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_female_sp_winner_user_activity_log_displayed_message'] = 'FIXED:feMale sp:dispute case {dispute_reference_id} was closed by admin.You lost the dispute against <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a>. Total disputed amount was <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fixed_budget_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a>.Bussiness service fees {fixed_budget_project_disputed_amount_service_fees} and dispute arbitartion fees. <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged from you';

// For po when sp is company 
$config['fixed_budget_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_company_sp_winner_user_activity_log_displayed_message'] = 'FIXED:company sp:dispute case {dispute_reference_id} was closed by admin.You lost the dispute against <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a>. Total disputed amount was <span  class="touch_line_break">{fixed_budget_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fixed_budget_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a>.Bussiness service fees <span  class="touch_line_break">{fixed_budget_project_disputed_amount_service_fees}</span> and dispute arbitartion fees. <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged from you';


// For hourly
// for Sp
$config['hourly_rate_based_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_sp_winner_user_activity_log_displayed_message'] = 'HOURLY:dispute case {dispute_reference_id} was closed by admin.You won the dispute against <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> therefore <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to your account';

// For po when sp is male 
$config['hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_male_sp_winner_user_activity_log_displayed_message'] = 'HOURLY:Male sp:dispute case {dispute_reference_id} was closed by admin.You lost the dispute against <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a>. Total disputed amount was <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a>.Bussiness service fees <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_service_fees}</span> and dispute arbitartion fees. <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged from you';

// For po when sp is female 
$config['hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_female_sp_winner_user_activity_log_displayed_message'] = 'HOURLY:feMale sp:dispute case {dispute_reference_id} was closed by admin.You lost the dispute against <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a>. Total disputed amount was <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a>.Bussiness service fees {hourly_rate_based_project_disputed_amount_service_fees} and dispute arbitartion fees. <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged from you';

// For po when sp is company 
$config['hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_company_sp_winner_user_activity_log_displayed_message'] = 'HOURLY:company sp:dispute case {dispute_reference_id} was closed by admin.You lost the dispute against <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a>. Total disputed amount was <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a>.Bussiness service fees <span  class="touch_line_break">{hourly_rate_based_project_disputed_amount_service_fees}</span> and dispute arbitartion fees. <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged from you';

// For fulltime project
// for Sp
$config['fulltime_project_message_sent_to_employee_when_dispute_project_decided_admin_arbitration_employee_winner_user_activity_log_displayed_message'] = 'Fulltime:dispute case {dispute_reference_id} was closed by admin.You won the dispute against <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> therefore <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to your account';

// For po when sp is male 
$config['fulltime_project_message_sent_to_employer_when_dispute_project_decided_admin_arbitration_male_employee_winner_user_activity_log_displayed_message'] = 'Fulltime:Male sp:dispute case {dispute_reference_id} was closed by admin.You lost the dispute against <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a>. Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a>.Bussiness service fees <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> and dispute arbitartion fees. <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged from you';

// For po when sp is female 
$config['fulltime_project_message_sent_to_employer_when_dispute_project_decided_admin_arbitration_female_employee_winner_user_activity_log_displayed_message'] = 'Fulltime:feMale sp:dispute case {dispute_reference_id} was closed by admin.You lost the dispute against <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a>. Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a>.Bussiness service fees {fulltime_project_disputed_amount_service_fees} and dispute arbitartion fees. <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged from you';

// For po when sp is company 
$config['fulltime_project_message_sent_to_employer_when_dispute_project_decided_admin_arbitration_company_employee_winner_user_activity_log_displayed_message'] = 'Fulltime:company sp:dispute case {dispute_reference_id} was closed by admin.You lost the dispute against <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a>. Total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, therefore <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a>.Bussiness service fees <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> and dispute arbitartion fees. <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span> charged from you';



################### For dispute page management #########
// Config for dispute management page for closed dispute tab
// dispute initaited and cancelled by PO

// For fixed/hourly project

// Po view
$config['user_projects_disputes_management_page_dispute_cancelled_by_po_initiator_po_view_reason_txt'] = 'CZ:cancelled by initiator PO - PO view - You decided to cancel this dispute. Therefore, the disputed amount <span  class="touch_line_break">{project_disputed_amount}</span> was released to {other_party_first_name_last_name_or_company_name} account balance. The related service fees of <span  class="touch_line_break">{project_disputed_amount_service_fees}</span>, was charged by business.';
// Sp view
$config['user_projects_disputes_management_page_dispute_cancelled_by_male_po_initiator_sp_view_reason_txt'] = 'CZ:cancelled by initiator PO - SP view - Male:{po_first_name_last_name} decided to cancel this dispute. Therefore, the disputed amount <span  class="touch_line_break">{project_disputed_amount}</span>was released to you account balance.';
$config['user_projects_disputes_management_page_dispute_cancelled_by_female_po_initiator_sp_view_reason_txt'] = 'CZ:cancelled by initiator PO - SP view - Female:{po_first_name_last_name} decided to cancel this dispute. Therefore, the disputed amount <span  class="touch_line_break">{project_disputed_amount}</span>was released to you account balance.';
$config['user_projects_disputes_management_page_dispute_cancelled_by_company_po_initiator_sp_view_reason_txt'] = 'CZ:cancelled by initiator PO - SP view -Company:{po_company_name} decided to cancel this dispute. Therefore, the disputed amount <span  class="touch_line_break">{project_disputed_amount}</span>was released to you account balance.';

// For fulltime projects
//Po View
$config['user_projects_disputes_management_page_fulltime_project_dispute_cancelled_by_employer_initiator_employer_view_reason_txt'] = 'CZ:Fulltime->cancelled by initiator PO - PO view - You decided to cancel this dispute. Therefore, the disputed amount <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> was released to {other_party_first_name_last_name_or_company_name} account balance. The related service fees of <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span>, was charged by business.';

// SP View
$config['user_projects_disputes_management_page_fulltime_project_dispute_cancelled_by_male_employer_initiator_employee_view_reason_txt'] = 'CZ:Fulltime->cancelled by initiator PO - SP view - Male:{po_first_name_last_name} decided to cancel this dispute. Therefore, the disputed amount <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>was released to you account balance.';
$config['user_projects_disputes_management_page_fulltime_project_dispute_cancelled_by_female_employer_initiator_employee_view_reason_txt'] = 'CZ:Fulltime->cancelled by initiator PO - SP view - Female:{po_first_name_last_name} decided to cancel this dispute. Therefore, the disputed amount <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>was released to you account balance.';
$config['user_projects_disputes_management_page_fulltime_project_dispute_cancelled_by_company_employer_initiator_employee_view_reason_txt'] = 'CZ:Fulltime->cancelled by initiator PO - SP view -Company:{po_company_name} decided to cancel this dispute. Therefore, the disputed amount <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>was released to you account balance.';

// dispute initaited and cancelled by sp
// For fixed/hourly project
//sp view
$config['user_projects_disputes_management_page_dispute_cancelled_by_sp_initiator_sp_view_reason_txt'] = 'CZ:cancelled by initiator SP - SP view - You decided to cancel this dispute. Therefore, the disputed amount <span  class="touch_line_break">{project_disputed_amount}</span> Kc was returned to {other_party_first_name_last_name_or_company_name} account balance.';
// Po view
$config['user_projects_disputes_management_page_dispute_cancelled_by_male_sp_initiator_po_view_reason_txt'] = 'CZ:cancelled by initiator SP - PO view - Male:{sp_first_name_last_name} decided to cancel this dispute. Therefore, the disputed amount <span  class="touch_line_break">{project_disputed_amount}</span> together with the related service fee amount of <span  class="touch_line_break">{project_disputed_amount_service_fees}</span> was returned to your account balance.';

$config['user_projects_disputes_management_page_dispute_cancelled_by_female_sp_initiator_po_view_reason_txt'] = 'CZ:cancelled by initiator SP - PO view - Female:{sp_first_name_last_name} decided to cancel this dispute. Therefore, the disputed amount <span  class="touch_line_break">{project_disputed_amount}</span> together with the related service fee amount of <span  class="touch_line_break">{project_disputed_amount_service_fees}</span> was returned to your account balance.';

$config['user_projects_disputes_management_page_dispute_cancelled_by_company_sp_initiator_po_view_reason_txt'] = 'CZ:cancelled by initiator SP - PO view - Company:{sp_company_name} decided to cancel this dispute. Therefore, the disputed amount <span  class="touch_line_break">{project_disputed_amount}</span> together with the related service fee amount of <span  class="touch_line_break">{project_disputed_amount_service_fees}</span> was returned to your account balance.';

// For fulltime project
//sp view
$config['user_projects_disputes_management_page_fulltime_project_dispute_cancelled_by_employee_initiator_employee_view_reason_txt'] = 'CZ:Fulltime->cancelled by initiator SP - SP view - You decided to cancel this dispute. Therefore, the disputed amount <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> Kc was returned to {other_party_first_name_last_name_or_company_name} account balance.';

// Po view
$config['user_projects_disputes_management_page_fulltime_project_dispute_cancelled_by_male_employee_initiator_employer_view_reason_txt'] = 'CZ:Fulltime->cancelled by initiator SP - PO view - Male:{sp_first_name_last_name} decided to cancel this dispute. Therefore, the disputed amount <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> together with the related service fee amount of <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> was returned to your account balance.';
$config['user_projects_disputes_management_page_fulltime_project_dispute_cancelled_by_female_employee_initiator_employer_view_reason_txt'] = 'CZ:Fulltime->cancelled by initiator SP - PO view - Female:{sp_first_name_last_name} decided to cancel this dispute. Therefore, the disputed amount <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> together with the related service fee amount of <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> was returned to your account balance.';
$config['user_projects_disputes_management_page_fulltime_project_dispute_cancelled_by_company_employee_initiator_employer_view_reason_txt'] = 'CZ:Fulltime->cancelled by initiator SP - PO view - Company:{sp_company_name} decided to cancel this dispute. Therefore, the disputed amount <span  class="touch_line_break">{fulltime_project_disputed_amount}</span> together with the related service fee amount of <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> was returned to your account balance.';

// when po accept the counter offer

// for fixed/hourly project
// for Po
$config['user_projects_disputes_management_page_dispute_counter_offer_accepted_by_po_po_view_reason_txt'] = 'CZ:Accepted By Po:Po View ->This dispute was closed by you having an agreement with {sp_first_name_last_name_or_company_name} during the dispute negotiation time. Based on that agreement, you decided to release (pay) {sp_first_name_last_name_or_company_name} <span  class="touch_line_break">{project_counter_offer_value}</span>. As the total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, the remaining <span  class="touch_line_break">{project_remaining_amount}</span> was transferred back to your account balance. The total service fees related to the disputed amount was <span  class="touch_line_break">{service_fees_charged_from_po}</span>, therefore <span  class="touch_line_break">{service_fees_return_to_po}</span> was also returned to your balance account.';
// For sp

$config['user_projects_disputes_management_page_dispute_counter_offer_accepted_by_male_po_sp_view_reason_txt'] ='CZ:Accepted By Po[Male]: SP View ->This dispute was closed by you having an agreement with {po_first_name_last_name} during the dispute negotiation time. Based on that agreement, {po_first_name_last_name} decided to release (pay) you <span  class="touch_line_break">{project_counter_offer_value}</span>. As the total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, the remaining <span  class="touch_line_break">{project_remaining_amount}</span> was transferred back to {po_first_name_last_name} account balance.';
$config['user_projects_disputes_management_page_dispute_counter_offer_accepted_by_female_po_sp_view_reason_txt'] ='CZ:Accepted By Po[Female]: SP View ->This dispute was closed by you having an agreement with {po_first_name_last_name} during the dispute negotiation time. Based on that agreement, {po_first_name_last_name} decided to release (pay) you <span  class="touch_line_break">{project_counter_offer_value}</span>. As the total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, the remaining <span  class="touch_line_break">{project_remaining_amount}</span> was transferred back to {po_first_name_last_name} account balance.';
$config['user_projects_disputes_management_page_dispute_counter_offer_accepted_by_company_po_sp_view_reason_txt'] ='CZ:Accepted By Po[Company]: SP View ->This dispute was closed by you having an agreement with {po_company_name} during the dispute negotiation time. Based on that agreement, {po_company_name} decided to release (pay) you <span  class="touch_line_break">{project_counter_offer_value}</span>. As the total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, the remaining <span  class="touch_line_break">{project_remaining_amount}</span> was transferred back to {po_company_name} account balance.';

// for fulltime project

// for Po
$config['user_projects_disputes_management_page_fulltime_project_dispute_counter_offer_accepted_by_employer_employer_view_reason_txt'] = 'CZ:Fulltime->Accepted By Po:Po View ->This dispute was closed by you having an agreement with {sp_first_name_last_name_or_company_name} during the dispute negotiation time. Based on that agreement, you decided to release (pay) {sp_first_name_last_name_or_company_name} <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span>. As the total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, the remaining <span  class="touch_line_break">{fulltime_project_remaining_amount}</span> was transferred back to your account balance. The total service fees related to the disputed amount was <span  class="touch_line_break">{service_fees_charged_from_po}</span>, therefore <span  class="touch_line_break">{service_fees_return_to_po}</span> was also returned to your balance account.';
// For sp

$config['user_projects_disputes_management_page_fulltime_project_dispute_counter_offer_accepted_by_male_employer_employee_view_reason_txt'] ='CZ:Fulltime-Accepted By Po[Male]: SP View ->This dispute was closed by you having an agreement with {po_first_name_last_name} during the dispute negotiation time. Based on that agreement, {po_first_name_last_name} decided to release (pay) you <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span>. As the total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, the remaining <span  class="touch_line_break">{fulltime_project_remaining_amount}</span> was transferred back to {po_first_name_last_name} account balance.';

$config['user_projects_disputes_management_page_fulltime_project_dispute_counter_offer_accepted_by_female_employer_employee_view_reason_txt'] ='CZ:Fulltime-Accepted By Po[Female]: SP View ->This dispute was closed by you having an agreement with {po_first_name_last_name} during the dispute negotiation time. Based on that agreement, {po_first_name_last_name} decided to release (pay) you <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span>. As the total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, the remaining <span  class="touch_line_break">{fulltime_project_remaining_amount}</span> was transferred back to {po_first_name_last_name} account balance.';

$config['user_projects_disputes_management_page_fulltime_project_dispute_counter_offer_accepted_by_company_employer_employee_view_reason_txt'] ='CZ:Fulltime-Accepted By Po[Company]: SP View ->This dispute was closed by you having an agreement with {po_company_name} during the dispute negotiation time. Based on that agreement, {po_company_name} decided to release (pay) you <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span>. As the total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, the remaining <span  class="touch_line_break">{fulltime_project_remaining_amount}</span> was transferred back to {po_company_name} account balance.';

// when sp accept the counter offer
// for fixed/hourly project

// for SP
$config['user_projects_disputes_management_page_dispute_counter_offer_accepted_by_sp_sp_view_reason_txt'] ='CZ:Accepted By sp: SP View ->This dispute was closed by you having an agreement with {po_user_first_name_last_name_or_company_name} during the dispute negotiation time. You accepted the counter offer of {po_user_first_name_last_name_or_company_name} Based on that agreement, <span  class="touch_line_break">{project_counter_offer_value}</span> released to you . As the total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, the remaining <span  class="touch_line_break">{project_remaining_amount}</span> was transferred back to {po_user_first_name_last_name_or_company_name} account balance.';
// for Po
$config['user_projects_disputes_management_page_dispute_counter_offer_accepted_by_male_sp_po_view_reason_txt'] = 'CZ:Accepted By SP:Po View[Male] ->This dispute was closed by you having an agreement with {sp_first_name_last_name} during the dispute negotiation time. {sp_first_name_last_name} accepted your counter offer Based on that agreement,{project_counter_offer_value} released to {sp_first_name_last_name} . As the total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, the remaining <span  class="touch_line_break">{project_remaining_amount}</span> was transferred back to your account balance. The total service fees related to the disputed amount was <span  class="touch_line_break">{service_fees_charged_from_po}</span>, therefore <span  class="touch_line_break">{service_fees_return_to_po}</span> was also returned to your balance account.';
$config['user_projects_disputes_management_page_dispute_counter_offer_accepted_by_female_sp_po_view_reason_txt'] = 'CZ:Accepted By SP:Po View[Female] ->This dispute was closed by you having an agreement with {sp_first_name_last_name} during the dispute negotiation time. {sp_first_name_last_name} accepted your counter offer Based on that agreement,{project_counter_offer_value} released to {sp_first_name_last_name} . As the total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, the remaining <span  class="touch_line_break">{project_remaining_amount}</span> was transferred back to your account balance. The total service fees related to the disputed amount was <span  class="touch_line_break">{service_fees_charged_from_po}</span>, therefore <span  class="touch_line_break">{service_fees_return_to_po}</span> was also returned to your balance account.';
$config['user_projects_disputes_management_page_dispute_counter_offer_accepted_by_company_sp_po_view_reason_txt'] = 'CZ:Accepted By SP:Po View[Company] ->This dispute was closed by you having an agreement with {sp_company_name} during the dispute negotiation time. {sp_company_name} accepted your counter offer Based on that agreement,{project_counter_offer_value} released to {sp_company_name} . As the total disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, the remaining <span  class="touch_line_break">{project_remaining_amount}</span> was transferred back to your account balance. The total service fees related to the disputed amount was <span  class="touch_line_break">{service_fees_charged_from_po}</span>, therefore <span  class="touch_line_break">{service_fees_return_to_po}</span> was also returned to your balance account.';

// for fulltime project
// for SP
$config['user_projects_disputes_management_page_fulltime_project_dispute_counter_offer_accepted_by_employee_employee_view_reason_txt'] ='CZ:Fulltime-->Accepted By sp: SP View ->This dispute was closed by you having an agreement with {po_user_first_name_last_name_or_company_name} during the dispute negotiation time. You accepted the counter offer of {po_user_first_name_last_name_or_company_name} Based on that agreement, <span  class="touch_line_break">{fulltime_project_counter_offer_value}</span> released to you . As the total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, the remaining <span  class="touch_line_break">{fulltime_project_remaining_amount}</span> was transferred back to {po_user_first_name_last_name_or_company_name} account balance.';
// for Po
$config['user_projects_disputes_management_page_fulltime_project_dispute_counter_offer_accepted_by_male_employee_employer_view_reason_txt'] = 'CZ:Fulltime-->Accepted By SP:Po View[Male] ->This dispute was closed by you having an agreement with {sp_first_name_last_name} during the dispute negotiation time. {sp_first_name_last_name} accepted your counter offer Based on that agreement,{fulltime_project_counter_offer_value} released to {sp_first_name_last_name} . As the total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, the remaining <span  class="touch_line_break">{fulltime_project_remaining_amount}</span> was transferred back to your account balance. The total service fees related to the disputed amount was <span  class="touch_line_break">{service_fees_charged_from_po}</span>, therefore <span  class="touch_line_break">{service_fees_return_to_po}</span> was also returned to your balance account.';
$config['user_projects_disputes_management_page_fulltime_project_dispute_counter_offer_accepted_by_female_employee_employer_view_reason_txt'] = 'CZ:Fulltime-->Accepted By SP:Po View[Female] ->This dispute was closed by you having an agreement with {sp_first_name_last_name} during the dispute negotiation time. {sp_first_name_last_name} accepted your counter offer Based on that agreement,{fulltime_project_counter_offer_value} released to {sp_first_name_last_name} . As the total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, the remaining <span  class="touch_line_break">{fulltime_project_remaining_amount}</span> was transferred back to your account balance. The total service fees related to the disputed amount was <span  class="touch_line_break">{service_fees_charged_from_po}</span>, therefore <span  class="touch_line_break">{service_fees_return_to_po}</span> was also returned to your balance account.';
$config['user_projects_disputes_management_page_fulltime_project_dispute_counter_offer_accepted_by_company_employee_employer_view_reason_txt'] = 'CZ:Fulltime-->Accepted By SP:Po View[Company] ->This dispute was closed by you having an agreement with {sp_company_name} during the dispute negotiation time. {sp_company_name} accepted your counter offer Based on that agreement,{fulltime_project_counter_offer_value} released to {sp_company_name} . As the total disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, the remaining <span  class="touch_line_break">{fulltime_project_remaining_amount}</span> was transferred back to your account balance. The total service fees related to the disputed amount was <span  class="touch_line_break">{service_fees_charged_from_po}</span>, therefore <span  class="touch_line_break">{service_fees_return_to_po}</span> was also returned to your balance account.';

// when dispute is decided automatically

// for fixed/hourly budget
// for PO
$config['user_projects_disputes_management_page_when_dispute_project_decided_automatic_po_view_reason_txt'] = 'CZ:Po View: This dispute was closed by automatic decision. Therefore, as the disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, 50% of the disputed amount (<span class="touch_line_break">{project_50%_disputed_amount}</span>) was released to {other_party_first_name_last_name_or_company_name}, and the other 50% (<span class="touch_line_break">{project_50%_disputed_amount}</span>) was returned back to your balance account. As the total service fee related to the disputed amount was <span  class="touch_line_break">{project_disputed_amount_service_fees}</span> Kc 50% of this (<span  class="touch_line_break">{project_50%_disputed_amount_service_fees}) was also returned to your balance account.';

// for SP
$config['user_projects_disputes_management_page_when_dispute_project_decided_automatic_sp_view_reason_txt'] = 'CZ:Sp View: his dispute was closed by automatic decision. Therefore, as the disputed amount was <span  class="touch_line_break">{project_disputed_amount}</span>, 50% of the disputed amount (<span class="touch_line_break">{project_50%_disputed_amount}</span>) was released to your account balance. The other 50% ( <span class="touch_line_break">{project_50%_disputed_amount}</span>) was returned back to {other_party_first_name_last_name_or_company_name} balance account.';

// for fulltime project
// for PO
$config['user_projects_disputes_management_page_when_dispute_fulltime_project_decided_automatic_employer_view_reason_txt'] = 'CZ:Fulltime:->Po View: This dispute was closed by automatic decision. Therefore, as the disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, 50% of the disputed amount (<span class="touch_line_break">{fulltime_project_50%_disputed_amount}</span>) was released to {other_party_first_name_last_name_or_company_name}, and the other 50% (<span class="touch_line_break">{fulltime_project_50%_disputed_amount}</span>) was returned back to your balance account. As the total service fee related to the disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> Kc 50% of this (<span  class="touch_line_break">{fulltime_project_50%_disputed_amount_service_fees}) was also returned to your balance account.';
// for SP
$config['user_projects_disputes_management_page_when_dispute_fulltime_project_decided_automatic_employee_view_reason_txt'] = 'CZ:Fulltime:->Sp View: his dispute was closed by automatic decision. Therefore, as the disputed amount was <span  class="touch_line_break">{fulltime_project_disputed_amount}</span>, 50% of the disputed amount (<span class="touch_line_break">{fulltime_project_50%_disputed_amount}</span>) was released to your account balance. The other 50% ( <span class="touch_line_break">{fulltime_project_50%_disputed_amount}</span>) was returned back to {other_party_first_name_last_name_or_company_name} balance account.';

// when dispute is decided by admin
// Admin decided the winner to sp

// Sp view
// For fixed/hourly project
$config['user_projects_disputes_management_page_when_dispute_project_decided_admin_arbitration_sp_winner_sp_view_reason_txt'] = 'CZ:Admin Arbitartion - Sp View->SP winner:This dispute was closed at admin decision. The decision was in your favor. Therefore, the amount <span  class="touch_line_break">{project_disputed_amount_excluding_admin_arbitration_fee}</span> (disputed amount minus the admin mediation charges of <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span>) was transferred to your account balance';
// PO view
// For fixed/hourly project
$config['user_projects_disputes_management_page_when_dispute_project_decided_admin_arbitration_male_sp_winner_po_view_reason_txt'] = 'CZ:Admin Arbitartion - PO View->SP winner[Male]:This dispute was closed at admin decision. The decision was in {sp_first_name_last_name} favor. Therefore, the amount <span  class="touch_line_break">{project_disputed_amount_excluding_admin_arbitration_fee}</span> (disputed amount minus the admin mediation charges of <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span>) was transferred to {sp_first_name_last_name} account balance. The related service fees of <span  class="touch_line_break">{project_disputed_amount_service_fees}</span> was charged.';
$config['user_projects_disputes_management_page_when_dispute_project_decided_admin_arbitration_female_sp_winner_po_view_reason_txt'] = 'CZ:Admin Arbitartion - PO View->SP winner[Female]:This dispute was closed at admin decision. The decision was in {sp_first_name_last_name} favor. Therefore, the amount <span  class="touch_line_break">{project_disputed_amount_excluding_admin_arbitration_fee}</span> (disputed amount minus the admin mediation charges of <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span>) was transferred to {sp_first_name_last_name} account balance. The related service fees of <span  class="touch_line_break">{project_disputed_amount_service_fees}</span> was charged.';
$config['user_projects_disputes_management_page_when_dispute_project_decided_admin_arbitration_company_sp_winner_po_view_reason_txt'] = 'CZ:Admin Arbitartion - PO View->SP winner[Company]:This dispute was closed at admin decision. The decision was in {sp_company_name} favor. Therefore, the amount <span  class="touch_line_break">{project_disputed_amount_excluding_admin_arbitration_fee}</span> (disputed amount minus the admin mediation charges of <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span>) was transferred to {sp_company_name} account balance. The related service fees of <span  class="touch_line_break">{project_disputed_amount_service_fees}</span> was charged.';

// Sp view
// For fulltime project
$config['user_projects_disputes_management_page_when_dispute_fulltime_project_decided_admin_arbitration_employee_winner_employee_view_reason_txt'] = 'CZ:Fulltime:->Admin Arbitartion - Sp View->SP winner:This dispute was closed at admin decision. The decision was in your favor. Therefore, the amount <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> (disputed amount minus the admin mediation charges of <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span>) was transferred to your account balance';
// PO view
// For fulltime project
$config['user_projects_disputes_management_page_when_dispute_fulltime_project_decided_admin_arbitration_male_employee_winner_employer_view_reason_txt'] = 'CZ:Fulltime:->Admin Arbitartion - PO View->SP winner[Male]:This dispute was closed at admin decision. The decision was in {sp_first_name_last_name} favor. Therefore, the amount <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> (disputed amount minus the admin mediation charges of <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span>) was transferred to {sp_first_name_last_name} account balance. The related service fees of <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> was charged.';
$config['user_projects_disputes_management_page_when_dispute_fulltime_project_decided_admin_arbitration_female_employee_winner_employer_view_reason_txt'] = 'CZ:Fulltime:->Admin Arbitartion - PO View->SP winner[Female]:This dispute was closed at admin decision. The decision was in {sp_first_name_last_name} favor. Therefore, the amount <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> (disputed amount minus the admin mediation charges of <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span>) was transferred to {sp_first_name_last_name} account balance. The related service fees of <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> was charged.';

$config['user_projects_disputes_management_page_when_dispute_fulltime_project_decided_admin_arbitration_company_employee_winner_employer_view_reason_txt'] = 'CZ:Fulltime:->Admin Arbitartion - PO View->SP winner[Company]:This dispute was closed at admin decision. The decision was in {sp_company_name} favor. Therefore, the amount <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> (disputed amount minus the admin mediation charges of <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span>) was transferred to {sp_company_name} account balance. The related service fees of <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span> was charged.';

// Admin decided the winner to PO

// Po view
// For fixed/hourly project
$config['user_projects_disputes_management_page_when_dispute_project_decided_admin_arbitration_po_winner_po_view_reason_txt'] = 'CZ:Admin Arbitartion - PO View->PO winner:This dispute was closed at admin decision. The decision was in your favor. Therefore, the amount <span  class="touch_line_break">{project_disputed_amount_excluding_admin_arbitration_fee}</span> (disputed amount minus the admin mediation charges of <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span>), together with related service fees of <span  class="touch_line_break">{project_disputed_amount_service_fees}</span>, was transferred back to your account balance.';
//Sp view
// For fixed/hourly project
$config['user_projects_disputes_management_page_when_dispute_project_decided_admin_arbitration_male_po_winner_sp_view_reason_txt'] = 'CZ:Admin Arbitartion - SP View->PO winner[Male]:This dispute was closed at admin decision. The decision was in {po_first_name_last_name} favor. Therefore, the amount <span  class="touch_line_break">{project_disputed_amount_excluding_admin_arbitration_fee}</span> (disputed amount minus the admin mediation charges of <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span>)) was transferred back to poname account balance.';
$config['user_projects_disputes_management_page_when_dispute_project_decided_admin_arbitration_female_po_winner_sp_view_reason_txt'] = 'CZ:Admin Arbitartion - SP View->PO winner[Female]:This dispute was closed at admin decision. The decision was in {po_first_name_last_name} favor. Therefore, the amount <span  class="touch_line_break">{project_disputed_amount_excluding_admin_arbitration_fee}</span> (disputed amount minus the admin mediation charges of <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span>)) was transferred back to poname account balance.';
$config['user_projects_disputes_management_page_when_dispute_project_decided_admin_arbitration_company_po_winner_sp_view_reason_txt'] = 'CZ:Admin Arbitartion - SP View->PO winner[Company]:This dispute was closed at admin decision. The decision was in {po_company_name} favor. Therefore, the amount <span  class="touch_line_break">{project_disputed_amount_excluding_admin_arbitration_fee}</span> (disputed amount minus the admin mediation charges of <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span>)) was transferred back to poname account balance.';

// Po view
// For fulltime project
$config['user_projects_disputes_management_page_when_dispute_fulltime_project_decided_admin_arbitration_employer_winner_employer_view_reason_txt'] = 'CZ:Fulltime:->Admin Arbitartion - PO View->PO winner:This dispute was closed at admin decision. The decision was in your favor. Therefore, the amount <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> (disputed amount minus the admin mediation charges of <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span>), together with related service fees of <span  class="touch_line_break">{fulltime_project_disputed_amount_service_fees}</span>, was transferred back to your account balance.';
//Sp view
// For fulltime project
$config['user_projects_disputes_management_page_when_dispute_fulltime_project_decided_admin_arbitration_male_employer_winner_employee_view_reason_txt'] = 'CZ:Fulltime:->Admin Arbitartion - SP View->PO winner[Male]:This dispute was closed at admin decision. The decision was in {po_first_name_last_name} favor. Therefore, the amount <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> (disputed amount minus the admin mediation charges of <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span>)) was transferred back to poname account balance.';

$config['user_projects_disputes_management_page_when_dispute_fulltime_project_decided_admin_arbitration_female_employer_winner_employee_view_reason_txt'] = 'CZ:Fulltime:->Admin Arbitartion - SP View->PO winner[Female]:This dispute was closed at admin decision. The decision was in {po_first_name_last_name} favor. Therefore, the amount <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> (disputed amount minus the admin mediation charges of <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span>)) was transferred back to poname account balance.';

$config['user_projects_disputes_management_page_when_dispute_fulltime_project_decided_admin_arbitration_company_employer_winner_employee_view_reason_txt'] = 'CZ:Fulltime:->Admin Arbitartion - SP View->PO winner[Company]:This dispute was closed at admin decision. The decision was in {po_company_name} favor. Therefore, the amount <span  class="touch_line_break">{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> (disputed amount minus the admin mediation charges of <span  class="touch_line_break">{admin_dispute_arbitration_amount_fee}</span>)) was transferred back to poname account balance.';

?>