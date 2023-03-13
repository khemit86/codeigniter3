<?php
  foreach($transactions_histroy as $val) {
    if($val['source'] == 'deposit_paypal') {
?>
<div class="col-md-12 col-sm-12 col-12 dfThistory">
  <label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_deposited_amount_lbl'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo format_money_amount_display($val['amount']).' '.CURRENCY ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transaction_date_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])); ?></span></div></label><?php 
    if($val['deposit_funds_paypal_charge']) {
  ?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_paypal_transaction_charge_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo format_money_amount_display($val['deposit_funds_paypal_charge']).' '.CURRENCY; ?></span></div></label> <?php 
    }
  ?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('paypal_account_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['paypal_account']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transaction_id_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['transaction_id']; ?></span></div></label></div><?php
    } else if($val['source'] == 'deposit_bank') {
?><div class="col-md-12 col-sm-12 col-12 dfThistory"><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_deposited_amount_lbl'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo format_money_amount_display($val['amount']).' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_transaction_id_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['transaction_id']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_transaction_date_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_FORMAT, strtotime($val['transaction_date'])); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_account_owner_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo htmlspecialchars($val['bank_account_owner_name'], ENT_QUOTES); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_account_number_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo htmlspecialchars($val['bank_account_number'], ENT_QUOTES); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_name_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo htmlspecialchars($val['bank_name'], ENT_QUOTES); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_code_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo htmlspecialchars($val['bank_code'], ENT_QUOTES); ?></span></div></label><?php
    if($val['bank_account_iban_code'] != '') {
  ?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_iban_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo htmlspecialchars($val['bank_account_iban_code'], ENT_QUOTES); ?></span></div></label><?php
    }
  ?><?php 
    if($val['bank_account_bic_swift_code'] != '') {
  ?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_bic_or_swift_code_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo htmlspecialchars($val['bank_account_bic_swift_code'], ENT_QUOTES); ?></span></div></label><?php
    }
  ?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_country_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['country_name']; ?></span></div></label><?php
    if($val['status'] == 'transaction_confirmed_by_admin') {
  ?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transaction_status_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo ($val['admin_manual_entry'] ? $this->config->item('deposit_funds_direct_bank_transfer_added_by_admin_status_txt') : $this->config->item('deposit_funds_direct_bank_transfer_confirmed_status_txt')); ?></span></div></label><?php
    }
  ?></div><?php
    } else if($val['source'] == 'payment_processor') {
?><div class="col-md-12 col-sm-12 col-12 dfThistory"><label><?php
      $transaction_amt_lbl = '';
      if(in_array($val['status_code'], [2,6])) {
        $transaction_amt_lbl = $this->config->item('deposit_funds_via_payment_processor_deposited_amount_lbl');
      } else {
        $transaction_amt_lbl = $this->config->item('deposit_funds_via_payment_processor_transaction_amount_lbl');
      }
    ?><div><b class="default_black_bold_medium"><?php echo $transaction_amt_lbl; ?></b><span class="default_black_regular_medium display-inline-block"><?php echo format_money_amount_display($val['amount']).' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_transaction_date_lbl'); ?></b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_transaction_charge_lbl'); ?></b><span class="default_black_regular_medium"><?php echo format_money_amount_display($val['payment_processor_business_transaction_charged_fee']).' '.CURRENCY; ?></span></div></label><label class="<?php echo !in_array($val['status_code'], [2,6]) ? 'd-none' : ''; ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_transaction_id_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['transaction_id']; ?></span></div></label><?php
    if($val['deposit_transfer_type'] == 'payment_card_transaction') {
  ?><label class="<?php echo empty($val['card_number']) ? 'd-none' : '';  ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_card_number_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['card_number']; ?></span></div></label><label class="<?php echo empty($val['card_brand']) ? 'd-none' : '';  ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_card_brand_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['card_brand']; ?></span></div></label><label class="<?php echo empty($val['country_code']) ? 'd-none' : '';  ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_country_code_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['country_code']; ?></span></div></label><label class="<?php echo empty($val['card_bank_name']) ? 'd-none' : '';  ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_card_bank_name_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['card_bank_name']; ?></span></div></label><label class="<?php echo empty($val['card_type']) ? 'd-none' : '';  ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_card_type_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['card_type']; ?></span></div></label><?php
    } else if($val['deposit_transfer_type'] == 'bank_transfer_transaction') {
  ?><label class="<?php echo empty($val['pp_bank_name']) ? 'd-none' : '';  ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_bank_name_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['pp_bank_name'].' via '.$this->config->item('deposit_funds_via_payment_processor_method_id_associated_method_name')[$val['method_id']]; ?></span></div></label><label class="<?php echo empty($val['pp_bank_account_number']) ? 'd-none' : '';  ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_bank_account_number_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['pp_bank_account_number']; ?></span></div></label><label class="<?php echo empty($val['pp_bank_account_owner_name']) ? 'd-none' : '';  ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_bank_owner_name_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['pp_bank_account_owner_name']; ?></span></div></label><?php
    }
  ?><label><div><?php
        $status = '';
        if(in_array($val['status_code'], [2,6])) {
          $status = $this->config->item('deposit_funds_via_payment_processor_transaction_success_status_txt');
        } else if($val['status_code'] == 3) {
          $status = $this->config->item('deposit_funds_via_payment_processor_transaction_cancelled_status_txt');
        } else if($val['status_code'] == 4) {
          $status = $this->config->item('deposit_funds_via_payment_processor_transaction_failed_status_txt');
        } else if($val['status_code'] == 7) {
            $status = $this->config->item('deposit_funds_via_payment_processor_transaction_waiting_for_confirmation_status_txt');
        }
      ?><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_transaction_status_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $status; ?></span></div></label></div><?php
    } else if($val['source'] == 'withdraw_paypal') {
?><div class="col-md-12 col-sm-12 col-12 dfThistory"><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_withdrawal_amount_lbl'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo format_money_amount_display($val['amount']).' '.CURRENCY ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transaction_date_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('paypal_account_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['paypal_account']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transaction_id_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['transaction_id']; ?></span></div></label></div><?php
    } else if($val['source'] == 'withdraw_bank') {
?><div class="col-md-12 col-sm-12 col-12 dfThistory"><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_withdrawal_amount_lbl'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo format_money_amount_display($val['amount']).' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_transaction_id_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['transaction_id']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_transaction_date_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_FORMAT, strtotime($val['transaction_date'])); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_account_owner_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo htmlspecialchars($val['bank_account_owner_name'], ENT_QUOTES); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_account_number_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo htmlspecialchars($val['bank_account_number'], ENT_QUOTES); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_name_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo htmlspecialchars($val['bank_name'], ENT_QUOTES); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_code_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo htmlspecialchars($val['bank_code'], ENT_QUOTES); ?></span></div></label><?php
    if($val['bank_account_iban_code'] != '') {
  ?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_iban_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo htmlspecialchars($val['bank_account_iban_code'], ENT_QUOTES); ?></span></div></label><?php
    }
  ?><?php
    if($val['bank_account_bic_swift_code'] != '') {
  ?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_bic_or_swift_code_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo htmlspecialchars($val['bank_account_bic_swift_code'], ENT_QUOTES); ?></span></div></label><?php
    }
  ?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_country_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['country_name'];; ?></span></div></label><?php 
    if($val['status'] == 'request_confirmed_by_admin') {
  ?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_withdraw_request_status_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo ($val['admin_manual_entry'] ? $this->config->item('withdraw_funds_direct_bank_transfer_added_by_admin_status_txt'):$this->config->item('withdraw_funds_direct_bank_transfer_confirmed_by_admin_status_txt')); ?></span></div></label><?php 
    }
  ?></div><?php
    } else if($val['source'] == 'withdraw_referral_earnings') {
?><div class="col-md-12 col-sm-12 col-12 dfThistory"><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdrawal_request_amount_label_txt'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo format_money_amount_display($val['amount']).' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdrawal_request_approval_date_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transaction_id_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['transaction_id']; ?></span></div></label></div><?php
    } else if($val['source'] == 'project_upgrades') {
?><div class="col-md-12 col-sm-12 col-12 dfThistory"><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_project_upgrade_name_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $this->config->item('transaction_history_project_upgrades_upgrade_types')[$val['project_upgrade_type']]; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_purchase_on_project_name_lbl'); ?>:</b><span class="default_black_regular_medium"><a href="<?php echo $this->config->item('project_detail_page_url').'?id='.$val['project_id']; ?>" target="_blank"><?php echo $val['project_title'].(!empty($val['project_title']) ? ' ': '').'('.$this->config->item('transactions_history_project_id_txt').$val['project_id'].')'; ?></a></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_project_upgrade_purchase_on_lbl'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo format_money_amount_display($val['amount']).' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_project_upgrade_purchase_date_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])); ?></span></div></label><label><div><?php
        $source = '';
        if($val['project_upgrade_purchase_source'] == 'membership_included') {
          $source = $this->config->item('transactions_history_project_upgrade_purchase_included_membership_txt');
        } else if($val['project_upgrade_purchase_source'] == 'bonus_based') {
          $source = $this->config->item('transactions_history_project_upgrade_purchase_bonus_balance_txt');
        } else {
          $source = $this->config->item('transactions_history_project_upgrade_purchase_account_balance_txt');
        }
      ?><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_project_upgrade_payment_source_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $source; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_payment_reference_id_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['transaction_id']; ?></span></div></label></div><?php
    } else if($val['source'] == 'payments_project') {
?><div class="col-md-12 col-sm-12 col-12 dfThistory"><label><?php
      $username = ''; 
      if($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')) {
        $username = $val['first_name'].' '.$val['last_name'];
      } else {
        $username = $val['company_name'];
      }
    ?><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_service_provider_name_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $username; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_project_name_lbl'); ?>:</b><span class="default_black_regular_medium"><a href="<?php echo $this->config->item('project_detail_page_url').'?id='.$val['project_id']; ?>" target="_blank"><?php echo htmlspecialchars($val['project_title'], ENT_QUOTES).(!empty($val['project_title']) ? ' ': '').'('.$this->config->item('transactions_history_project_id_txt').$val['project_id'].')'; ?></a></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_project_paid_amount_lbl'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo format_money_amount_display($val['amount']).' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_payment_date_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_payment_reference_id_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['transaction_id']; ?></span></div></label></div><div class="col-md-12 col-sm-12 col-12 dfThistory"><label><?php
      $username = ''; 
      if($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')) {
        $username = $val['first_name'].' '.$val['last_name'];
      } else {
        $username = $val['company_name'];
      }
    ?><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_service_provider_name_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $username; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_project_name_lbl'); ?>:</b><span class="default_black_regular_medium"><a href="<?php echo $this->config->item('project_detail_page_url').'?id='.$val['project_id']; ?>" target="_blank"><?php echo htmlspecialchars($val['project_title'], ENT_QUOTES).(!empty($val['project_title']) ? ' ': '').'('.$this->config->item('transactions_history_project_id_txt').$val['project_id'].')'; ?></a></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_project_paid_service_fees_amount_lbl'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo format_money_amount_display($val['service_fee_charges']).' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_payment_date_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_payment_reference_id_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['transaction_id']; ?></span></div></label></div><?php
    } else if($val['source'] == 'payments_fulltime_project') {
?><div class="col-md-12 col-sm-12 col-12 dfThistory"><label><?php
      $username = ''; 
      if($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')) {
        $username = $val['first_name'].' '.$val['last_name'];
      } else {
        $username = $val['company_name'];
      }
    ?><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_employee_name_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $username; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_fulltime_job_name_lbl'); ?>:</b><span class="default_black_regular_medium"><a href="<?php echo $this->config->item('project_detail_page_url').'?id='.$val['project_id']; ?>" target="_blank"><?php echo htmlspecialchars($val['project_title'], ENT_QUOTES).(!empty($val['project_title']) ? ' ': '').'('.$this->config->item('transactions_history_project_id_txt').$val['project_id'].')'; ?></a></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_salary_paid_amount_lbl'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo format_money_amount_display($val['amount']).' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_payment_date_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_payment_reference_id_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['transaction_id']; ?></span></div></label></div><div class="col-md-12 col-sm-12 col-12 dfThistory"><label><?php
      $username = ''; 
      if($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')) {
        $username = $val['first_name'].' '.$val['last_name'];
      } else {
        $username = $val['company_name'];
      }
    ?><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_employee_name_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $username; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_fulltime_job_name_lbl'); ?>:</b><span class="default_black_regular_medium"><a href="<?php echo $this->config->item('project_detail_page_url').'?id='.$val['project_id']; ?>" target="_blank"><?php echo htmlspecialchars($val['project_title'], ENT_QUOTES).(!empty($val['project_title']) ? ' ': '').'('.$this->config->item('transactions_history_project_id_txt').$val['project_id'].')'; ?></a></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_project_paid_service_fees_amount_lbl'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo format_money_amount_display($val['service_fee_charges']).' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_payment_date_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_payment_reference_id_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['transaction_id']; ?></span></div></label></div><?php
    } else if($val['source'] == 'payments_receive_project') {
?><div class="col-md-12 col-sm-12 col-12 dfThistory"><label><?php
      $username = ''; 
      if($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')) {
        $username = $val['first_name'].' '.$val['last_name'];
      } else {
        $username = $val['company_name'];
      }
    ?><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_project_owner_name_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $username; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_project_name_lbl'); ?>:</b><span class="default_black_regular_medium"><a href="<?php echo $this->config->item('project_detail_page_url').'?id='.$val['project_id']; ?>" target="_blank"><?php echo htmlspecialchars($val['project_title'], ENT_QUOTES).(!empty($val['project_title']) ? ' ': '').'('.$this->config->item('transactions_history_project_id_txt').$val['project_id'].')'; ?></a></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_project_received_payment_amount_lbl'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo format_money_amount_display($val['amount']).' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_payment_date_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_payment_reference_id_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['transaction_id']; ?></span></div></label></div><?php
    } else if($val['source'] == 'payments_receive_fulltime_project') {
?><div class="col-md-12 col-sm-12 col-12 dfThistory"><label><?php
      $username = ''; 
      if($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')) {
        $username = $val['first_name'].' '.$val['last_name'];
      } else {
        $username = $val['company_name'];
      }
    ?><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_employer_name_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $username; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_fulltime_job_name_lbl'); ?>:</b><span class="default_black_regular_medium"><a href="<?php echo $this->config->item('project_detail_page_url').'?id='.$val['project_id']; ?>" target="_blank"><?php echo htmlspecialchars($val['project_title'], ENT_QUOTES).(!empty($val['project_title']) ? ' ': '').'('.$this->config->item('transactions_history_project_id_txt').$val['project_id'].')'; ?></a></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_salary_received_amount_lbl'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo format_money_amount_display($val['amount']).' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_payment_date_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_payment_reference_id_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['transaction_id']; ?></span></div></label></div><?php
    } //else if($val['source'] == 'service_fees_project') {
