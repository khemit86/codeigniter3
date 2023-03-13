<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation menu name
$config['projects_management_left_nav_projects_pending_feedbacks'] = 'Čekající hodnocení';

// config is using on find professional/dashboard/profile page for showing number of reviews
$config['user_0_reviews_received'] = 'hodnocení';

$config['user_1_review_received'] = 'hodnocení';

$config['user_2_or_more_reviews_received'] = 'hodnocení';


// config is using on find professional under user avatar
$config['user_completed_projects'] = 'dokončené projekty';

// this config is using to display the number of project is completed by sp/number of fulltime project on which sp hires on project detail page bidder listing(active/awarded/completed/inprogress)
$config['project_details_page_user_completed_projects_as_sp'] = "dokončené projekty";

//USED ON FIND PROFESSIONALS AND PROJECTDETAILS PAGE FOR USER RATING
$config['project_details_page_male_user_hires_on_fulltime_projects_as_employee'] = 'zaměstnaný';
$config['project_details_page_female_user_hires_on_fulltime_projects_as_employee'] = 'zaměstnaná';
$config['project_details_page_company_user_hires_on_fulltime_projects_as_employee'] = 'zaměstnaní';


// Config for feedback received/given tab text under the feedback tab on project detail page
$config['projects_users_ratings_feedbacks_received_tab_project_detail'] = 'Přijatá hodnocení';

$config['projects_users_ratings_feedbacks_given_tab_project_detail'] = 'Odeslaná hodnocení';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_give_feedback_button_txt'] = 'Vytvořit hodnocení';

// Config for feedback and rating form headline/title

$config['projects_users_ratings_feedbacks_popup_modal_headline'] = 'Vytvořit hodnocení pro <a class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a> na projekt "<a class="default_popup_blue_text">{project_title}</a>"';

$config['fulltime_projects_users_ratings_feedbacks_popup_modal_headline'] = 'Vytvořit hodnocení pro <a class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a> pracovní pozici "<a class="default_popup_blue_text">{project_title}</a>"';

$config['projects_users_ratings_feedbacks_radio_button_label_yes'] = 'Ano';

$config['projects_users_ratings_feedbacks_radio_button_label_no'] = 'Ne';


$config['users_ratings_feedbacks_popup_rating_level_low_quality'] = 'Špatný';

$config['users_ratings_feedbacks_popup_rating_level_modarate_quality'] = 'Dobrý';

$config['users_ratings_feedbacks_popup_rating_level_high_quality'] = 'Výborný';


//popup feedback section - projects - fixed/hourly/fulltime - PO/SP view -for 100% sure to be tested
$config['projects_users_ratings_feedbacks_popup_feedback_po_view'] = 'Zkušenosti s tímto dodavatelem';
$config['projects_users_ratings_feedbacks_popup_feedback_sp_view'] = 'Zkušenosti s tímto zadavatelem';

//For tooltip message
$config['projects_users_ratings_feedbacks_popup_feedback_message_tooltip_po_view'] = "zde můžete napsat vše ohledně vaší spolupráce, jaké máte pocity, celkové zkušenosti, návrhy, zpětnou vazbu";
$config['projects_users_ratings_feedbacks_popup_feedback_message_tooltip_sp_view'] = "zde můžete napsat vše ohledně vaší spolupráce, jaké máte pocity, celkové zkušenosti, návrhy, zpětnou vazbu";

//popup feedback section - fulltime projects - fixed/hourly/fulltime - PO/SP view
$config['fulltime_projects_users_ratings_feedbacks_popup_feedback_employer_view'] = 'Zkušenosti s tímto zaměstnancem';
$config['fulltime_projects_users_ratings_feedbacks_popup_feedback_employee_view'] = 'Zkušenosti s tímto zaměstnavatelem';

//For tooltip message
$config['fulltime_projects_users_ratings_feedbacks_popup_feedback_message_tooltip_employer_view'] = "zde můžete napsat vše ohledně vaší spolupráce, jaké máte pocity, celkové zkušenosti, návrhy, zpětnou vazbu";
$config['fulltime_projects_users_ratings_feedbacks_popup_feedback_message_tooltip_employee_view'] = "zde můžete napsat vše ohledně vaší spolupráce, jaké máte pocity, celkové zkušenosti, návrhy, zpětnou vazbu";


// Feedback and rating form option for PO view (fixed/hourly projects)
$config['projects_users_ratings_feedbacks_project_delivered_within_agreed_budget_po_view'] = 'Realizace a dodání v dohodnutém rozpočtu';

$config['projects_users_ratings_feedbacks_work_delivered_within_agreed_time_slot_po_view'] = 'Dodání v dohodnutém termínu';

