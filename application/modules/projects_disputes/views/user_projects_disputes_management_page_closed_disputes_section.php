<?php 
$user = $this->session->userdata ('user');
?>
<div class="closeDispute">
	<div>
		<?php
		if($closed_disputes_listing_project_data_count >0){
			$total_closed_disputes = count($closed_disputes_listing_project_data);
			$record_counter = 1;
			foreach($closed_disputes_listing_project_data as $dispute_listing_data_key=>$dispute_listing_data_value){
				if($record_counter == $total_closed_disputes){
					$last_record_class_border_full_width = 'border_full_width';
				}	
				
				
			$dispute_close_reason = '';	
			
			$disputed_amount = 	str_replace(".00","",number_format($dispute_listing_data_value['disputed_amount'],  2, '.', ' '))." ".CURRENCY;
			$disputed_service_fees = str_replace(".00","",number_format($dispute_listing_data_value['disputed_service_fees'],  2, '.', ' '))." ".CURRENCY;
				
			$po_name = $dispute_listing_data_value['po_account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $dispute_listing_data_value['po_first_name'] . ' ' . $dispute_listing_data_value['po_last_name'] : $dispute_listing_data_value['po_company_name'];		
				
			$sp_name = $dispute_listing_data_value['sp_account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $dispute_listing_data_value['sp_first_name'] . ' ' . $dispute_listing_data_value['sp_last_name'] : $dispute_listing_data_value['sp_company_name'];
				
			if($dispute_listing_data_value['project_type'] == 'fixed'){
				$admin_arbitration_value = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
			}	
			if($dispute_listing_data_value['project_type'] == 'hourly'){
				$admin_arbitration_value = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
			}
			if($dispute_listing_data_value['project_type'] == 'fulltime'){
				$admin_arbitration_value = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
			}
			
			if($dispute_listing_data_value['dispute_status'] == 'dispute_cancelled_by_initiator_po'){
				
				if($user[0]->user_id == $dispute_listing_data_value['project_owner_id'] ){
					$dispute_close_reason = $this->config->item('user_projects_disputes_management_page_dispute_cancelled_by_po_initiator_po_view_reason_txt');
					$dispute_close_reason = str_replace(array("{other_party_first_name_last_name_or_company_name}","{project_disputed_amount}","{project_disputed_amount_service_fees}"),array($sp_name,$disputed_amount,$disputed_service_fees),$dispute_close_reason);
					
					
					
					
				}else{
					if($dispute_listing_data_value['po_account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
						$dispute_close_reason  = $dispute_listing_data_value['po_gender'] == 'M' ? $this->config->item('user_projects_disputes_management_page_dispute_cancelled_by_male_po_initiator_sp_view_reason_txt') :$this->config->item('user_projects_disputes_management_page_dispute_cancelled_by_female_po_initiator_sp_view_reason_txt');
						
						$dispute_close_reason = str_replace(array("{po_first_name_last_name}"),array($po_name),$dispute_close_reason);
						
					}else{
						$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_dispute_cancelled_by_company_po_initiator_sp_view_reason_txt');
						$dispute_close_reason = str_replace(array("{po_company_name}"),array($po_name),$dispute_close_reason);
					}	
					$dispute_close_reason = str_replace(array("{project_disputed_amount}"),array($disputed_amount),$dispute_close_reason);		
				}	
			}
			else if($dispute_listing_data_value['dispute_status'] == 'dispute_cancelled_by_initiator_employer'){
				if($user[0]->user_id == $dispute_listing_data_value['project_owner_id'] ){
					$dispute_close_reason = $this->config->item('user_projects_disputes_management_page_fulltime_project_dispute_cancelled_by_employer_initiator_employer_view_reason_txt');
					$dispute_close_reason = str_replace(array("{other_party_first_name_last_name_or_company_name}","{fulltime_project_disputed_amount}","{fulltime_project_disputed_amount_service_fees}"),array($sp_name,$disputed_amount,$disputed_service_fees),$dispute_close_reason);
					
				}else{
					if($dispute_listing_data_value['po_account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
						$dispute_close_reason  = $dispute_listing_data_value['po_gender'] == 'M' ? $this->config->item('user_projects_disputes_management_page_fulltime_project_dispute_cancelled_by_male_employer_initiator_employee_view_reason_txt') :$this->config->item('user_projects_disputes_management_page_fulltime_project_dispute_cancelled_by_female_po_initiator_sp_view_reason_txt');
						
						$dispute_close_reason = str_replace(array("{po_first_name_last_name}"),array($po_name),$dispute_close_reason);
						
					}else{
						$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_dispute_cancelled_by_company_po_initiator_sp_view_reason_txt');
						$dispute_close_reason = str_replace(array("{po_company_name}"),array($po_name),$dispute_close_reason);
					}	
					$dispute_close_reason = str_replace(array("{fulltime_project_disputed_amount}"),array($disputed_amount),$dispute_close_reason);		
				}	
			}
			else if($dispute_listing_data_value['dispute_status'] == 'dispute_cancelled_by_initiator_sp'){
				if($user[0]->user_id == $dispute_listing_data_value['winner_id'] ){
					$dispute_close_reason = $this->config->item('user_projects_disputes_management_page_dispute_cancelled_by_sp_initiator_sp_view_reason_txt');
					$dispute_close_reason = str_replace(array("{other_party_first_name_last_name_or_company_name}","{project_disputed_amount}"),array($po_name,$disputed_amount),$dispute_close_reason);
					
				}else{
					if($dispute_listing_data_value['sp_account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
						$dispute_close_reason  = $dispute_listing_data_value['sp_gender'] == 'M' ? $this->config->item('user_projects_disputes_management_page_dispute_cancelled_by_male_sp_initiator_po_view_reason_txt') :$this->config->item('user_projects_disputes_management_page_dispute_cancelled_by_female_sp_initiator_po_view_reason_txt');
						
						$dispute_close_reason = str_replace(array("{sp_first_name_last_name}"),array($sp_name),$dispute_close_reason);
						
					}else{
						$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_dispute_cancelled_by_company_po_initiator_sp_view_reason_txt');
						$dispute_close_reason = str_replace(array("{sp_company_name}"),array($sp_name),$dispute_close_reason);
					}	
					$dispute_close_reason = str_replace(array("{project_disputed_amount}","{project_disputed_amount_service_fees}"),array($disputed_amount,$disputed_service_fees),$dispute_close_reason);		
				}	
			}else if($dispute_listing_data_value['dispute_status'] == 'dispute_cancelled_by_initiator_employee'){
				if($user[0]->user_id == $dispute_listing_data_value['winner_id'] ){
					$dispute_close_reason = $this->config->item('user_projects_disputes_management_page_fulltime_project_dispute_cancelled_by_employee_initiator_employee_view_reason_txt');
					$dispute_close_reason = str_replace(array("{other_party_first_name_last_name_or_company_name}","{fulltime_project_disputed_amount}"),array($po_name,$disputed_amount),$dispute_close_reason);
					
				}else{
					if($dispute_listing_data_value['sp_account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
						$dispute_close_reason  = $dispute_listing_data_value['sp_gender'] == 'M' ? $this->config->item('user_projects_disputes_management_page_fulltime_project_dispute_cancelled_by_male_employee_initiator_employer_view_reason_txt') :$this->config->item('user_projects_disputes_management_page_fulltime_project_dispute_cancelled_by_female_employee_initiator_employer_view_reason_txt');
						
						$dispute_close_reason = str_replace(array("{sp_first_name_last_name}"),array($sp_name),$dispute_close_reason);
						
					}else{
						$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_fulltime_project_dispute_cancelled_by_company_employee_initiator_employer_view_reason_txt');
						$dispute_close_reason = str_replace(array("{sp_company_name}"),array($sp_name),$dispute_close_reason);
					}	
					$dispute_close_reason = str_replace(array("{fulltime_project_disputed_amount}","{fulltime_project_disputed_amount_service_fees}"),array($disputed_amount,$disputed_service_fees),$dispute_close_reason);		
				}	
			}else if($dispute_listing_data_value['dispute_status'] == 'parties_agreement'){
				
				$get_projects_closed_disputes_po_reverted_amounts_data = get_projects_closed_disputes_po_reverted_amounts($dispute_listing_data_value['project_type'],array('dispute_reference_id'=>$dispute_listing_data_value['dispute_reference_id']));
				
				

				$counter_offer_value = str_replace(".00","",number_format($dispute_listing_data_value['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY;
				
				$disputed_service_fee = str_replace(".00","",number_format($dispute_listing_data_value['disputed_service_fees'],  2, '.', ' '))." ".CURRENCY;
				
				$reverted_amount_to_po = str_replace(".00","",number_format($get_projects_closed_disputes_po_reverted_amounts_data['reverted_amount'],  2, '.', ' '))." ".CURRENCY;
				
				$reverted_service_fee_to_po = str_replace(".00","",number_format($get_projects_closed_disputes_po_reverted_amounts_data['reverted_service_fee_amount'],  2, '.', ' '))." ".CURRENCY;
				

				if($dispute_listing_data_value['disputed_winner_id'] == $dispute_listing_data_value['winner_id']){
					if($user[0]->user_id == $dispute_listing_data_value['project_owner_id']){
						
						if($dispute_listing_data_value['project_type'] == 'fulltime'){
							 $dispute_close_reason = $this->config->item('user_projects_disputes_management_page_fulltime_project_dispute_counter_offer_accepted_by_employer_employer_view_reason_txt');
						}else{
						
						 $dispute_close_reason = $this->config->item('user_projects_disputes_management_page_dispute_counter_offer_accepted_by_po_po_view_reason_txt');
						}
						if($dispute_listing_data_value['project_type'] == 'fulltime'){
							$dispute_close_reason = str_replace(array("{sp_first_name_last_name_or_company_name}","{fulltime_project_disputed_amount}","{fulltime_project_counter_offer_value}","{service_fees_charged_from_po}","{fulltime_project_remaining_amount}","{service_fees_return_to_po}"),array($sp_name,$disputed_amount,$counter_offer_value,$disputed_service_fee,$reverted_amount_to_po,$reverted_service_fee_to_po),$dispute_close_reason);
							
						}else{	
							$dispute_close_reason = str_replace(array("{sp_first_name_last_name_or_company_name}","{project_disputed_amount}","{project_counter_offer_value}","{service_fees_charged_from_po}","{project_remaining_amount}","{service_fees_return_to_po}"),array($sp_name,$disputed_amount,$counter_offer_value,$disputed_service_fee,$reverted_amount_to_po,$reverted_service_fee_to_po),$dispute_close_reason);
						}
						
						
					}else{
						if($dispute_listing_data_value['po_account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
							if($dispute_listing_data_value['project_type'] == 'fulltime'){
								$dispute_close_reason  = $dispute_listing_data_value['po_gender'] == 'M' ? $this->config->item('user_projects_disputes_management_page_fulltime_project_dispute_counter_offer_accepted_by_male_employer_employee_view_reason_txt') :$this->config->item('user_projects_disputes_management_page_fulltime_project_dispute_counter_offer_accepted_by_female_employer_employee_view_reason_txt');
							}else{	
								$dispute_close_reason  = $dispute_listing_data_value['po_gender'] == 'M' ? $this->config->item('user_projects_disputes_management_page_dispute_counter_offer_accepted_by_male_po_sp_view_reason_txt') :$this->config->item('user_projects_disputes_management_page_dispute_counter_offer_accepted_by_female_po_sp_view_reason_txt');
							}
							
							$dispute_close_reason = str_replace(array("{po_first_name_last_name}"),array($po_name),$dispute_close_reason);
							
						}else{
							if($dispute_listing_data_value['project_type'] == 'fulltime'){
								$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_fulltime_project_dispute_counter_offer_accepted_by_company_employer_employee_view_reason_txt');
							}else{
							 $dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_dispute_counter_offer_accepted_by_company_po_sp_view_reason_txt');
							}
							$dispute_close_reason = str_replace(array("{po_company_name}"),array($po_name),$dispute_close_reason);
						}	
						if($dispute_listing_data_value['project_type'] == 'fulltime'){
							$dispute_close_reason = str_replace(array("{fulltime_project_disputed_amount}","{fulltime_project_counter_offer_value}","{fulltime_project_remaining_amount}"),array($disputed_amount,$counter_offer_value,$reverted_amount_to_po),$dispute_close_reason);
						}else{	
							$dispute_close_reason = str_replace(array("{project_disputed_amount}","{project_counter_offer_value}","{project_remaining_amount}"),array($disputed_amount,$counter_offer_value,$reverted_amount_to_po),$dispute_close_reason);
						}
						
						
					}		
				}
				if($dispute_listing_data_value['disputed_winner_id'] == $dispute_listing_data_value['project_owner_id']){
					if($user[0]->user_id == $dispute_listing_data_value['project_owner_id']){
						
						if($dispute_listing_data_value['sp_account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
							
							if($dispute_listing_data_value['project_type'] == 'fulltime'){
								$dispute_close_reason  = $dispute_listing_data_value['sp_gender'] == 'M' ? $this->config->item('user_projects_disputes_management_page_fulltime_project_dispute_counter_offer_accepted_by_male_employee_employer_view_reason_txt') :$this->config->item('user_projects_disputes_management_page_fulltime_project_dispute_counter_offer_accepted_by_female_employee_employer_view_reason_txt');
							}else{	
							
								$dispute_close_reason  = $dispute_listing_data_value['sp_gender'] == 'M' ? $this->config->item('user_projects_disputes_management_page_dispute_counter_offer_accepted_by_male_sp_po_view_reason_txt') :$this->config->item('user_projects_disputes_management_page_dispute_counter_offer_accepted_by_female_sp_po_view_reason_txt');
							}
							
							$dispute_close_reason = str_replace(array("{sp_first_name_last_name}"),array($sp_name),$dispute_close_reason);
							
						}else{
							if($dispute_listing_data_value['project_type'] == 'fulltime'){
								$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_fulltime_project_dispute_counter_offer_accepted_by_company_employee_employer_view_reason_txt');
								$dispute_close_reason = str_replace(array("{sp_company_name}"),array($sp_name),$dispute_close_reason);
							}else{
								$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_dispute_counter_offer_accepted_by_company_sp_po_view_reason_txt');
								$dispute_close_reason = str_replace(array("{sp_company_name}"),array($sp_name),$dispute_close_reason);
							}
						}
						
						if($dispute_listing_data_value['project_type'] == 'fulltime'){
							
							$dispute_close_reason = str_replace(array("{sp_first_name_last_name_or_company_name}","{fulltime_project_disputed_amount}","{fulltime_project_counter_offer_value}","{service_fees_charged_from_po}","{fulltime_project_remaining_amount}","{service_fees_return_to_po}"),array($sp_name,$disputed_amount,$counter_offer_value,$disputed_service_fee,$reverted_amount_to_po,$reverted_service_fee_to_po),$dispute_close_reason);
							
						}else{	
						
							$dispute_close_reason = str_replace(array("{sp_first_name_last_name_or_company_name}","{project_disputed_amount}","{project_counter_offer_value}","{service_fees_charged_from_po}","{project_remaining_amount}","{service_fees_return_to_po}"),array($sp_name,$disputed_amount,$counter_offer_value,$disputed_service_fee,$reverted_amount_to_po,$reverted_service_fee_to_po),$dispute_close_reason);
						
						}
						
					}else{
						if($dispute_listing_data_value['project_type'] == 'fulltime'){
							$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_fulltime_project_dispute_counter_offer_accepted_by_employee_employee_view_reason_txt');
						}else{
							$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_dispute_counter_offer_accepted_by_sp_sp_view_reason_txt');
						}
						
						if($dispute_listing_data_value['project_type'] == 'fulltime'){
							
							$dispute_close_reason = str_replace(array("{po_user_first_name_last_name_or_company_name}","{fulltime_project_disputed_amount}","{fulltime_project_counter_offer_value}","{fulltime_project_remaining_amount}"),array($po_name,$disputed_amount,$counter_offer_value,$reverted_amount_to_po),$dispute_close_reason);
							
						}else{	
							$dispute_close_reason = str_replace(array("{po_user_first_name_last_name_or_company_name}","{project_disputed_amount}","{project_counter_offer_value}","{project_remaining_amount}"),array($po_name,$disputed_amount,$counter_offer_value,$reverted_amount_to_po),$dispute_close_reason);
						}
					}			
				}

			}else if($dispute_listing_data_value['dispute_status'] == 'automatic_decision'){
				
				$disputed_service_fee = str_replace(".00","",number_format($dispute_listing_data_value['disputed_service_fees'],  2, '.', ' '))." ".CURRENCY;
				
			
				
				$disputed_50_amount = str_replace(".00","",number_format($dispute_listing_data_value['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY;
				
				$disputed_50_percent_service_fee = str_replace(".00","",number_format($dispute_listing_data_value['disputed_final_settlement_service_fees'],  2, '.', ' '))." ".CURRENCY;
				
				
				if($user[0]->user_id == $dispute_listing_data_value['project_owner_id']){
					
					if($dispute_listing_data_value['project_type'] == 'fulltime'){
						$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_when_dispute_fulltime_project_decided_automatic_employer_view_reason_txt');
					}else{
						$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_when_dispute_project_decided_automatic_po_view_reason_txt');
					}
					if($dispute_listing_data_value['project_type'] == 'fulltime'){
						$dispute_close_reason = str_replace(array("{other_party_first_name_last_name_or_company_name}","{fulltime_project_disputed_amount}","{fulltime_project_disputed_amount_service_fees}","{fulltime_project_50%_disputed_amount}","{fulltime_project_50%_disputed_amount_service_fees}"),array($sp_name,$disputed_amount,$disputed_service_fee,$disputed_50_amount,$disputed_50_percent_service_fee),$dispute_close_reason);
						
					}else{	
						$dispute_close_reason = str_replace(array("{other_party_first_name_last_name_or_company_name}","{project_disputed_amount}","{project_disputed_amount_service_fees}","{project_50%_disputed_amount}","{project_50%_disputed_amount_service_fees}"),array($sp_name,$disputed_amount,$disputed_service_fee,$disputed_50_amount,$disputed_50_percent_service_fee),$dispute_close_reason);
					}
				}
				if($user[0]->user_id == $dispute_listing_data_value['winner_id']){
					if($dispute_listing_data_value['project_type'] == 'fulltime'){
						$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_when_dispute_fulltime_project_decided_automatic_employee_view_reason_txt');
					}else{	
						$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_when_dispute_project_decided_automatic_sp_view_reason_txt');
					}
					if($dispute_listing_data_value['project_type'] == 'fulltime'){
						$dispute_close_reason = str_replace(array("{other_party_first_name_last_name_or_company_name}","{fulltime_project_disputed_amount}","{fulltime_project_50%_disputed_amount}"),array($po_name,$disputed_amount,$disputed_50_amount),$dispute_close_reason);
					}else{	
						$dispute_close_reason = str_replace(array("{other_party_first_name_last_name_or_company_name}","{project_disputed_amount}","{project_50%_disputed_amount}"),array($po_name,$disputed_amount,$disputed_50_amount),$dispute_close_reason);
					}
				}

			}else if($dispute_listing_data_value['dispute_status'] == 'admin_decision'){
				
				
				$get_projects_disputes_admin_arbitration_fees_data = get_projects_disputes_admin_arbitration_fees(array('dispute_reference_id'=>$dispute_listing_data_value['dispute_reference_id']));
				
				$get_projects_closed_disputes_po_reverted_amounts_data = get_projects_closed_disputes_po_reverted_amounts($dispute_listing_data_value['project_type'],array('dispute_reference_id'=>$dispute_listing_data_value['dispute_reference_id']));
				
				$project_disputed_amount_excluding_admin_arbitration_fee = str_replace(".00","",number_format($dispute_listing_data_value['disputed_final_settlement_amount'],  2, '.', ' '))." ".CURRENCY;
				
				$admin_dispute_arbitration_amount_fee = str_replace(".00","",number_format($get_projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'],  2, '.', ' '))." ".CURRENCY;
				
				$disputed_service_fee = str_replace(".00","",number_format($dispute_listing_data_value['disputed_service_fees'],  2, '.', ' '))." ".CURRENCY;
				
				
				
				if($dispute_listing_data_value['disputed_winner_id'] == $dispute_listing_data_value['winner_id']){
					
					if($user[0]->user_id == $dispute_listing_data_value['winner_id']){
						
						if($dispute_listing_data_value['project_type'] == 'fulltime'){
							$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_when_dispute_fulltime_project_decided_admin_arbitration_employee_winner_employee_view_reason_txt');
						}else{	
						
							$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_when_dispute_project_decided_admin_arbitration_sp_winner_sp_view_reason_txt');
						}
						if($dispute_listing_data_value['project_type'] == 'fulltime'){
							$dispute_close_reason = str_replace(array("{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}","{admin_dispute_arbitration_amount_fee}"),array($project_disputed_amount_excluding_admin_arbitration_fee,$admin_dispute_arbitration_amount_fee,$disputed_service_fee),$dispute_close_reason);
						}else{	
							$dispute_close_reason = str_replace(array("{project_disputed_amount_excluding_admin_arbitration_fee}","{admin_dispute_arbitration_amount_fee}"),array($project_disputed_amount_excluding_admin_arbitration_fee,$admin_dispute_arbitration_amount_fee,$disputed_service_fee),$dispute_close_reason);
						}
						
					}else{
						if($dispute_listing_data_value['sp_account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
							if($dispute_listing_data_value['project_type'] == 'fulltime'){
								
								$dispute_close_reason  = $dispute_listing_data_value['sp_gender'] == 'M' ? $this->config->item('user_projects_disputes_management_page_when_dispute_fulltime_project_decided_admin_arbitration_male_employee_winner_employer_view_reason_txt') :$this->config->item('user_projects_disputes_management_page_when_dispute_fulltime_project_decided_admin_arbitration_female_employee_winner_employer_view_reason_txt');
								
							}else{	
								$dispute_close_reason  = $dispute_listing_data_value['sp_gender'] == 'M' ? $this->config->item('user_projects_disputes_management_page_when_dispute_project_decided_admin_arbitration_male_sp_winner_po_view_reason_txt') :$this->config->item('user_projects_disputes_management_page_when_dispute_project_decided_admin_arbitration_female_sp_winner_po_view_reason_txt');
							}
							
							$dispute_close_reason = str_replace(array("{sp_first_name_last_name}"),array($sp_name),$dispute_close_reason);
							
						}else{
							if($dispute_listing_data_value['project_type'] == 'fulltime'){
								$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_when_dispute_fulltime_project_decided_admin_arbitration_company_employee_winner_employer_view_reason_txt');
							}else{	
								$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_when_dispute_project_decided_admin_arbitration_company_sp_winner_po_view_reason_txt');
							}
							$dispute_close_reason = str_replace(array("{sp_company_name}"),array($sp_name),$dispute_close_reason);
							
						}
						if($dispute_listing_data_value['project_type'] == 'fulltime'){
							$dispute_close_reason = str_replace(array("{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}","{admin_dispute_arbitration_amount_fee}","{fulltime_project_disputed_amount_service_fees}"),array($project_disputed_amount_excluding_admin_arbitration_fee,$admin_dispute_arbitration_amount_fee,$disputed_service_fee),$dispute_close_reason);
						}else{	
							$dispute_close_reason = str_replace(array("{project_disputed_amount_excluding_admin_arbitration_fee}","{admin_dispute_arbitration_amount_fee}","{project_disputed_amount_service_fees}"),array($project_disputed_amount_excluding_admin_arbitration_fee,$admin_dispute_arbitration_amount_fee,$disputed_service_fee),$dispute_close_reason);
						}
					}		
					
				}
				if($dispute_listing_data_value['disputed_winner_id'] == $dispute_listing_data_value['project_owner_id']){
					
					if($user[0]->user_id == $dispute_listing_data_value['project_owner_id']){
						
						if($dispute_listing_data_value['project_type'] == 'fulltime'){
							$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_when_dispute_fulltime_project_decided_admin_arbitration_employer_winner_employer_view_reason_txt');
						}else{	
						
							$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_when_dispute_project_decided_admin_arbitration_po_winner_po_view_reason_txt');
						}
						
						if($dispute_listing_data_value['project_type'] == 'fulltime'){
							$dispute_close_reason = str_replace(array("{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}","{admin_dispute_arbitration_amount_fee}","{fulltime_project_disputed_amount_service_fees}"),array($project_disputed_amount_excluding_admin_arbitration_fee,$admin_dispute_arbitration_amount_fee,$disputed_service_fee),$dispute_close_reason);
						}else{	
							$dispute_close_reason = str_replace(array("{project_disputed_amount_excluding_admin_arbitration_fee}","{admin_dispute_arbitration_amount_fee}","{project_disputed_amount_service_fees}"),array($project_disputed_amount_excluding_admin_arbitration_fee,$admin_dispute_arbitration_amount_fee,$disputed_service_fee),$dispute_close_reason);
						}
						
						
						
					}else{
						if($dispute_listing_data_value['po_account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
							
							if($dispute_listing_data_value['project_type'] == 'fulltime'){
								$dispute_close_reason  = $dispute_listing_data_value['sp_gender'] == 'M' ? $this->config->item('user_projects_disputes_management_page_when_dispute_fulltime_project_decided_admin_arbitration_male_employer_winner_employee_view_reason_txt') :$this->config->item('user_projects_disputes_management_page_when_dispute_fulltime_project_decided_admin_arbitration_female_employer_winner_employee_view_reason_txt');
								
							}else{	
								$dispute_close_reason  = $dispute_listing_data_value['sp_gender'] == 'M' ? $this->config->item('user_projects_disputes_management_page_when_dispute_project_decided_admin_arbitration_male_po_winner_sp_view_reason_txt') :$this->config->item('user_projects_disputes_management_page_when_dispute_project_decided_admin_arbitration_female_po_winner_sp_view_reason_txt');
							}
							
							$dispute_close_reason = str_replace(array("{po_first_name_last_name}"),array($po_name),$dispute_close_reason);
							
							
						}else{
							if($dispute_listing_data_value['project_type'] == 'fulltime'){
								$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_when_dispute_fulltime_project_decided_admin_arbitration_company_employer_winner_employee_view_reason_txt');
							}else{
								$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_when_dispute_project_decided_admin_arbitration_company_po_winner_sp_view_reason_txt');
								
							}
							$dispute_close_reason = str_replace(array("{po_company_name}"),array($po_name),$dispute_close_reason);
						}
						if($dispute_listing_data_value['project_type'] == 'fulltime'){
							$dispute_close_reason = str_replace(array("{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}","{admin_dispute_arbitration_amount_fee}"),array($project_disputed_amount_excluding_admin_arbitration_fee,$admin_dispute_arbitration_amount_fee),$dispute_close_reason);
						}else{	
							$dispute_close_reason = str_replace(array("{project_disputed_amount_excluding_admin_arbitration_fee}","{admin_dispute_arbitration_amount_fee}"),array($project_disputed_amount_excluding_admin_arbitration_fee,$admin_dispute_arbitration_amount_fee),$dispute_close_reason);
						}
						
					}		
					
				}
			
			}else{ 
				
				$disputed_service_fee = str_replace(".00","",number_format($dispute_listing_data_value['disputed_service_fees'],  2, '.', ' '))." ".CURRENCY;
				
			
				
				$disputed_50_amount = $dispute_listing_data_value['disputed_amount']/2;
				$disputed_50_amount = str_replace(".00","",number_format($disputed_50_amount,  2, '.', ' '))." ".CURRENCY;
				
				$disputed_50_percent_service_fee = $dispute_listing_data_value['disputed_service_fees']/2;
				$disputed_50_percent_service_fee = str_replace(".00","",number_format($disputed_50_percent_service_fee,  2, '.', ' '))." ".CURRENCY;
				
				
				
				
				if($user[0]->user_id == $dispute_listing_data_value['project_owner_id']){
					
					if($dispute_listing_data_value['project_type'] == 'fulltime'){
						$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_when_dispute_fulltime_project_decided_automatic_employer_view_reason_txt');
						$dispute_close_reason = str_replace(array("{other_party_first_name_last_name_or_company_name}","{fulltime_project_disputed_amount}","{fulltime_project_disputed_amount_service_fees}","{fulltime_project_50%_disputed_amount}","{fulltime_project_50%_disputed_amount_service_fees}"),array($sp_name,$disputed_amount,$disputed_service_fee,$disputed_50_amount,$disputed_50_percent_service_fee),$dispute_close_reason);
					}else{
					
						$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_when_dispute_project_decided_automatic_po_view_reason_txt');
						$dispute_close_reason = str_replace(array("{other_party_first_name_last_name_or_company_name}","{project_disputed_amount}","{project_disputed_amount_service_fees}","{project_50%_disputed_amount}","{project_50%_disputed_amount_service_fees}"),array($sp_name,$disputed_amount,$disputed_service_fee,$disputed_50_amount,$disputed_50_percent_service_fee),$dispute_close_reason);
					}
				}
				if($user[0]->user_id == $dispute_listing_data_value['winner_id']){
					
					if($dispute_listing_data_value['project_type'] == 'fulltime'){
						$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_when_dispute_fulltime_project_decided_automatic_employee_view_reason_txt');
						$dispute_close_reason = str_replace(array("{other_party_first_name_last_name_or_company_name}","{fulltime_project_disputed_amount}","{fulltime_project_50%_disputed_amount}"),array($po_name,$disputed_amount,$disputed_50_amount),$dispute_close_reason);
					}else{	
					
						$dispute_close_reason  =  $this->config->item('user_projects_disputes_management_page_when_dispute_project_decided_automatic_sp_view_reason_txt');
						$dispute_close_reason = str_replace(array("{other_party_first_name_last_name_or_company_name}","{project_disputed_amount}","{project_50%_disputed_amount}"),array($po_name,$disputed_amount,$disputed_50_amount),$dispute_close_reason);
					}
				}
				

			}		
			
			
		?>
			<div class="disputeTab <?php echo $last_record_class_border_full_width; ?>">
				<h6><b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_disputes_management_page_dispute_id_txt'); ?></b><a class="default_project_title_link" target="_blank" href="<?php echo base_url() . $this->config->item('project_dispute_details_page_url') . "?id=" . $dispute_listing_data_value['dispute_reference_id']; ?>"><?php echo $dispute_listing_data_value['dispute_reference_id']; ?></a></h6>
				<h6><b class="default_black_bold_medium"><?php echo ($dispute_listing_data_value['project_type'] == 'fulltime')?$this->config->item('user_projects_disputes_management_page_fulltime_project_title_txt'):$this->config->item('user_projects_disputes_management_page_project_title_txt'); ?></b><a class="default_project_title_link" target="_blank" href="<?php echo base_url() . $this->config->item('project_detail_page_url') . "?id=" . $dispute_listing_data_value['project_id']; ?>"><?php echo htmlspecialchars(trim($dispute_listing_data_value['project_title']), ENT_QUOTES); ?></a></h6>
				
				<!--<h6 class="cProject"><b>Admin taken action</b></h6>-->
				<div class="row">
					<div class="col-md-12 col-sm-12 col-12">
						<label>
							<div>
								<b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_disputes_management_page_disputed_amount_txt'); ?></b><span class="default_black_regular_medium touch_line_break"><?php echo $disputed_amount ?></span>
							</div></label><?php
						if($user[0]->user_id == $dispute_listing_data_value['project_owner_id'] ){
						?><label>
							<div>
								<b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_disputes_management_page_total_related_service_fees_txt'); ?></b><span class="default_black_regular_medium touch_line_break"><?php echo$disputed_service_fees;  ?></span>
							</div></label>
						<?php
						}
						?>
						<br>
						<?php
						if($user[0]->user_id != $dispute_listing_data_value['dispute_initiated_by_user_id'] ){
							
							if($dispute_listing_data_value['dispute_initiated_by_user_id'] == $dispute_listing_data_value['project_owner_id'])
							{
								$initiator_name = $po_name;
							}else{
								
								$initiator_name = $sp_name;
							}
							
						?><label>
								<div>
									<b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_disputes_management_page_dispute_initiated_by_txt'); ?></b><span class="default_black_regular_medium word_break_only"><?php echo $initiator_name; ?></span>
								</div>
							</label><?php
						}
						?><label>
							<div>
								<b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_disputes_management_page_dispute_start_date'); ?></b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT,strtotime($dispute_listing_data_value['dispute_start_date'])); ?></span>
							</div></label><label>
							<div>
								<b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_disputes_management_page_dispute_close_date'); ?></b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT,strtotime($dispute_listing_data_value['dispute_end_date'])); ?></span>
							</div>
						</label>
					</div>
				</div>
				<p class="default_black_regular_medium"><?php echo $dispute_close_reason; ?></p>
				
				
			</div>
		<?php
			$record_counter ++;
			}
		?>
		<div class="paging_section">
		<?php
			echo $this->load->view('projects_disputes/user_projects_disputes_management_page_paging',array('disputes_listing_project_data_count'=>$closed_disputes_listing_project_data_count,'generate_pagination_links_user_projects_disputes_management'=>$generate_pagination_links_user_projects_disputes_management,'disputes_listing_project_data_paging_limit'=>$this->config->item('user_projects_disputes_management_page_closed_disputes_listing_limit')), true);
		?>
		</div>

		<?php	
		}else{
		?>
		<div class="default_blank_message"><?php echo $this->config->item('user_projects_disputes_management_page_no_closed_disputes_message'); ?></div>
		<?php

		}		
		?>
		<!--<div class="disputeTab">
			<h6><b class="default_black_bold_medium">Project title:</b><a class="default_project_title_link" href="#">The standard chunk of Lorem Ipsum used</a></h6>
			<p class="default_black_regular_medium"><b class="default_black_bold_medium">Description:</b>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making.Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making.</p>
			<h6 class="cProject"><b>Admin taken action</b></h6>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-12">
					<label>
						<div>
							<b class="default_black_bold_medium">Favour of:</b><span class="default_black_regular_medium">Workspace</span>
						</div>
					</label>
					<label>
						<div>
							<b class="default_black_bold_medium">Dispute Start:</b><span class="default_black_regular_medium">06.12.2018 14:32:28</span>
						</div>
					</label>
					<label>
						<div>
							<b class="default_black_bold_medium">Dispute End:</b><span class="default_black_regular_medium">06.12.2018 14:32:28</span>
						</div>
					</label>
					<label>
						<div>
							<b class="default_black_bold_medium">Dispute Amount:</b><span class="default_black_regular_medium">500.00 Kč</span>
						</div>
					</label>
				</div>
			</div>
		</div>-->
		
	</div>
</div>