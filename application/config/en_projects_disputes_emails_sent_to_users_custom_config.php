<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Email Config Variables for projects disputes functionality
|--------------------------------------------------------------------------
| 
*/	
################### Email Configs Start ##################




//email config when dispute is cancelled by po
// For po

// for fixed/hourly project
$config['project_dispute_cancelled_by_po_email_cc_sent_to_po'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_cancelled_by_po_email_bcc_sent_to_po'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_cancelled_by_po_email_from_sent_to_po'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_cancelled_by_po_email_reply_to_sent_to_po'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_cancelled_by_po_email_from_name_sent_to_po'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_cancelled_by_po_email_subject_sent_to_po'] = 'you closed successfully dispute case {dispute_reference_id} against {sp_first_name_last_name_or_company_name}';
$config['project_dispute_cancelled_by_po_email_message_sent_to_po'] = '<p>Hello {po_first_name_last_name_or_company_name}</p><p>You marked dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> as resolved. amount {project_disputed_amount} was transfered to <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name_or_company_name}</a> and associated service fees {project_disputed_amount_service_fees} charged by business.</p>';

// for fulltime project
$config['fulltime_project_dispute_cancelled_by_employer_email_cc_sent_to_employer'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_cancelled_by_employer_email_bcc_sent_to_employer'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_cancelled_by_employer_email_from_sent_to_employer'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_cancelled_by_employer_email_reply_to_sent_to_employer'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_cancelled_by_employer_email_from_name_sent_to_employer'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_cancelled_by_employer_email_subject_sent_to_employer'] = 'Fulltime:you closed successfully dispute case {dispute_reference_id} against {sp_first_name_last_name_or_company_name}';
$config['fulltime_project_dispute_cancelled_by_employer_email_message_sent_to_employer'] = '<p>Fulltime:Hello {po_first_name_last_name_or_company_name}</p><p>You marked dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> as resolved. amount {fulltime_project_disputed_amount} was transfered to <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name_or_company_name}</a> and associated service fees {fulltime_project_disputed_amount_service_fees} charged by business.</p>';


// For sp
// For fixed hourly project
//po male
$config['project_dispute_cancelled_by_male_po_email_cc_sent_to_sp'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_cancelled_by_male_po_email_bcc_sent_to_sp'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_cancelled_by_male_po_email_from_sent_to_sp'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_cancelled_by_male_po_email_reply_to_sent_to_sp'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_cancelled_by_male_po_email_from_name_sent_to_sp'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_cancelled_by_male_po_email_subject_sent_to_sp'] = 'dispute case {dispute_reference_id} closed by {po_first_name_last_name}';
$config['project_dispute_cancelled_by_male_po_email_message_sent_to_sp'] = '<p>Hello {sp_first_name_last_name_or_company_name}</p><p>Male:<a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name}</a> marked dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> as resolved. entire amount of {project_disputed_amount} was transferred to your account balance.</p>';

// for fulltime project
$config['fulltime_project_dispute_cancelled_by_male_employer_email_cc_sent_to_employee'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_cancelled_by_male_employer_email_bcc_sent_to_employee'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_cancelled_by_male_employer_email_from_sent_to_employee'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_cancelled_by_male_employer_email_reply_to_sent_to_employee'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_cancelled_by_male_employer_email_from_name_sent_to_employee'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_cancelled_by_male_employer_email_subject_sent_to_employee'] = 'Fulltime:dispute case {dispute_reference_id} closed by {po_first_name_last_name}';
$config['fulltime_project_dispute_cancelled_by_male_employer_email_message_sent_to_employee'] = '<p>Fulltime:Hello {sp_first_name_last_name_or_company_name}</p><p>Male:<a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name}</a> marked dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> as resolved. entire amount of {fulltime_project_disputed_amount} was transferred to your account balance.</p>';

//po female

// for fixed/hourly budget
$config['project_dispute_cancelled_by_female_po_email_cc_sent_to_sp'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_cancelled_by_female_po_email_bcc_sent_to_sp'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_cancelled_by_female_po_email_from_sent_to_sp'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_cancelled_by_female_po_email_reply_to_sent_to_sp'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_cancelled_by_female_po_email_from_name_sent_to_sp'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_cancelled_by_female_po_email_subject_sent_to_sp'] = 'dispute case {dispute_reference_id} closed by {po_first_name_last_name}';
$config['project_dispute_cancelled_by_female_po_email_message_sent_to_sp'] = '<p>Hello {sp_first_name_last_name_or_company_name}</p><p>feMale:<a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name}</a> marked dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> as resolved. entire amount of {project_disputed_amount} was transferred to your account balance.</p>';

//for fulltime project
$config['fulltime_project_dispute_cancelled_by_female_employer_email_cc_sent_to_employee'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_cancelled_by_female_employer_email_bcc_sent_to_employee'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_cancelled_by_female_employer_email_from_sent_to_employee'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_cancelled_by_female_employer_email_reply_to_sent_to_employee'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_cancelled_by_female_employer_email_from_name_sent_to_employee'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_cancelled_by_female_employer_email_subject_sent_to_employee'] = 'Fulltime:dispute case {dispute_reference_id} closed by {po_first_name_last_name}';
$config['fulltime_project_dispute_cancelled_by_female_employer_email_message_sent_to_employee'] = '<p>Fulltime:Hello {sp_first_name_last_name_or_company_name}</p><p>feMale:<a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name}</a> marked dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> as resolved. entire amount of {fulltime_project_disputed_amount} was transferred to your account balance.</p>';

//po company
// for fixed hourly project
$config['project_dispute_cancelled_by_company_po_email_cc_sent_to_sp'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_cancelled_by_company_po_email_bcc_sent_to_sp'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_cancelled_by_company_po_email_from_sent_to_sp'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_cancelled_by_company_po_email_reply_to_sent_to_sp'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_cancelled_by_company_po_email_from_name_sent_to_sp'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_cancelled_by_company_po_email_subject_sent_to_sp'] = 'dispute case {dispute_reference_id} closed by {po_company_name}';
$config['project_dispute_cancelled_by_company_po_email_message_sent_to_sp'] = '<p>Hello {sp_first_name_last_name_or_company_name}</p><p>Company:<a href="{po_profile_url_link}" target="_blank">{po_company_name}</a> marked dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> as resolved. entire amount of {project_disputed_amount} was transferred to your account balance.</p>';
// for fulltime project

$config['fulltime_project_dispute_cancelled_by_company_employer_email_cc_sent_to_employee'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_cancelled_by_company_employer_email_bcc_sent_to_employee'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_cancelled_by_company_employer_email_from_sent_to_employee'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_cancelled_by_company_employer_email_reply_to_sent_to_employee'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_cancelled_by_company_employer_email_from_name_sent_to_employee'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_cancelled_by_company_employer_email_subject_sent_to_employee'] = 'Fulltime:dispute case {dispute_reference_id} closed by {po_company_name}';
$config['fulltime_project_dispute_cancelled_by_company_employer_email_message_sent_to_employee'] = '<p>Fulltime:Hello {sp_first_name_last_name_or_company_name}</p><p>Company:<a href="{po_profile_url_link}" target="_blank">{po_company_name}</a> marked dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> as resolved. entire amount of {fulltime_project_disputed_amount} was transferred to your account balance.</p>';

//email config when dispute is cancelled by sp
// For sp

// For fixed/hourly project
$config['project_dispute_cancelled_by_sp_email_cc_sent_to_sp'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_cancelled_by_sp_email_bcc_sent_to_sp'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_cancelled_by_sp_email_from_sent_to_sp'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_cancelled_by_sp_email_reply_to_sent_to_sp'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_cancelled_by_sp_email_from_name_sent_to_sp'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_cancelled_by_sp_email_subject_sent_to_sp'] = 'you closed successfully dispute case {dispute_reference_id} against {po_first_name_last_name_or_company_name}';
$config['project_dispute_cancelled_by_sp_email_message_sent_to_sp'] = '<p>Hello {sp_first_name_last_name_or_company_name}</p><p>You marked dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> as resolved. amount {project_disputed_amount} was transfered to <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name_or_company_name}</a></p>';

// For fulltime project
$config['fulltime_project_dispute_cancelled_by_employee_email_cc_sent_to_employee'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_cancelled_by_employee_email_bcc_sent_to_employee'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_cancelled_by_employee_email_from_sent_to_employee'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_cancelled_by_employee_email_reply_to_sent_to_employee'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_cancelled_by_employee_email_from_name_sent_to_employee'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_cancelled_by_employee_email_subject_sent_to_employee'] = 'Fulltime:you closed successfully dispute case {dispute_reference_id} against {po_first_name_last_name_or_company_name}';
$config['fulltime_project_dispute_cancelled_by_employee_email_message_sent_to_employee'] = '<p>Fulltime:Hello {sp_first_name_last_name_or_company_name}</p><p>You marked dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> as resolved. amount {fulltime_project_disputed_amount} was transfered to <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name_or_company_name}</a></p>';


// For po
//sp male
// for fixed/hourly project
$config['project_dispute_cancelled_by_male_sp_email_cc_sent_to_po'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_cancelled_by_male_sp_email_bcc_sent_to_po'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_cancelled_by_male_sp_email_from_sent_to_po'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_cancelled_by_male_sp_email_reply_to_sent_to_po'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_cancelled_by_male_sp_email_from_name_sent_to_po'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_cancelled_by_male_sp_email_subject_sent_to_po'] = 'dispute case {dispute_reference_id} closed by {sp_first_name_last_name}';
$config['project_dispute_cancelled_by_male_sp_email_message_sent_to_po'] = '<p>Hello {po_first_name_last_name_or_company_name}</p><p>Male:<a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a> marked dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> as resolved. Entire amount of {project_disputed_amount} with associated service fees {project_disputed_amount_service_fees} charged by business transferred to your account balance.</p>';

// for fulltime project
$config['fulltime_project_dispute_cancelled_by_male_employee_email_cc_sent_to_employer'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_cancelled_by_male_employee_email_bcc_sent_to_employer'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_cancelled_by_male_employee_email_from_sent_to_employer'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_cancelled_by_male_employee_email_reply_to_sent_to_employer'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_cancelled_by_male_employee_email_from_name_sent_to_employer'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_cancelled_by_male_employee_email_subject_sent_to_employer'] = 'Fulltime:dispute case {dispute_reference_id} closed by {sp_first_name_last_name}';
$config['fulltime_project_dispute_cancelled_by_male_employee_email_message_sent_to_employer'] = '<p>Fulltime:Hello {po_first_name_last_name_or_company_name}</p><p>Male:<a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a> marked dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> as resolved. Entire amount of {fulltime_project_disputed_amount} with associated service fees {fulltime_project_disputed_amount_service_fees} charged by business transferred to your account balance.</p>';

//sp female
// For fixed/hourly project
$config['project_dispute_cancelled_by_female_sp_email_cc_sent_to_po'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_cancelled_by_female_sp_email_bcc_sent_to_po'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_cancelled_by_female_sp_email_from_sent_to_po'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_cancelled_by_female_sp_email_reply_to_sent_to_po'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_cancelled_by_female_sp_email_from_name_sent_to_po'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_cancelled_by_female_sp_email_subject_sent_to_po'] = 'dispute case {dispute_reference_id} closed by {sp_first_name_last_name}';
$config['project_dispute_cancelled_by_female_sp_email_message_sent_to_po'] = '<p>Hello {po_first_name_last_name_or_company_name}</p><p>feMale:<a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a> marked dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> as resolved. Entire amount of {project_disputed_amount} with associated service fees {project_disputed_amount_service_fees} charged by business transferred to your account balance.</p>';

// For fulltime project
$config['fulltime_project_dispute_cancelled_by_female_employee_email_cc_sent_to_employer'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_cancelled_by_female_employee_email_bcc_sent_to_employer'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_cancelled_by_female_employee_email_from_sent_to_employer'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_cancelled_by_female_employee_email_reply_to_sent_to_employer'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_cancelled_by_female_employee_email_from_name_sent_to_employer'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_cancelled_by_female_employee_email_subject_sent_to_employer'] = 'fulltime:dispute case {dispute_reference_id} closed by {sp_first_name_last_name}';
$config['fulltime_project_dispute_cancelled_by_female_employee_email_message_sent_to_employer'] = '<p>Fulltime:Hello {po_first_name_last_name_or_company_name}</p><p>feMale:<a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a> marked dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> as resolved. Entire amount of {fulltime_project_disputed_amount} with associated service fees {fulltime_project_disputed_amount_service_fees} charged by business transferred to your account balance.</p>';

//sp company
// for fixed /hourly project
$config['project_dispute_cancelled_by_company_sp_email_cc_sent_to_po'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_cancelled_by_company_sp_email_bcc_sent_to_po'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_cancelled_by_company_sp_email_from_sent_to_po'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_cancelled_by_company_sp_email_reply_to_sent_to_po'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_cancelled_by_company_sp_email_from_name_sent_to_po'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_cancelled_by_company_sp_email_subject_sent_to_po'] = 'dispute case {dispute_reference_id} closed by {sp_company_name}';
$config['project_dispute_cancelled_by_company_sp_email_message_sent_to_po'] = '<p>Hello {po_first_name_last_name_or_company_name}</p><p>Company:<a href="{sp_profile_url_link}" target="_blank">{sp_company_name}</a> marked dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> as resolved.Entire amount of {project_disputed_amount} with associated service fees {project_disputed_amount_service_fees} charged by business transferred to your account balance.</p>';

// for fulltime project
$config['fulltime_project_dispute_cancelled_by_company_employee_email_cc_sent_to_employer'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_cancelled_by_company_employee_email_bcc_sent_to_employer'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_cancelled_by_company_employee_email_from_sent_to_employer'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_cancelled_by_company_employee_email_reply_to_sent_to_employer'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_cancelled_by_company_employee_email_from_name_sent_to_employer'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_cancelled_by_company_employee_email_subject_sent_to_employer'] = 'Fulltime:dispute case {dispute_reference_id} closed by {sp_company_name}';
$config['fulltime_project_dispute_cancelled_by_company_employee_email_message_sent_to_employer'] = '<p>Fulltime:Hello {po_first_name_last_name_or_company_name}</p><p>Company:<a href="{sp_profile_url_link}" target="_blank">{sp_company_name}</a> marked dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> as resolved.Entire amount of {fulltime_project_disputed_amount} with associated service fees {fulltime_project_disputed_amount_service_fees} charged by business transferred to your account balance.</p>';


#################### Email Config when dispute decided automatic #############

################# FOR PO ######################
// For Male po
// for fixed/hourly project
$config['project_dispute_email_cc_sent_to_male_po_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_bcc_sent_to_male_po_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_from_sent_to_male_po_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_email_reply_to_sent_to_male_po_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_email_from_name_sent_to_male_po_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_email_subject_sent_to_male_po_dispute_decided_automatic'] = 'dispute case {dispute_reference_id} resolved automatically(male po)';
$config['project_dispute_email_message_sent_to_male_po_dispute_decided_automatic'] = '<p>Male: Hello {po_first_name_last_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was automatically closed. Total disputed amount was {project_disputed_amount}, therefore 50% of that amount {project_50%_disputed_amount} was transferred back to you, together with associated service fees in total amount of {project_disputed_amount_service_fees} (50% from initial service fee value of {project_50%_disputed_amount_service_fees}). The other 50% amount was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> <p>';

// For fulltime project
$config['fulltime_project_dispute_email_cc_sent_to_male_employer_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_bcc_sent_to_male_employer_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_from_sent_to_male_employer_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_email_reply_to_sent_to_male_employer_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_email_from_name_sent_to_male_employer_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_email_subject_sent_to_male_employer_dispute_decided_automatic'] = 'Fulltime:dispute case {dispute_reference_id} resolved automatically(male po)';
$config['fulltime_project_dispute_email_message_sent_to_male_employer_dispute_decided_automatic'] = '<p>fulltime:Male: Hello {po_first_name_last_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was automatically closed. Total disputed amount was {fulltime_project_disputed_amount}, therefore 50% of that amount {fulltime_project_50%_disputed_amount} was transferred back to you, together with associated service fees in total amount of {fulltime_project_disputed_amount_service_fees} (50% from initial service fee value of {fulltime_project_50%_disputed_amount_service_fees}). The other 50% amount was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> <p>';

// For Female po
// for fixed/hourly project
$config['project_dispute_email_cc_sent_to_female_po_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_bcc_sent_to_female_po_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_from_sent_to_female_po_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_email_reply_to_sent_to_female_po_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_email_from_name_sent_to_female_po_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_email_subject_sent_to_female_po_dispute_decided_automatic'] = 'dispute case {dispute_reference_id} resolved automatically(female po)';
$config['project_dispute_email_message_sent_to_female_po_dispute_decided_automatic'] = '<p>Female: Hello {po_first_name_last_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was automatically closed. Total disputed amount was {project_disputed_amount}, therefore 50% of that amount {project_50%_disputed_amount} was transferred back to you, together with associated service fees in total amount of {project_disputed_amount_service_fees} (50% from initial service fee value of {project_50%_disputed_amount_service_fees}). The other 50% amount was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a><p>';

// for fulltime project

$config['fulltime_project_dispute_email_cc_sent_to_female_employer_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_bcc_sent_to_female_employer_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_from_sent_to_female_employer_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_email_reply_to_sent_to_female_employer_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_email_from_name_sent_to_female_employer_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_email_subject_sent_to_female_employer_dispute_decided_automatic'] = 'Fulltime:dispute case {dispute_reference_id} resolved automatically(female po)';
$config['fulltime_project_dispute_email_message_sent_to_female_employer_dispute_decided_automatic'] = '<p>Fulltime:Female: Hello {po_first_name_last_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was automatically closed. Total disputed amount was {fulltime_project_disputed_amount}, therefore 50% of that amount {fulltime_project_50%_disputed_amount} was transferred back to you, together with associated service fees in total amount of {fulltime_project_disputed_amount_service_fees} (50% from initial service fee value of {fulltime_project_50%_disputed_amount_service_fees}). The other 50% amount was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a><p>';


// For company po
// for fixed/hourly project
$config['project_dispute_email_cc_sent_to_company_po_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_bcc_sent_to_company_po_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_from_sent_to_company_po_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_email_reply_to_sent_to_company_po_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_email_from_name_sent_to_company_po_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_email_subject_sent_to_company_po_dispute_decided_automatic'] = 'dispute case {dispute_reference_id} resolved automatically(company po)';
$config['project_dispute_email_message_sent_to_company_po_dispute_decided_automatic'] = '<p>Company: Hello {po_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was automatically closed. Total disputed amount was {project_disputed_amount}, therefore 50% of that amount {project_50%_disputed_amount} was transferred back to you, together with associated service fees in total amount of {project_disputed_amount_service_fees} (50% from initial service fee value of {project_50%_disputed_amount_service_fees}). The other 50% amount was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a><p>';

// for fulltime project

$config['fulltime_project_dispute_email_cc_sent_to_company_employer_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_bcc_sent_to_company_employer_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_from_sent_to_company_employer_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_email_reply_to_sent_to_company_employer_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_email_from_name_sent_to_company_employer_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_email_subject_sent_to_company_employer_dispute_decided_automatic'] = 'Fulltime:dispute case {dispute_reference_id} resolved automatically(company po)';
$config['fulltime_project_dispute_email_message_sent_to_company_employer_dispute_decided_automatic'] = '<p>Fulltime:Company: Hello {po_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was automatically closed. Total disputed amount was {fulltime_project_disputed_amount}, therefore 50% of that amount {fulltime_project_50%_disputed_amount} was transferred back to you, together with associated service fees in total amount of {fulltime_project_disputed_amount_service_fees} (50% from initial service fee value of {fulltime_project_50%_disputed_amount_service_fees}). The other 50% amount was transferred to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a><p>';

###############################FOR SP#######################

// For Male sp
// For fixed/hourly project
$config['project_dispute_email_cc_sent_to_male_sp_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_bcc_sent_to_male_sp_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_from_sent_to_male_sp_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_email_reply_to_sent_to_male_sp_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_email_from_name_sent_to_male_sp_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_email_subject_sent_to_male_sp_dispute_decided_automatic'] = 'dispute case {dispute_reference_id} resolved automatically(male sp)';
$config['project_dispute_email_message_sent_to_male_sp_dispute_decided_automatic'] = '<p>Male: Hello {sp_first_name_last_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed automatically . Total disputed amount was {project_disputed_amount}, therefore 50% of that amount <span>{project_50%_disputed_amount}</span> was transferred to you account<p>';

// For fulltime project
$config['fulltime_project_dispute_email_cc_sent_to_male_employee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_bcc_sent_to_male_employee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_from_sent_to_male_employee_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_email_reply_to_sent_to_male_employee_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_email_from_name_sent_to_male_employee_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_email_subject_sent_to_male_employee_dispute_decided_automatic'] = 'fulltime:dispute case {dispute_reference_id} resolved automatically(male sp)';
$config['fulltime_project_dispute_email_message_sent_to_male_employee_dispute_decided_automatic'] = '<p>Fulltime:Male: Hello {sp_first_name_last_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed automatically . Total disputed amount was {fulltime_project_disputed_amount}, therefore 50% of that amount <span>{fulltime_project_50%_disputed_amount}</span> was transferred to you account<p>';

// For Female sp
// For fixed/hourly project
$config['project_dispute_email_cc_sent_to_female_sp_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_bcc_sent_to_female_sp_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_from_sent_to_female_sp_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_email_reply_to_sent_to_female_sp_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_email_from_name_sent_to_female_sp_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_email_subject_sent_to_female_sp_dispute_decided_automatic'] = 'dispute case {dispute_reference_id} resolved automatically(female sp)';
$config['project_dispute_email_message_sent_to_female_sp_dispute_decided_automatic'] = '<p>Female: Hello {sp_first_name_last_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed automatically . Total disputed amount was {project_disputed_amount}, therefore 50% of that amount <span>{project_50%_disputed_amount}</span> was transferred to you account<p>';

// For fulltime
$config['fulltime_project_dispute_email_cc_sent_to_female_employee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_bcc_sent_to_female_employee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_from_sent_to_female_employee_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_email_reply_to_sent_to_female_employee_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_email_from_name_sent_to_female_employee_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_email_subject_sent_to_female_employee_dispute_decided_automatic'] = 'Fulltime:dispute case {dispute_reference_id} resolved automatically(female sp)';
$config['fulltime_project_dispute_email_message_sent_to_female_employee_dispute_decided_automatic'] = '<p>Fulltime:Female: Hello {sp_first_name_last_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed automatically . Total disputed amount was {fulltime_project_disputed_amount}, therefore 50% of that amount <span>{fulltime_project_50%_disputed_amount}</span> was transferred to you account<p>';


// For Company sp
// For fixed/hourly project
$config['project_dispute_email_cc_sent_to_company_sp_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_bcc_sent_to_company_sp_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_from_sent_to_company_sp_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_email_reply_to_sent_to_company_sp_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_email_from_name_sent_to_company_sp_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_email_subject_sent_to_company_sp_dispute_decided_automatic'] = 'dispute case {dispute_reference_id} resolved automatically(company sp)';
$config['project_dispute_email_message_sent_to_company_sp_dispute_decided_automatic'] = 'Company: <p>Hello {sp_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed automatically . Total disputed amount was {project_disputed_amount}, therefore 50% of that amount <span>{project_50%_disputed_amount}</span> was transferred to you account<p>';

// For Fulltime project
$config['fulltime_project_dispute_email_cc_sent_to_company_employee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_bcc_sent_to_company_employee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_from_sent_to_company_employee_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_email_reply_to_sent_to_company_employee_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_email_from_name_sent_to_company_employee_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_email_subject_sent_to_company_employee_dispute_decided_automatic'] = 'dispute case {dispute_reference_id} resolved automatically(company sp)';
$config['fulltime_project_dispute_email_message_sent_to_company_employee_dispute_decided_automatic'] = 'Company: <p>Hello {sp_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed automatically . Total disputed amount was {fulltime_project_disputed_amount}, therefore 50% of that amount <span>{fulltime_project_50%_disputed_amount}</span> was transferred to you account<p>';

###################### Email config when po accept counter offer start ##

// po
// for fixed/hourly project
$config['project_dispute_po_accept_counter_offer_email_cc_sent_to_po'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_po_accept_counter_offer_email_bcc_sent_to_po'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_po_accept_counter_offer_email_from_sent_to_po'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_po_accept_counter_offer_email_reply_to_sent_to_po'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_po_accept_counter_offer_email_from_name_sent_to_po'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_po_accept_counter_offer_email_subject_sent_to_po'] = 'you accept the counter offer of dispute case {dispute_reference_id} against {sp_first_name_last_name_or_company_name}';
$config['project_dispute_po_accept_counter_offer_email_message_sent_to_po'] = '<p>Hello {po_first_name_last_name_or_company_name}</p><p>Dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was resolved. You accepted the counter offer received from <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name_or_company_name}</a>, in value of {project_counter_offer_value}. Total disputed amount was {project_disputed_amount}, therefore {project_counter_offer_value} will be transferred to <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name_or_company_name}</a> and associated service fees {service_fees_charged_from_po} will be charged. The remaining {project_remaining_amount} will be returned to your balance together with the associated services fees {service_fees_return_to_po}</p>';

// For fulltime project

$config['fulltime_project_dispute_employer_accept_counter_offer_email_cc_sent_to_employer'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_employer_accept_counter_offer_email_bcc_sent_to_employer'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_employer_accept_counter_offer_email_from_sent_to_employer'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_employer_accept_counter_offer_email_reply_to_sent_to_employer'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_employer_accept_counter_offer_email_from_name_sent_to_employer'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_employer_accept_counter_offer_email_subject_sent_to_employer'] = 'Fulltime:you accept the counter offer of dispute case {dispute_reference_id} against {sp_first_name_last_name_or_company_name}';
$config['fulltime_project_dispute_employer_accept_counter_offer_email_message_sent_to_employer'] = '<p>Fulltime:Hello {po_first_name_last_name_or_company_name}</p><p>Dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was resolved. You accepted the counter offer received from <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name_or_company_name}</a>, in value of {fulltime_project_counter_offer_value}. Total disputed amount was {fulltime_project_disputed_amount}, therefore {fulltime_project_counter_offer_value} will be transferred to <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name_or_company_name}</a> and associated service fees {service_fees_charged_from_po} will be charged. The remaining {fulltime_project_remaining_amount} will be returned to your balance together with the associated services fees {service_fees_return_to_po}</p>';


// for sp when po is male
// for fixed hourly project
$config['project_dispute_male_po_accept_counter_offer_email_cc_sent_to_sp'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_male_po_accept_counter_offer_email_bcc_sent_to_sp'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_male_po_accept_counter_offer_email_from_sent_to_sp'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_male_po_accept_counter_offer_email_reply_to_sent_to_sp'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_male_po_accept_counter_offer_email_from_name_sent_to_sp'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_male_po_accept_counter_offer_email_subject_sent_to_sp'] = 'Male:dispute case {dispute_reference_id} resolved by {po_first_name_last_name}';

$config['project_dispute_male_po_accept_counter_offer_email_message_sent_to_sp'] = '<p>Hello {sp_first_name_last_name_or_company_name}</p><p>Male:Dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was resolved. <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name}</a> accepted your counter offer , in value of {project_counter_offer_value}. Total disputed amount was {project_disputed_amount}, therefore {project_counter_offer_value} tranfer to your account balance</p>';

// for fulltime project
$config['fulltime_project_dispute_male_employer_accept_counter_offer_email_cc_sent_to_employee'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_male_employer_accept_counter_offer_email_bcc_sent_to_employee'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_male_employer_accept_counter_offer_email_from_sent_to_employee'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_male_employer_accept_counter_offer_email_reply_to_sent_to_employee'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_male_employer_accept_counter_offer_email_from_name_sent_to_employee'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_male_employer_accept_counter_offer_email_subject_sent_to_employee'] = 'Fulltime:Male:dispute case {dispute_reference_id} resolved by {po_first_name_last_name}';

$config['fulltime_project_dispute_male_employer_accept_counter_offer_email_message_sent_to_employee'] = '<p>Fulltime:Hello {sp_first_name_last_name_or_company_name}</p><p>Male:Dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was resolved. <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name}</a> accepted your counter offer , in value of {fulltime_project_counter_offer_value}. Total disputed amount was {fulltime_project_disputed_amount}, therefore {fulltime_project_counter_offer_value} tranfer to your account balance</p>';

// for sp when po is female
// for fixed/hourly project
$config['project_dispute_female_po_accept_counter_offer_email_cc_sent_to_sp'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_female_po_accept_counter_offer_email_bcc_sent_to_sp'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_female_po_accept_counter_offer_email_from_sent_to_sp'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_female_po_accept_counter_offer_email_reply_to_sent_to_sp'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_female_po_accept_counter_offer_email_from_name_sent_to_sp'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_female_po_accept_counter_offer_email_subject_sent_to_sp'] = 'female:dispute case {dispute_reference_id} resolved by {po_first_name_last_name}';

$config['project_dispute_female_po_accept_counter_offer_email_message_sent_to_sp'] = '<p>Hello {sp_first_name_last_name_or_company_name}</p><p>female:Dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was resolved. <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name}</a> accepted your counter offer , in value of {project_counter_offer_value}. Total disputed amount was {project_disputed_amount}, therefore {project_counter_offer_value} tranfer to your account balance</p>';

// for fulltime project
$config['fulltime_project_dispute_female_employer_accept_counter_offer_email_cc_sent_to_employee'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_female_employer_accept_counter_offer_email_bcc_sent_to_employee'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_female_employer_accept_counter_offer_email_from_sent_to_employee'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_female_employer_accept_counter_offer_email_reply_to_sent_to_employee'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_female_employer_accept_counter_offer_email_from_name_sent_to_employee'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_female_po_accept_counter_offer_email_subject_sent_to_employee'] = 'Fulltime:female:dispute case {dispute_reference_id} resolved by {po_first_name_last_name}';

$config['fulltime_project_dispute_female_employer_accept_counter_offer_email_message_sent_to_employee'] = '<p>fulltime:Hello {sp_first_name_last_name_or_company_name}</p><p>female:Dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was resolved. <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name}</a> accepted your counter offer , in value of {fulltime_project_counter_offer_value}. Total disputed amount was {fulltime_project_disputed_amount}, therefore {fulltime_project_counter_offer_value} tranfer to your account balance</p>';


// for sp when po is company
// for fixed/hourly project
$config['project_dispute_company_po_accept_counter_offer_email_cc_sent_to_sp'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_company_po_accept_counter_offer_email_bcc_sent_to_sp'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_company_po_accept_counter_offer_email_from_sent_to_sp'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_company_po_accept_counter_offer_email_reply_to_sent_to_sp'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_company_po_accept_counter_offer_email_from_name_sent_to_sp'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_company_po_accept_counter_offer_email_subject_sent_to_sp'] = 'Company:dispute case {dispute_reference_id} resolved by {po_company_name}';

$config['project_dispute_company_po_accept_counter_offer_email_message_sent_to_sp'] = '<p>Hello {sp_first_name_last_name_or_company_name}</p><p>Company:Dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was resolved. <a href="{po_profile_url_link}" target="_blank">{po_company_name}</a> accepted your counter offer , in value of {project_counter_offer_value}. Total disputed amount was {project_disputed_amount}, therefore {project_counter_offer_value} tranfer to your account balance</p>';

// for fulltime project
$config['fulltime_project_dispute_company_employer_accept_counter_offer_email_cc_sent_to_employee'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_company_employer_accept_counter_offer_email_bcc_sent_to_employee'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_company_employer_accept_counter_offer_email_from_sent_to_employee'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_company_employer_accept_counter_offer_email_reply_to_sent_to_employee'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_company_employer_accept_counter_offer_email_from_name_sent_to_employee'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_company_po_accept_counter_offer_email_subject_sent_to_employee'] = 'Company:dispute case {dispute_reference_id} resolved by {po_company_name}';

$config['fulltime_project_dispute_company_employer_accept_counter_offer_email_message_sent_to_employee'] = '<p>Hello {sp_first_name_last_name_or_company_name}</p><p>Company:Dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was resolved. <a href="{po_profile_url_link}" target="_blank">{po_company_name}</a> accepted your counter offer , in value of {fulltime_project_counter_offer_value}. Total disputed amount was {fulltime_project_disputed_amount}, therefore {fulltime_project_counter_offer_value} tranfer to your account balance</p>';

############# Email config when sp accept counter offer ####

// For sp

// For fixed/hourly project

$config['project_dispute_sp_accept_counter_offer_email_cc_sent_to_sp'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_sp_accept_counter_offer_email_bcc_sent_to_sp'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_sp_accept_counter_offer_email_from_sent_to_sp'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_sp_accept_counter_offer_email_reply_to_sent_to_sp'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_sp_accept_counter_offer_email_from_name_sent_to_sp'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_sp_accept_counter_offer_email_subject_sent_to_sp'] = 'you accept the counter offer of dispute case {dispute_reference_id} against {po_first_name_last_name_or_company_name}';

$config['project_dispute_sp_accept_counter_offer_email_message_sent_to_sp'] = '<p>Hello {sp_first_name_last_name_or_company_name}</p><p>Dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was resolved. You accepted the counter offer {project_counter_offer_value} created by <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name_or_company_name}</a>. Total disputed amount was {project_disputed_amount}, therefore {project_counter_offer_value} tranfer to your account balance</p>';

// For fulltime project

$config['fulltime_project_dispute_employee_accept_counter_offer_email_cc_sent_to_employee'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_employee_accept_counter_offer_email_bcc_sent_to_employee'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_employee_accept_counter_offer_email_from_sent_to_employee'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_employee_accept_counter_offer_email_reply_to_sent_to_employee'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_employee_accept_counter_offer_email_from_name_sent_to_employee'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_employee_accept_counter_offer_email_subject_sent_to_employee'] = 'Fulltime:you accept the counter offer of dispute case {dispute_reference_id} against {po_first_name_last_name_or_company_name}';

$config['fulltime_project_dispute_employee_accept_counter_offer_email_message_sent_to_employee'] = '<p>Fulltime:Hello {sp_first_name_last_name_or_company_name}</p><p>Dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was resolved. You accepted the counter offer {fulltime_project_counter_offer_value} created by <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name_or_company_name}</a>. Total disputed amount was {fulltime_project_disputed_amount}, therefore {fulltime_project_counter_offer_value} tranfer to your account balance</p>';

// for po when sp is male
// For fixed/hourly project
$config['project_dispute_male_sp_accept_counter_offer_email_cc_sent_to_po'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_male_sp_accept_counter_offer_email_bcc_sent_to_po'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_male_sp_accept_counter_offer_email_from_sent_to_po'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_male_sp_accept_counter_offer_email_reply_to_sent_to_po'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_male_sp_accept_counter_offer_email_from_name_sent_to_po'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_male_sp_accept_counter_offer_email_subject_sent_to_po'] = 'Male:dispute case {dispute_reference_id} resolved by {sp_first_name_last_name}';

$config['project_dispute_male_sp_accept_counter_offer_email_message_sent_to_po'] = '<p>Hello {po_first_name_last_name_or_company_name}</p><p>Dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was resolved. <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a> accepted your counter offer {project_counter_offer_value}. Total disputed amount was {project_disputed_amount}, therefore {project_counter_offer_value} will be transferred to <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a> and associated service fees {service_fees_charged_from_po} will be charged. The remaining {project_remaining_amount} will be returned to your balance together with the associated services fees {service_fees_return_to_po}</p></p>';

// For fulltime project
$config['fulltime_project_dispute_male_employee_accept_counter_offer_email_cc_sent_to_employer'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_male_employee_accept_counter_offer_email_bcc_sent_to_employer'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_male_employee_accept_counter_offer_email_from_sent_to_employer'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_male_employee_accept_counter_offer_email_reply_to_sent_to_employer'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_male_employee_accept_counter_offer_email_from_name_sent_to_employer'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_male_employee_accept_counter_offer_email_subject_sent_to_employer'] = 'Fulltime:Male:dispute case {dispute_reference_id} resolved by {sp_first_name_last_name}';

$config['fulltime_project_dispute_male_employee_accept_counter_offer_email_message_sent_to_employer'] = '<p>Fulltime:Hello {po_first_name_last_name_or_company_name}</p><p>Dispute case <a href="{fulltime_project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was resolved. <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a> accepted your counter offer {fulltime_project_counter_offer_value}. Total disputed amount was {fulltime_project_disputed_amount}, therefore {fulltime_project_counter_offer_value} will be transferred to <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a> and associated service fees {service_fees_charged_from_po} will be charged. The remaining {fulltime_project_remaining_amount} will be returned to your balance together with the associated services fees {service_fees_return_to_po}</p></p>';


// for po when sp is female
// for fixed/hourly project
$config['project_dispute_female_sp_accept_counter_offer_email_cc_sent_to_po'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_female_sp_accept_counter_offer_email_bcc_sent_to_po'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_female_sp_accept_counter_offer_email_from_sent_to_po'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_female_sp_accept_counter_offer_email_reply_to_sent_to_po'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_female_sp_accept_counter_offer_email_from_name_sent_to_po'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_female_sp_accept_counter_offer_email_subject_sent_to_po'] = 'FEMale:dispute case {dispute_reference_id} resolved by {sp_first_name_last_name}';

$config['project_dispute_female_sp_accept_counter_offer_email_message_sent_to_po'] = '<p>Hello {po_first_name_last_name_or_company_name}</p><p>Dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was resolved. <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a> accepted your counter offer {project_counter_offer_value}. Total disputed amount was {project_disputed_amount}, therefore {project_counter_offer_value} will be transferred to <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a> and associated service fees {service_fees_charged_from_po} will be charged. The remaining {project_remaining_amount} will be returned to your balance together with the associated services fees {service_fees_return_to_po}</p></p>';

// for fulltime project
$config['fulltime_project_dispute_female_employee_accept_counter_offer_email_cc_sent_to_employer'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_female_employee_accept_counter_offer_email_bcc_sent_to_employer'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_female_employee_accept_counter_offer_email_from_sent_to_employer'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_female_employee_accept_counter_offer_email_reply_to_sent_to_employer'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_female_employee_accept_counter_offer_email_from_name_sent_to_employer'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_female_employee_accept_counter_offer_email_subject_sent_to_employer'] = 'fulltime:FEMale:dispute case {dispute_reference_id} resolved by {sp_first_name_last_name}';

$config['fulltime_project_dispute_female_employee_accept_counter_offer_email_message_sent_to_employer'] = '<p>Fulltime:Hello {po_first_name_last_name_or_company_name}</p><p>Dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was resolved. <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a> accepted your counter offer {fulltime_project_counter_offer_value}. Total disputed amount was {fulltime_project_disputed_amount}, therefore {fulltime_project_counter_offer_value} will be transferred to <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a> and associated service fees {service_fees_charged_from_po} will be charged. The remaining {fulltime_project_remaining_amount} will be returned to your balance together with the associated services fees {service_fees_return_to_po}</p></p>';

// for po when sp is company
// For fixed/hourly budget
$config['project_dispute_company_sp_accept_counter_offer_email_cc_sent_to_po'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_company_sp_accept_counter_offer_email_bcc_sent_to_po'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_company_sp_accept_counter_offer_email_from_sent_to_po'] = 'registrace@'.HTTP_HOST;
$config['project_dispute_company_sp_accept_counter_offer_email_reply_to_sent_to_po'] = 'no-reply@'.HTTP_HOST;
$config['project_dispute_company_sp_accept_counter_offer_email_from_name_sent_to_po'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_company_sp_accept_counter_offer_email_subject_sent_to_po'] = 'Company:dispute case {dispute_reference_id} resolved by {sp_company_name}';

$config['project_dispute_company_sp_accept_counter_offer_email_message_sent_to_po'] = '<p>Hello {po_first_name_last_name_or_company_name}</p><p>Dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was resolved. <a href="{sp_profile_url_link}" target="_blank">{sp_company_name}</a> accepted your counter offer {project_counter_offer_value}. Total disputed amount was {project_disputed_amount}, therefore {project_counter_offer_value} will be transferred to <a href="{sp_profile_url_link}" target="_blank">{sp_company_name}</a> and associated service fees {service_fees_charged_from_po} will be charged. The remaining {project_remaining_amount} will be returned to your balance together with the associated services fees {service_fees_return_to_po}</p></p>';

// for fulltime

$config['fulltime_project_dispute_company_employee_accept_counter_offer_email_cc_sent_to_employer'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_company_employee_accept_counter_offer_email_bcc_sent_to_employer'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_company_employee_accept_counter_offer_email_from_sent_to_employer'] = 'registrace@'.HTTP_HOST;
$config['fulltime_project_dispute_company_employee_accept_counter_offer_email_reply_to_sent_to_employer'] = 'no-reply@'.HTTP_HOST;
$config['fulltime_project_dispute_company_employee_accept_counter_offer_email_from_name_sent_to_employer'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_company_employee_accept_counter_offer_email_subject_sent_to_employer'] = 'Fulltime:Company:dispute case {dispute_reference_id} resolved by {sp_company_name}';




$config['fulltime_project_dispute_company_employee_accept_counter_offer_email_message_sent_to_employer'] = '<p>Fulltime:Hello {po_first_name_last_name_or_company_name}</p><p>Dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was resolved. <a href="{sp_profile_url_link}" target="_blank">{sp_company_name}</a> accepted your counter offer {fulltime_project_counter_offer_value}. Total disputed amount was {fulltime_project_disputed_amount}, therefore {fulltime_project_counter_offer_value} will be transferred to <a href="{sp_profile_url_link}" target="_blank">{sp_company_name}</a> and associated service fees {service_fees_charged_from_po} will be charged. The remaining {fulltime_project_remaining_amount} will be returned to your balance together with the associated services fees {service_fees_return_to_po}</p></p>';

// Email configs when dipsute is initiated and its go to automatic decision phase, sent to initiator

// For fixed/hourly
$config['initiate_project_dispute_email_cc_sent_to_initiator_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['initiate_project_dispute_email_bcc_sent_to_initiator_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['initiate_project_dispute_email_from_sent_to_initiator_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['initiate_project_dispute_email_reply_to_sent_to_initiator_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['initiate_project_dispute_email_from_name_sent_to_initiator_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['initiate_project_dispute_email_subject_sent_to_initiator_dispute_decided_automatic'] = 'Nejvíce pracovních příležitostí na jednom místě';
$config['initiate_project_dispute_email_message_sent_to_initiator_dispute_decided_automatic'] = '<p>Your dispute case {dispute_reference_id}</p><p>Hello {user_first_name_last_name_or_company_name},</p><p>we have informed <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> about your decission to initiate a dispute case on project <a href="{project_url_link}" target="_blank">{project_title}</a> for the amount of {project_disputed_amount}</p><p>You need to allow time for <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> the seller to respond.</p><p>You have 28 calendar days, from the date the dispute is initiated, to negotiate and amiabily reach an agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a></p><p>If you can’t reach any agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> by date+time {dispute_negotiation_period}, your case will be automatically escalated to 50% 50% decide the outcome (usually within 10 working days).</p><p>There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage.</p><p>Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.</p><p>Should you have any question, do not hesitate to contact us at 999999999.</p>';

// for fulltime
$config['initiate_fulltime_project_dispute_email_cc_sent_to_initiator_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['initiate_fulltime_project_dispute_email_bcc_sent_to_initiator_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['initiate_fulltime_project_dispute_email_from_sent_to_initiator_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['initiate_fulltime_project_dispute_email_reply_to_sent_to_initiator_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['initiate_fulltime_project_dispute_email_from_name_sent_to_initiator_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['initiate_fulltime_project_dispute_email_subject_sent_to_initiator_dispute_decided_automatic'] = 'Fulltime:Nejvíce pracovních příležitostí na jednom místě';
$config['initiate_fulltime_project_dispute_email_message_sent_to_initiator_dispute_decided_automatic'] = '<p>Fulltime:Your dispute case {dispute_reference_id}</p><p>Hello {user_first_name_last_name_or_company_name},</p><p>we have informed <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> about your decission to initiate a dispute case on project <a href="{project_url_link}" target="_blank">{project_title}</a> for the amount of {fulltime_project_disputed_amount}</p><p>You need to allow time for <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> the seller to respond.</p><p>You have 28 calendar days, from the date the dispute is initiated, to negotiate and amiabily reach an agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a></p><p>If you can’t reach any agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> by date+time {dispute_negotiation_period}, your case will be automatically escalated to 50% 50% decide the outcome (usually within 10 working days).</p><p>There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage.</p><p>Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.</p><p>Should you have any question, do not hesitate to contact us at 999999999.</p>';

// Email configs when dipsute is initiated and its go to automatic decision, sent to disputee based on initiator gender

// When initiator gender is male

// For fixed/hourly
$config['initiate_project_dispute_by_male_initiator_email_cc_sent_to_disputee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['initiate_project_dispute_by_male_initiator_email_bcc_sent_to_disputee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['initiate_project_dispute_by_male_initiator_email_from_sent_to_disputee_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['initiate_project_dispute_by_male_initiator_email_reply_to_sent_to_disputee_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['initiate_project_dispute_by_male_initiator_email_from_name_sent_to_disputee_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['initiate_project_dispute_by_male_initiator_email_subject_sent_to_disputee_dispute_decided_automatic'] = 'Přímý přístup k seznamu odborníků a společností z celé ČR';
$config['initiate_project_dispute_by_male_initiator_email_message_sent_to_disputee_dispute_decided_automatic'] = '<p>this one is initial email sent to other party</p><p>Hello {user_first_name_last_name_or_company_name},</p><p><a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> decided to initiate a dispute case on project <a href="{project_url_link}" target="_blank">{project_title}</a> for the amount of {project_disputed_amount}.
</p><p>You have 28 calendar days, from the date the dispute is initiated {dispute_initiated_date}, to negotiate and amiably reach an agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a>.</p><p>If you can’t reach any agreement with him by date+time {dispute_negotiation_period}, the dispute case will be automatically escalated to 50% 50% to decide the outcome (usually within 10 working days).
</p><p>There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage.</p><p>Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.</p><p>Should you have any question, do not hesitate to contact us at 9999999999.</p>';

// For fulltime
$config['initiate_fulltime_project_dispute_by_male_initiator_email_cc_sent_to_disputee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['initiate_fulltime_project_dispute_by_male_initiator_email_bcc_sent_to_disputee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['initiate_fulltime_project_dispute_by_male_initiator_email_from_sent_to_disputee_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['initiate_fulltime_project_dispute_by_male_initiator_email_reply_to_sent_to_disputee_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['initiate_fulltime_project_dispute_by_male_initiator_email_from_name_sent_to_disputee_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['initiate_fulltime_project_dispute_by_male_initiator_email_subject_sent_to_disputee_dispute_decided_automatic'] = 'Fulltime:Přímý přístup k seznamu odborníků a společností z celé ČR';
$config['initiate_fulltime_project_dispute_by_male_initiator_email_message_sent_to_disputee_dispute_decided_automatic'] = '<p>Fulltime:this one is initial email sent to other party</p><p>Hello {user_first_name_last_name_or_company_name},</p><p><a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> decided to initiate a dispute case on project <a href="{project_url_link}" target="_blank">{project_title}</a> for the amount of {fulltime_project_disputed_amount}.
</p><p>You have 28 calendar days, from the date the dispute is initiated {dispute_initiated_date}, to negotiate and amiably reach an agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a>.</p><p>If you can’t reach any agreement with him by date+time {dispute_negotiation_period}, the dispute case will be automatically escalated to 50% 50% to decide the outcome (usually within 10 working days).
</p><p>There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage.</p><p>Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.</p><p>Should you have any question, do not hesitate to contact us at 9999999999.</p>';



// When initiator gender is female
// for fixed/hourly project
$config['initiate_project_dispute_by_female_initiator_email_cc_sent_to_disputee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['initiate_project_dispute_by_female_initiator_email_bcc_sent_to_disputee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['initiate_project_dispute_by_female_initiator_email_from_sent_to_disputee_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['initiate_project_dispute_by_female_initiator_email_reply_to_sent_to_disputee_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['initiate_project_dispute_by_female_initiator_email_from_name_sent_to_disputee_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['initiate_project_dispute_by_female_initiator_email_subject_sent_to_disputee_dispute_decided_automatic'] = 'Přímý přístup k seznamu odborníků a společností z celé ČR';
$config['initiate_project_dispute_by_female_initiator_email_message_sent_to_disputee_dispute_decided_automatic'] = '<p>this one is initial email sent to other party</p><p>Hello {user_first_name_last_name_or_company_name},</p><p><a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> decided to initiate a dispute case on project <a href="{project_url_link}" target="_blank">{project_title}</a> for the amount of {project_disputed_amount}.
</p><p>You have 28 calendar days, from the date the dispute is initiated {dispute_initiated_date}, to negotiate and amiably reach an agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a>.</p><p>If you can’t reach any agreement with her by date+time {dispute_negotiation_period}, the dispute case will be automatically 50% 50% decide the outcome (usually within 10 working days).
</p><p>There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage.</p><p>Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.</p><p>Should you have any question, do not hesitate to contact us at 9999999999.</p>';

// for fulltime project
$config['initiate_fulltime_project_dispute_by_female_initiator_email_cc_sent_to_disputee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['initiate_fulltime_project_dispute_by_female_initiator_email_bcc_sent_to_disputee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['initiate_fulltime_project_dispute_by_female_initiator_email_from_sent_to_disputee_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['initiate_fulltime_project_dispute_by_female_initiator_email_reply_to_sent_to_disputee_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['initiate_fulltime_project_dispute_by_female_initiator_email_from_name_sent_to_disputee_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['initiate_fulltime_project_dispute_by_female_initiator_email_subject_sent_to_disputee_dispute_decided_automatic'] = 'Fulltime:Přímý přístup k seznamu odborníků a společností z celé ČR';
$config['initiate_fulltime_project_dispute_by_female_initiator_email_message_sent_to_disputee_dispute_decided_automatic'] = '<p>Fulltime:this one is initial email sent to other party</p><p>Hello {user_first_name_last_name_or_company_name},</p><p><a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> decided to initiate a dispute case on project <a href="{project_url_link}" target="_blank">{project_title}</a> for the amount of {fulltime_project_disputed_amount}.
</p><p>You have 28 calendar days, from the date the dispute is initiated {dispute_initiated_date}, to negotiate and amiably reach an agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a>.</p><p>If you can’t reach any agreement with her by date+time {dispute_negotiation_period}, the dispute case will be automatically 50% 50% decide the outcome (usually within 10 working days).
</p><p>There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage.</p><p>Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.</p><p>Should you have any question, do not hesitate to contact us at 9999999999.</p>';



// When initiator gender is company

$config['initiate_project_dispute_by_company_initiator_email_cc_sent_to_disputee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['initiate_project_dispute_by_company_initiator_email_bcc_sent_to_disputee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['initiate_project_dispute_by_company_initiator_email_from_sent_to_disputee_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['initiate_project_dispute_by_company_initiator_email_reply_to_sent_to_disputee_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['initiate_project_dispute_by_company_initiator_email_from_name_sent_to_disputee_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['initiate_project_dispute_by_company_initiator_email_subject_sent_to_disputee_dispute_decided_automatic'] = 'Přímý přístup k seznamu odborníků a společností z celé ČR';
$config['initiate_project_dispute_by_company_initiator_email_message_sent_to_disputee_dispute_decided_automatic'] = '<p>Hello {user_first_name_last_name_or_company_name},</p><p><a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> decided to initiate a dispute case on project <a href="{project_url_link}" target="_blank">{project_title}</a> for the amount of {project_disputed_amount}.
</p><p>You have 28 calendar days, from the date the dispute is initiated {dispute_initiated_date}, to negotiate and amiably reach an agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a>.</p><p>If you can’t reach any agreement with it by date+time {dispute_negotiation_period}, the dispute case will be automatically 50% 50% decide the outcome (usually within 10 working days).
</p><p>There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage.</p><p>Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.</p><p>Should you have any question, do not hesitate to contact us at 9999999999.</p>';


// for fulltime project
$config['initiate_fulltime_project_dispute_by_company_initiator_email_cc_sent_to_disputee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['initiate_fulltime_project_dispute_by_company_initiator_email_bcc_sent_to_disputee_dispute_decided_automatic'] = 'catalin.basturescu@gmail.com';
$config['initiate_fulltime_project_dispute_by_company_initiator_email_from_sent_to_disputee_dispute_decided_automatic'] = 'registrace@'.HTTP_HOST;
$config['initiate_fulltime_project_dispute_by_company_initiator_email_reply_to_sent_to_disputee_dispute_decided_automatic'] = 'no-reply@'.HTTP_HOST;
$config['initiate_fulltime_project_dispute_by_company_initiator_email_from_name_sent_to_disputee_dispute_decided_automatic'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['initiate_fulltime_project_dispute_by_company_initiator_email_subject_sent_to_disputee_dispute_decided_automatic'] = 'Fulltime:Přímý přístup k seznamu odborníků a společností z celé ČR';
$config['initiate_fulltime_project_dispute_by_company_initiator_email_message_sent_to_disputee_dispute_decided_automatic'] = '<p>Fulltime:Hello {user_first_name_last_name_or_company_name},</p><p><a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> decided to initiate a dispute case on project <a href="{project_url_link}" target="_blank">{project_title}</a> for the amount of {fulltime_project_disputed_amount}.
</p><p>You have 28 calendar days, from the date the dispute is initiated {dispute_initiated_date}, to negotiate and amiably reach an agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a>.</p><p>If you can’t reach any agreement with it by date+time {dispute_negotiation_period}, the dispute case will be automatically 50% 50% decide the outcome (usually within 10 working days).
</p><p>There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage.</p><p>Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.</p><p>Should you have any question, do not hesitate to contact us at 9999999999.</p>';


// Email when dispute is decided by admin arbitartion and Po is winner of dispute
// po
// for fixed/hourly project

// WEBSITE_HOST is replace by site domain. This constant we will use for all emails which will send by admin
$config['project_dispute_email_cc_sent_to_po_dispute_decided_admin_arbitration_po_winner'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_bcc_sent_to_po_dispute_decided_admin_arbitration_po_winner'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_from_sent_to_po_dispute_decided_admin_arbitration_po_winner'] = 'registrace@'.WEBSITE_HOST;
$config['project_dispute_email_reply_to_sent_to_po_dispute_decided_admin_arbitration_po_winner'] = 'no-reply@'.WEBSITE_HOST;
$config['project_dispute_email_from_name_sent_to_po_dispute_decided_admin_arbitration_po_winner'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_email_subject_sent_to_po_dispute_decided_admin_arbitration_po_winner'] = 'dispute case {dispute_reference_id} Closed By Admin';

$config['project_dispute_email_message_sent_to_po_dispute_decided_admin_arbitration_po_winner'] = '<p>Hello {po_first_name_last_name_or_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed By admin.You won the dispute against <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name_or_company_name}</a>.Total disputed amount was {project_disputed_amount}, therefore {project_disputed_amount_excluding_admin_arbitration_fee} was transferred back to you, together with associated service fees {project_disputed_amount_service_fees} and {admin_dispute_arbitration_amount_fee} charged by admin as dsipute arbitartion fees.<p>';

// For fulltime project
$config['fulltime_project_dispute_email_cc_sent_to_employer_dispute_decided_admin_arbitration_employer_winner'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_bcc_sent_to_employer_dispute_decided_admin_arbitration_employer_winner'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_from_sent_to_employer_dispute_decided_admin_arbitration_employer_winner'] = 'registrace@'.WEBSITE_HOST;
$config['fulltime_project_dispute_email_reply_to_sent_to_employer_dispute_decided_admin_arbitration_employer_winner'] = 'no-reply@'.WEBSITE_HOST;
$config['fulltime_project_dispute_email_from_name_sent_to_employer_dispute_decided_admin_arbitration_employer_winner'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_email_subject_sent_to_employer_dispute_decided_admin_arbitration_po_winner'] = 'Fulltime:dispute case {dispute_reference_id} Closed By Admin';

$config['fulltime_project_dispute_email_message_sent_to_employer_dispute_decided_admin_arbitration_employer_winner'] = '<p>Fulltime:Hello {po_first_name_last_name_or_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed By admin.You won the dispute against <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name_or_company_name}</a>.Total disputed amount was {fulltime_project_disputed_amount}, therefore {fulltime_project_disputed_amount_excluding_admin_arbitration_fee} was transferred back to you, together with associated service fees {fulltime_project_disputed_amount_service_fees} and {admin_dispute_arbitration_amount_fee} charged by admin as dsipute arbitartion fees.<p>';

// sp when po is male
// for fixed/hourly project

$config['project_dispute_email_cc_sent_to_sp_dispute_decided_admin_arbitration_male_po_winner'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_bcc_sent_to_sp_dispute_decided_admin_arbitration_male_po_winner'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_from_sent_to_sp_dispute_decided_admin_arbitration_male_po_winner'] = 'registrace@'.WEBSITE_HOST;
$config['project_dispute_email_reply_to_sent_to_sp_dispute_decided_admin_arbitration_male_po_winner'] = 'no-reply@'.WEBSITE_HOST;
$config['project_dispute_email_from_name_sent_to_sp_dispute_decided_admin_arbitration_male_po_winner'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_email_subject_sent_to_sp_dispute_decided_admin_arbitration_male_po_winner'] = 'dispute case {dispute_reference_id} Closed By Admin[Male PO]';

$config['project_dispute_email_message_sent_to_sp_dispute_decided_admin_arbitration_male_po_winner'] = '<p>Hello {sp_first_name_last_name_or_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed By admin.You loss the dispute against <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name}</a> therefore <span>{project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name}</a><p>';

// for fulltime project

$config['fulltime_project_dispute_email_cc_sent_to_employee_dispute_decided_admin_arbitration_male_employer_winner'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_bcc_sent_to_employee_dispute_decided_admin_arbitration_male_employer_winner'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_from_sent_to_employee_dispute_decided_admin_arbitration_male_employer_winner'] = 'registrace@'.WEBSITE_HOST;
$config['fulltime_project_dispute_email_reply_to_sent_to_employee_dispute_decided_admin_arbitration_male_employer_winner'] = 'no-reply@'.WEBSITE_HOST;
$config['fulltime_project_dispute_email_from_name_sent_to_employee_dispute_decided_admin_arbitration_male_employer_winner'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_email_subject_sent_to_employee_dispute_decided_admin_arbitration_male_employer_winner'] = 'Fulltime:dispute case {dispute_reference_id} Closed By Admin[Male PO]';

$config['fulltime_project_dispute_email_message_sent_to_employee_dispute_decided_admin_arbitration_male_employer_winner'] = '<p>Fulltime:Hello {sp_first_name_last_name_or_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed By admin.You loss the dispute against <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name}</a> therefore <span>{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name}</a><p>';


// sp when po is female
// for hourly/fixed project
$config['project_dispute_email_cc_sent_to_sp_dispute_decided_admin_arbitration_female_po_winner'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_bcc_sent_to_sp_dispute_decided_admin_arbitration_female_po_winner'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_from_sent_to_sp_dispute_decided_admin_arbitration_female_po_winner'] = 'registrace@'.WEBSITE_HOST;
$config['project_dispute_email_reply_to_sent_to_sp_dispute_decided_admin_arbitration_female_po_winner'] = 'no-reply@'.WEBSITE_HOST;
$config['project_dispute_email_from_name_sent_to_sp_dispute_decided_admin_arbitration_female_po_winner'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_email_subject_sent_to_sp_dispute_decided_admin_arbitration_female_po_winner'] = 'dispute case {dispute_reference_id} Closed By Admin[FEMale PO]';

$config['project_dispute_email_message_sent_to_sp_dispute_decided_admin_arbitration_female_po_winner'] = '<p>Hello {sp_first_name_last_name_or_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed By admin.You loss the dispute against <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name}</a> therefore <span>{project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name}</a><p>';

// for fulltime project
$config['fulltime_project_dispute_email_cc_sent_to_employee_dispute_decided_admin_arbitration_female_employer_winner'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_bcc_sent_to_employee_dispute_decided_admin_arbitration_female_employer_winner'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_from_sent_to_employee_dispute_decided_admin_arbitration_female_employer_winner'] = 'registrace@'.WEBSITE_HOST;
$config['fulltime_project_dispute_email_reply_to_sent_to_employee_dispute_decided_admin_arbitration_female_employer_winner'] = 'no-reply@'.WEBSITE_HOST;
$config['fulltime_project_dispute_email_from_name_sent_to_employee_dispute_decided_admin_arbitration_female_employer_winner'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_email_subject_sent_to_employee_dispute_decided_admin_arbitration_female_employer_winner'] = 'dispute case {dispute_reference_id} Closed By Admin[FEMale PO]';

$config['fulltime_project_dispute_email_message_sent_to_employee_dispute_decided_admin_arbitration_female_employer_winner'] = '<p>Hello {sp_first_name_last_name_or_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed By admin.You loss the dispute against <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name}</a> therefore <span>{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name}</a><p>';


// sp when po is company

//for fixed hourly project

$config['project_dispute_email_cc_sent_to_sp_dispute_decided_admin_arbitration_company_po_winner'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_bcc_sent_to_sp_dispute_decided_admin_arbitration_company_po_winner'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_from_sent_to_sp_dispute_decided_admin_arbitration_company_po_winner'] = 'registrace@'.WEBSITE_HOST;
$config['project_dispute_email_reply_to_sent_to_sp_dispute_decided_admin_arbitration_company_po_winner'] = 'no-reply@'.WEBSITE_HOST;
$config['project_dispute_email_from_name_sent_to_sp_dispute_decided_admin_arbitration_company_po_winner'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_email_subject_sent_to_sp_dispute_decided_admin_arbitration_company_po_winner'] = 'dispute case {dispute_reference_id} Closed By Admin[company PO]';

$config['project_dispute_email_message_sent_to_sp_dispute_decided_admin_arbitration_company_po_winner'] = '<p>Hello {sp_first_name_last_name_or_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed By admin.You loss the dispute against <a href="{po_profile_url_link}" target="_blank">{po_company_name}</a> therefore <span>{project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{po_profile_url_link}" target="_blank">{po_company_name}</a><p>';

// for fulltime project

$config['fulltime_project_dispute_email_cc_sent_to_employee_dispute_decided_admin_arbitration_company_employer_winner'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_bcc_sent_to_employee_dispute_decided_admin_arbitration_company_employer_winner'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_from_sent_to_employee_dispute_decided_admin_arbitration_company_employer_winner'] = 'registrace@'.WEBSITE_HOST;
$config['fulltime_project_dispute_email_reply_to_sent_to_employee_dispute_decided_admin_arbitration_company_employer_winner'] = 'no-reply@'.WEBSITE_HOST;
$config['fulltime_project_dispute_email_from_name_sent_to_employee_dispute_decided_admin_arbitration_company_employer_winner'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_email_subject_sent_to_employee_dispute_decided_admin_arbitration_company_employer_winner'] = 'Fulltime:dispute case {dispute_reference_id} Closed By Admin[company PO]';

$config['fulltime_project_dispute_email_message_sent_to_employee_dispute_decided_admin_arbitration_company_employer_winner'] = '<p>Fulltime:Hello {sp_first_name_last_name_or_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed By admin.You loss the dispute against <a href="{po_profile_url_link}" target="_blank">{po_company_name}</a> therefore <span>{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}</span> was transferred to <a href="{po_profile_url_link}" target="_blank">{po_company_name}</a><p>';


// Email when dispute is decided by admin arbitartion and sp is winner of dispute

// sp
// For fixed/ hourly project
$config['project_dispute_email_cc_sent_to_sp_dispute_decided_admin_arbitration_sp_winner'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_bcc_sent_to_sp_dispute_decided_admin_arbitration_sp_winner'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_from_sent_to_sp_dispute_decided_admin_arbitration_sp_winner'] = 'registrace@'.WEBSITE_HOST;
$config['project_dispute_email_reply_to_sent_to_sp_dispute_decided_admin_arbitration_sp_winner'] = 'no-reply@'.WEBSITE_HOST;
$config['project_dispute_email_from_name_sent_to_sp_dispute_decided_admin_arbitration_sp_winner'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_email_subject_sent_to_sp_dispute_decided_admin_arbitration_sp_winner'] = 'dispute case {dispute_reference_id} Closed By Admin';

$config['project_dispute_email_message_sent_to_sp_dispute_decided_admin_arbitration_sp_winner'] = '<p>Hello {sp_first_name_last_name_or_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed By admin.You won the dispute against <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name_or_company_name}</a>.Total disputed amount was {project_disputed_amount}, therefore {project_disputed_amount_excluding_admin_arbitration_fee} was transferred to you<p>';

// For fulltime project
$config['fulltime_project_dispute_email_cc_sent_to_employee_dispute_decided_admin_arbitration_employee_winner'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_bcc_sent_to_employee_dispute_decided_admin_arbitration_employee_winner'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_from_sent_to_employee_dispute_decided_admin_arbitration_employee_winner'] = 'registrace@'.WEBSITE_HOST;
$config['fulltime_project_dispute_email_reply_to_sent_to_employee_dispute_decided_admin_arbitration_employee_winner'] = 'no-reply@'.WEBSITE_HOST;
$config['fulltime_project_dispute_email_from_name_sent_to_employee_dispute_decided_admin_arbitration_employee_winner'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_email_subject_sent_to_employee_dispute_decided_admin_arbitration_employee_winner'] = 'dispute case {dispute_reference_id} Closed By Admin';

$config['fulltime_project_dispute_email_message_sent_to_employee_dispute_decided_admin_arbitration_employee_winner'] = '<p>Hello {sp_first_name_last_name_or_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed By admin.You won the dispute against <a href="{po_profile_url_link}" target="_blank">{po_first_name_last_name_or_company_name}</a>.Total disputed amount was {fulltime_project_disputed_amount}, therefore {fulltime_project_disputed_amount_excluding_admin_arbitration_fee} was transferred to you<p>';



// po when sp is male
// For fixed/ hourly project
$config['project_dispute_email_cc_sent_to_po_dispute_decided_admin_arbitration_male_sp_winner'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_bcc_sent_to_po_dispute_decided_admin_arbitration_male_sp_winner'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_from_sent_to_po_dispute_decided_admin_arbitration_male_sp_winner'] = 'registrace@'.WEBSITE_HOST;
$config['project_dispute_email_reply_to_sent_to_po_dispute_decided_admin_arbitration_male_sp_winner'] = 'no-reply@'.WEBSITE_HOST;
$config['project_dispute_email_from_name_sent_to_po_dispute_decided_admin_arbitration_male_sp_winner'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_email_subject_sent_to_po_dispute_decided_admin_arbitration_male_sp_winner'] = 'dispute case {dispute_reference_id} Closed By Admin[Male SP]';

$config['project_dispute_email_message_sent_to_po_dispute_decided_admin_arbitration_male_sp_winner'] = '<p>Hello {po_first_name_last_name_or_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed By admin.You lost the dispute against <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a>.Total disputed amount was {project_disputed_amount}, therefore {project_disputed_amount_excluding_admin_arbitration_fee} was transferred to <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a>. Bussiness service fees {project_disputed_amount_service_fees} and dispute arbitartion fees {admin_dispute_arbitration_amount_fee} charged from you<p>';

// For fulltime project
$config['fulltime_project_dispute_email_cc_sent_to_employer_dispute_decided_admin_arbitration_male_employee_winner'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_bcc_sent_to_employer_dispute_decided_admin_arbitration_male_employee_winner'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_from_sent_to_employer_dispute_decided_admin_arbitration_male_employee_winner'] = 'registrace@'.WEBSITE_HOST;
$config['fulltime_project_dispute_email_reply_to_sent_to_employer_dispute_decided_admin_arbitration_male_employee_winner'] = 'no-reply@'.WEBSITE_HOST;
$config['fulltime_project_dispute_email_from_name_sent_to_employer_dispute_decided_admin_arbitration_male_employee_winner'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_email_subject_sent_to_po_dispute_decided_admin_arbitration_male_employee_winner'] = 'Fulltime:dispute case {dispute_reference_id} Closed By Admin[Male SP]';

$config['fulltime_project_dispute_email_message_sent_to_employer_dispute_decided_admin_arbitration_male_employee_winner'] = '<p>Fulltime:Hello {po_first_name_last_name_or_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed By admin.You lost the dispute against <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a>.Total disputed amount was {fulltime_project_disputed_amount}, therefore {fulltime_project_disputed_amount_excluding_admin_arbitration_fee} was transferred to <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a>. Bussiness service fees {fulltime_project_disputed_amount_service_fees} and dispute arbitartion fees {admin_dispute_arbitration_amount_fee} charged from you<p>';


// po when sp is female
// For fixed/ hourly project
$config['project_dispute_email_cc_sent_to_po_dispute_decided_admin_arbitration_female_sp_winner'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_bcc_sent_to_po_dispute_decided_admin_arbitration_female_sp_winner'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_from_sent_to_po_dispute_decided_admin_arbitration_female_sp_winner'] = 'registrace@'.WEBSITE_HOST;
$config['project_dispute_email_reply_to_sent_to_po_dispute_decided_admin_arbitration_female_sp_winner'] = 'no-reply@'.WEBSITE_HOST;
$config['project_dispute_email_from_name_sent_to_po_dispute_decided_admin_arbitration_female_sp_winner'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_email_subject_sent_to_po_dispute_decided_admin_arbitration_female_sp_winner'] = 'dispute case {dispute_reference_id} Closed By Admin[FeMale SP]';

$config['project_dispute_email_message_sent_to_po_dispute_decided_admin_arbitration_female_sp_winner'] = '<p>Hello {po_first_name_last_name_or_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed By admin.You lost the dispute against <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a>.Total disputed amount was {project_disputed_amount}, therefore {project_disputed_amount_excluding_admin_arbitration_fee} was transferred to <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a>. Bussiness service fees {project_disputed_amount_service_fees} and dispute arbitartion fees {admin_dispute_arbitration_amount_fee} charged from you</p>';

// For fulltime project
$config['fulltime_project_dispute_email_cc_sent_to_employer_dispute_decided_admin_arbitration_female_employee_winner'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_bcc_sent_to_employer_dispute_decided_admin_arbitration_female_employee_winner'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_from_sent_to_employer_dispute_decided_admin_arbitration_female_employee_winner'] = 'registrace@'.WEBSITE_HOST;
$config['fulltime_project_dispute_email_reply_to_sent_to_employer_dispute_decided_admin_arbitration_female_employee_winner'] = 'no-reply@'.WEBSITE_HOST;
$config['fulltime_project_dispute_email_from_name_sent_to_employer_dispute_decided_admin_arbitration_female_employee_winner'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_email_subject_sent_to_employer_dispute_decided_admin_arbitration_female_employee_winner'] = 'Fulltime:dispute case {dispute_reference_id} Closed By Admin[FeMale SP]';

$config['fulltime_project_dispute_email_message_sent_to_employer_dispute_decided_admin_arbitration_female_employee_winner'] = '<p>Fulltime:Hello {po_first_name_last_name_or_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed By admin.You lost the dispute against <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a>.Total disputed amount was {fulltime_project_disputed_amount}, therefore {fulltime_project_disputed_amount_excluding_admin_arbitration_fee} was transferred to <a href="{sp_profile_url_link}" target="_blank">{sp_first_name_last_name}</a>. Bussiness service fees {fulltime_project_disputed_amount_service_fees} and dispute arbitartion fees {admin_dispute_arbitration_amount_fee} charged from you</p>';

// po when sp is company
// For fixed/ hourly project
$config['project_dispute_email_cc_sent_to_po_dispute_decided_admin_arbitration_company_sp_winner'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_bcc_sent_to_po_dispute_decided_admin_arbitration_company_sp_winner'] = 'catalin.basturescu@gmail.com';
$config['project_dispute_email_from_sent_to_po_dispute_decided_admin_arbitration_company_sp_winner'] = 'registrace@'.WEBSITE_HOST;
$config['project_dispute_email_reply_to_sent_to_po_dispute_decided_admin_arbitration_company_sp_winner'] = 'no-reply@'.WEBSITE_HOST;
$config['project_dispute_email_from_name_sent_to_po_dispute_decided_admin_arbitration_company_sp_winner'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['project_dispute_email_subject_sent_to_po_dispute_decided_admin_arbitration_company_sp_winner'] = 'dispute case {dispute_reference_id} Closed By Admin[company SP]';

$config['project_dispute_email_message_sent_to_po_dispute_decided_admin_arbitration_company_sp_winner'] = '<p>Hello {po_first_name_last_name_or_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed By admin.You lost the dispute against <a href="{sp_profile_url_link}" target="_blank">{sp_company_name}</a>.Total disputed amount was {project_disputed_amount}, therefore {project_disputed_amount_excluding_admin_arbitration_fee} was transferred to <a href="{sp_profile_url_link}" target="_blank">{sp_company_name}</a>. Bussiness service fees {project_disputed_amount_service_fees} and dispute arbitartion fees {admin_dispute_arbitration_amount_fee} charged from you<p>';

// For fulltime project
$config['fulltime_project_dispute_email_cc_sent_to_employer_dispute_decided_admin_arbitration_company_employee_winner'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_bcc_sent_to_employer_dispute_decided_admin_arbitration_company_employee_winner'] = 'catalin.basturescu@gmail.com';
$config['fulltime_project_dispute_email_from_sent_to_employer_dispute_decided_admin_arbitration_company_employee_winner'] = 'registrace@'.WEBSITE_HOST;
$config['fulltime_project_dispute_email_reply_to_sent_to_employer_dispute_decided_admin_arbitration_company_employee_winner'] = 'no-reply@'.WEBSITE_HOST;
$config['fulltime_project_dispute_email_from_name_sent_to_employer_dispute_decided_admin_arbitration_company_employee_winner'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['fulltime_project_dispute_email_subject_sent_to_employer_dispute_decided_admin_arbitration_company_employee_winner'] = 'Fulltime:dispute case {dispute_reference_id} Closed By Admin[Company SP]';

$config['fulltime_project_dispute_email_message_sent_to_employer_dispute_decided_admin_arbitration_company_employee_winner'] = '<p>Fulltime:Hello {po_first_name_last_name_or_company_name}</p><p>dispute case <a href="{project_dispute_details_page_url}" target="_blank">{dispute_reference_id}</a> was closed By admin.You lost the dispute against <a href="{sp_profile_url_link}" target="_blank">{sp_company_name}</a>.Total disputed amount was {fulltime_project_disputed_amount}, therefore {fulltime_project_disputed_amount_excluding_admin_arbitration_fee} was transferred to <a href="{sp_profile_url_link}" target="_blank">{sp_company_name}</a>. Bussiness service fees {fulltime_project_disputed_amount_service_fees} and dispute arbitartion fees {admin_dispute_arbitration_amount_fee} charged from you<p>';

// Email configs when dipsute is initiated and its go to admin arbitration, sent to initiator

// For fixed/hourly project

$config['initiate_project_dispute_email_cc_sent_to_initiator_dispute_decided_admin_arbitration'] = 'catalin.basturescu@gmail.com';
$config['initiate_project_dispute_email_bcc_sent_to_initiator_dispute_decided_admin_arbitration'] = 'catalin.basturescu@gmail.com';
$config['initiate_project_dispute_email_from_sent_to_initiator_dispute_decided_admin_arbitration'] = 'registrace@'.HTTP_HOST;
$config['initiate_project_dispute_email_reply_to_sent_to_initiator_dispute_decided_admin_arbitration'] = 'no-reply@'.HTTP_HOST;
$config['initiate_project_dispute_email_from_name_sent_to_initiator_dispute_decided_admin_arbitration'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['initiate_project_dispute_email_subject_sent_to_initiator_dispute_decided_admin_arbitration'] = 'Nejvíce pracovních příležitostí na jednom místě';
$config['initiate_project_dispute_email_message_sent_to_initiator_dispute_decided_admin_arbitration'] = '<p>Your dispute case {dispute_reference_id}</p><p>Hello {user_first_name_last_name_or_company_name},</p><p>we have informed <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> about your decission to initiate a dispute case on project <a href="{project_url_link}" target="_blank">{project_title}</a> for the amount of {project_disputed_amount}</p><p>You need to allow time for <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> the seller to respond.</p><p>You have 28 calendar days, from the date the dispute is initiated, to negotiate and amiabily reach an agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a></p><p>If you can’t reach any agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> by date+time {dispute_negotiation_period}, your case will be automatically escalated to administration team will decide the outcome (usually within 10 working days).</p><p>In case your dispute is escalated to administration team, arbitration fee of {admin_dispute_arbitration_fee}% will be charged - the fee wil be deducted from the disputed amount.</p><p>There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage.</p><p>Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.</p><p>Should you have any question, do not hesitate to contact us at 999999999.</p>';

// for fulltime

$config['initiate_fulltime_project_dispute_email_cc_sent_to_initiator_dispute_decided_admin_arbitration'] = 'catalin.basturescu@gmail.com';
$config['initiate_fulltime_project_dispute_email_bcc_sent_to_initiator_dispute_decided_admin_arbitration'] = 'catalin.basturescu@gmail.com';
$config['initiate_fulltime_project_dispute_email_from_sent_to_initiator_dispute_decided_admin_arbitration'] = 'registrace@'.HTTP_HOST;
$config['initiate_fulltime_project_dispute_email_reply_to_sent_to_initiator_dispute_decided_admin_arbitration'] = 'no-reply@'.HTTP_HOST;
$config['initiate_fulltime_project_dispute_email_from_name_sent_to_initiator_dispute_decided_admin_arbitration'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['initiate_fulltime_project_dispute_email_subject_sent_to_initiator_dispute_decided_admin_arbitration'] = 'Fulltime:Nejvíce pracovních příležitostí na jednom místě';
$config['initiate_fulltime_project_dispute_email_message_sent_to_initiator_dispute_decided_admin_arbitration'] = '<p>Fulltime:Your dispute case {dispute_reference_id}</p><p>Hello {user_first_name_last_name_or_company_name},</p><p>we have informed <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> about your decission to initiate a dispute case on project <a href="{project_url_link}" target="_blank">{project_title}</a> for the amount of {fulltime_project_disputed_amount}</p><p>You need to allow time for <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> the seller to respond.</p><p>You have 28 calendar days, from the date the dispute is initiated, to negotiate and amiabily reach an agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a></p><p>If you can’t reach any agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> by date+time {dispute_negotiation_period}, your case will be automatically escalated to administration team will decide the outcome (usually within 10 working days).</p><p>In case your dispute is escalated to administration team, arbitration fee of {admin_dispute_arbitration_fee}% will be charged - the fee wil be deducted from the disputed amount.</p><p>There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage.</p><p>Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.</p><p>Should you have any question, do not hesitate to contact us at 999999999.</p>';



// Email configs when dipsute is initiated and its go to admin arbitration, sent to disputee based on initiator gender

// When initiator gender is male

// For fixed/hourly
$config['initiate_project_dispute_by_male_initiator_email_cc_sent_to_disputee_dispute_decided_admin_arbitration'] = 'catalin.basturescu@gmail.com';
$config['initiate_project_dispute_by_male_initiator_email_bcc_sent_to_disputee_dispute_decided_admin_arbitration'] = 'catalin.basturescu@gmail.com';
$config['initiate_project_dispute_by_male_initiator_email_from_sent_to_disputee_dispute_decided_admin_arbitration'] = 'registrace@'.HTTP_HOST;
$config['initiate_project_dispute_by_male_initiator_email_reply_to_sent_to_disputee_dispute_decided_admin_arbitration'] = 'no-reply@'.HTTP_HOST;
$config['initiate_project_dispute_by_male_initiator_email_from_name_sent_to_disputee_dispute_decided_admin_arbitration'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['initiate_project_dispute_by_male_initiator_email_subject_sent_to_disputee_dispute_decided_admin_arbitration'] = 'Přímý přístup k seznamu odborníků a společností z celé ČR';
$config['initiate_project_dispute_by_male_initiator_email_message_sent_to_disputee_dispute_decided_admin_arbitration'] = '<p>this one is initial email sent to other party</p><p>Hello {user_first_name_last_name_or_company_name},</p><p><a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> decided to initiate a dispute case on project <a href="{project_url_link}" target="_blank">{project_title}</a> for the amount of {project_disputed_amount}.
</p><p>You have 28 calendar days, from the date the dispute is initiated {dispute_initiated_date}, to negotiate and amiably reach an agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a>.</p><p>If you can’t reach any agreement with him by date+time {dispute_negotiation_period}, the dispute case will be automatically escalated to administration team to decide the outcome (usually within 10 working days).
</p><p>In case the case is escalated to administration team, arbitration fee of {admin_dispute_arbitration_fee}% will be charged - the fee will be deducted from the disputed amount.</p><p>There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage.</p><p>Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.</p><p>Should you have any question, do not hesitate to contact us at 9999999999.</p>';

// For fulltime 
$config['initiate_fulltime_project_dispute_by_male_initiator_email_cc_sent_to_disputee_dispute_decided_admin_arbitration'] = 'catalin.basturescu@gmail.com';
$config['initiate_fulltime_project_dispute_by_male_initiator_email_bcc_sent_to_disputee_dispute_decided_admin_arbitration'] = 'catalin.basturescu@gmail.com';
$config['initiate_fulltime_project_dispute_by_male_initiator_email_from_sent_to_disputee_dispute_decided_admin_arbitration'] = 'registrace@'.HTTP_HOST;
$config['initiate_fulltime_project_dispute_by_male_initiator_email_reply_to_sent_to_disputee_dispute_decided_admin_arbitration'] = 'no-reply@'.HTTP_HOST;
$config['initiate_fulltime_project_dispute_by_male_initiator_email_from_name_sent_to_disputee_dispute_decided_admin_arbitration'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['initiate_fulltime_project_dispute_by_male_initiator_email_subject_sent_to_disputee_dispute_decided_admin_arbitration'] = 'Fulltime:Přímý přístup k seznamu odborníků a společností z celé ČR';
$config['initiate_fulltime_project_dispute_by_male_initiator_email_message_sent_to_disputee_dispute_decided_admin_arbitration'] = '<p>Fulltime:this one is initial email sent to other party</p><p>Hello {user_first_name_last_name_or_company_name},</p><p><a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> decided to initiate a dispute case on project <a href="{project_url_link}" target="_blank">{project_title}</a> for the amount of {fulltime_project_disputed_amount}.
</p><p>You have 28 calendar days, from the date the dispute is initiated {dispute_initiated_date}, to negotiate and amiably reach an agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a>.</p><p>If you can’t reach any agreement with him by date+time {dispute_negotiation_period}, the dispute case will be automatically escalated to administration team to decide the outcome (usually within 10 working days).
</p><p>In case the case is escalated to administration team, arbitration fee of {admin_dispute_arbitration_fee}% will be charged - the fee will be deducted from the disputed amount.</p><p>There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage.</p><p>Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.</p><p>Should you have any question, do not hesitate to contact us at 9999999999.</p>';



// When initiator gender is female
// For fixed/hourly
$config['initiate_project_dispute_by_female_initiator_email_cc_sent_to_disputee_dispute_decided_admin_arbitration'] = 'catalin.basturescu@gmail.com';
$config['initiate_project_dispute_by_female_initiator_email_bcc_sent_to_disputee_dispute_decided_admin_arbitration'] = 'catalin.basturescu@gmail.com';
$config['initiate_project_dispute_by_female_initiator_email_from_sent_to_disputee_dispute_decided_admin_arbitration'] = 'registrace@'.HTTP_HOST;
$config['initiate_project_dispute_by_female_initiator_email_reply_to_sent_to_disputee_dispute_decided_admin_arbitration'] = 'no-reply@'.HTTP_HOST;
$config['initiate_project_dispute_by_female_initiator_email_from_name_sent_to_disputee_dispute_decided_admin_arbitration'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['initiate_project_dispute_by_female_initiator_email_subject_sent_to_disputee_dispute_decided_admin_arbitration'] = 'Přímý přístup k seznamu odborníků a společností z celé ČR';
$config['initiate_project_dispute_by_female_initiator_email_message_sent_to_disputee_dispute_decided_admin_arbitration'] = '<p>this one is initial email sent to other party</p><p>Hello {user_first_name_last_name_or_company_name},</p><p><a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> decided to initiate a dispute case on project <a href="{project_url_link}" target="_blank">{project_title}</a> for the amount of {project_disputed_amount}.
</p><p>You have 28 calendar days, from the date the dispute is initiated {dispute_initiated_date}, to negotiate and amiably reach an agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a>.</p><p>If you can’t reach any agreement with her by date+time {dispute_negotiation_period}, the dispute case will be automatically escalated to administration team to decide the outcome (usually within 10 working days).
</p><p>In case the case is escalated to administration team, arbitration fee of {admin_dispute_arbitration_fee}% will be charged - the fee will be deducted from the disputed amount.</p><p>There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage.</p><p>Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.</p><p>Should you have any question, do not hesitate to contact us at 9999999999.</p>';

// for FULLTIME
$config['initiate_fulltime_project_dispute_by_female_initiator_email_cc_sent_to_disputee_dispute_decided_admin_arbitration'] = 'catalin.basturescu@gmail.com';
$config['initiate_fulltime_project_dispute_by_female_initiator_email_bcc_sent_to_disputee_dispute_decided_admin_arbitration'] = 'catalin.basturescu@gmail.com';
$config['initiate_fulltime_project_dispute_by_female_initiator_email_from_sent_to_disputee_dispute_decided_admin_arbitration'] = 'registrace@'.HTTP_HOST;
$config['initiate_fulltime_project_dispute_by_female_initiator_email_reply_to_sent_to_disputee_dispute_decided_admin_arbitration'] = 'no-reply@'.HTTP_HOST;
$config['initiate_fulltime_project_dispute_by_female_initiator_email_from_name_sent_to_disputee_dispute_decided_admin_arbitration'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['initiate_fulltime_project_dispute_by_female_initiator_email_subject_sent_to_disputee_dispute_decided_admin_arbitration'] = 'Fulltime:Přímý přístup k seznamu odborníků a společností z celé ČR';
$config['initiate_fulltime_project_dispute_by_female_initiator_email_message_sent_to_disputee_dispute_decided_admin_arbitration'] = '<p>Fulltime:this one is initial email sent to other party</p><p>Hello {user_first_name_last_name_or_company_name},</p><p><a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> decided to initiate a dispute case on project <a href="{project_url_link}" target="_blank">{project_title}</a> for the amount of {fulltime_project_disputed_amount}.
</p><p>You have 28 calendar days, from the date the dispute is initiated {dispute_initiated_date}, to negotiate and amiably reach an agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a>.</p><p>If you can’t reach any agreement with her by date+time {dispute_negotiation_period}, the dispute case will be automatically escalated to administration team to decide the outcome (usually within 10 working days).
</p><p>In case the case is escalated to administration team, arbitration fee of {admin_dispute_arbitration_fee}% will be charged - the fee will be deducted from the disputed amount.</p><p>There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage.</p><p>Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.</p><p>Should you have any question, do not hesitate to contact us at 9999999999.</p>';



// When initiator gender is company

// for fixed/hourly
$config['initiate_project_dispute_by_company_initiator_email_cc_sent_to_disputee_dispute_decided_admin_arbitration'] = 'catalin.basturescu@gmail.com';
$config['initiate_project_dispute_by_company_initiator_email_bcc_sent_to_disputee_dispute_decided_admin_arbitration'] = 'catalin.basturescu@gmail.com';
$config['initiate_project_dispute_by_company_initiator_email_from_sent_to_disputee_dispute_decided_admin_arbitration'] = 'registrace@'.HTTP_HOST;
$config['initiate_project_dispute_by_company_initiator_email_reply_to_sent_to_disputee_dispute_decided_admin_arbitration'] = 'no-reply@'.HTTP_HOST;
$config['initiate_project_dispute_by_company_initiator_email_from_name_sent_to_disputee_dispute_decided_admin_arbitration'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['initiate_project_dispute_by_company_initiator_email_subject_sent_to_disputee_dispute_decided_admin_arbitration'] = 'Přímý přístup k seznamu odborníků a společností z celé ČR';
$config['initiate_project_dispute_by_company_initiator_email_message_sent_to_disputee_dispute_decided_admin_arbitration'] = '<p>Hello {user_first_name_last_name_or_company_name},</p><p><a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> decided to initiate a dispute case on project <a href="{project_url_link}" target="_blank">{project_title}</a> for the amount of {project_disputed_amount}.
</p><p>You have 28 calendar days, from the date the dispute is initiated {dispute_initiated_date}, to negotiate and amiably reach an agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a>.</p><p>If you can’t reach any agreement with it by date+time {dispute_negotiation_period}, the dispute case will be automatically escalated to administration team to decide the outcome (usually within 10 working days).
</p><p>In case the case is escalated to administration team, arbitration fee of {admin_dispute_arbitration_fee}% will be charged - the fee will be deducted from the disputed amount.</p><p>There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage.</p><p>Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.</p><p>Should you have any question, do not hesitate to contact us at 9999999999.</p>';

// for fulltime
$config['initiate_fulltime_project_dispute_by_company_initiator_email_cc_sent_to_disputee_dispute_decided_admin_arbitration'] = 'catalin.basturescu@gmail.com';
$config['initiate_fulltime_project_dispute_by_company_initiator_email_bcc_sent_to_disputee_dispute_decided_admin_arbitration'] = 'catalin.basturescu@gmail.com';
$config['initiate_fulltime_project_dispute_by_company_initiator_email_from_sent_to_disputee_dispute_decided_admin_arbitration'] = 'registrace@'.HTTP_HOST;
$config['initiate_fulltime_project_dispute_by_company_initiator_email_reply_to_sent_to_disputee_dispute_decided_admin_arbitration'] = 'no-reply@'.HTTP_HOST;
$config['initiate_fulltime_project_dispute_by_company_initiator_email_from_name_sent_to_disputee_dispute_decided_admin_arbitration'] = 'že některé informace zveřejněné manish.Devserver1.info';
$config['initiate_fulltime_project_dispute_by_company_initiator_email_subject_sent_to_disputee_dispute_decided_admin_arbitration'] = 'Fulltime:Přímý přístup k seznamu odborníků a společností z celé ČR';
$config['initiate_fulltime_project_dispute_by_company_initiator_email_message_sent_to_disputee_dispute_decided_admin_arbitration'] = '<p>Fulltime:Hello {user_first_name_last_name_or_company_name},</p><p><a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a> decided to initiate a dispute case on project <a href="{project_url_link}" target="_blank">{project_title}</a> for the amount of {fulltime_project_disputed_amount}.
</p><p>You have 28 calendar days, from the date the dispute is initiated {dispute_initiated_date}, to negotiate and amiably reach an agreement with <a href="{other_party_profile_url_link}" target="_blank">{other_party_first_name_last_name_or_company_name}</a>.</p><p>If you can’t reach any agreement with it by date+time {dispute_negotiation_period}, the dispute case will be automatically escalated to administration team to decide the outcome (usually within 10 working days).
</p><p>In case the case is escalated to administration team, arbitration fee of {admin_dispute_arbitration_fee}% will be charged - the fee will be deducted from the disputed amount.</p><p>There is no charges encountered if the dispute is either settled through mutual agreement or cancelled before reaching arbitration stage.</p><p>Once a case has been moved to admin arbitration process and decision has been taken, the dispute is closed and cannot be reopened.</p><p>Should you have any question, do not hesitate to contact us at 9999999999.</p>';
?>
	