// Config using in feedback popup for project(po trying to given feedback to sp)
// One pair enable at a time both pair cannot enable at same time
// Based on him/her/it
/* $config['projects_users_ratings_feedbacks_would_you_hire_sp_male_again_po_view'] = 'Would you hire him again?';
$config['projects_users_ratings_feedbacks_would_you_hire_sp_female_again_po_view'] = 'Would you hire her again?';
$config['projects_users_ratings_feedbacks_would_you_hire_sp_company_again_po_view'] = 'Would you hire it again?';
$config['projects_users_ratings_feedbacks_would_you_hire_sp_company_app_male_again_po_view'] = 'App Male: Would you hire her again?';
$config['projects_users_ratings_feedbacks_would_you_hire_sp_company_app_female_again_po_view'] = 'App Female: Would you hire her again?';

$config['projects_users_ratings_feedbacks_would_you_recommend_sp_male_po_view'] = 'Would you recommend him?';
$config['projects_users_ratings_feedbacks_would_you_recommend_sp_female_po_view'] = 'Would you recommend her?';
$config['projects_users_ratings_feedbacks_would_you_recommend_sp_company_po_view'] = 'Would you recommend it?';
$config['projects_users_ratings_feedbacks_would_you_recommend_sp_company_app_male_po_view'] = 'App male: Would you recommend him?';
$config['projects_users_ratings_feedbacks_would_you_recommend_sp_company_app_female_po_view'] = 'App Female: Would you recommend her?'; */

// Based on username
$config['projects_users_ratings_feedbacks_would_you_hire_sp_male_again_po_view'] = 'Spolupracovali byste s {user_first_name_or_company_name} znovu?';

$config['projects_users_ratings_feedbacks_would_you_hire_sp_female_again_po_view'] = 'Spolupracovali byste s {user_first_name_or_company_name} znovu?';

$config['projects_users_ratings_feedbacks_would_you_hire_sp_company_again_po_view'] = 'Spolupracovali byste s {user_first_name_or_company_name} znovu?';

$config['projects_users_ratings_feedbacks_would_you_hire_sp_company_app_male_again_po_view'] = 'Spolupracovali byste s {user_first_name_or_company_name} znovu?';

$config['projects_users_ratings_feedbacks_would_you_hire_sp_company_app_female_again_po_view'] = 'Spolupracovali byste s {user_first_name_or_company_name} znovu?';


$config['projects_users_ratings_feedbacks_would_you_recommend_sp_male_po_view'] = 'Doporučili byste {user_first_name_or_company_name} svému okolí?';

$config['projects_users_ratings_feedbacks_would_you_recommend_sp_female_po_view'] = 'Doporučili byste {user_first_name_or_company_name} svému okolí?';

$config['projects_users_ratings_feedbacks_would_you_recommend_sp_company_po_view'] = 'Doporučili byste {user_first_name_or_company_name} svému okolí?';

$config['projects_users_ratings_feedbacks_would_you_recommend_sp_company_app_male_po_view'] = 'Doporučili byste {user_first_name_or_company_name} svému okolí?';

$config['projects_users_ratings_feedbacks_would_you_recommend_sp_company_app_female_po_view'] = 'Doporučili byste {user_first_name_or_company_name} svému okolí?';



$config['projects_users_ratings_feedbacks_quality_po_view'] = 'Kvalita služeb';

$config['projects_users_ratings_feedbacks_communication_po_view'] = 'Komunikační dovednosti';

$config['projects_users_ratings_feedbacks_expertise_po_view'] = 'Odbornost a důvěra';

$config['projects_users_ratings_feedbacks_professionalism_po_view'] = 'Profesionalita a pečlivost';

$config['projects_users_ratings_feedbacks_value_for_money_po_view'] = 'Hodnota dodaných služeb';


// Feedback and rating form option for SP view (fixed/hourly projects)
$config['projects_users_ratings_feedbacks_clarity_in_requirements_sp_view'] = 'Jasnost sdělení požadavků a potřeb';

$config['projects_users_ratings_feedbacks_communication_sp_view'] = 'Komunikační dovednosti';

$config['projects_users_ratings_feedbacks_payment_promptness_sp_view'] = 'Včasná úhrada';

// Config using in feedback popup for project(sp trying to given feedback to po)
// Below the pair is comment at a time. both pair cannot un coment at same time

