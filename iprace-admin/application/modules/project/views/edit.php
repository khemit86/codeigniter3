<section id="content">
    <div class="wrapper">
        <?php 
            if($status=='O'){
                    $fnc = 'open';
            }elseif($status=='F'){
                    $fnc = 'frozen';
            }
            elseif($status=='P'){
                    $fnc = 'process';
            }
            elseif($status=='C'){
                    $fnc = 'complete';
            }
            elseif($status=='E'){
                    $fnc = 'expire';
            }
            ?>
                        
        <div class="crumb">
            <ul class="breadcrumb">
                <li class="active"><a href="<?php echo base_url() ?>"><i class="icon16 i-home-4"></i>Home</a></li>
                <li class="active"><a href="<?php echo base_url() ?>project/<?php echo$fnc?>">Project List</a></li>

            </ul>
        </div>

        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-list-4"></i>Project Management </h1>
            </div>
            <div class="row">
                <div class="col-lg-12">
                <?php
                    if ($this->session->flashdata('succ_msg')) {
                        ?>
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><i class="icon24 i-checkmark-circle"></i> Well done!</strong> <?php echo $this->session->flashdata('succ_msg') ?>
                        </div> 
                        <?php
                    }
                    if ($this->session->flashdata('error_msg')) {
                        ?>
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><i class="icon24 i-close-4"></i> Oh snap!</strong> <?php echo $this->session->flashdata('error_msg') ?>
                        </div>
    <?php
}
?>
                    <div class="panel panel-default">
                        <div class="panel-heading">

                            <div class="icon"><i class="icon20 i-stack-checkmark"></i></div> 
                            <h4>Edit/Modify Project  </h4>
                            <a href="#" class="minimize2"></a>
                        </div><!-- End .panel-heading -->
                        
                        
                    
                        
                        

      <div class="panel-body">
        <form id="validate" action="<?php echo base_url(); ?>project/edit_project/<?php echo $status;?>/<?php echo $id;?>" class="form-horizontal" role="form" name="state" method="post" enctype="multipart/form-data">
        <?php 
		 
    if(isset($all_data))
	{ 
        $buget=$all_data['buget_min']."#".$all_data['buget_max'];
		$buget=trim($buget,"");
		$skill=explode(",",$all_data['skills']);
 		$parentc=$this->auto_model->getFeild('cat_id','categories','cat_name',$all_data['category']);        
		$subcat=$this->project_model->getSubcategory($parentc);	
        
    }
		?>

			<input type="hidden" name="id" value="<?php echo $id; ?>" />
            <div class="form-group">
				<label class="col-lg-2 control-label" for="required">Project Title</label>
				<div class="col-lg-6">
                	<input type="text" id="required" value="<?php echo $all_data['title']; ?>" name="title" class="required form-control" <?php if($status!='O'){ ?> readonly="readonly" <?php } ?> />
                					<?php echo form_error('title', '<label class="error" for="required">', '</label>'); ?>
                				</div>
                            </div><!-- End .control-group  -->
                            
                              <div class="form-group">
                				<label class="col-lg-2 control-label" for="required">Category</label>
                				<div class="col-lg-6">
                                <select class="required form-control" name="category" id="required" onchange="getscat(this.value)" disabled  />
                                <option value="">Please Choose Category</option>
                                <?php
                                foreach($parent_cat as $key=>$val)
                				{
                					$num_scat=$this->auto_model->getnumsubcat($val['cat_id']);
                					if($num_scat>0)
                					{
                				?>
                                <option value="<?php echo $val['cat_name']?>" <?php if($val['cat_name']==$all_data['category']){echo "selected";}?>><?php echo $val['cat_name'];?></option>
                                <?php
                					}
                				}
                				?>
                                </select>
                        	
                					<?php echo form_error('parentcat', '<label class="error" for="required">', '</label>'); ?>
                				</div>
                            </div><!-- End .control-group  -->
                            
                            <div class="form-group">
                				<label class="col-lg-2 control-label" for="required">Sub Category</label>
                				<div class="col-lg-6">
                                <select class="required form-control" name="skills" id="subcategory_id" disabled />
                                <option value="">Please Choose Sub-category</option>
                                <?php 
                                foreach($subcat as $key=>$val)
                				{
                					
                				?>
                                <option value="<?php echo $val['cat_name']?>" <?php if($val['cat_name']==$all_data['skills']){echo "selected";}?>><?php echo $val['cat_name'];?></option>
                                <?php
                					
                				}
                				?>
                                </select>
                        	<!--<input type="text" id="required" value="<?php echo $all_data['category']; ?>" name="category" class="required form-control" <?php if($status!='O'){ ?> readonly="readonly" <?php } ?>>-->
                					<?php echo form_error('category', '<label class="error" for="required">', '</label>'); ?>
                				</div>
                            </div><!-- End .control-group  -->
                            
                            
                              <div class="form-group">
                				<label class="col-lg-2 control-label" for="required">Description</label>
                				<div class="col-lg-6">
                				 <textarea class="form-control elastic" id="textarea1" name="description" rows="3" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 100px;"><?php echo strip_tags($all_data['description']);//strip_tags($this->auto_model->truncate(, 5000, '', true, true)); ?></textarea>
                					<?php echo form_error('description', '<label class="error" for="required">', '</label>'); ?>
                				</div>
                            </div><!-- End .control-group  -->
                          
                            
                              <div class="form-group">
                				<label class="col-lg-2 control-label" for="required">Project Type</label>
                				<div class="col-lg-6">
                                        <select onchange="setBudget(this.value)" class="acount-input" size="1" id="project_type" name="project_type" disabled />
                                        <option value="F" <?php if($all_data['project_type']=="F"){echo "selected";}?>>Fixed</option>
                                        <option value="H" <?php if($all_data['project_type']=="H"){echo "selected";}?>>Hourly</option>
                                        <option value="FU" <?php if($all_data['project_type']=="FU"){echo "selected";}?>>Fulltime</option>
                                        </select>
                					<?php echo form_error('project_type', '<label class="error" for="required">', '</label>'); ?>
                				</div>
                            </div><!-- End .control-group  -->
                            
                            <?php
                                if($all_data['project_type'] == 'H'){
                                    $bugets = $this->project_model->getHourlyRate();
                                }else if($all_data['project_type'] == 'F'){
                                    $bugets = $this->project_model->getFixed();
                                }else if($all_data['project_type'] == 'FU'){
                                    $bugets = $this->project_model->getFulltime();
                                }  
                            ?>           
                            
                                <div class="form-group" id="ptype_f" >
                                <label class="col-lg-2 control-label" for="required">Budget</label>
                                
                                <div class="col-lg-6">
                                        <select class="acount-input" size="1" name="budgetall" id="budgetall" disabled />
                                        <option value="">--- Please Select ---</option>
                                        <?php foreach($bugets as $bg){ ?>
                                            <?php if($all_data['project_type'] == 'H'){ ?>
                                                <?php if($bg['max'] != 0){ ?>                                 
                                                    <option value="<?php echo $bg['min'].'#'.$bg['max']; ?>" <?php if($all_data['buget_min'].'#'.$all_data['buget_max'] == $bg['min'].'#'.$bg['max']){echo "selected='selected'";}?>><?php echo '$'.$bg['min'].'/hr to $'.$bg['max'].'/hr'; ?></option>
                                                <?php }else{ ?>
                                                    <option value="<?php echo $bg['min'].'#0'; ?>" <?php if($all_data['buget_min'].'#0' == $bg['min'].'#0'){echo "selected='selected'";}?>><?php echo 'More then $'.$bg['min'].'/hr'; ?></option>
                                                <?php } ?>    
                                            <?php }else if($all_data['project_type'] == 'F'){ ?>
                                                <?php if($bg['max'] != 0){ ?>                                 
                                                    <option value="<?php echo $bg['min'].'#'.$bg['max']; ?>" <?php if($all_data['buget_min'].'#'.$all_data['buget_max'] == $bg['min'].'#'.$bg['max']){echo "selected='selected'";}?>><?php echo 'Between $'.$bg['min'].' and $'.$bg['max'];  ?></option>
                                                <?php }else{ ?>
                                                    <option value="<?php echo $bg['min'].'#0'; ?>" <?php if($all_data['buget_min'].'#0' == $bg['min'].'#0'){echo "selected='selected'";}?>><?php echo 'More then $'.$bg['min'] ?></option>
                                                <?php } ?> 
                                            <?php }else if($all_data['project_type'] == 'FU'){ ?>
                                                <?php if($bg['max'] != 0){ ?>                                 
                                                    <option value="<?php echo $bg['min'].'#'.$bg['max']; ?>" <?php if($all_data['buget_min'].'#'.$all_data['buget_max'] == $bg['min'].'#'.$bg['max']){echo "selected='selected'";}?>><?php echo 'Between $'.$bg['min'].' and $'.$bg['max'];  ?></option>
                                                <?php }else{ ?>
                                                    <option value="<?php echo $bg['min'].'#0'; ?>" <?php if($all_data['buget_min'].'#0' == $bg['min'].'#0'){echo "selected='selected'";}?>><?php echo 'More then $'.$bg['min'] ?></option>
                                                <?php } ?> 
                                            <?php } ?>                            
                                        <?php } ?>                        
                                        </select>
                             		<?php echo form_error('budgetall', '<label class="error" for="required">', '</label>'); ?>
                				</div>
                            </div><!-- End .control-group  -->
                                            
                
                    			<div class="form-group">
                                  <label class="col-lg-2 control-label" for="agree">Featured</label>
                                  <label class="checkbox-inline">
                                  <input class="form-control" type="radio" id="featured" name="featured" disabled value="Y" <?php if($all_data['featured']=='Y'){echo "checked";} ?> />Yes
                                  <input class="form-control" type="radio" id="featured" name="featured" disabled value="N" <?php if($all_data['featured']=='N'){echo "checked";} ?> />No
                                  
                                  </label>
                                </div>                                
                                <!-- End .control-group  -->
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label" for="agree">Private</label>
                                  <label class="checkbox-inline">
                                  <input class="form-control" type="radio" id="private" name="private" disabled value="Y" <?php if($all_data['private']=='Y'){echo "checked";} ?> />Yes
                                  <input class="form-control" type="radio" id="private" name="private" disabled value="N" <?php if($all_data['private']=='N'){echo "checked";} ?> />No
                                  
                                  </label>
                                </div>                                
                                <!-- End .control-group  -->
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label" for="agree">Urgent</label>
                                  <label class="checkbox-inline">
                                  <input class="form-control" type="radio" id="urgent" name="urgent" disabled value="Y" <?php if($all_data['urgent']=='Y'){echo "checked";} ?> />Yes
                                  <input class="form-control" type="radio" id="urgent" name="urgent" disabled value="N" <?php if($all_data['urgent']=='N'){echo "checked";} ?> />No
                                  
                                  </label>
                                </div>                                
                                <!-- End .control-group  -->
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label" for="agree">Sealed</label>
                                  <label class="checkbox-inline">
                                  <input class="form-control" type="radio" id="sealed" name="sealed" disabled value="Y" <?php if($all_data['sealed']=='Y'){echo "checked";} ?> />Yes
                                  <input class="form-control" type="radio" id="sealed" name="sealed" disabled value="N" <?php if($all_data['sealed']=='N'){echo "checked";} ?> />No
                                  
                                  </label>
                                </div>                                
                                <!-- End .control-group  -->
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label" for="agree">Hidden</label>
                                  <label class="checkbox-inline">
                                  <input class="form-control" type="radio" id="hidden" name="hidden" disabled value="Y" <?php if($all_data['hidden']=='Y'){echo "checked";} ?> />Yes
                                  <input class="form-control" type="radio" id="hidden" name="hidden" disabled value="N" <?php if($all_data['hidden']=='N'){echo "checked";} ?> />No
                                  
                                  </label>
                                </div>                                
                                <!-- End .control-group  -->
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label" for="agree">Fulltime</label>
                                  <label class="checkbox-inline">
                                  <input class="form-control" type="radio" id="fulltime" name="fulltime" disabled value="Y" <?php if($all_data['project_type']=='FU'){echo "checked";} ?> />Yes
                                  <input class="form-control" type="radio" id="fulltime" name="fulltime" disabled value="N" <?php if($all_data['project_type']!='FU'){echo "checked";} ?> />No
                                  
                                  </label>
                                </div>                                
                                <!-- End .control-group  -->
                                
                                <div class="form-group">
                                    <div class="col-lg-offset-2">
                                        <div class="pad-left15">
                                            <input type="submit" name="submit" class="btn btn-primary" value="Update" <?php if($status!='O'){ ?> style="display:none;" <?php } ?> />
                                            <button type="button" onclick="redirect_to('<?php echo base_url() . 'project/'.$fnc; ?>');" class="btn" <?php if($status!='O'){ ?> style="display:none;" <?php } ?>>Cancel</button>
                                        </div>
                                    </div>
                                </div><!-- End .form-group  -->

                            </form>
                        </div><!-- End .panel-body -->
                    </div><!-- End .widget -->
                </div><!-- End .col-lg-12  --> 
            </div><!-- End .row-fluid  -->

        </div> <!-- End .container-fluid  -->
    </div> <!-- End .wrapper  -->
</section>
<script>
  function project_type_box(v){ 
     if(v=="H"){ 
       $("#ptype_h").show();
       $("#ptype_f").hide();
     }
     else{ 
       $("#ptype_f").show();
       $("#ptype_h").hide();     
     }
  }
  function getscat(v){     
      $.ajax({
         type:"POST",
         data:{'cat_name':v},
         url:"<?php echo VPATH;?>project/getsubcat",
         success:function(return_data){
            $("#subcategory_id").html(return_data);
         }
    });
  }
  
  function setBudget(v){
    var dataString = 'type='+v;
    
      $.ajax({
         type:"POST",
         data:dataString,
         url:"<?php echo VPATH;?>project/getBudget",
         success:function(return_data){
            $("#budgetall").html(return_data);
         }
        });
  }
  
 function getskill(v){ 
     var dataString = 'sid='+v;
    
  $.ajax({
     type:"POST",
     data:dataString,
     url:"<?php echo VPATH;?>project/getsubskill",
     success:function(return_data){
        $("#subskill_id").html(return_data);
     }
    });
  }
</script>