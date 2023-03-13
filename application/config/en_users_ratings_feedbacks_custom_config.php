<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation menu name
$config['projects_management_left_nav_projects_pending_feedbacks'] = 'Pending Feedbacks';

// config is using on find professional/dashboard/profile page for showing number of reviews
$config['user_0_reviews_received'] = '(0) hodnocení';
$config['user_1_review_received'] = '(1) hodnocení';
$config['user_2_or_more_reviews_received'] = '(2+) hodnocení';

// config is using on find professionals page under avatar
$config['user_completed_projects'] = 'completed projects:';

// this config is using to display the number of project is completed by sp/number of fulltime project on which sp hires on project detail page bidder listing(active/awarded/completed/inprogress)
$config['project_details_page_user_completed_projects_as_sp'] = "Completed Projects";

$config['project_details_page_male_user_hires_on_fulltime_projects_as_employee'] = '(M) Hires';
$config['project_details_page_female_user_hires_on_fulltime_projects_as_employee'] = '(F) Hires';
$config['project_details_page_company_user_hires_on_fulltime_projects_as_employee'] = '(Comp) Hires';


// Config for feedback received/given tab text under the feedback tab on project detail page
$config['projects_users_ratings_feedbacks_received_tab_project_detail'] = 'Feedback Received';
$config['projects_users_ratings_feedbacks_given_tab_project_detail'] = 'Feedback Given';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_give_feedback_button_txt'] = 'Give Feedback';

// Config for feedback and rating form headline/title
$config['projects_users_ratings_feedbacks_popup_modal_headline'] = '<span>Leave feedback and rate for</span><a class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a><span>on project</span><a class="default_popup_blue_text">{project_title}</a>';

$config['fulltime_projects_users_ratings_feedbacks_popup_modal_headline'] = '<span>Leave feedback and rate for</span><a class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a><span>on fulltime project</span><a class="default_popup_blue_text">{project_title}</a>';


//options in feedback modal
$config['projects_users_ratings_feedbacks_radio_button_label_yes'] = 'Yes';
$config['projects_users_ratings_feedbacks_radio_button_label_no'] = 'No';

$config['users_ratings_feedbacks_popup_rating_level_low_quality'] = 'Low Quality';
$config['users_ratings_feedbacks_popup_rating_level_modarate_quality'] = 'Modatrate Quality';
$config['users_ratings_feedbacks_popup_rating_level_high_quality'] = 'High Quality';



//popup feedback section - projects - fixed/hourly/fulltime - PO/SP view

$config['projects_users_ratings_feedbacks_popup_feedback_po_view'] = 'po view->Popis zkušenosti s tímto projektem';
$config['projects_users_ratings_feedbacks_popup_feedback_sp_view'] = 'sp view Popis zkušenosti s tímto projektem';

//For tooltip message
$config['projects_users_ratings_feedbacks_popup_feedback_message_tooltip_po_view'] = "po view->zde můžete napsat vše ohledně vaší spolupráce, jaké máte pocity, celkové zkušenosti, návrhy, zpětnou vazbu";
$config['projects_users_ratings_feedbacks_popup_feedback_message_tooltip_sp_view'] = "sp  view->zde můžete napsat vše ohledně vaší spolupráce, jaké máte pocity, celkové zkušenosti, návrhy, zpětnou vazbu";

//popup feedback section - fulltime projects - fixed/hourly/fulltime - PO/SP view
$config['fulltime_projects_users_ratings_feedbacks_popup_feedback_employer_view'] = 'Fulltime:Employer view->Popis zkušenosti s tímto projektem';
$config['fulltime_projects_users_ratings_feedbacks_popup_feedback_employee_view'] = 'Fulltime:Employee view->Popis zkušenosti s tímto projektem';

//For tooltip message
$config['fulltime_projects_users_ratings_feedbacks_popup_feedback_message_tooltip_employer_view'] = "Fulltime:Employer view->v popisu můžete uvést všechny dobré i špatné zkušenosti se svým zaměstnancem";
$config['fulltime_projects_users_ratings_feedbacks_popup_feedback_message_tooltip_employee_view'] = "Fulltime:Employee view->v popisu můžete uvést všechny dobré i špatné zkušenosti se svým zaměstnavatelem";



// Feedback and rating form option for PO view (fixed/hourly projects)
$config['projects_users_ratings_feedbacks_project_delivered_within_agreed_budget_po_view'] = 'project delivered within agreed budget';