// Based on him/her/it
/* $config['projects_users_ratings_feedbacks_would_you_work_again_with_po_male_sp_view'] = 'Would you work again with him?';
$config['projects_users_ratings_feedbacks_would_you_work_again_with_po_female_sp_view'] = 'Would you work again with her?';
$config['projects_users_ratings_feedbacks_would_you_work_again_with_po_company_sp_view'] = 'Would you work again with it?';
$config['projects_users_ratings_feedbacks_would_you_work_again_with_po_company_app_male_sp_view'] = 'App Male: Would you work again with him?';
$config['projects_users_ratings_feedbacks_would_you_work_again_with_po_company_app_female_sp_view'] = 'App Female Would you work again with her?';

$config['projects_users_ratings_feedbacks_would_you_recommend_po_male_sp_view'] = 'Would you recommend him to your family members, friends, business partners ?';
$config['projects_users_ratings_feedbacks_would_you_recommend_po_female_sp_view'] = 'Would you recommend her to your family members, friends, business partners ?';
$config['projects_users_ratings_feedbacks_would_you_recommend_po_company_sp_view'] = 'Would you recommend it to your family members, friends, business partners ?';
$config['projects_users_ratings_feedbacks_would_you_recommend_po_company_app_male_sp_view'] = 'App male: Would you recommend him to your family members, friends, business partners ?';
$config['projects_users_ratings_feedbacks_would_you_recommend_po_company_app_female_sp_view'] = 'App Female: Would you recommend her to your family members, friends, business partners ?'; */


// Based on username - CORRECT IS TO CONSIDER BASED ON THE SIDE WHO IS GIVING THE FEEDBACK - IS NOT DEPENDEDNT IF IS PO OR SP
//EXCEPT FOR MALE - ALL ARE TRANSLATED BUT NOT TESTED - 05.12.2020

$config['projects_users_ratings_feedbacks_would_you_work_again_with_po_male_sp_view'] = 'Pracovali byste znovu s {user_first_name_or_company_name}?';

$config['projects_users_ratings_feedbacks_would_you_work_again_with_po_female_sp_view'] = 'Pracovali byste znovu s {user_first_name_or_company_name}?';

$config['projects_users_ratings_feedbacks_would_you_work_again_with_po_company_sp_view'] = 'Pracovali byste znovu s {user_first_name_or_company_name}?';

$config['projects_users_ratings_feedbacks_would_you_work_again_with_po_company_app_male_sp_view'] = 'Pracovali byste znovu s {user_first_name_or_company_name}?';

$config['projects_users_ratings_feedbacks_would_you_work_again_with_po_company_app_female_sp_view'] = 'Pracovali byste znovu s {user_first_name_or_company_name}?';


$config['projects_users_ratings_feedbacks_would_you_recommend_po_male_sp_view'] = 'Doporučili byste {user_first_name_or_company_name} svému okolí?';

$config['projects_users_ratings_feedbacks_would_you_recommend_po_female_sp_view'] = 'Doporučili byste {user_first_name_or_company_name} svému okolí?';

$config['projects_users_ratings_feedbacks_would_you_recommend_po_company_sp_view'] = 'Doporučili byste {user_first_name_or_company_name} svému okolí?';

$config['projects_users_ratings_feedbacks_would_you_recommend_po_company_app_male_sp_view'] = 'Doporučili byste {user_first_name_or_company_name} svému okolí?';

$config['projects_users_ratings_feedbacks_would_you_recommend_po_company_app_female_sp_view'] = 'Doporučili byste {user_first_name_or_company_name} svému okolí?';


// Feedback and rating form option for employer view (fulltime projects)
$config['fulltime_projects_users_ratings_feedbacks_employee_demonstrates_effective_oral_verbal_communication_skills_employer_view'] = 'Ústní a verbální komunikační schopnosti';

$config['fulltime_projects_users_ratings_feedbacks_employee_work_quality_employer_view'] = 'Pracuje k dosáhnutí nejlepších výsledků';

$config['fulltime_projects_users_ratings_feedbacks_employee_self_motivated_employer_view'] = 'Přebírání dalších odpovědností';


$config['fulltime_projects_users_ratings_feedbacks_employee_working_relations_employer_view'] = 'Respektuje a spolupracuje efektivně s kolegy a firemními zákazníky';

$config['fulltime_projects_users_ratings_feedbacks_employee_demonstrates_flexibility_adaptability_employer_view'] = 'Prokazuje flexibilitu a přizpůsobivost';

$config['fulltime_projects_users_ratings_feedbacks_employee_solves_problems_employer_view'] = 'Přemýšlí o činnosti práce';

$config['fulltime_projects_users_ratings_feedbacks_employee_work_ethic_employer_view'] = 'Pracovní etika';

$config['fulltime_projects_users_ratings_feedbacks_employee_shows_interest_enthusiasm_for_work_employer_view'] = 'Má pozitivní přístup k práci';

$config['fulltime_projects_users_ratings_feedbacks_employee_demonstrates_competency_in_knowledge_skills_employer_view'] = 'Prokazuje kompetence v oblasti znalostí a dovedností požadovaných pro danou pozici';

$config['fulltime_projects_users_ratings_feedbacks_employee_demonstrates_levels_of_skill_knowledge_employer_view'] = 'Prokazuje úroveň dovedností a znalostí, které předčily pracovní požadavky';

