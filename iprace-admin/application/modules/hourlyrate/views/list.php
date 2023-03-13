<section id="content">
    <div class="wrapper">
        <div class="crumb">
            <ul class="breadcrumb">
            <li class="active"><a href="<?= base_url()?>"><i class="icon16 i-home-4"></i>Home</a></li>
            <li class="active">Hourly rate list</a> </li>
            <li class="active"><a onclick="redirect_to('<?php echo base_url() . 'hourlyrate'; ?>');">Add Hourly Rate</a></li>
            </ul>
        </div>
        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-table-2"></i> Hourly rate list</h1>
            </div>
            <div class="row">
                <div class="col-lg-12">
					<table class="table table-hover table-bordered adminmenu_list">
					<tr>
					<td colspan="5" align="right">
					<a href="<?=base_url().'hourlyrate/add'?> ">	<input class="btn btn-default" type="button" name="add_hourlyrate" value="Add Hourly Rate">
					</td>
					</tr>
					</table>
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
                    <table class="table table-hover table-bordered adminmenu_list">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th >Hourly Rate</th>                                
                                <th >Action</th>
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
                    foreach ($list as $key => $hourly) {  
                        ?>
                            <tr class="pointer_class">
                                    <td><?php echo $hourly['id']; ?></td>
                                    <td class="center">
                                        <?php 
                                            if($hourly['max'] != "All") {
                                                echo 'Between  '.number_format($hourly['min'], 0, '', ' ').' to '.number_format($hourly['max'], 0, '', ' ');                                                    
                                            } else {
                                                echo 'more then '.number_format($hourly['min'], 0, '', ' ');
                                            } 
                                        ?>
                                    </td>                                                                      
                                    <td align="center"><?php                                    
                                    $atr2 = array('class' => 'i-highlight', 'title' => 'Edit', 'style' => 'text-decoration:none',);																		
                                    
                                    echo anchor(base_url() . 'hourlyrate/edit/' . $hourly['id'], '&nbsp;', $atr2);
                                    echo anchor(base_url() . 'hourlyrate/delete/' . $hourly['id'], '&nbsp;', $attr);
                                        ?>
                                    </td>
                                </tr>                                
                            <?php } ?>
                            <?php
                                if(empty($list)) {
                            ?>
                            <tr>
                                <td colspan="3" class="text-danger" align="center">No records found...</td>
                            </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- End .col-lg-6  -->
            </div>
            <!-- End .row-fluid  -->
        </div>
        <!-- End .container-fluid  -->
    </div>
    <!-- End .wrapper  -->
</section>