$config['projects_users_ratings_feedbacks_work_delivered_within_agreed_time_slot_po_view'] = 'work delivered within agreed timeline';


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

$config['projects_users_ratings_feedbacks_would_you_hire_sp_male_again_po_view'] = 'Male:Would you hire {user_first_name_or_company_name} again?';
$config['projects_users_ratings_feedbacks_would_you_hire_sp_female_again_po_view'] = 'Female:Would you hire {user_first_name_or_company_name} again?';
$config['projects_users_ratings_feedbacks_would_you_hire_sp_company_again_po_view'] = 'Company:Would you hire {user_first_name_or_company_name} again?';
$config['projects_users_ratings_feedbacks_would_you_hire_sp_company_app_male_again_po_view'] = 'App Male: Would you hire {user_first_name_or_company_name} again?';
$config['projects_users_ratings_feedbacks_would_you_hire_sp_company_app_female_again_po_view'] = 'App Female: Would you hire {user_first_name_or_company_name} again?';

$config['projects_users_ratings_feedbacks_would_you_recommend_sp_male_po_view'] = 'Male:Would you recommend {user_first_name_or_company_name}?';
$config['projects_users_ratings_feedbacks_would_you_recommend_sp_female_po_view'] = 'Female:Would you recommend {user_first_name_or_company_name}?';
$config['projects_users_ratings_feedbacks_would_you_recommend_sp_company_po_view'] = 'Company:Would you recommend {user_first_name_or_company_name}?';
$config['projects_users_ratings_feedbacks_would_you_recommend_sp_company_app_male_po_view'] = 'App Male: Would you recommend {user_first_name_or_company_name}?';
$config['projects_users_ratings_feedbacks_would_you_recommend_sp_company_app_female_po_view'] = 'App Female:Would you recommend {user_first_name_or_company_name}?';

$config['projects_users_ratings_feedbacks_quality_po_view'] = 'Quality';

$config['projects_users_ratings_feedbacks_communication_po_view'] = 'Communication';

$config['projects_users_ratings_feedbacks_expertise_po_view'] = 'Expertise';

$config['projects_users_ratings_feedbacks_professionalism_po_view'] = 'Professionalism';

$config['projects_users_ratings_feedbacks_value_for_money_po_view'] = 'Value for money';


// Feedback and rating form option for SP view (fixed/hourly projects)
$config['projects_users_ratings_feedbacks_clarity_in_requirements_sp_view'] = 'Clarity in Specifications / Requirements';

$config['projects_users_ratings_feedbacks_communication_sp_view'] = 'Communication';

$config['projects_users_ratings_feedbacks_payment_promptness_sp_view'] = 'Payment Promptness';

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


// Based on username

$config['projects_users_ratings_feedbacks_would_you_work_again_with_po_male_sp_view'] = 'Male: Would you work again with {user_first_name_or_company_name}?';
$config['projects_users_ratings_feedbacks_would_you_work_again_with_po_female_sp_view'] = 'Female: Would you work again with {user_first_name_or_company_name}?';
$config['projects_users_ratings_feedbacks_would_you_work_again_with_po_company_sp_view'] = 'Company: Would you work again with {user_first_name_or_company_name}?';
$config['projects_users_ratings_feedbacks_would_you_work_again_with_po_company_app_male_sp_view'] = 'App Male: Would you work again with {user_first_name_or_company_name}?';
$config['projects_users_ratings_feedbacks_would_you_work_again_with_po_company_app_female_sp_view'] = 'App Female Would you work again with {user_first_name_or_company_name}?';

$config['projects_users_ratings_feedbacks_would_you_recommend_po_male_sp_view'] = 'Male: Would you recommend {user_first_name_or_company_name} to your family members, friends, business partners ?';
$config['projects_users_ratings_feedbacks_would_you_recommend_po_female_sp_view'] = 'Female: Would you recommend {user_first_name_or_company_name} to your family members, friends, business partners ?';
$config['projects_users_ratings_feedbacks_would_you_recommend_po_company_sp_view'] = 'Company: Would you recommend {user_first_name_or_company_name} to your family members, friends, business partners ?';
$config['projects_users_ratings_feedbacks_would_you_recommend_po_company_app_male_sp_view'] = 'App male: Would you recommend {user_first_name_or_company_name} to your family members, friends, business partners ?';
$config['projects_users_ratings_feedbacks_would_you_recommend_po_company_app_female_sp_view'] = 'App Female: Would you recommend {user_first_name_or_company_name} to your family members, friends, business partners ?'; 