$config['fulltime_projects_users_ratings_feedbacks_employee_dependable_and_reliable_employer_view'] = 'Prokazuje spolehlivost a lze důvěřovat při plnění úkolů';

$config['fulltime_projects_users_ratings_feedbacks_employee_properly_organizes_prioritizes_employer_view'] = 'Smysl pro organizaci své práce';

// Feedback and rating form option for employee view (fulltime projects)
$config['fulltime_projects_users_ratings_feedbacks_work_life_balance_feedback_employee_view'] = 'Rovnováha mezi pracovním a osobním životem';

$config['fulltime_projects_users_ratings_feedbacks_career_opportunities_employee_view'] = 'Příležitosti kariérního růstu';

$config['fulltime_projects_users_ratings_feedbacks_compensation_benefits_employee_view'] = 'Výhody a benefity';


$config['fulltime_projects_users_ratings_feedbacks_proper_training_support_mentorship_leadership_employee_view'] = 'Školení, podpora, mentorování a vedení';

$config['fulltime_projects_users_ratings_feedbacks_explained_job_responsibilities_expectation_employee_view'] = 'Jasně definované a vysvětlené pracovní povinnosti a očekávání';

$config['fulltime_projects_users_ratings_feedbacks_environment_encourages_expressing_sharing_ideas_innovation_employee_view'] = 'Zaměstnavatel podporuje iniciativu a sdílení nápadů, inovací a kreativity';

$config['fulltime_projects_users_ratings_feedbacks_safe_healthy_environment_employee_view'] = 'Zdravé pracovní klima a prostředí';


$config['fulltime_projects_users_ratings_feedbacks_recommend_this_company_employee_view'] = 'Doporučuji tohoto zaměstnavatele svému okolí';

$config['fulltime_projects_users_ratings_feedbacks_appreciated_right_level_employee_view'] = 'Mám pocit, že moje práce je oceňována na správné úrovni';

$config['fulltime_projects_users_ratings_feedbacks_empowered_take_extra_responsibilities_employee_view'] = 'Mám možnost získat další kompetence a odpovědnosti';

$config['fulltime_projects_users_ratings_feedbacks_recognition_work_achievements_employee_view'] = 'Získávám uznání za pracovní úspěchy';

$config['fulltime_projects_users_ratings_feedbacks_receive_regular_consistent_feedback_employee_view'] = 'Získávám pravidelné zpětné vazby od nadřízených';


// Conflict message when sp/po already given the feedback - NOT TESTED ONLY TRANSLATED - 05.12.202
$config['projects_users_ratings_feedbacks_po_sp_already_given_feedback'] = "Již jste odeslali hodnocení.";

$config['fulltime_projects_users_ratings_feedbacks_employer_employee_already_given_feedback'] = "Již jste odeslali hodnocení.";

//// CONFIG FOR VALIDATIONS/////// 
//Validation parameter for Feedback and rating form

// Validation messages for Feedback and rating form PO view(fixed/hourly project)
$config['projects_users_ratings_feedbacks_popup_project_delivered_within_agreed_budget_required_validation_message_po_view'] = "volba je povinná";

$config['projects_users_ratings_feedbacks_popup_work_delivered_within_agreed_time_slot_required_validation_message_po_view'] = "volba je povinná";

$config['projects_users_ratings_feedbacks_popup_would_you_hire_sp_again_required_validation_message_po_view'] = "volba je povinná";

$config['projects_users_ratings_feedbacks_popup_would_you_recommend_sp_required_validation_message_po_view'] = "volba je povinná";

$config['users_ratings_feedbacks_popup_project_quality_of_work_required_validation_message_po_view'] = "volba je povinná";

$config['users_ratings_feedbacks_popup_project_communication_required_validation_message_po_view'] = "volba je povinná";

$config['users_ratings_feedbacks_popup_project_expertise_required_validation_message_po_view'] = "volba je povinná";

$config['users_ratings_feedbacks_popup_project_professionalism_required_validation_message_po_view'] = "volba je povinná";

$config['users_ratings_feedbacks_popup_project_value_for_money_required_validation_message_po_view'] = "volba je povinná";

$config['projects_users_ratings_feedbacks_popup_feedback_required_validation_message'] = "volba je povinná";


// Validation messages for Feedback and rating form employer view(fulltime project)
$config['fulltime_projects_users_ratings_feedbacks_popup_employee_shows_interest_enthusiasm_for_work_required_validation_message_employer_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_employee_demonstrates_competency_in_knowledge_skills_required_validation_message_employer_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_employee_demonstrates_levels_of_skill_knowledge_required_validation_message_employer_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_employee_dependable_and_reliable_required_validation_message_employer_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_employee_properly_organizes_prioritizes_required_validation_message_employer_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_employee_demonstrates_effective_oral_verbal_communication_skills_required_validation_message_employer_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_employee_work_quality_required_validation_message_employer_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_employee_self_motivated_required_validation_message_employer_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_employee_working_relations_required_validation_message_employer_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_employee_demonstrates_flexibility_adaptability_required_validation_message_employer_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_employee_solves_problems_required_validation_message_employer_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_employee_work_ethic_required_validation_message_employer_view'] = 'volba je povinná';