?><?php
/* <div class="col-md-12 col-sm-12 col-12 dfThistory">
  <label>
    <?php
      $username = ''; 
      if($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')) {
        $username = $val['first_name'].' '.$val['last_name'];
      } else {
        $username = $val['company_name'];
      }
    ?>
    <div>
      <b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_service_provider_name_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $username; ?></span>
    </div>
  </label>
  <label>
    <div>
      <b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_project_name_lbl'); ?>:</b><span class="default_black_regular_medium"><a href="<?php echo $this->config->item('project_detail_page_url').'?id='.$val['project_id']; ?>" target="_blank"><?php echo htmlspecialchars($val['project_title'], ENT_QUOTES).(!empty($val['project_title']) ? ' ': '').'('.$this->config->item('transactions_history_project_id_txt').$val['project_id'].')'; ?></a></span>
    </div>
  </label>
  <label>
    <div>
      <b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_project_paid_service_fees_amount_lbl'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo format_money_amount_display($val['amount']).' '.CURRENCY; ?></span>
    </div>
  </label>
  <label>
    <div>
      <b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_payment_date_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])); ?></span>
    </div>
  </label>
  <label>
    <div>
      <b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_payment_reference_id_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['transaction_id']; ?></span>
    </div>
  </label>
</div> */
?>
<?php
    //} //else if($val['source'] == 'service_fees_fulltime') {