// Feedback and rating form option for employer view (fulltime projects)
$config['fulltime_projects_users_ratings_feedbacks_employee_demonstrates_effective_oral_verbal_communication_skills_employer_view'] = 'Demonstrates effective oral and verbal communication skills';

$config['fulltime_projects_users_ratings_feedbacks_employee_work_quality_employer_view'] = 'Work quality - works across the organization to develop the best results';

$config['fulltime_projects_users_ratings_feedbacks_employee_self_motivated_employer_view'] = 'Self-motivated - takes on extra responsibilities';

$config['fulltime_projects_users_ratings_feedbacks_employee_working_relations_employer_view'] = 'Working relations - respects and works effectively with diverse people';

$config['fulltime_projects_users_ratings_feedbacks_employee_demonstrates_flexibility_adaptability_employer_view'] = 'Demonstrates flexibility and adaptability';

$config['fulltime_projects_users_ratings_feedbacks_employee_solves_problems_employer_view'] = 'Solves problems - makes decisions based on thorough analysis of problems';

$config['fulltime_projects_users_ratings_feedbacks_employee_work_ethic_employer_view'] = 'Work ethic';

$config['fulltime_projects_users_ratings_feedbacks_employee_shows_interest_enthusiasm_for_work_employer_view'] = 'Shows interest in and enthusiasm for work';

$config['fulltime_projects_users_ratings_feedbacks_employee_demonstrates_competency_in_knowledge_skills_employer_view'] = 'Demonstrates competency in knowledge and skills required for position';

$config['fulltime_projects_users_ratings_feedbacks_employee_demonstrates_levels_of_skill_knowledge_employer_view'] = 'Demonstrates levels of skill and knowledge that surpassed his/her job requirements';

$config['fulltime_projects_users_ratings_feedbacks_employee_dependable_and_reliable_employer_view'] = 'Is dependable and can be relied on to complete tasks as expected';

$config['fulltime_projects_users_ratings_feedbacks_employee_properly_organizes_prioritizes_employer_view'] = 'Properly organizes and prioritizes assigned tasks';


// Feedback and rating form option for employee view (fulltime projects)
$config['fulltime_projects_users_ratings_feedbacks_work_life_balance_feedback_employee_view'] = 'work/life balance';

$config['fulltime_projects_users_ratings_feedbacks_career_opportunities_employee_view'] = 'career opportunities';

$config['fulltime_projects_users_ratings_feedbacks_compensation_benefits_employee_view'] = 'compensation and benefits';

$config['fulltime_projects_users_ratings_feedbacks_proper_training_support_mentorship_leadership_employee_view'] = 'you get proper training, support, mentorship and leadership from management';

$config['fulltime_projects_users_ratings_feedbacks_explained_job_responsibilities_expectation_employee_view'] = 'clearly defined and explained job responsibilities and expectations';

$config['fulltime_projects_users_ratings_feedbacks_environment_encourages_expressing_sharing_ideas_innovation_employee_view'] = 'this is an environment that encourages expressing and sharing ideas, innovation, creativity and initiatives';

$config['fulltime_projects_users_ratings_feedbacks_safe_healthy_environment_employee_view'] = 'this is a safe and healthy working environment';

$config['fulltime_projects_users_ratings_feedbacks_recommend_this_company_employee_view'] = 'how likely to recommend this company/employer to others';

$config['fulltime_projects_users_ratings_feedbacks_appreciated_right_level_employee_view'] = 'is your work valued/appreciated at the right level?';

$config['fulltime_projects_users_ratings_feedbacks_empowered_take_extra_responsibilities_employee_view'] = 'i am empowered to take on extra responsibilities';

$config['fulltime_projects_users_ratings_feedbacks_recognition_work_achievements_employee_view'] = 'do you get recognition for work achievements';

$config['fulltime_projects_users_ratings_feedbacks_receive_regular_consistent_feedback_employee_view'] = 'do you receive regular and consistent feedback (on performance) from supervisors/managers/peers';

// Conflict message when sp/po already given the feedback
$config['projects_users_ratings_feedbacks_po_sp_already_given_feedback'] = "You have already given feedback on this project.";
$config['fulltime_projects_users_ratings_feedbacks_employer_employee_already_given_feedback'] = "You have already given feedback on this fulltime project.";