// Validation messages for SP view(fixed/hourly projects)
$config['projects_users_ratings_feedbacks_popup_would_you_work_again_with_po_required_validation_message_sp_view'] = "volba je povinná";

$config['projects_users_ratings_feedbacks_popup_would_you_recommend_po_required_validation_message_sp_view'] = "volba je povinná";

$config['projects_users_ratings_feedbacks_popup_clarity_in_requirements_required_validation_message_sp_view'] = "volba je povinná";

$config['projects_users_ratings_feedbacks_popup_communication_required_validation_message_sp_view'] = "volba je povinná";

$config['users_ratings_feedbacks_popup_project_payment_promptness_required_validation_message_sp_view'] = "volba je povinná";


// Validation messages for employee view(fulltime)
$config['fulltime_projects_users_ratings_feedbacks_popup_appreciated_right_level_required_validation_message_employee_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_empowered_take_extra_responsibilities_required_validation_message_employee_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_recognition_work_achievements_required_validation_message_employee_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_receive_regular_consistent_feedback_required_validation_message_employee_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_work_life_balance_feedback_required_validation_message_employee_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_career_opportunities_required_validation_message_employee_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_compensation_benefits_required_validation_message_employee_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_proper_training_support_mentorship_leadership_required_validation_message_employee_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_explained_job_responsibilities_expectation_required_validation_message_employee_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_environment_encourages_expressing_sharing_ideas_innovation_required_validation_message_employee_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_safe_healthy_environment_required_validation_message_employee_view'] = 'volba je povinná';

$config['fulltime_projects_users_ratings_feedbacks_popup_recommend_this_company_required_validation_message_employee_view'] = 'volba je povinná';


// Config message shown under the feedback received tab under feedback tab on project detail page when other party give the feedback for hourly/fixed project.
$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_male_party_given_feedback'] = '{user_first_name_last_name} vytvořil hodnocení dne {feedback_provided_on_date}. Také vytvořte hodnocení.';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_female_party_given_feedback'] = '{user_first_name_last_name} vytvořila hodnocení dne {feedback_provided_on_date}. Také vytvořte hodnocení.';


$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_male_party_given_feedback'] = '{user_first_name_last_name} vytvořil hodnocení dne {feedback_provided_on_date}. Také vytvořte hodnocení.';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_female_party_given_feedback'] = 'App Female: {user_first_name_last_name} vytvořila hodnocení dne {feedback_provided_on_date}. Také vytvořte hodnocení.';


$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_party_given_feedback'] = '{user_company_name} vytvořili hodnocení dne {feedback_provided_on_date}. Také vytvořte hodnocení.';


// Config message shown under the feedback received tab under feedback tab on project detail page when other party give the feedback for fulltime project.
$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_male_party_given_feedback'] = 'Male: {user_first_name_last_name} vytvořil hodnocení dne {feedback_provided_on_date}. Také vytvořte hodnocení.';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_female_party_given_feedback'] = '{user_first_name_last_name} vytvořila hodnocení dne {feedback_provided_on_date}. Také vytvořte hodnocení.';


$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_male_party_given_feedback'] = 'App Male: {user_first_name_last_name} vytvořil hodnocení dne {feedback_provided_on_date}. Také vytvořte hodnocení.';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_female_party_given_feedback'] = 'App Female: {user_first_name_last_name} vytvořila hodnocení dne {feedback_provided_on_date}. Také vytvořte hodnocení.';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_party_given_feedback'] = 'Company: {user_company_name} vytvořili hodnocení dne {feedback_provided_on_date}. Také vytvořte hodnocení.';


// Config message shown under the feedback received tab under feedback tab on project detail page when other party not given the feedback for fixed/hourly project.
$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_other_male_party_not_given_feedback'] = '{user_first_name_last_name} ještě neposlal hodnocení';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_other_female_party_not_given_feedback'] = '{user_first_name_last_name} ještě neposlala hodnocení';


$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_male_party_not_given_feedback'] = '{user_first_name_last_name} ještě neposlal hodnocení';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_female_party_not_given_feedback'] = '{user_first_name_last_name} ještě neposlala hodnocení';


$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_party_not_given_feedback'] = '{user_company_name} ještě neposlali hodnocení';


