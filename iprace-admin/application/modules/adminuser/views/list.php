<section id="content">
    <div class="wrapper">
        <div class="crumb">
            <ul class="breadcrumb">
                <li class="active"><a href="<?= base_url() ?>"><i class="icon16 i-home-4"></i>Home</a></li>
                <li class="active"><a href="<?= base_url() ?>adminuser/user_list">User List</a></li>
            </ul>
        </div>


        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-table-2"></i> Admin User Management</h1>
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
					<!--<div class="col-lg-12">
					
            
                    <div class="input-group-btn">
                  
                    <input type="text" placeholder="Enter Knowledge Title ..." class="searchfield " name="search_element" id="srch" size="30">
                    <input type="submit" name='submit' id="submit" class="btn" value="SEARCH" onclick="hdd();">
                    </div></br>
                    </div>-->
                    <table class="table table-hover table-bordered adminmenu_list">
                        <thead>
                            <tr>
                                <th style="text-align:left;">Id</th>
                                <th style="text-align:left;">Username</th>
                                <th style="text-align:left;">Email</th>
                                <th style="text-align:left;">Type</th>
                                <th style="text-align:center;">Status</th>
                                <th style="text-align:center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $attr = array(
                                'onclick' => "javascript: return confirm('Do you want to delete?');",
                                'class' => 'i-cancel-circle-2 red',
                                'title' => 'Delete'
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
							$atr9 = array(
                                'onclick' => "javascript: return confirm('Do you want to change pasword?');",
                                'class' => 'i-key green',
                                'title' => 'Change password'
                            );

                            //$atr3 = array('class' => 'i-close-4 red', 'title' => 'Inactive');
                            //$atr4 = array('class' => 'i-checkmark-4 green', 'title' => 'Active');

                            if (count($all_data) > 0) {
                                foreach ($all_data as $key => $val) {
									$type = 'Admin';
                                    ?>

                                    <tr> 	

                                        <td style="text-align:left;"><?= $val['admin_id'] ?></td>
                                        <td><?= $val['username'] ?></td>
                                        <td><?= $val['email'] ?></td>
                                        <td><?= $type ?></td>
                                        <td align="center">
                                            <?php
                                            if ($val['status'] == 'Y') {
                                                echo anchor(base_url() . 'adminuser/change_status/' . $val['admin_id'] .'/inact', '&nbsp;', $atr4);
                                            } else {

                                                echo anchor(base_url() . 'adminuser/change_status/' . $val['admin_id'] . '/act', '&nbsp;', $atr3);
                                            }
                                            ?>




                                        </td>
                                        <td align="center">
                                            <?php
                                            $atr2 = array('class' => 'i-highlight', 'title' => 'Edit user');
                                            echo anchor(base_url() . 'adminuser/edit_user/' . $val['admin_id'].'/', '&nbsp;', $atr2);
                                            echo anchor(base_url() . 'adminuser/change_status/' . $val['admin_id'] . '/' . 'del/', '&nbsp;', $attr);
											echo anchor(base_url() . 'settings/pass_edit/' . $val['admin_id'] . '/', '&nbsp;', $atr9);
                                            ?>

                                        </td>
                                    </tr>



                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="7" align="center">No records found...</td>
                                </tr>

    <?php
}
?>
                        </tbody>
                    </table>
                    <?php   
						echo "<div class='pagi'>".$links."</div>";
                      ?>
                </div><!-- End .col-lg-6  -->
            </div><!-- End .row-fluid  -->

        </div> <!-- End .container-fluid  -->
    </div> <!-- End .wrapper  -->
</section>
<script>
function hdd()
{
	var elmnt=$('#srch').val();
	window.location.href='<?php echo base_url();?>knowledge/search_knowledge/'+elmnt+'/';		
}
</script>