//// CONFIG FOR VALIDATIONS/////// 
//Validation parameter for Feedback and rating form
// Validation messages for Feedback and rating form PO view(fixed/hourly project)
$config['projects_users_ratings_feedbacks_popup_project_delivered_within_agreed_budget_required_validation_message_po_view'] = "Field is required(1)";
$config['projects_users_ratings_feedbacks_popup_work_delivered_within_agreed_time_slot_required_validation_message_po_view'] = "Field is required(2)";
$config['projects_users_ratings_feedbacks_popup_would_you_hire_sp_again_required_validation_message_po_view'] = "Field is required(3)";
$config['projects_users_ratings_feedbacks_popup_would_you_recommend_sp_required_validation_message_po_view'] = "Field is required(4)";
$config['users_ratings_feedbacks_popup_project_quality_of_work_required_validation_message_po_view'] = "Quality is required(5)";
$config['users_ratings_feedbacks_popup_project_communication_required_validation_message_po_view'] = "Communication is required(6)";
$config['users_ratings_feedbacks_popup_project_expertise_required_validation_message_po_view'] = "Expertise is required(7)";
$config['users_ratings_feedbacks_popup_project_professionalism_required_validation_message_po_view'] = "Professionalism is required(8)";
$config['users_ratings_feedbacks_popup_project_value_for_money_required_validation_message_po_view'] = "Value for money is required(9)";
$config['projects_users_ratings_feedbacks_popup_feedback_required_validation_message'] = "Feedback is required";


// Validation messages for Feedback and rating form employer view(fulltime project)
$config['fulltime_projects_users_ratings_feedbacks_popup_employee_shows_interest_enthusiasm_for_work_required_validation_message_employer_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_employee_demonstrates_competency_in_knowledge_skills_required_validation_message_employer_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_employee_demonstrates_levels_of_skill_knowledge_required_validation_message_employer_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_employee_dependable_and_reliable_required_validation_message_employer_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_employee_properly_organizes_prioritizes_required_validation_message_employer_view'] = 'Required(ft)';


$config['fulltime_projects_users_ratings_feedbacks_popup_employee_demonstrates_effective_oral_verbal_communication_skills_required_validation_message_employer_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_employee_work_quality_required_validation_message_employer_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_employee_self_motivated_required_validation_message_employer_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_employee_working_relations_required_validation_message_employer_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_employee_demonstrates_flexibility_adaptability_required_validation_message_employer_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_employee_solves_problems_required_validation_message_employer_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_employee_work_ethic_required_validation_message_employer_view'] = 'Required(ft)';


// Validation messages for SP view(fixed/hourly projects)
$config['projects_users_ratings_feedbacks_popup_would_you_work_again_with_po_required_validation_message_sp_view'] = "Field is required(1)";
$config['projects_users_ratings_feedbacks_popup_would_you_recommend_po_required_validation_message_sp_view'] = "Field is required(2)";
$config['projects_users_ratings_feedbacks_popup_clarity_in_requirements_required_validation_message_sp_view'] = "Field is required(3)";
$config['projects_users_ratings_feedbacks_popup_communication_required_validation_message_sp_view'] = "Field is required(4)";
$config['users_ratings_feedbacks_popup_project_payment_promptness_required_validation_message_sp_view'] = "Quality is required(5)";


// Validation messages for employee view(fulltime)
$config['fulltime_projects_users_ratings_feedbacks_popup_appreciated_right_level_required_validation_message_employee_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_empowered_take_extra_responsibilities_required_validation_message_employee_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_recognition_work_achievements_required_validation_message_employee_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_receive_regular_consistent_feedback_required_validation_message_employee_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_work_life_balance_feedback_required_validation_message_employee_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_career_opportunities_required_validation_message_employee_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_compensation_benefits_required_validation_message_employee_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_proper_training_support_mentorship_leadership_required_validation_message_employee_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_explained_job_responsibilities_expectation_required_validation_message_employee_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_environment_encourages_expressing_sharing_ideas_innovation_required_validation_message_employee_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_safe_healthy_environment_required_validation_message_employee_view'] = 'Required(ft)';
$config['fulltime_projects_users_ratings_feedbacks_popup_recommend_this_company_required_validation_message_employee_view'] = 'Required(ft)';