// Config message shown under the feedback received tab under feedback tab on project detail page when other party not given the feedback for fulltime project.
//EXCEPT FOR MALE - ALL OTHER ARE ONLYTRANSLATED WITHOUT BEING TESTED = 05.12.2020
$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_male_party_not_given_feedback'] = '{user_first_name_last_name} ještě neobdržel žádné hodnocení';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_female_party_not_given_feedback'] = '{user_first_name_last_name} ještě neobdržela žádné hodnocení';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_male_party_not_given_feedback'] = '{user_first_name_last_name} ještě neobdržel žádné hodnocení';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_female_party_not_given_feedback'] = '{user_first_name_last_name} ještě neobdržela žádné hodnocení';


$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_party_not_given_feedback'] = '{user_company_name} ještě neobdrželi žádné hodnocení';



// config message for feedback received tab on project detail page for fixed/hourly project
$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_male_feedback_provider'] = '{user_first_name_last_name} vytvořil hodnocení dne {feedback_provided_on_date}';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_female_feedback_provider'] = '{user_first_name_last_name} vytvořila hodnocení dne {feedback_provided_on_date}';


$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_male_feedback_provider'] = 'App Male: {user_first_name_last_name} vytvořil hodnocení dne {feedback_provided_on_date}';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_female_feedback_provider'] = 'App Female: {user_first_name_last_name} vytvořila hodnocení dne {feedback_provided_on_date}';



$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_feedback_provider'] = '{user_company_name} vytvořili hodnocenídne {feedback_provided_on_date}';


// config message for feedback received tab on project detail page for fulltime project
$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_male_feedback_provider'] = 'Male: {user_first_name_last_name} vytvořil hodnocení dne {feedback_provided_on_date}';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_female_feedback_provider'] = '{user_first_name_last_name} vytvořila hodnocení dne {feedback_provided_on_date}';



$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_male_feedback_provider'] = 'App Male: {user_first_name_last_name} vytvořil hodnocení dne {feedback_provided_on_date} for fulltime';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_female_feedback_provider'] = 'app Female: {user_first_name_last_name} vytvořila hodnocení dne {feedback_provided_on_date} for fulltime';



$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_feedback_provider'] = 'Company: {user_company_name} vytvořili hodnocení dne {feedback_provided_on_date} for fulltime';


// config message for feedback given tab on project detail page for fixed/hourly project
$config['projects_users_ratings_feedbacks_given_tabs_project_details_page_feedback_message'] = 'vytvořeno dne {feedback_provided_on_date}';

// config message for feedback given tab on project detail page for fulltime
$config['fulltime_projects_users_ratings_feedbacks_given_tabs_project_details_page_feedback_message'] = 'vytvořeno dne {feedback_provided_on_date}';




// Activity log message when PO given the feedback to SP (for fixed/hourly projects)
$config['projects_rating_feedbacks_message_sent_to_po_when_given_feedback_user_activity_log_displayed_message'] = '
Vytvořili jste hodnocení pro <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['projects_rating_feedbacks_message_sent_to_sp_when_po_male_given_feedback_user_activity_log_displayed_message'] = 'Obdrželi jste hodnocení od <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['projects_rating_feedbacks_message_sent_to_sp_when_po_female_given_feedback_user_activity_log_displayed_message'] = 'Obdrželi jste hodnocení od <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['projects_rating_feedbacks_message_sent_to_sp_when_po_company_app_male_given_feedback_user_activity_log_displayed_message'] = 'Obdrželi jste hodnocení od <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['projects_rating_feedbacks_message_sent_to_sp_when_po_company_app_female_given_feedback_user_activity_log_displayed_message'] = 'Obdrželi jste hodnocení od <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['projects_rating_feedbacks_message_sent_to_sp_when_po_company_given_feedback_user_activity_log_displayed_message'] = 'Obdrželi jste hodnocení od <a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


// Activity log message when employer given the feedback to employee (for fulltime projects)
$config['fulltime_projects_rating_feedbacks_message_sent_to_employer_when_given_feedback_user_activity_log_displayed_message'] = 'Vytvořili jste hodnocení pro <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_male_given_feedback_user_activity_log_displayed_message'] = 'Male:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořil hodnocení na"<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_female_given_feedback_user_activity_log_displayed_message'] = 'Female:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořila hodnocení na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_company_app_male_given_feedback_user_activity_log_displayed_message'] = 'App Male:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořil hodnocení na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_company_app_female_given_feedback_user_activity_log_displayed_message'] = 'App Female:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořila hodnocení na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_company_given_feedback_user_activity_log_displayed_message'] = 'Company:<a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> vytvořili hodnocení na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


