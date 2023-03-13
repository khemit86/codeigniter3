<?php
$user = $this->session->userdata('user');
$sp_name = $sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $sp_data['first_name'] . ' ' . $sp_data['last_name'] : $sp_data['company_name'];
$po_name = $po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $po_data['first_name'] . ' ' . $po_data['last_name'] : $po_data['company_name'];



$get_projects_closed_disputes_po_reverted_amounts_data = get_projects_closed_disputes_po_reverted_amounts($project_type,array('dispute_reference_id'=>$projects_disputes_data['dispute_reference_id']));

/* echo "<pre>";
print_r($get_projects_closed_disputes_po_reverted_amounts_data);
die; */


if(($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_po' && $user[0]->user_id == $projects_disputes_data['project_owner_id_of_disputed_project']) || ($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_sp' && $user[0]->user_id == $projects_disputes_data['sp_winner_id_of_disputed_project']) || ($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employer' && $user[0]->user_id == $projects_disputes_data['employer_id_of_disputed_fulltime_project']) || ($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employee' && $user[0]->user_id == $projects_disputes_data['employee_winner_id_of_disputed_fulltime_project'])){
	
	if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_po' && $user[0]->user_id == $projects_disputes_data['project_owner_id_of_disputed_project']){
		$other_party_name = $sp_name;
		$dispute_closed_reason = $this->config->item('project_dispute_details_page_dispute_cancelled_by_po_initiator_po_view_reason_txt');
		
		$dispute_closed_reason = str_replace(array('{project_disputed_amount}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,$other_party_name),$dispute_closed_reason);
		
	}
	if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employer' && $user[0]->user_id == $projects_disputes_data['employer_id_of_disputed_fulltime_project']){
		$other_party_name = $sp_name;
		$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_dispute_cancelled_by_employer_initiator_employer_view_reason_txt');
		$dispute_closed_reason = str_replace(array('{fulltime_project_disputed_amount}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,$other_party_name),$dispute_closed_reason);
	}
	if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_sp' && $user[0]->user_id == $projects_disputes_data['sp_winner_id_of_disputed_project']){
		$other_party_name = $po_name;
		$dispute_closed_reason = $this->config->item('project_dispute_details_page_dispute_cancelled_by_sp_initiator_sp_view_reason_txt');
		$dispute_closed_reason = str_replace(array('{project_disputed_amount}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,$other_party_name),$dispute_closed_reason);
	}
	if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employee' && $user[0]->user_id == $projects_disputes_data['employee_winner_id_of_disputed_fulltime_project']){
		$other_party_name = $po_name;
		$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_dispute_cancelled_by_employee_initiator_employee_view_reason_txt');
		$dispute_closed_reason = str_replace(array('{fulltime_project_disputed_amount}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,$other_party_name),$dispute_closed_reason);
	}
	
	$dispute_closed_reason = str_replace(array('{dispute_end_date}','{other_party_first_name_last_name_or_company_name}'),array(date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date'])),$other_party_name),$dispute_closed_reason);
	
	$dispute_result_txt = $this->config->item('project_dispute_details_page_dispute_resolved_txt');
	
}else if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_sp' || $projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_po' || $projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employer' || $projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employee'){

$dispute_result_txt = $this->config->item('project_dispute_details_page_dispute_resolved_txt');
	if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_po' || $projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employer'){
		
		if($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
			
			if($po_data['gender'] == 'M'){
				
				if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_po'){
					$dispute_closed_reason = $this->config->item('project_dispute_details_page_dispute_cancelled_by_male_po_initiator_sp_view_reason_txt');
				}
				if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employer'){
					$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_dispute_cancelled_by_male_employer_initiator_employee_view_reason_txt');
					
				}
				
			}else{
				if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_po'){
					$dispute_closed_reason = $this->config->item('project_dispute_details_page_dispute_cancelled_by_female_po_initiator_sp_view_reason_txt');
				}
				if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employer'){
					$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_dispute_cancelled_by_female_employer_initiator_employee_view_reason_txt');
				}
				
				
				
			}
			if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_po'){
				$dispute_closed_reason = str_replace(array('{project_disputed_amount}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			}
			if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employer'){
				$dispute_closed_reason = str_replace(array('{fulltime_project_disputed_amount}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			}
			
			$dispute_closed_reason = str_replace(array('{dispute_end_date}','{po_first_name_last_name}'),array(date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date'])),$po_name),$dispute_closed_reason);
			
		}else{
			if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_po'){
				$dispute_closed_reason = $this->config->item('project_dispute_details_page_dispute_cancelled_by_company_po_initiator_sp_view_reason_txt');
			}
			if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employer'){
				$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_dispute_cancelled_by_company_employer_initiator_employee_view_reason_txt');
			}
			
			if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_po'){
				$dispute_closed_reason = str_replace(array('{project_disputed_amount}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			}
			if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employer'){
				$dispute_closed_reason = str_replace(array('{fulltime_project_disputed_amount}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			}	
				
			$dispute_closed_reason = str_replace(array('{dispute_end_date}','{po_company_name}'),array(date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date'])),$po_name),$dispute_closed_reason);
		}
		
	
	}
	if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_sp' || $projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employee'){
		
		if($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
			
			if($sp_data['gender'] == 'M'){
				if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_sp'){
					$dispute_closed_reason = $this->config->item('project_dispute_details_page_dispute_cancelled_by_male_sp_initiator_po_view_reason_txt');
				}
				if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employee'){
					$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_dispute_cancelled_by_male_employee_initiator_employer_view_reason_txt');
					
				}
				
			}else{
				if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_sp'){
					$dispute_closed_reason = $this->config->item('project_dispute_details_page_dispute_cancelled_by_female_sp_initiator_po_view_reason_txt');
				}	
				if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employee'){
					$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_dispute_cancelled_by_female_employee_initiator_employer_view_reason_txt');
					
				}
			}
			
			if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_sp'){
				$dispute_closed_reason = str_replace(array('{project_disputed_amount}','{project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_service_fees'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			}
			if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employee'){
				$dispute_closed_reason = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_service_fees'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			}
			
			
			$dispute_closed_reason = str_replace(array('{dispute_end_date}','{sp_first_name_last_name}'),array(date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date'])),$sp_name),$dispute_closed_reason);
			
		}else{
			if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_sp'){
				$dispute_closed_reason = $this->config->item('project_dispute_details_page_dispute_cancelled_by_company_sp_initiator_po_view_reason_txt');
			}
			if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employee'){
				$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_dispute_cancelled_by_company_employee_initiator_employer_view_reason_txt');
				
			}
			
			if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_sp'){
				$dispute_closed_reason = str_replace(array('{project_disputed_amount}','{project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_service_fees'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			}
			if($projects_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employee'){
				$dispute_closed_reason = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_service_fees'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			}				
			$dispute_closed_reason = str_replace(array('{dispute_end_date}','{sp_company_name}'),array(date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date'])),$sp_name),$dispute_closed_reason);
		}
		
	}
	
}
if($projects_disputes_data['dispute_status'] == 'automatic_decision' && ($user[0]->user_id == $projects_disputes_data['project_owner_id_of_disputed_project'] || $user[0]->user_id == $projects_disputes_data['sp_winner_id_of_disputed_project'] || $projects_disputes_data['employer_id_of_disputed_fulltime_project'] || $user[0]->user_id == $projects_disputes_data['employee_winner_id_of_disputed_fulltime_project'])){
	
	$dispute_result_txt = $this->config->item('project_dispute_details_page_dispute_closed_txt');
	
	
	$released_escrow_payment_amount = $projects_disputes_data['disputed_amount']/2;
	$service_fee_charges = $projects_disputes_data['disputed_service_fees']/2;
	
	if($user[0]->user_id == $projects_disputes_data['project_owner_id_of_disputed_project']){
		
		$other_party_name = $sp_name;
		
		$dispute_closed_reason = $this->config->item('project_dispute_details_page_when_dispute_project_decided_automatic_po_view_reason_txt');
		
		$dispute_closed_reason = str_replace(array('{dispute_end_date}','{project_disputed_amount}','{other_party_first_name_last_name_or_company_name}','{project_50%_disputed_amount}','{project_50%_disputed_amount_service_fees}','{project_disputed_amount_service_fees}'),array(date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date'])),str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,$sp_name,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($service_fee_charges,  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$dispute_closed_reason);
		
	}if($user[0]->user_id == $projects_disputes_data['sp_winner_id_of_disputed_project']){
		
		
		
		$dispute_closed_reason = $this->config->item('project_dispute_details_page_when_dispute_project_decided_automatic_sp_view_reason_txt');
		
		$dispute_closed_reason = str_replace(array('{dispute_end_date}','{project_disputed_amount}','{project_50%_disputed_amount}'),array(date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date'])),str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY),$dispute_closed_reason);
		
	}
	if($user[0]->user_id == $projects_disputes_data['employer_id_of_disputed_fulltime_project']){
		
		$other_party_name = $sp_name;
		
		$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_when_dispute_project_decided_automatic_employer_view_reason_txt');
		
		$dispute_closed_reason = str_replace(array('{dispute_end_date}','{fulltime_project_disputed_amount}','{other_party_first_name_last_name_or_company_name}','{fulltime_project_50%_disputed_amount}','{fulltime_project_50%_disputed_amount_service_fees}','{fulltime_project_disputed_amount_service_fees}'),array(date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date'])),str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,$sp_name,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($service_fee_charges,  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$dispute_closed_reason);
		
	}if($user[0]->user_id == $projects_disputes_data['employee_winner_id_of_disputed_fulltime_project']){
		
		$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_when_dispute_project_decided_automatic_employee_view_reason_txt');
		
		$dispute_closed_reason = str_replace(array('{dispute_end_date}','{fulltime_project_disputed_amount}','{fulltime_project_50%_disputed_amount}'),array(date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date'])),str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY),$dispute_closed_reason);
		
	}	
	
}	
if(($projects_disputes_data['dispute_status'] == 'parties_agreement' && $user[0]->user_id == $projects_disputes_data['project_owner_id_of_disputed_project']) || ($projects_disputes_data['dispute_status'] == 'parties_agreement' && $user[0]->user_id == $projects_disputes_data['sp_winner_id_of_disputed_project']) || ($projects_disputes_data['dispute_status'] == 'parties_agreement' && $user[0]->user_id == $projects_disputes_data['employer_id_of_disputed_fulltime_project']) || ($projects_disputes_data['dispute_status'] == 'parties_agreement' && $user[0]->user_id == $projects_disputes_data['employee_winner_id_of_disputed_fulltime_project'])){
	
	
	if($projects_disputes_data['disputed_winner_id'] == $projects_disputes_data['project_owner_id_of_disputed_project']){
	
		if($projects_disputes_data['dispute_status'] == 'parties_agreement' && $user[0]->user_id == $projects_disputes_data['sp_winner_id_of_disputed_project']){
			
			$dispute_closed_reason = $this->config->item('project_dispute_details_page_dispute_counter_offer_accepted_by_sp_sp_view_reason_txt');
			$dispute_closed_reason = str_replace(array('{po_first_name_last_name_or_company_name}','{project_counter_offer_value}','{dispute_end_date}'),array($po_name,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date']))),$dispute_closed_reason);
			
			
		}

		if($projects_disputes_data['dispute_status'] == 'parties_agreement' && $user[0]->user_id == $projects_disputes_data['project_owner_id_of_disputed_project']){
			
			
			if($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
				
				if($sp_data['gender'] == 'M'){
					$dispute_closed_reason = $this->config->item('project_dispute_details_page_dispute_counter_offer_accepted_by_male_sp_po_view_reason_txt');
					
				}else{
					$dispute_closed_reason = $this->config->item('project_dispute_details_page_dispute_counter_offer_accepted_by_female_sp_po_view_reason_txt');
				}
				$dispute_closed_reason = str_replace(array('{sp_first_name_last_name}','{project_counter_offer_value}','{dispute_end_date}'),array($sp_name,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date']))),$dispute_closed_reason);
				
				
			}else{
				$dispute_closed_reason = $this->config->item('project_dispute_details_page_dispute_counter_offer_accepted_by_company_sp_po_view_reason_txt');
				$dispute_closed_reason = str_replace(array('{sp_company_name}','{project_counter_offer_value}','{dispute_end_date}'),array($sp_name,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date']))),$dispute_closed_reason);
				
				
			}
			
			
			$dispute_closed_reason = str_replace(array('{service_fees_charged_from_po}','{project_remaining_amount}','{service_fees_return_to_po}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_service_fees'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($get_projects_closed_disputes_po_reverted_amounts_data['reverted_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($get_projects_closed_disputes_po_reverted_amounts_data['reverted_service_fee_amount'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			
			
		}
	}
	if($projects_disputes_data['disputed_winner_id'] == $projects_disputes_data['employer_id_of_disputed_fulltime_project']){
	
		if($projects_disputes_data['dispute_status'] == 'parties_agreement' && $user[0]->user_id == $projects_disputes_data['employee_winner_id_of_disputed_fulltime_project']){
			
			$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_dispute_counter_offer_accepted_by_employee_employee_view_reason_txt');
			$dispute_closed_reason = str_replace(array('{po_first_name_last_name_or_company_name}','{fulltime_project_counter_offer_value}','{dispute_end_date}'),array($po_name,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date']))),$dispute_closed_reason);
			
			
		}

		if($projects_disputes_data['dispute_status'] == 'parties_agreement' && $user[0]->user_id == $projects_disputes_data['employer_id_of_disputed_fulltime_project']){
			
			
			if($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
				
				if($sp_data['gender'] == 'M'){
					
					$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_dispute_counter_offer_accepted_by_male_employee_employer_view_reason_txt');
					
					
				}else{
					$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_dispute_counter_offer_accepted_by_female_employee_employer_view_reason_txt');
				}
				$dispute_closed_reason = str_replace(array('{sp_first_name_last_name}','{fulltime_project_counter_offer_value}','{dispute_end_date}'),array($sp_name,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date']))),$dispute_closed_reason);
				
				
			}else{
				$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_dispute_counter_offer_accepted_by_company_employee_employer_view_reason_txt');
				$dispute_closed_reason = str_replace(array('{sp_company_name}','{fulltime_project_counter_offer_value}','{dispute_end_date}'),array($sp_name,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date']))),$dispute_closed_reason);
				
				
			}
			
			
			$dispute_closed_reason = str_replace(array('{service_fees_charged_from_po}','{fulltime_project_remaining_amount}','{service_fees_return_to_po}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_service_fees'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($get_projects_closed_disputes_po_reverted_amounts_data['reverted_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($get_projects_closed_disputes_po_reverted_amounts_data['reverted_service_fee_amount'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			
			
		}
	}
	if($projects_disputes_data['disputed_winner_id'] == $projects_disputes_data['sp_winner_id_of_disputed_project']){
	
		if($projects_disputes_data['dispute_status'] == 'parties_agreement' && $user[0]->user_id == $projects_disputes_data['project_owner_id_of_disputed_project']){
			//$other_party_name = $sp_name;
			$dispute_closed_reason = $this->config->item('project_dispute_details_page_dispute_counter_offer_accepted_by_po_po_view_reason_txt');
			
			$dispute_closed_reason = str_replace(array('{sp_first_name_last_name_or_company_name}','{project_counter_offer_value}','{dispute_end_date}','{service_fees_charged_from_po}','{project_remaining_amount}','{service_fees_return_to_po}'),array($sp_name,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date'])),str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_service_fees'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($get_projects_closed_disputes_po_reverted_amounts_data['reverted_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($get_projects_closed_disputes_po_reverted_amounts_data['reverted_service_fee_amount'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			
			
			
		}
		if($projects_disputes_data['dispute_status'] == 'parties_agreement' && $user[0]->user_id == $projects_disputes_data['sp_winner_id_of_disputed_project']){
			
			if($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
				
				if($po_data['gender'] == 'M'){
					$dispute_closed_reason = $this->config->item('project_dispute_details_page_dispute_counter_offer_accepted_by_male_po_sp_view_reason_txt');
					
				}else{
					$dispute_closed_reason = $this->config->item('project_dispute_details_page_dispute_counter_offer_accepted_by_female_po_sp_view_reason_txt');
				}
				$dispute_closed_reason = str_replace(array('{po_first_name_last_name}','{project_counter_offer_value}','{dispute_end_date}'),array($po_name,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date']))),$dispute_closed_reason);
				
			}else{
				$dispute_closed_reason = $this->config->item('project_dispute_details_page_dispute_counter_offer_accepted_by_company_po_sp_view_reason_txt');
				
				$dispute_closed_reason = str_replace(array('{po_company_name}','{project_counter_offer_value}','{dispute_end_date}'),array($po_name,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date']))),$dispute_closed_reason);
			}
			
		}
	}
	if($projects_disputes_data['disputed_winner_id'] == $projects_disputes_data['employee_winner_id_of_disputed_fulltime_project']){
	
		if($projects_disputes_data['dispute_status'] == 'parties_agreement' && $user[0]->user_id == $projects_disputes_data['employer_id_of_disputed_fulltime_project']){
			//$other_party_name = $sp_name;
			$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_dispute_counter_offer_accepted_by_employer_employer_view_reason_txt');
			
			$dispute_closed_reason = str_replace(array('{sp_first_name_last_name_or_company_name}','{fulltime_project_counter_offer_value}','{dispute_end_date}','{service_fees_charged_from_po}','{fulltime_project_remaining_amount}','{service_fees_return_to_po}'),array($sp_name,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date'])),str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_service_fees'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($get_projects_closed_disputes_po_reverted_amounts_data['reverted_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($get_projects_closed_disputes_po_reverted_amounts_data['reverted_service_fee_amount'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			
			
			
		}
		if($projects_disputes_data['dispute_status'] == 'parties_agreement' && $user[0]->user_id == $projects_disputes_data['employee_winner_id_of_disputed_fulltime_project']){
			
			if($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
				
				if($po_data['gender'] == 'M'){
					$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_dispute_counter_offer_accepted_by_male_po_sp_view_reason_txt');
					
				}else{
					$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_dispute_counter_offer_accepted_by_female_po_sp_view_reason_txt');
				}
				$dispute_closed_reason = str_replace(array('{po_first_name_last_name}','{fulltime_project_counter_offer_value}','{dispute_end_date}'),array($po_name,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date']))),$dispute_closed_reason);
				
			}else{
				$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_dispute_counter_offer_accepted_by_company_po_sp_view_reason_txt');
				
				$dispute_closed_reason = str_replace(array('{po_company_name}','{fulltime_project_counter_offer_value}','{dispute_end_date}'),array($po_name,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,date(DATE_FORMAT,strtotime($projects_disputes_data['dispute_end_date']))),$dispute_closed_reason);
			}
			
		}
	}
	
	$dispute_result_txt = $this->config->item('project_dispute_details_page_dispute_resolved_txt');
	
}
if(($projects_disputes_data['dispute_status'] == 'admin_decision' && $user[0]->user_id == $projects_disputes_data['project_owner_id_of_disputed_project']) || ($projects_disputes_data['dispute_status'] == 'admin_decision' && $user[0]->user_id == $projects_disputes_data['sp_winner_id_of_disputed_project']) || ($projects_disputes_data['dispute_status'] == 'admin_decision' && $user[0]->user_id == $projects_disputes_data['employer_id_of_disputed_fulltime_project']) || ($projects_disputes_data['dispute_status'] == 'admin_decision' && $user[0]->user_id == $projects_disputes_data['employee_winner_id_of_disputed_fulltime_project'])){
	
	
	$get_projects_disputes_admin_arbitration_fees_data = get_projects_disputes_admin_arbitration_fees(array('dispute_reference_id'=>$projects_disputes_data['dispute_reference_id']));
	
	if($projects_disputes_data['disputed_winner_id'] == $projects_disputes_data['sp_winner_id_of_disputed_project']){
		
		if($projects_disputes_data['dispute_status'] == 'admin_decision' && $user[0]->user_id == $projects_disputes_data['sp_winner_id_of_disputed_project']){
			
			$dispute_closed_reason = $this->config->item('project_dispute_details_page_when_dispute_project_decided_admin_arbitration_sp_winner_sp_view_reason_txt');
			
			$dispute_closed_reason = str_replace(array('{project_disputed_amount}','{project_disputed_amount_excluding_admin_arbitration_fee}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			
			
			
		}
		if($projects_disputes_data['dispute_status'] == 'admin_decision' && $user[0]->user_id == $projects_disputes_data['project_owner_id_of_disputed_project']){
			
			
			
			if($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
				
				if($sp_data['gender'] == 'M'){
					$dispute_closed_reason = $this->config->item('project_dispute_details_page_when_dispute_project_decided_admin_arbitration_male_sp_winner_po_view_reason_txt');
				}else{
					$dispute_closed_reason = $this->config->item('project_dispute_details_page_when_dispute_project_decided_admin_arbitration_female_sp_winner_po_view_reason_txt');
				}	
				$dispute_closed_reason = str_replace(array('{sp_first_name_last_name}','{project_disputed_amount}','{project_disputed_amount_excluding_admin_arbitration_fee}','{project_disputed_amount_service_fees}','{admin_dispute_arbitration_amount_fee}'),array($sp_name,str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_service_fees'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($get_projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);

			}else{

				$dispute_closed_reason = $this->config->item('project_dispute_details_page_when_dispute_project_decided_admin_arbitration_company_sp_winner_po_view_reason_txt');
				
				$dispute_closed_reason = str_replace(array('{sp_company_name}','{project_disputed_amount}','{project_disputed_amount_excluding_admin_arbitration_fee}','{project_disputed_amount_service_fees}','{admin_dispute_arbitration_amount_fee}'),array($sp_name,str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_service_fees'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($get_projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			}		
			
			
		}	
	}
	if($projects_disputes_data['disputed_winner_id'] == $projects_disputes_data['employee_winner_id_of_disputed_fulltime_project']){
		
		if($projects_disputes_data['dispute_status'] == 'admin_decision' && $user[0]->user_id == $projects_disputes_data['employee_winner_id_of_disputed_fulltime_project']){
			
			$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_when_dispute_project_decided_admin_arbitration_employee_winner_employee_view_reason_txt');
			
			$dispute_closed_reason = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			
			
			
		}
		if($projects_disputes_data['dispute_status'] == 'admin_decision' && $user[0]->user_id == $projects_disputes_data['employer_id_of_disputed_fulltime_project']){
			
			
			
			if($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
				
				if($sp_data['gender'] == 'M'){
					$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_when_dispute_project_decided_admin_arbitration_male_employee_winner_employer_view_reason_txt');
				}else{
					$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_when_dispute_project_decided_admin_arbitration_female_employee_winner_employer_view_reason_txt');
				}	
				$dispute_closed_reason = str_replace(array('{sp_first_name_last_name}','{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}','{fulltime_project_disputed_amount_service_fees}','{admin_dispute_arbitration_amount_fee}'),array($sp_name,str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_service_fees'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($get_projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);

			}else{

				$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_when_dispute_project_decided_admin_arbitration_company_employee_winner_employer_view_reason_txt');
				
				$dispute_closed_reason = str_replace(array('{sp_company_name}','{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}','{fulltime_project_disputed_amount_service_fees}','{admin_dispute_arbitration_amount_fee}'),array($sp_name,str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_service_fees'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($get_projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			}		
			
			
		}	
	}
	if($projects_disputes_data['disputed_winner_id'] == $projects_disputes_data['project_owner_id_of_disputed_project']){
		
		if($projects_disputes_data['dispute_status'] == 'admin_decision' && $user[0]->user_id == $projects_disputes_data['project_owner_id_of_disputed_project']){
			
			$dispute_closed_reason = $this->config->item('project_dispute_details_page_when_dispute_project_decided_admin_arbitration_po_winner_po_view_reason_txt');
			
			$dispute_closed_reason = str_replace(array('{project_disputed_amount}','{project_disputed_amount_excluding_admin_arbitration_fee}','{project_disputed_amount_service_fees}','{admin_dispute_arbitration_amount_fee}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_service_fees'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($get_projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			
		}
		if($projects_disputes_data['dispute_status'] == 'admin_decision' && $user[0]->user_id == $projects_disputes_data['sp_winner_id_of_disputed_project']){
			
			if($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
				
				if($po_data['gender'] == 'M'){
					$dispute_closed_reason = $this->config->item('project_dispute_details_page_when_dispute_project_decided_admin_arbitration_male_po_winner_sp_view_reason_txt');
				}else{
					$dispute_closed_reason = $this->config->item('project_dispute_details_page_when_dispute_project_decided_admin_arbitration_female_po_winner_sp_view_reason_txt');
				}	
				
				$dispute_closed_reason = str_replace(array('{project_disputed_amount}','{project_disputed_amount_excluding_admin_arbitration_fee}','{po_first_name_last_name}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,$po_name),$dispute_closed_reason);
				
			}else{
				$dispute_closed_reason = $this->config->item('project_dispute_details_page_when_dispute_project_decided_admin_arbitration_company_po_winner_sp_view_reason_txt');
				
				$dispute_closed_reason = str_replace(array('{project_disputed_amount}','{project_disputed_amount_excluding_admin_arbitration_fee}','{po_company_name}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,$po_name),$dispute_closed_reason);
			}		
			
		}
	}
	if($projects_disputes_data['disputed_winner_id'] == $projects_disputes_data['employer_id_of_disputed_fulltime_project']){
		
		if($projects_disputes_data['dispute_status'] == 'admin_decision' && $user[0]->user_id == $projects_disputes_data['employer_id_of_disputed_fulltime_project']){
			
			$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_when_dispute_project_decided_admin_arbitration_employer_winner_employer_view_reason_txt');
			
			$dispute_closed_reason = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}','{fulltime_project_disputed_amount_service_fees}','{admin_dispute_arbitration_amount_fee}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_service_fees'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($get_projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'],  2, '.', ' '))." ".CURRENCY),$dispute_closed_reason);
			
		}
		if($projects_disputes_data['dispute_status'] == 'admin_decision' && $user[0]->user_id == $projects_disputes_data['employee_winner_id_of_disputed_fulltime_project']){
			
			if($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
				
				if($po_data['gender'] == 'M'){
					$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_when_dispute_project_decided_admin_arbitration_male_employer_winner_employee_view_reason_txt');
				}else{
					$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_when_dispute_project_decided_admin_arbitration_female_employer_winner_employee_view_reason_txt');
				}	
				
				$dispute_closed_reason = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}','{po_first_name_last_name}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,$po_name),$dispute_closed_reason);
				
			}else{
				$dispute_closed_reason = $this->config->item('fulltime_project_dispute_details_page_when_dispute_project_decided_admin_arbitration_company_employer_winner_employee_view_reason_txt');
				
				$dispute_closed_reason = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}','{po_company_name}'),array(str_replace(".00","",number_format($projects_disputes_data['disputed_amount'],  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($projects_disputes_data['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY,$po_name),$dispute_closed_reason);
			}		
			
		}
	}
	$dispute_result_txt = $this->config->item('project_dispute_details_page_dispute_closed_txt');
}	
?>


<div class="col-md-8 col-sm-8 col-12 offset-md-2 offset-sm-2" style="margin-top : 20px">
	<div class="ansBack">
		<div class="tR">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-12">
					<div class="tLeft">&nbsp;</div>
				</div>
				<div class="col-md-6 col-sm-6 col-12">
				</div>	
			</div>	
		</div>
		<p class="dResolved" id="dispute_result_txt"><?php echo $dispute_result_txt; ?></p>
		<div class="reason">Reason: <?php echo $dispute_closed_reason; ?> </div>
	</div>
</div>