// Config message shown under the feedback received tab under feedback tab on project detail page when other party give the feedback for hourly/fixed project.
$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_male_party_given_feedback'] = 'Male: {user_first_name_last_name} vytvořil hodnocení {feedback_provided_on_date}. Také odešlete hodnocení.';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_female_party_given_feedback'] = 'Female: {user_first_name_last_name} vytvořila hodnocení hodnocení {feedback_provided_on_date}. Také odešlete hodnocení.';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_male_party_given_feedback'] = 'app Male: {user_first_name_last_name} vytvořil hodnocení {feedback_provided_on_date}. Také odešlete hodnocení.';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_female_party_given_feedback'] = 'app Female: {user_first_name_last_name} vytvořila hodnocení hodnocení {feedback_provided_on_date}. Také odešlete hodnocení.';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_party_given_feedback'] = 'Company: {user_company_name} vytvořila hodnocení hodnocení {feedback_provided_on_date}. Také odešlete hodnocení.';



// Config message shown under the feedback received tab under feedback tab on project detail page when other party give the feedback for fulltime project.
$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_male_party_given_feedback'] = 'Male: {user_first_name_last_name} placed feedback on date {feedback_provided_on_date}.Place your feedback too for fulltime project.';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_female_party_given_feedback'] = 'Female: {user_first_name_last_name} placed feedback on date {feedback_provided_on_date}.Place your feedback too for fulltime project.';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_male_party_given_feedback'] = 'App Male: {user_first_name_last_name} placed feedback on date {feedback_provided_on_date}.Place your feedback too for fulltime project.';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_female_party_given_feedback'] = 'App Female: {user_first_name_last_name} placed feedback on date {feedback_provided_on_date}.Place your feedback too for fulltime project.';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_party_given_feedback'] = 'Company: {user_company_name} placed feedback on date {feedback_provided_on_date}.Place your feedback too for fulltime project.';


// Config message shown under the feedback received tab under feedback tab on project detail page when other party not given the feedback for fixed/hourly project.
$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_other_male_party_not_given_feedback'] = 'Male: {user_first_name_last_name} ještě neposlal hodnocení.';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_other_female_party_not_given_feedback'] = 'Female: {user_first_name_last_name} ještě neposlala hodnocení.';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_male_party_not_given_feedback'] = 'App Male: {user_first_name_last_name} ještě neposlal hodnocení.';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_female_party_not_given_feedback'] = 'App Female: {user_first_name_last_name} ještě neposlala hodnocení.';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_party_not_given_feedback'] = 'Company: {user_company_name} ještě neposlala hodnocení.';


// Config message shown under the feedback received tab under feedback tab on project detail page when other party not given the feedback for fulltime project.
$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_male_party_not_given_feedback'] = 'Male: {user_first_name_last_name} not placed feedback for fulltime project.';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_female_party_not_given_feedback'] = 'Female: {user_first_name_last_name} not placed feedback for fulltime project.';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_male_party_not_given_feedback'] = 'App Male: {user_first_name_last_name} not placed feedback for fulltime project.';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_female_party_not_given_feedback'] = 'App Female: {user_first_name_last_name} not placed feedback for fulltime project.';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_party_not_given_feedback'] = 'Company: {user_company_name} not placed feedback for fulltime project.';


// config message for feedback received tab on project detail page for fixed/hourly project
$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_male_feedback_provider'] = 'Male: <label class="default_black_regular"><b class="default_black_bold" style="word-wrap: break-word;">{user_first_name_last_name}</b>feedback to you</label><label class="default_black_regular">sent on {feedback_provided_on_date}</label>';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_female_feedback_provider'] = 'Female: <label class="default_black_regular"><b class="default_black_bold" style="word-wrap: break-word;">{user_first_name_last_name}</b>feedback to you</label><label class="default_black_regular">sent on {feedback_provided_on_date}</label>';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_male_feedback_provider'] = 'App Male: <label class="default_black_regular"><b class="default_black_bold" style="word-wrap: break-word;">{user_first_name_last_name}</b>feedback to you</label><label class="default_black_regular">sent on {feedback_provided_on_date}</label>';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_female_feedback_provider'] = 'App Female: <label class="default_black_regular"><b class="default_black_bold" style="word-wrap: break-word;">{user_first_name_last_name}</b>feedback to you</label><label class="default_black_regular">sent on {feedback_provided_on_date}</label>';

$config['projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_feedback_provider'] = 'Company: <label class="default_black_regular"><b class="default_black_bold" style="word-wrap: break-word;">{user_company_name}</b>feedback to you</label><label class="default_black_regular">sent on {feedback_provided_on_date}</label>';


