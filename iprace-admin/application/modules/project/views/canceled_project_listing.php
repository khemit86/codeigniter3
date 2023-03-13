<style>
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

</style>

<section id="content">
    <div class="wrapper">
        <div class="crumb">
            
            <ul class="breadcrumb">
                <li class="active"><a href="<?= base_url() ?>"><i class="icon16 i-home-4"></i>Home</a></li>
                <li class="active"><a href="<?= base_url() ?>project/">Project List</a></li>
            </ul>
        </div>


        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-table-2"></i> Project Management (Cancelled)</h1>
            </div>
            
            <div class="row">
              <div class="col-lg-12">
                <!-- End .form-group  -->
                <div class="srcType">
                  <div class="row marginBtm0">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <div class="form-group">
                        <input type="text" id="search_keyword" class="form-control default_input_field hideField" data-role="tagsinput" placeholder="search keyword" />
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6" style="margin-top:5px;">
                      <div class="col-md-4 col-sm-4 col-12 pLt0 srchBtn">
                        <button type="button" class="btn btn-block default_btn blue_btn search_clear"><?php echo 'Clear Search'; ?></button>
                      </div>
                      <div class="col-md-4 col-sm-4 col-12 pLt0 srchBtn">
                        <button type="button" class="btn btn-block orange_btn default_btn search_btn"><?php echo 'Search'; ?></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="srchName">
                  <div class="row marginBtm0">
                    <div class="col-md-8 col-sm-8 col-12 tySrch">
                      <div class="topSearch">
                        <label class="typeSrch default_black_bold"><?php echo 'Search In'; ?></label>
                          <span class="chkAdjust">
                            <div class="form-check">
                              <label class="default_checkbox">
                                <input type="checkbox" id="project_id" class="search_type" value="project_id" checked><span class="checkmark"></span>
                              </label>
                              <span class="textGap"><?php echo 'Project Id'; ?></span>
                            </div>
                            <div class="form-check">
                              <label class="default_checkbox">
                                <input type="checkbox" id="project_title" class="search_type" value="project_title">
                                <span class="checkmark"></span>
                              </label>
                              <span class="textGap"><?php echo 'Project Title'; ?></span>
                            </div>
                            <div class="form-check">
                              <label class="default_checkbox">
                                  <input type="checkbox" class="search_type" value="project_owner" ><span class="checkmark"></span>
                              </label>
                              <span class="textGap"><?php echo 'Project Owner'; ?></span>
                            </div>
                          </span>
                      </div>
                      <div class="receive_notification">
                        <a class="rcv_notfy_btn" onclick="showMoreSearch()">Show More <small>( + )</small></a>
                        <input type="hidden" id="moreSearch" value="1">
                      </div>
                    </div>
                    <!-- <div class="col-md-2 col-sm-2 col-12 pLt0 srchBtn">
                      <button type="button" class="btn btn-block default_btn blue_btn search_clear"><?php echo 'Clear Search'; ?></button>
                    </div>
                    <div class="col-md-2 col-sm-2 col-12 pLt0 srchBtn">
                      <button type="button" class="btn btn-block orange_btn default_btn search_btn"><?php echo 'Search'; ?></button>
                    </div> -->
                  </div>
                </div>
                <div class="proDtls">
                  <div id="rcv_notfy" class="pDtls" style="display:none">					
                    <div class="fbSelect">
                      <div class="fProfessionallr">
                        <div class="multiselect pSelect">
                          <div class="selectBox">
                            <select class="">
                              <option>Project Type</option>
                            </select>
                            <div class="overSelect"></div>
                          </div>
                          <div id="checkboxes2" class="visible_option select_full_width" style="display: none;">
                            <div class="drpChk">
                              <label for="project_type_all" class="default_checkbox">
                                <input type="checkbox" id="project_type_all" value="project_type_all" checked="">
                                <span class="checkmark"></span>
                              </label>
                              <small class="boldCat">vše</small>
                            </div>
                            <div class="drpChk">
                              <label for="project_type_1" class="default_checkbox">
                                <input type="checkbox" id="project_type_1" value="fixed">
                                <span class="checkmark"></span>
                              </label>
                              <small class="">Fixed</small>
                            </div>
                            <div class="drpChk">
                              <label for="project_type_2" class="default_checkbox">
                                <input type="checkbox" id="project_type_2" value="hourly">
                                <span class="checkmark"></span>
                              </label>
                              <small class="">Hourly</small>
                            </div>
                            <div class="drpChk">
                              <label for="project_type_3" class="default_checkbox">
                                <input type="checkbox" id="project_type_3" value="fulltime">
                                <span class="checkmark"></span>
                              </label>
                              <small class="">Fulltime</small>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="clearfix"></div>
                    </div>
                    <div class="filter_wrapper">
                      <label class="defaultTag">
                        <label class="checkboxes2">
                          <span class="tagFirst">Project Type</span>
                          <small class="tagSecond" style="display:block">vše<i class="fa fa-times" aria-hidden="true" style="display:none"></i></small>
                        </label>
                      </label>

                      <label class="defaultTag">
                        <button class="btn default_btn blue_btn btnBold clear_all_filters clear_all_filter">Zrušit filtry</button>
                      </label>
                    </div>
                  </div>
                </div>
                </br>
              </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <?php
                    if ($this->session->flashdata('succ_msg')) {
                        ?>
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><i class="icon24 i-checkmark-circle"></i> Well done!</strong> <?= $this->session->flashdata('succ_msg') ?>
                        </div> 
                        <?php
                    }
                    if ($this->session->flashdata('error_msg')) {
                        ?>
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><i class="icon24 i-close-4"></i> Oh snap!</strong> <?= $this->session->flashdata('error_msg') ?>
                        </div>
							<?php
                            }
                            ?>    
                  			              
			    <div class="member_wrapper">
                    <?php echo $this->load->view('ajax_canceled_project_list', ['project_listing' => $project_listing, 'links' => $links, 'total_rows' => $total_rows], TRUE); ?>
                </div>
					           
                    </section>

<script src="<?php echo JS ?>bootstrap_tagsinput.js"></script>
<script src="<?php echo JS ?>typeahead.bundle.js"></script>
<script src="<?php echo JS; ?>modules/project.js" type="text/javascript"></script>
<script src="<?php echo JS; ?>modules/canceled_project.js" type="text/javascript"></script>


                