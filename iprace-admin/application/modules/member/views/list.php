<style type="text/css">
    .mybox{
        background-color: #F7F7F7;
        border: 1px solid #C9C9C9;
        margin: 0;
        padding: 4px;

    }
    .mybox:hover{
        background-color: #C9C9C9;
        color:#fff;
    }
    .myactiveclass{
        background-color: #C9C9C9;
        color:#fff;
    }
    
    .cke_browser_webkit {
        z-index: 9999!important;
    }
    
    .cke_dialog_background_cover {
        display: none!important;
    }
    
</style>

<section id="content">
    <div class="wrapper">
        <div class="crumb">
            <ul class="breadcrumb">
               <li class="active"><a href="<?php echo base_url(); ?>"><i class="icon16 i-home-4"></i>Home</a></li>
               <li class="active"><a href="<?php echo base_url('member/member_list'); ?>">Member List</a></li>
             <!--  <li class="active"><a onclick="redirect_to('<?php echo base_url() . 'member/edit'; ?>');">View Member</a></li>-->
            </ul>
        </div>
        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-table-2"></i> Member Management</h1>
            </div>

        <?php 
          $user_id = $this->uri->segment(3);
		    ?>
            <div class="row">

                <div class="col-lg-12">
                    <div class="page-header">
                      <h4>Member List</h4>
                    </div><!-- End .form-group  -->
                        <div class="srcType">
                          <div class="row marginBtm0">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                              <div class="form-group">
                                <input type="text" id="search_keyword" class="form-control default_input_field hideField" data-role="tagsinput" placeholder="search keyword" />
                              </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                              <div class="form-group">
                                <input type="text" id="autocomplete" class="form-control default_input_field hideField" data-role="tagsinput" placeholder="search Location" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="srchName">
                          <div class="row marginBtm0">
                            <div class="col-md-8 col-sm-8 col-12 tySrch">
                              <div class="topSearch">
                                <label class="typeSrch default_black_bold"><?php echo 'Search Type'; ?></label>
                                  <span class="chkAdjust">
                                    <div class="form-check">
                                      <label class="default_checkbox">
                                        <input type="checkbox" id="include" class="search_type" value="include" checked><span class="checkmark"></span>
                                      </label>
                                      <span class="textGap"><?php echo 'include'; ?></span>
                                    </div>
                                    <div class="form-check">
                                      <label class="default_checkbox">
                                        <input type="checkbox" id="exclude" class="search_type" value="exclude">
                                        <span class="checkmark"></span>
                                      </label>
                                      <span class="textGap"><?php echo 'Exclude'; ?></span>
                                    </div>
                                  </span>
                                <label class="typeSrchLabel">|</label>
                                <span class="chkAdjust chkBoxRight">
                                  <div class="form-check">
                                    <label class="default_checkbox">
                                        <input type="checkbox" id="searchTitle" ><span class="checkmark"></span>
                                    </label>
                                    <label class="typeSrch default_black_bold textGap"><?php echo 'Search By Name'; ?></label>
                                  </div>
                                </span>
                              </div>
                              <div class="receive_notification">
                                <a class="rcv_notfy_btn" onclick="showMoreSearch()">Show More <small>( + )</small></a>
                                <input type="hidden" id="moreSearch" value="1">
                              </div>
                            </div>
                            <div class="col-md-2 col-sm-2 col-12 pLt0 srchBtn">
                              <button type="button" class="btn btn-block default_btn blue_btn search_clear"><?php echo 'Clear Search'; ?></button>
                            </div>
                            <div class="col-md-2 col-sm-2 col-12 pLt0 srchBtn">
                              <button type="button" class="btn btn-block orange_btn default_btn search_btn"><?php echo 'Search'; ?></button>
                            </div>
                          </div>
                        </div>

                        <div class="proDtls">
                          <div id="rcv_notfy" class="pDtls" style="display:none">					
                            <div class="fbSelect">
                              <div class="fProfessionallr">
                                <div class="multiselect pSelect">
                                  <div class="selectBox">
                                    <select class="">
                                      <option>Membership Plan</option>
                                    </select>
                                    <div class="overSelect"></div>
                                  </div>
                                  <div id="checkboxes2" class="visible_option select_full_width" style="display: none;">
                                    <div class="drpChk">
                                      <label for="membership_plan_all" class="default_checkbox">
                                        <input type="checkbox" id="membership_plan_all" value="membership_plan_all" checked="">
                                        <span class="checkmark"></span>
                                      </label>
                                      <small class="boldCat">vše</small>
                                    </div>
                                    <div class="drpChk">
                                      <label for="membership_plan_1" class="default_checkbox">
                                        <input type="checkbox" id="membership_plan_1" value="4">
                                        <span class="checkmark"></span>
                                      </label>
                                      <small class="">Gold</small>
                                    </div>
                                    <div class="drpChk">
                                      <label for="membership_plan_2" class="default_checkbox">
                                        <input type="checkbox" id="membership_plan_2" value="1">
                                        <span class="checkmark"></span>
                                      </label>
                                      <small class="">Free</small>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            
                              <div class="fProfessionallr">
                                <div class="multiselect pSelect">
                                  <div class="selectBox">
                                    <select class="">
                                      <option>User Additional Settings</option>
                                    </select>
                                    <div class="overSelect"></div>
                                  </div>
                                  <div id="checkboxes3" class="visible_option select_flex_width" style="display: none;">
                                    <div class="drpChk">
                                      <label for="additional_settings_all" class="default_checkbox">
                                        <input type="checkbox" id="additional_settings_all" value="additional_settings_all" checked="">
                                        <span class="checkmark"></span>
                                      </label>
                                      <small class="boldCat">vše</small>
                                    </div>
                                    <div class="drpChk">
                                      <label  for="additional_settings_1" class="default_checkbox">
                                        <input type="checkbox" id="additional_settings_1" value="additional_dropdpwn_find_professional_page">
                                        <span class="checkmark"></span>
                                      </label>
                                      <small class="">Enable additional drop downs on find professionals page</small>
                                    </div> 
                                    <div class="drpChk">
                                      <label  for="additional_settings_2" class="default_checkbox">
                                        <input type="checkbox" id="additional_settings_2" value="featured_profile_on_find_professionals_page">
                                        <span class="checkmark"></span>
                                      </label>
                                      <small class="">Featured profile on find profesisonals page</small>
                                    </div> 
                                  </div>
                                </div>
                              </div>

                              <div class="fProfessionallr">
                                <div class="multiselect pSelect">
                                  <div class="selectBox">
                                    <select class="">
                                      <option>Banned user profile</option>
                                    </select>
                                    <div class="overSelect"></div>
                                  </div>
                                  <div id="checkboxes4" class="visible_option select_flex_width" style="display: none;">
                                    <div class="drpChk">
                                      <label for="banned_user_profile_all" class="default_checkbox">
                                        <input type="checkbox" id="banned_user_profile_all" value="banned_user_profile_all" checked="">
                                        <span class="checkmark"></span>
                                      </label>
                                      <small class="boldCat">vše</small>
                                    </div>
                                    <div class="drpChk">
                                      <label  for="banned_user_profile_1" class="default_checkbox">
                                        <input type="checkbox" id="banned_user_profile_1" value="banned_profile_on_find_professional_page">
                                        <span class="checkmark"></span>
                                      </label>
                                      <small class="">user profile not to be displayed on find professionals page</small>
                                    </div>
                                    <div class="drpChk">
                                      <label for="banned_user_profile_2" class="default_checkbox">
                                        <input type="checkbox" id="banned_user_profile_2"  value="banned_profile_on_user_profile_page">
                                        <span class="checkmark"></span>
                                      </label>
                                      <small class="">user profile page not to be accessible to public</small>
                                    </div>
                                  </div>
                                </div>
                              </div>
                                        
                              <div class="clearfix"></div>
                            </div>
                            <div class="filter_wrapper">
                              <label class="defaultTag">
                                <label class="checkboxes2">
                                  <span class="tagFirst">Membership Plan</span>
                                  <small class="tagSecond" style="display:block">vše<i class="fa fa-times" aria-hidden="true" style="display:none"></i></small>
                                </label>
                              </label>
                              
                              <label class="defaultTag">
                                <label class="checkboxes3">
                                  <span class="tagFirst">User Additional Settings</span>
                                  <small class="tagSecond" style="display:block">vše<i class="fa fa-times" aria-hidden="true" style="display:none"></i></small>
                                </label>
                              </label>
                              
                              <label class="defaultTag">
                                <label class="checkboxes4">
                                  <span class="tagFirst">Banned user profile</span>
                                  <small class="tagSecond" style="display:block">vše<i class="fa fa-times" aria-hidden="true" style="display:none"></i></small>
                                </label>
                              </label>

                              <label class="defaultTag">
                                <button class="btn default_btn blue_btn btnBold clear_all_filters clear_all_filter">Zrušit filtry</button>
                              </label>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <?php
                              if ($this->session->flashdata('succ_msg')) {
                                  ?>
                                  <div class="alert alert-success">
                                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                                      <strong><i class="icon24 i-checkmark-circle"></i> Well done!</strong> <?php echo $this->session->flashdata('succ_msg'); ?>
                                  </div> 
                                  <?php
                              }
                              if ($this->session->flashdata('error_msg')) {
                                  ?>
                                  <div class="alert alert-error">
                                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                                      <strong><i class="icon24 i-close-4"></i> Oh snap!</strong> <?php echo $this->session->flashdata('error_msg'); ?>
                                  </div>
                                  <?php
                              }
                          ?>
                          <div id="succ" class="alert alert-success" style="display:none">
                              <button type="button" class="close" data-dismiss="alert">&times;</button>
                              <strong><i class="icon24 i-checkmark-circle"></i> </strong><span id="msg"></span>
                          </div> 
                        </div>
                        <div class="member_wrapper">
                          <?php echo $this->load->view('ajax_member_list', ['all_data' => $all_data, 'links' => $links, 'total_rows' => $total_rows], TRUE); ?>
                        </div>
                </div><!-- End .col-lg-6  -->
            </div><!-- End .row-fluid  -->
			
    </div> <!-- End .container-fluid  -->
    </div> <!-- End .wrapper  -->
</section>             
<!-- Modal -->

  <!-- <script src="<?php echo JS ?>jquery-3.3.1.js"></script> -->
  <script src="<?php echo JS ?>typeahead.bundle.js"></script>
  <script src="<?php echo JS ?>bootstrap_tagsinput.js"></script>
  <!-- <script src="<?php echo JS ?>popper.1.14.0.min.js"></script> -->
  <!-- <script src="<?php echo JS ?>bootstrap-4.js"></script> -->

<script type="text/javascript">
  var SITE_URL = '<?php echo SITE_URL; ?>';
  var data = '<?php echo json_encode($locations); ?>';

  var task = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace("text"),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: jQuery.parseJSON(data) //your can use json type
  });
  task.initialize();
  var elt = $("#autocomplete");

  elt.tagsinput({
    itemValue: "value",
    itemText: "text",
    typeaheadjs: {
    minlength : 0,
    name: "task",
    displayKey: "text",
    source: task.ttAdapter(),
    limit : 10
  }
});
</script>
<script src="<?php echo JS; ?>modules/member.js" type="text/javascript"></script>