// config message for feedback received tab on project detail page for fulltime project
$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_male_feedback_provider'] = 'Male: <label class="default_black_regular"><b class="default_black_bold" style="word-wrap: break-word;">{user_first_name_last_name}</b>feedback to you</label><label class="default_black_regular">sent on {feedback_provided_on_date} for fulltime</label>';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_female_feedback_provider'] = 'Female: <label class="default_black_regular"><b class="default_black_bold" style="word-wrap: break-word;">{user_first_name_last_name}</b>feedback to you</label><label class="default_black_regular">sent on {feedback_provided_on_date} for fulltime</label>';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_male_feedback_provider'] = 'App Male: <label class="default_black_regular"><b class="default_black_bold" style="word-wrap: break-word;">{user_first_name_last_name}</b>feedback to you</label><label class="default_black_regular">sent on {feedback_provided_on_date} for fulltime</label>';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_female_feedback_provider'] = 'App Female: <label class="default_black_regular"><b class="default_black_bold" style="word-wrap: break-word;">{user_first_name_last_name}</b>feedback to you</label><label class="default_black_regular">sent on {feedback_provided_on_date} for fulltime</label>';

$config['fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_feedback_provider'] = 'Company: <label class="default_black_regular"><b class="default_black_bold" style="word-wrap: break-word;">{user_company_name}</b>feedback to you</label><label class="default_black_regular">sent on {feedback_provided_on_date} for fulltime</label>';



// config message for feedback given tab on project detail page for fixed/hourly project
$config['projects_users_ratings_feedbacks_given_tabs_project_details_page_feedback_message'] = '<label class="default_black_regular">you given feedback on {feedback_provided_on_date}</label>';

// config message for feedback given tab on project detail page for fulltime
$config['fulltime_projects_users_ratings_feedbacks_given_tabs_project_details_page_feedback_message'] = '<label class="default_black_regular">you given feedback on {feedback_provided_on_date} fulltime</label>';


// Activity log message when PO given the feedback to SP (for fixed/hourly projects)
$config['projects_rating_feedbacks_message_sent_to_po_when_given_feedback_user_activity_log_displayed_message'] = 'You left the feedback to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['projects_rating_feedbacks_message_sent_to_sp_when_po_male_given_feedback_user_activity_log_displayed_message'] = 'Male:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['projects_rating_feedbacks_message_sent_to_sp_when_po_female_given_feedback_user_activity_log_displayed_message'] = 'Female:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['projects_rating_feedbacks_message_sent_to_sp_when_po_company_app_male_given_feedback_user_activity_log_displayed_message'] = 'App Male: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['projects_rating_feedbacks_message_sent_to_sp_when_po_company_app_female_given_feedback_user_activity_log_displayed_message'] = 'App Female: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['projects_rating_feedbacks_message_sent_to_sp_when_po_company_given_feedback_user_activity_log_displayed_message'] = 'Company:<a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';



// Activity log message when employer given the feedback to employee (for fulltime projects)
$config['fulltime_projects_rating_feedbacks_message_sent_to_employer_when_given_feedback_user_activity_log_displayed_message'] = 'You left the feedback to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_male_given_feedback_user_activity_log_displayed_message'] = 'Male:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_female_given_feedback_user_activity_log_displayed_message'] = 'Female:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_company_app_male_given_feedback_user_activity_log_displayed_message'] = 'App Male: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_company_app_female_given_feedback_user_activity_log_displayed_message'] = 'App Female: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_company_given_feedback_user_activity_log_displayed_message'] = 'Company:<a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';


// Activity log message when SP given the feedback to PO
$config['projects_rating_feedbacks_message_sent_to_sp_when_given_feedback_user_activity_log_displayed_message'] = 'You left the feedback to <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['projects_rating_feedbacks_message_sent_to_po_when_sp_male_given_feedback_user_activity_log_displayed_message'] = 'Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['projects_rating_feedbacks_message_sent_to_po_when_sp_female_given_feedback_user_activity_log_displayed_message'] = 'Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';


$config['projects_rating_feedbacks_message_sent_to_po_when_sp_company_app_male_given_feedback_user_activity_log_displayed_message'] = 'App Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['projects_rating_feedbacks_message_sent_to_po_when_sp_company_app_female_given_feedback_user_activity_log_displayed_message'] = 'App Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['projects_rating_feedbacks_message_sent_to_po_when_sp_company_given_feedback_user_activity_log_displayed_message'] = 'Company:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';


// Activity log message when employee given the feedback to employer
$config['fulltime_projects_rating_feedbacks_message_sent_to_employee_when_given_feedback_user_activity_log_displayed_message'] = 'You left the feedback to <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_male_given_feedback_user_activity_log_displayed_message'] = 'Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_female_given_feedback_user_activity_log_displayed_message'] = 'Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_company_app_male_given_feedback_user_activity_log_displayed_message'] = 'App Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_company_app_female_given_feedback_user_activity_log_displayed_message'] = 'App Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_company_given_feedback_user_activity_log_displayed_message'] = 'Company:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> left a feedback ratings on "<a href="{project_url_link}" target="_blank">{project_title}</a>"';


