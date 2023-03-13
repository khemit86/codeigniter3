<div class="table-responsive" style="overflow:auto;">
  <table class="table table-hover table-bordered adminmenu_list checkAll" id="example1">
    <thead>
        <tr>  
            <th style="text-align:left;">Id</th>
            
            <th style="text-align:left;">Avatar</th>
            <th style="text-align:left;">Account Type</th>
            
            <th style="text-align:left;">Profile url/Email</th>
            
            <th style="text-align:left;">Name</th>
            
                        
            <th style="text-align:left;">Balance</th>

            <th style="text-align:left;">Membership Plan</th> 
            
              <th style="text-align:left;">Join Date/Last Login</th>

            <th>Status</th>
            
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="id2">
      <?php
        $attr = array(
        'onclick' => "javascript: return confirm('Do you want to delete?');",
        'class' => 'i-cancel-circle-2 red',
        'title' => 'Delete'
        );
        $attr9 = array(
        'onclick' => "javascript: return confirm('Do you want to make feature this client?');",
        'class' => 'i-checkmark-3 red',
        'title' => 'Normal'
        );
        $attr8 = array(
        'onclick' => "javascript: return confirm('Do you want to remove featured from this client?');",
        'class' => 'i-checkmark-3 green',
        'title' => 'Featured'
        );
        $atr3 = array(
          'onclick' => "javascript: return confirm('Do you want to active this?');",
          'class' => 'i-checkmark-3 red',
          'title' => 'Inactive'
        );
        $atr4 = array(
          'onclick' => "javascript: return confirm('Do you want to inactive this?');",
          'class' => 'i-checkmark-3 green',
          'title' => 'Active'
        );


        if (count($all_data) != 0) {
            foreach ($all_data as $key => $user) {
            // $plan = $this->auto_model->getFeild('membership_plan_name','membership_plans','id',$user['membership_plan']);
      ?>

        <tr>
          <td align="center"><?php echo $user['user_id']; ?></td>
          <td>
              <?php 
                  $bUrl =  SITE_URL;
                    $fUrl = str_replace("iprace-admin/","",$bUrl);
                  $url = HTTP_WEBSITE_HOST;
              ?>                                          
              <?php if ($user['logo'] != "") { ?>
                <img src="<?php echo $url.'users/'.$user['username'].'/avatar/'.$user['logo']; ?>" height="60" width="60" />
              <?php 
                } else { 
                  if($user['account_type'] == 1 || ($user['account_type'] == 2 && $user['is_authorized_physical_person'] == 'Y')) {
                    if($user['gender'] == "M") {
              ?>
                <img src="<?php echo $url . 'assets/images/avatar_default_male.png'; ?>" height="60" width="60"/>	
              <?php
                  } else {
              ?>
                <img src="<?php echo $url . 'assets/images/avatar_default_female.png'; ?>" height="60" width="60"/>	
              <?php
                  }
                } else {
              ?>
                <img src="<?php echo $url . 'assets/images/avatar_default_company.png'; ?>" height="60" width="60"/>	
              <?php
                }
              } 
              ?>           
          </td>
          <td><?php echo $user['account_type'] == 1 ? 'Personal' : 'Company'; ?></td>
          <td><?php echo $user['username']."<br/>".$user['email']; ?></td> 
          <td><?php echo $user['account_type'] == 1 || ($user['account_type'] == 2 && $user['is_authorized_physical_person'] == 'Y') ? ucwords($user['first_name'])." ".ucwords($user['last_name']) : ucwords($user['company_name']) ;?></td>
          <td><?php echo format_money_amount_display($user['acc_balance']).' '.CURRENCY; ?></td>
          <td><?php echo $user['plan'];?></td>
          <td><?php echo (!empty($user['reg_date']) ? date(DATE_TIME_FORMAT,strtotime($user['reg_date'])) : '----') ."<br/>".(!empty($user['latest_login_date']) ? date(DATE_TIME_FORMAT,strtotime($user['latest_login_date'])) : '---'); ?></td> 
          <td>   
              <?php
                if ($user['status'] == 'Y') {
                  echo anchor(base_url() . 'member/change_status/' . $user['user_id'].'/inact/'.$user['status'], '&nbsp;', $atr4);
                }
                else if($user['status'] == 'N') {
                  echo anchor(base_url() . 'member/change_status/' . $user['user_id'].'/act/'.$user['status'], '&nbsp;', $atr3);
                } else {
                  echo "Active";	
                }
            ?>
          </td>
          <td align="center">
            <a href="" class="display_additional" data-id="<?php echo  $user['user_id'];?>" ><i class="i-plus-circle-2"></i></a>           
            <?php
              
              $atr5= array('class' => 'i-mail-2', 'title' => 'Send mail');
              echo anchor(base_url() . 'member/change_status/' . $user['user_id'].'/del/','&nbsp;', $attr);
            ?>
            </td>
        </tr>
        <tr style="display:none;" id="<?php echo  $user['user_id'];?>">
            <td colspan="10">
              <div>
                <label class="default_checkbox">
                  <input type="checkbox" value="<?php echo $user['user_id'];?>" data-type="additional_dropdown" <?php echo !empty($user['additional_dropdpwn_on_find_professionals_page']) && $user['additional_dropdpwn_on_find_professionals_page'] == 'Y' ? 'checked' : '';  ?> class="find_professional_dropdown"><span class="checkmark"></span>
                </label>
                <span style="padding-left:5px;font-weight:bold;">Enable additional drop down on find professionals page</span>
              </div>
              <div>
                <label class="default_checkbox">
                  <input type="checkbox" value="<?php echo $user['user_id'];?>" data-type="banned_on_find_professional_page" <?php echo !empty($user['user_profile_not_displayed_on_find_professionals_page']) && $user['user_profile_not_displayed_on_find_professionals_page'] == 'Y' ? 'checked' : '';  ?> class="find_professional_dropdown"><span class="checkmark"></span>
                </label>
                <span style="padding-left:5px;font-weight:bold;">user profile not to be displayed on find professionals page</span>
              </div>
              <div>
                <label class="default_checkbox">
                  <input type="checkbox" value="<?php echo $user['user_id'];?>" data-type="banned_on_user_profile_page" <?php echo !empty($user['user_profile_page_not_accessible']) && $user['user_profile_page_not_accessible'] == 'Y' ? 'checked' : '';  ?> class="find_professional_dropdown"><span class="checkmark"></span>
                </label>
                <span style="padding-left:5px;font-weight:bold;">user profile page not to be accessible to public</span>
              </div>
              <div>
                <label class="default_checkbox">
                  <input type="checkbox" value="<?php echo $user['user_id'];?>" data-type="featured_user_profile_page" <?php echo !empty($user['featured_user_profile']) && $user['featured_user_profile'] == $user['user_id'] ? 'checked' : '';  ?> class="featured_user_profile"><span class="checkmark"></span>
                </label>
                <span style="padding-left:5px;font-weight:bold;">Featured profile on find professionals page</span> <span id="featured_<?php echo $user['user_id'];?>"><?php echo !empty($user['featured_profile_enabled_date']) ? date(DATE_TIME_FORMAT, strtotime($user['featured_profile_enabled_date'])): ''; ?></span>
                
              </div>
            </td>
        </tr>

      <?php } ?>

    <?php } else { ?>
          <tr>
              <td colspan="10" align="center" class="red">
                  No Records Found
              </td>
          </tr>
    <?php } ?>


    </tbody>
  </table>
</div>
<!-- Pagination Start -->			
  <div class="pagnOnly" style="display:<?php echo !empty($all_data) ? 'block' : 'none';?>">
    <div class="row">
      <div class="no_page_links <?php echo !empty($links) ? 'col-md-7 col-sm-7 col-12' : 'col-md-12 col-12'; ?>">
        <?php
          if(empty($rec_per_page)) {
            $rec_per_page = ($total_rows > PAGING_LIMIT) ? PAGING_LIMIT : $total_rows;
          }
        ?>
        <div class="pageOf">
          <label><?php echo 'showing' ?> <span class="page_no"><?php echo !empty($page_no) ? $page_no : '1'; ?></span> - <span class="rec_per_page"><?php echo !empty($record_per_page) ? $record_per_page  : $rec_per_page; ?></span> <?php echo 'out of' ?> <span class="total_rec"><?php echo $total_rows; ?></span> <?php echo 'listing' ?></label>
        </div>
      </div>
      <div class="page_links col-md-5 col-sm-5 col-12" style="display:<?php echo !empty($links) ? 'block' : 'none'; ?>">
        <div class="modePage">
          <?php
            echo $links;
          ?>
        </div>
      </div>
    </div>
  </div>

<!-- Pagination End -->