// Activity log message when SP given the feedback to PO
$config['projects_rating_feedbacks_message_sent_to_sp_when_given_feedback_user_activity_log_displayed_message'] = '
Vytvořili jste hodnocení pro <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['projects_rating_feedbacks_message_sent_to_po_when_sp_male_given_feedback_user_activity_log_displayed_message'] = 'Obdrželi jste hodnocení od <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['projects_rating_feedbacks_message_sent_to_po_when_sp_female_given_feedback_user_activity_log_displayed_message'] = 'Obdrželi jste hodnocení od <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';



$config['projects_rating_feedbacks_message_sent_to_po_when_sp_company_app_male_given_feedback_user_activity_log_displayed_message'] = 'App male: Obdrželi jste hodnocení od <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['projects_rating_feedbacks_message_sent_to_po_when_sp_company_app_female_given_feedback_user_activity_log_displayed_message'] = 'Obdrželi jste hodnocení od <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';



$config['projects_rating_feedbacks_message_sent_to_po_when_sp_company_given_feedback_user_activity_log_displayed_message'] = 'Obdrželi jste hodnocení od <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


// Activity log message when employee given the feedback to employer
$config['fulltime_projects_rating_feedbacks_message_sent_to_employee_when_given_feedback_user_activity_log_displayed_message'] = 'Vytvořili jste hodnocení pro <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';



$config['fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_male_given_feedback_user_activity_log_displayed_message'] = 'Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořil hodnocení na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_female_given_feedback_user_activity_log_displayed_message'] = 'Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořila hodnocení na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';



$config['fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_company_app_male_given_feedback_user_activity_log_displayed_message'] = 'App Male: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořil hodnocení na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_company_app_female_given_feedback_user_activity_log_displayed_message'] = 'App Female: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořila hodnocení na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_company_given_feedback_user_activity_log_displayed_message'] = 'Company:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> vytvořili hodnocení na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


// When hourly project is complete after given feedback
// activity log message
$config['hourly_rate_based_project_message_sent_to_sp_when_project_completed_user_activity_log_displayed_message'] = 'Projekt "<a href="{project_url_link}" target="_blank">{project_title}</a>" je dokončený.';

$config['hourly_rate_based_project_message_sent_to_po_when_project_completed_user_activity_log_displayed_message'] = 'Projekt "<a href="{project_url_link}" target="_blank">{project_title}</a>" je dokončený.';


$config['hourly_rate_based_project_realtime_notification_message_sent_to_sp_when_project_completed'] = 'projekt "<a href="{project_url_link}" target="_blank">{project_title}</a>" je dokončený';
$config['hourly_rate_based_project_realtime_notification_message_sent_to_po_when_project_completed'] = 'projekt "<a href="{project_url_link}" target="_blank">{project_title}</a>" je dokončený';


//Config of tabs under feedback tab on user profile page 
$config['user_profile_page_ratings_feedbacks_on_projects_tab'] = 'Projekty';

$config['user_profile_page_ratings_feedbacks_on_projects_as_sp_tab'] = 'Poskytovatel';

$config['user_profile_page_ratings_feedbacks_on_projects_as_po_tab'] = 'Zadavatel';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_tab'] = 'Pracovní pozice';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab'] = 'Zaměstnanec';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab'] = 'Zaměstnavatel';


$config['user_profile_page_feedbacks_tab_review_posted_on_date_txt'] = "<b>hodnocení zveřejněno {feedback_provided_on_date}</b> od<a href='{user_profile_page_url}' target='_blank'>{user_first_name_last_name_or_company_name}</a>";


//Heading of As service provider tab under feedback tab on user profile page
$config['user_profile_page_ratings_total_avg_rating_and_reviews_as_sp_txt'] = 'přijato {total_projects_reviews} jako poskytovatel a průměrné hodnocení je {project_user_total_avg_rating_as_sp} z 5.00';

//Heading of As project owner tab under feedback tab on user profile page
$config['user_profile_page_ratings_total_avg_rating_and_reviews_as_po_txt'] = 'přijato {total_projects_reviews} jako zadavatel a průměrné hodnocení je {project_user_total_avg_rating_as_po} z 5.00';


//Heading of As employee tab under feedback tab on user profile page
$config['user_profile_page_ratings_total_avg_rating_and_reviews_as_employee_txt'] = 'přijato {total_fulltime_projects_reviews} jako zaměstnanec a průměrné hodnocení je {project_user_total_avg_rating_as_employee} z 5.00';

//Heading of As employer tab under feedback tab on user profile page
$config['user_profile_page_ratings_total_avg_rating_and_reviews_as_employer_txt'] = 'přijato {total_fulltime_projects_reviews} jako zaměstnavatel a průměrné hodnocení je {project_user_total_avg_rating_as_employer} z 5.00';


// config for as service provider tab under feedbacks tab on profile page when there is no feedback
$config['user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_male_visitor_view'] = '{user_first_name_last_name} ještě nemá žádné hodnocení jako poskytovatel';