// When hourly project is complete after given feedback
// activity log message
$config['hourly_rate_based_project_message_sent_to_sp_when_project_completed_user_activity_log_displayed_message'] = 'congratulations, your project <a href="{project_url_link}" target="_blank">{project_title}</a>" is now completed successfully';

$config['hourly_rate_based_project_message_sent_to_po_when_project_completed_user_activity_log_displayed_message'] = 'congratulations, your project <a href="{project_url_link}" target="_blank">{project_title}</a>" is now completed successfully';

//realtime notification/acknowledgement
$config['hourly_rate_based_project_realtime_notification_message_sent_to_sp_when_project_completed'] = 'congratulations, your project <a href="{project_url_link}" target="_blank">{project_title}</a>" is now completed successfully';

$config['hourly_rate_based_project_realtime_notification_message_sent_to_po_when_project_completed'] = 'congratulations, your project <a href="{project_url_link}" target="_blank">{project_title}</a>" is now completed successfully';


//Config of tabs under feedback tab on user profile page 
$config['user_profile_page_ratings_feedbacks_on_projects_tab'] = 'On Projects';
$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_tab'] = 'On Fulltime Jobs';

$config['user_profile_page_ratings_feedbacks_on_projects_as_sp_tab'] = 'As Service Provider';
$config['user_profile_page_ratings_feedbacks_on_projects_as_po_tab'] = 'As Project Owner';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab'] = 'As Employee';
$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab'] = 'As Employer';


$config['user_profile_page_feedbacks_tab_review_posted_on_date_txt'] = "Review Posted on {feedback_provided_on_date} by <a href='{user_profile_page_url}' target='_blank'>{user_first_name_last_name_or_company_name}</a>";

//Heading of As service provider tab under feedback tab on user profile page
//$config['user_profile_page_ratings_total_avg_rating_and_reviews_as_sp_txt'] = '{user_first_name_last_name_or_company_name} received {total_projects_reviews} as service provider, and scored an average {project_user_total_avg_rating_as_sp} out of 5.00';
$config['user_profile_page_ratings_total_avg_rating_and_reviews_as_sp_txt'] = 'received {total_projects_reviews} as service provider, and scores an average {project_user_total_avg_rating_as_sp} out of 5.00';


//Heading of As project owner tab under feedback tab on user profile page
//$config['user_profile_page_ratings_total_avg_rating_and_reviews_as_po_txt'] = '{user_first_name_last_name_or_company_name} received {total_projects_reviews} as project owner, and scored an average {project_user_total_avg_rating_as_po} out of 5.00';
$config['user_profile_page_ratings_total_avg_rating_and_reviews_as_po_txt'] = 'received {total_projects_reviews} as project owner, and scores an average {project_user_total_avg_rating_as_po} out of 5.00';


//Heading of As employee tab under feedback tab on user profile page
//$config['user_profile_page_ratings_total_avg_rating_and_reviews_as_employee_txt'] = '{user_first_name_last_name_or_company_name} received {total_fulltime_projects_reviews} as employee, and scored an average {project_user_total_avg_rating_as_employee} out of 5.00';
$config['user_profile_page_ratings_total_avg_rating_and_reviews_as_employee_txt'] = 'received {total_fulltime_projects_reviews} as employee, and scores an average {project_user_total_avg_rating_as_employee} out of 5.00';


//Heading of As employer tab under feedback tab on user profile page
//$config['user_profile_page_ratings_total_avg_rating_and_reviews_as_employer_txt'] = '{user_first_name_last_name_or_company_name} received {total_fulltime_projects_reviews} as employer, and scored an average {project_user_total_avg_rating_as_employer} out of 5.00';
$config['user_profile_page_ratings_total_avg_rating_and_reviews_as_employer_txt'] = 'received {total_fulltime_projects_reviews} as employer, and scores an average {project_user_total_avg_rating_as_employer} out of 5.00';


// config for as service provider tab under feedbacks tab on profile page when there is no feedback
$config['user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_male_visitor_view'] = 'Male: {user_first_name_last_name} does not current have any rating feedback on any project as sp';

$config['user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_female_visitor_view'] = 'Female:{user_first_name_last_name} does not current have any rating feedback on any project as sp';

