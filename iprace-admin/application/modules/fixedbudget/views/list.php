<section id="content">
    <div class="wrapper">
        <div class="crumb">
            <ul class="breadcrumb">
            <li class="active"><a href="<?= base_url()?>"><i class="icon16 i-home-4"></i>Home</a></li>
            <li class="active">Fixed budget list</a> </li>
            <li class="active"><a onclick="redirect_to('<?php echo base_url() . 'fixedbudget'; ?>');">Add Fixed Budget</a></li>
            </ul>
        </div>
        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-table-2"></i> Fixed budget list</h1>
            </div>
            <div class="row">
                <div class="col-lg-12">
					<table class="table table-hover table-bordered adminmenu_list">
					<tr>
					<td colspan="5" align="right">
					<a href="<?=base_url().'fixedbudget/add'?> ">	<input class="btn btn-default" type="button" name="add_fixedbudget" value="Add Fixed Budget">
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
                                <th >Fixed Budget</th>                         
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
                    foreach ($list as $key => $fixed) {  
                        ?>
                            <tr class="pointer_class">
                                    <td><?php echo $fixed['id']; ?></td>
                                    <td class="center">
                                    <?php 
                                        if($fixed['max_budget'] != "All") {
                                            echo 'Between  '.number_format($fixed['min_budget'], 0, '', ' ').' to '.number_format($fixed['max_budget'], 0, '', ' ');                                                    
                                        } else {
                                            echo 'more then '.number_format($fixed['min_budget'], 0, '', ' ');
                                        } 
                                    ?>
                                    </td> 
                                    <td align="center"><?php                                    
                                    $atr2 = array('class' => 'i-highlight', 'title' => 'Edit', 'style' => 'text-decoration:none',);																		
                                    
                                    echo anchor(base_url() . 'fixedbudget/edit/' . $fixed['id'], '&nbsp;', $atr2);
                                    echo anchor(base_url() . 'fixedbudget/deletefixed/' . $fixed['id'], '&nbsp;', $attr);
                                        ?>
                                    </td>
                                </tr>                                
                            <?php } ?>
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