$config['user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_female_visitor_view'] = '{user_first_name_last_name} ještě nemá žádné hodnocení jako poskytovatelka';


$config['user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_company_app_male_visitor_view'] = '{user_first_name_last_name} ještě nemá žádné hodnocení jako poskytovatel';

$config['user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_company_app_female_visitor_view'] = '{user_first_name_last_name} ještě nemá žádné hodnocení jako poskytovatelka';



$config['user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_company_visitor_view'] = '{user_company_name} ještě nemají žádné hodnocení jako poskytovatelé';


$config['user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_sp_view'] = 'ještě nemáte žádné hodnocení';


// config for as project owner tab under feedbacks tab on profile page when there is no feedback
$config['user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_male_visitor_view'] = '{user_first_name_last_name} ještě nemá žádné hodnocení jako zadavatel';

$config['user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_female_visitor_view'] = '{user_first_name_last_name} ještě nemá žádné hodnocení jako zadavatelka';

$config['user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_company_app_male_visitor_view'] = '{user_first_name_last_name} ještě nemá žádné hodnocení jako zadavatel';

$config['user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_company_app_female_visitor_view'] = '{user_first_name_last_name} ještě nemá žádné hodnocení jako zadavatelka';


$config['user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_company_visitor_view'] = '{user_company_name} ještě nemájí žádné hodnocení jako zadavatelé';

$config['user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_po_view'] = 'ještě nemáte žádné hodnocení';


// config for as employee tab under feedbacks tab on profile page when there is no feedback
$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_male_visitor_view'] = '{user_first_name_last_name} ještě nemá žádné hodnocení jako zaměstnanec';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_female_visitor_view'] = '{user_first_name_last_name} ještě nemá žádné hodnocení jako zaměstnankyně';


$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_company_app_male_visitor_view'] = '{user_first_name_last_name} ještě nemá žádné hodnocení jako zaměstnanec';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_company_app_female_visitor_view'] = '{user_first_name_last_name} ještě nemá žádné hodnocení jako zaměstnankyně';




$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_company_visitor_view'] = '{user_company_name} ještě nemají žádné hodnocení jako zaměstnanec';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_employee_view'] = 'ještě nemáte žádné hodnocení';


// config for as employer tab under feedbacks tab on profile page when there is no feedback
$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_male_visitor_view'] = '{user_first_name_last_name} ještě nemá žádné hodnocení jako zaměstnavatel';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_female_visitor_view'] = '{user_first_name_last_name} ještě nemá žádné hodnocení jako zaměstnavatelka';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_company_app_male_visitor_view'] = '{user_first_name_last_name} ještě nemá žádné hodnocení jako zaměstnavatel';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_company_app_female_visitor_view'] = '{user_first_name_last_name} ještě nemá žádné hodnocení jako zaměstnavatelka';



$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_company_visitor_view'] = '{user_company_name} ještě nemají žádné hodnocení jako zaměstnavatelé';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_employer_view'] = 'ještě nemáte žádné hodnocení';
#############################################
// config for reply functionality of rating and feedbacks on user profile page
// config of validation on reply functionality of rating and feedbacks on user profile page
$config['projects_users_ratings_feedbacks_reply_required_validation_message'] = "odpověď nemůže být prázdná";

$config['users_ratings_feedbacks_user_already_given_reply_on_feedback'] = "Odpověď již byla odeslána.";

$config['user_profile_page_users_ratings_feedbacks_reply_on_txt'] = "odpověď od {user_first_name_last_name_or_company_name} dne";

$config['pending_feedbacks_management_page_url'] = 'cekajici-hodnoceni';


// config are using for projects pending feedback page(which open from left navigation menu)
$config['user_projects_pending_ratings_feedbacks_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Čekající hodnocení';

$config['user_projects_pending_ratings_feedbacks_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Čekající hodnocení';

// This config is using on pending feedback overview page as a button text
$config['user_projects_pending_ratings_feedbacks_page_rate_button_txt'] = 'Hodnotit';

// config when singular records on pending feedback page
$config['user_projects_pending_ratings_feedbacks_page_project_name_txt'] = 'Název inzerátu';
$config['user_projects_pending_ratings_feedbacks_page_to_user_txt'] = 'Uživatel';
// config when plural records on pending feedback page
$config['user_projects_pending_ratings_feedbacks_page_projects_names_txt'] = 'Název inzerátů';
$config['user_projects_pending_ratings_feedbacks_page_to_users_txt'] = 'Uživatelé';


$config['user_projects_pending_ratings_feedbacks_page_headline_title'] = 'Čekající hodnocení projektů & zaměstnání';

$config['user_projects_pending_ratings_feedbacks_page_no_project_available_message'] = "Pro hodnocení není k dispozici žádný inzerát.";

?>