$config['user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_company_app_male_visitor_view'] = 'App Male: {user_first_name_last_name} does not current have any rating feedback on any project as sp';

$config['user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_company_app_female_visitor_view'] = 'AppFemale:{user_first_name_last_name} does not current have any rating feedback on any project as sp';

$config['user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_company_visitor_view'] = 'Company: {user_company_name} does not current have any rating feedback on any project as sp';

$config['user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_sp_view'] = 'you do not have any rating feedback on any project as sp';


// config for as project owner tab under feedbacks tab on profile page when there is no feedback
$config['user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_male_visitor_view'] = 'Male: {user_first_name_last_name} does not current have any rating feedback on any project as po';

$config['user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_female_visitor_view'] = 'Female:{user_first_name_last_name} does not current have any rating feedback on any project as po';

$config['user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_company_app_male_visitor_view'] = 'App Male: {user_first_name_last_name} does not current have any rating feedback on any project as po';

$config['user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_company_app_female_visitor_view'] = 'App Female:{user_first_name_last_name} does not current have any rating feedback on any project as po';

$config['user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_company_visitor_view'] = 'Company: {user_company_name} does not current have any rating feedback on any project as po';

$config['user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_po_view'] = 'you do not have any rating feedback on any project as po';


// config for as employee tab under feedbacks tab on profile page when there is no feedback
$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_male_visitor_view'] = 'Male: {user_first_name_last_name} does not current have any rating feedback on any fulltime project as employee';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_female_visitor_view'] = 'Female:{user_first_name_last_name} does not current have any rating feedback on any fulltime project as employee';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_company_app_male_visitor_view'] = 'App Male: {user_first_name_last_name} does not current have any rating feedback on any fulltime project as employee';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_company_app_female_visitor_view'] = 'App Female:{user_first_name_last_name} does not current have any rating feedback on any fulltime project as employee';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_company_visitor_view'] = 'Company: {user_company_name} does not current have any rating feedback on any fulltime project as employee';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_employee_view'] = 'you do not have any rating feedback on any fulltime project as employee';


// config for as employer tab under feedbacks tab on profile page when there is no feedback
$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_male_visitor_view'] = 'Male: {user_first_name_last_name} does not current have any rating feedback on any fulltime project as employer';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_female_visitor_view'] = 'Female:{user_first_name_last_name} does not current have any rating feedback on any fulltime project as employer';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_company_app_male_visitor_view'] = 'app Male: {user_first_name_last_name} does not current have any rating feedback on any fulltime project as employer';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_company_app_female_visitor_view'] = 'app Female:{user_first_name_last_name} does not current have any rating feedback on any fulltime project as employer';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_company_visitor_view'] = 'Company: {user_company_name} does not current have any rating feedback on any fulltime project as employer';

$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_employer_view'] = 'you do not have any rating feedback on any fulltime project as employer';

#############################################

// conflict message when two user trying to given the reply on feedback
$config['users_ratings_feedbacks_user_already_given_reply_on_feedback'] = "You have already given reply on this feedback.";

$config['user_profile_page_users_ratings_feedbacks_reply_on_txt'] = "Reply by {user_first_name_last_name_or_company_name} on date:";



$config['pending_feedbacks_management_page_url'] = 'pending-feedbacks-management';

// config are using for projects pending feedback page(which open from left navigation menu)
$config['user_projects_pending_ratings_feedbacks_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | pending feedbacks';

$config['user_projects_pending_ratings_feedbacks_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} |pending feedbacks';

// This config is using on pending feedback overview page as a button text
$config['user_projects_pending_ratings_feedbacks_page_rate_button_txt'] = 'Rate';

// config when singular records on pending feedback page
$config['user_projects_pending_ratings_feedbacks_page_project_name_txt'] = 'SINGULAR -název projektu';
$config['user_projects_pending_ratings_feedbacks_page_to_user_txt'] = 'SINGULAR -Uživateli';
// config when plural records on pending feedback page
$config['user_projects_pending_ratings_feedbacks_page_projects_names_txt'] = 'PLURAL-název projektu';
$config['user_projects_pending_ratings_feedbacks_page_to_users_txt'] = 'PLURAL-Uživateli';


$config['user_projects_pending_ratings_feedbacks_page_headline_title'] = 'Projekty čekají na zpětnou vazbu';

$config['user_projects_pending_ratings_feedbacks_page_no_project_available_message'] = "<h4>Pro zpětnou vazbu není k dispozici žádný projekt</h4>";

?>