?>
<?php
/* <div class="col-md-12 col-sm-12 col-12 dfThistory">
  <label>
    <?php
      $username = ''; 
      if($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')) {
        $username = $val['first_name'].' '.$val['last_name'];
      } else {
        $username = $val['company_name'];
      }
    ?>
    <div>
      <b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_employee_name_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $username; ?></span>
    </div>
  </label>
  <label>
    <div>
      <b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_fulltime_job_name_lbl'); ?>:</b><span class="default_black_regular_medium"><a href="<?php echo $this->config->item('project_detail_page_url').'?id='.$val['project_id']; ?>" target="_blank"><?php echo htmlspecialchars($val['project_title'], ENT_QUOTES).(!empty($val['project_title']) ? ' ': '').'('.$this->config->item('transactions_history_project_id_txt').$val['project_id'].')'; ?></a></span>
    </div>
  </label>
  <label>
    <div>
      <b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_project_paid_service_fees_amount_lbl'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo format_money_amount_display($val['amount']).' '.CURRENCY; ?></span>
    </div>
  </label>
  <label>
    <div>
      <b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_payment_date_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])); ?></span>
    </div>
  </label>
  <label>
    <div>
      <b class="default_black_bold_medium"><?php echo $this->config->item('transactions_history_payment_reference_id_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['transaction_id']; ?></span>
    </div>
  </label>
</div> */
?>
<?php
    //}

  }
?>

<?php 
  if(empty($transactions_histroy) && $transactions_histroy_cnt == 0) {
?>
<div class="initialViewNorecord">
    <h4><?php echo $this->config->item('transactions_history_search_no_results_returned_message'); ?></h4>
</div>
<?php
  }
?>


<?php
  if(!$is_last_page) {
?>
<div class="row rowAddAnother">
	<div class="col-md-12 text-center addAnother">
		<button type="button" id="loadmore_transactions" class="btn default_btn blue_btn"  data-page="<?php echo $page_no; ?>"><?php echo $this->config->item('load_more_results'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
	</div>
</div>
<?php
  }
?>

