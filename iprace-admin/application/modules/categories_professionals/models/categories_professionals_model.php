<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class categories_professionals_model extends BaseModel {

    public function __construct() {
        parent::__construct();
		 $this->load->model('member/member_model');
    }

    // Add new professional category into table
    public function add_category($data) {
        return $this->db->insert('categories_professionals', $data);
    }
	// update existing professional category
    public function update_category($post,$id) {
        $this->db->where('id', $id);
        return $this->db->update('categories_professionals', $post);
    }
	// Remove existing professional category
    public function delete_category($id) {
	
	
        $category_listing = $this->db->get_where('professionals_areas_of_expertise_listings_tracking', ['professional_category_id' => $id])->result_array();
        $professional_categories = $this->db->get('professionals_areas_of_expertise_listings_tracking')->result_array();
		$user_ids = array();
		$this->db->select('professionals_areas_of_expertise_listings_tracking.user_id,users.account_type');
		$this->db->from('professionals_areas_of_expertise_listings_tracking');
		$this->db->join('users', 'professionals_areas_of_expertise_listings_tracking.user_id = users.user_id', 'left');
		$this->db->where('professional_parent_category_id', $id);
		$this->db->or_where('professional_category_id', $id);
		$this->db->group_by('user_id');
		$areas_of_expertise_listings =  $this->db->get()->result_array();
        if(!empty($category_listing)) {
            foreach($category_listing as $val) {

                $cnt = 0;
                foreach($professional_categories as $pcat) {
                    if($pcat['user_id'] == $val['user_id'] && $val['professional_parent_category_id'] == $pcat['professional_parent_category_id']) {
                        $cnt++;
                    }
                }
                if($cnt == 1) {
                    $category_data = [
                        'professional_category_id' => $val['professional_parent_category_id'],
                        'professional_parent_category_id' => 0
                    ];
                    $this->db->update('professionals_areas_of_expertise_listings_tracking', $category_data, ['id' => $val['id']]);
                } else {
                    $this->db->where('professional_parent_category_id', $id);
                    $this->db->or_where('professional_category_id', $id);
                    $this->db->delete('professionals_areas_of_expertise_listings_tracking');            
                }
            }
        }
		$delete_status = $this->db->delete('categories_professionals', array('id' => $id));
		include '../application/config/'.SITE_LANGUAGE.'_dashboard_custom_config.php';
				
		if(!empty($areas_of_expertise_listings)){
			foreach($areas_of_expertise_listings as $key=>$value){
			
				if($value['account_type'] == 1){
					$profile_completion_parameters = $config['user_personal_account_type_profile_completion_parameters_tracking_options_value'];
					
				}elseif($value['account_type']  == 2){
					$profile_completion_parameters = $config['user_company_account_type_profile_completion_parameters_tracking_options_value'];
				}
			
				$user_profile_completion_data = array();
				################## count category start ###########
					
				$total_catgory_row = array();
				$professional_categories = $this->db // get the user detail
				->select('elt.id, elt.professional_category_id, elt.professional_parent_category_id')
				->from('professionals_areas_of_expertise_listings_tracking elt')
				->where('user_id', $value['user_id'])
				->get()->result_array();
				foreach ($professional_categories as $category) {
					$result = $this->db->get_where('categories_professionals', ['id' => $category['professional_category_id'], 'parent_id' => $category['professional_parent_category_id']])->row_array();
					
					if ($category['professional_parent_category_id'] == 0) {
						
						$total_catgory_row[$category['professional_category_id']][] = $category['professional_parent_category_id'];
					} else {
						
						$total_catgory_row[$category['professional_parent_category_id']][] = $category['professional_category_id'];
					}
				}
				
				################## count category end ###########
				
				
				if(count($total_catgory_row) == 0){
					$user_profile_completion_data['has_areas_of_expertise_indicated'] = 'N';
					$user_profile_completion_data['areas_of_expertise_strength_value'] = 0;
					$user_profile_completion_data['number_of_areas_of_expertise_entries'] = 0;
					
				}else{
					$user_profile_completion_data['has_areas_of_expertise_indicated'] = 'Y';
					$user_profile_completion_data['areas_of_expertise_strength_value'] = $profile_completion_parameters['areas_of_expertise_strength_value'];
					$user_profile_completion_data['number_of_areas_of_expertise_entries'] = count($total_catgory_row);
				}
				if(!empty($user_profile_completion_data)){
				$this->member_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));
				}
				
			}
		
		} 
	
	
        return $delete_status;
    }
    // Remove all parent child relationship if parent category will be removed
    public function delete_parent_child_categories($id) {
	
		$user_ids = array();
		$this->db->select('professionals_areas_of_expertise_listings_tracking.user_id,users.account_type');
		$this->db->from('professionals_areas_of_expertise_listings_tracking');
		$this->db->join('users', 'professionals_areas_of_expertise_listings_tracking.user_id = users.user_id', 'left');
		$this->db->where('professional_parent_category_id', $id);
		$this->db->or_where('professional_category_id', $id);
		$this->db->group_by('user_id');
		$areas_of_expertise_listings =  $this->db->get()->result_array();
	
        $results = $this->db->where('parent_id',$id)->get('categories_professionals')->result_array();
        foreach($results as $rs){
            $deled = $this->db->delete('categories_professionals', array('id' => $rs['id']));
        }   
        // remove entries from professional category tracking table
        $this->db->where('professional_parent_category_id', $id);
        $this->db->or_where('professional_category_id', $id);
        $this->db->delete('professionals_areas_of_expertise_listings_tracking');
        
        // remove entry from projects_professionals_categories_mapping_tracking table when professional category removed by admin
        $this->db->delete('projects_professionals_categories_mapping_tracking', ['professionals_category_id' => $id]);
		$delete_status = $this->db->delete('categories_professionals', array('id' => $id));
		
		include '../application/config/'.SITE_LANGUAGE.'_dashboard_custom_config.php';
		if(!empty($areas_of_expertise_listings)){
			foreach($areas_of_expertise_listings as $key=>$value){
			
				if($value['account_type'] == 1){
					$profile_completion_parameters = $config['user_personal_account_type_profile_completion_parameters_tracking_options_value'];
					
				}elseif($value['account_type']  == 2){
					$profile_completion_parameters = $config['user_company_account_type_profile_completion_parameters_tracking_options_value'];
				}
			
				$user_profile_completion_data = array();
				################## count category start ###########
					
				$total_catgory_row = array();
				$professional_categories = $this->db // get the user detail
				->select('elt.id, elt.professional_category_id, elt.professional_parent_category_id')
				->from('professionals_areas_of_expertise_listings_tracking elt')
				->where('user_id', $value['user_id'])
				->get()->result_array();
				foreach ($professional_categories as $category) {
					$result = $this->db->get_where('categories_professionals', ['id' => $category['professional_category_id'], 'parent_id' => $category['professional_parent_category_id']])->row_array();
					
					if ($category['professional_parent_category_id'] == 0) {
						
						$total_catgory_row[$category['professional_category_id']][] = $category['professional_parent_category_id'];
					} else {
						
						$total_catgory_row[$category['professional_parent_category_id']][] = $category['professional_category_id'];
					}
				}
				
				################## count category end ###########
				
				
				if(count($total_catgory_row) == 0){
					$user_profile_completion_data['has_areas_of_expertise_indicated'] = 'N';
					$user_profile_completion_data['areas_of_expertise_strength_value'] = 0;
					$user_profile_completion_data['number_of_areas_of_expertise_entries'] = 0;
					
				}else{
					$user_profile_completion_data['has_areas_of_expertise_indicated'] = 'Y';
					$user_profile_completion_data['areas_of_expertise_strength_value'] = $profile_completion_parameters['areas_of_expertise_strength_value'];
					$user_profile_completion_data['number_of_areas_of_expertise_entries'] = count($total_catgory_row);
				}
				if(!empty($user_profile_completion_data)){
				$this->member_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));
				}
				
			}
		
		}
		
		
        return $delete_status;
    }
    /**
     * Get all parent professional category
     */
    public function get_categories() {
        $this->db->select('id,name,parent_id,status');
        $this->db->order_by("name", "ASC");
        $rs = $this->db->get_where('categories_professionals', array('parent_id' => '0'));
        $data = array();
        foreach ($rs->result() as $row) {
            $data[] = array(
                'id' => $row->id,
                'name' => $row->name,
                'parent_id' => $row->parent_id,
                'status' => $row->status,
                'childs' => $this->get_child_categories_id($row->id)
            );
        }
        return $data;
    }
   
    /// Get Child menu list ////////////////////////////
    public function get_child_categories_id($id) {
        $this->db->select('id,name,parent_id,status');
        $rs = $this->db->get_where('categories_professionals', array('parent_id' => $id));
        $data = array();
        foreach ($rs->result() as $row) {
            $data[] = $row;
        }
        return $data;
    }
    // update category data
	
	public function updatecategory($data,$id, $type)
	{
        if($data['status'] == 'N') {
		
			$user_ids = array();
			$this->db->select('professionals_areas_of_expertise_listings_tracking.user_id,users.account_type');
			$this->db->from('professionals_areas_of_expertise_listings_tracking');
			$this->db->join('users', 'professionals_areas_of_expertise_listings_tracking.user_id = users.user_id', 'left');
			$this->db->where('professional_parent_category_id', $id);
			$this->db->or_where('professional_category_id', $id);
			$this->db->group_by('user_id');
			$areas_of_expertise_listings =  $this->db->get()->result_array();
			
            if($type == 's') {
                $category_listing = $this->db->get_where('professionals_areas_of_expertise_listings_tracking', ['professional_category_id' => $id])->result_array();
                $professional_categories = $this->db->get('professionals_areas_of_expertise_listings_tracking')->result_array();
                if(!empty($category_listing)) {
                    foreach($category_listing as $val) {

                        $cnt = 0;
                        foreach($professional_categories as $pcat) {
                            if($pcat['user_id'] == $val['user_id'] && $val['professional_parent_category_id'] == $pcat['professional_parent_category_id']) {
                                $cnt++;
                            }
                        }
                        if($cnt == 1) {
                            $category_data = [
                                'professional_category_id' => $val['professional_parent_category_id'],
                                'professional_parent_category_id' => 0
                            ];
                            $this->db->update('professionals_areas_of_expertise_listings_tracking', $category_data, ['id' => $val['id']]);
                        } else {
                            $this->db->where('professional_parent_category_id', $id);
                            $this->db->or_where('professional_category_id', $id);
                            $this->db->delete('professionals_areas_of_expertise_listings_tracking');            
                        }
                    }
                }
            } else if($type == 'c') {
                $this->db->where('professional_parent_category_id', $id);
                $this->db->or_where('professional_category_id', $id);
                $this->db->delete('professionals_areas_of_expertise_listings_tracking');

                // remove entry from projects_professionals_categories_mapping_tracking table when professional category disabled by admin
                $this->db->delete('projects_professionals_categories_mapping_tracking', ['professionals_category_id' => $id]);
            }
        }
		$this->db->where('id',$id);
		$result = $this->db->update('categories_professionals',$data);
		if($result){
			
			########
			if($data['status'] == 'N') {
				include '../application/config/'.SITE_LANGUAGE.'_dashboard_custom_config.php';
				
				if(!empty($areas_of_expertise_listings)){
					foreach($areas_of_expertise_listings as $key=>$value){
					
						if($value['account_type'] == 1){
							$profile_completion_parameters = $config['user_personal_account_type_profile_completion_parameters_tracking_options_value'];
							
						}elseif($value['account_type']  == 2){
							$profile_completion_parameters = $config['user_company_account_type_profile_completion_parameters_tracking_options_value'];
						}
					
						$user_profile_completion_data = array();
						################## count category start ###########
					
						$total_catgory_row = array();
						$professional_categories = $this->db // get the user detail
						->select('elt.id, elt.professional_category_id, elt.professional_parent_category_id')
						->from('professionals_areas_of_expertise_listings_tracking elt')
						->where('user_id', $value['user_id'])
						->get()->result_array();
						foreach ($professional_categories as $category) {
							$result = $this->db->get_where('categories_professionals', ['id' => $category['professional_category_id'], 'parent_id' => $category['professional_parent_category_id']])->row_array();
							
							if ($category['professional_parent_category_id'] == 0) {
								
								$total_catgory_row[$category['professional_category_id']][] = $category['professional_parent_category_id'];
							} else {
								
								$total_catgory_row[$category['professional_parent_category_id']][] = $category['professional_category_id'];
							}
						}
						
						################## count category end ###########
						
						
						if(count($total_catgory_row) == 0){
							$user_profile_completion_data['has_areas_of_expertise_indicated'] = 'N';
							$user_profile_completion_data['areas_of_expertise_strength_value'] = 0;
							$user_profile_completion_data['number_of_areas_of_expertise_entries'] = 0;
							
						}else{
							$user_profile_completion_data['has_areas_of_expertise_indicated'] = 'Y';
							$user_profile_completion_data['areas_of_expertise_strength_value'] = $profile_completion_parameters['areas_of_expertise_strength_value'];
							$user_profile_completion_data['number_of_areas_of_expertise_entries'] = count($total_catgory_row);
						}
						if(!empty($user_profile_completion_data)){
						$this->member_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));
						}
						
					}
				
				} 
			}
			#######
		
		}
		
		
		return $result;
		
	} 
	// Get all sub categories
    public function get_subcategories() {
        $this->db->select('id,name,parent_id,status');
        $this->db->order_by("parent_id", "ASC");
        $rs = $this->db->get_where('categories_professionals', array('parent_id !=' => '0'));
        $data = array();
        foreach ($rs->result() as $row) {
            $data[] = array(
                'id' => $row->id,
                'name' => $row->name,
                'parent_id' => $row->parent_id,
                'status' => $row->status,
                'parents' => $this->get_parent_categories($row->parent_id)
            );
        }
        return $data;
    }
    // get parent category by id
	public function get_parent_categories($id) {
        $this->db->select('name');
        $rs = $this->db->get_where('categories_professionals', array('id' => $id));
        $data = $rs->result();
        if(!empty($data)){
            $result = $data[0]->name;    
        }else{
            $result = "";
        }
               
        return $result;
    }
    // Get category list
    public function get_category_list()
	{
		$this->db->order_by('name', "asc");  
        $this->db->where('parent_id', "0");                          
        $rs = $this->db->get("categories_professionals");
        $data = array();        
            foreach ($rs->result() as $row) {
                $data[] = array(
                    'id'   => $row->id,
                    'name' => $row->name                    
                );
            }
            return $data;
       	
	}
    // load sub category data based on selection
    /* public function load_subcategory($id){
		$this->db->select('c1.id as id1,c1.name as name1,c1.parent_id as parent1,c1.status as status1,c2.name as name2,c2.parent_id as parent2,c2.status as status2');
		$this->db->from('categories_professionals c1');
		$this->db->join('categories_professionals c2', 'c1.parent_id =c2.id', 'left');
		$this->db->where('c1.parent_id',$id);
		$results = $this->db->get();
		$records = $results->result_array();
		
        $data = "";
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
       
            $data .= "<table class='table table-hover table-bordered adminmenu_list'>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th >Sub-category Name</th>                              
                                <th>Status</th>
                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody>";
             foreach($records as $rs){
			 
				if($rs['status2'] == 'N')
				{
					$status = '<i class="i-checkmark-4 red"></i>';
				}else{
			 
					if ($rs['status1'] == 'Y') {
						$status = anchor(base_url() . 'categories_professionals/change_subcategory_status/' . $rs['id1'].'/inact/'.$rs['status1'].'/s', '&nbsp;', $atr4);                
					} else {                
						$status = anchor(base_url() . 'categories_professionals/change_subcategory_status/' . $rs['id1'].'/act/'.$rs['status1'].'/s', '&nbsp;', $atr3);
					}
				}
                $attr = array('onclick' => "javascript: return confirm('Do you want to delete?');",'class' => 'i-cancel-circle-2 red','title' => 'Delete','style' => 'text-decoration:none');
                $atr1 = array('class' => 'i-plus-circle-2', 'title' => 'Add', 'style' => 'text-decoration:none',);
                $atr2 = array('class' => 'i-highlight', 'title' => 'Edit', 'style' => 'text-decoration:none',);
                $edit = anchor(base_url() . 'categories_professionals/edit/' . $rs['id1'], '&nbsp;', $atr2);
                $dele = anchor(base_url() . 'categories_professionals/delete_category/' . $rs['id1'], '&nbsp;', $attr);
                $data .= "<tr class='pointer_class'>
                            <td>".$rs['id1']."</td>
                            <td>".$rs['name1']."</td>
                            <td align='center'>".$status."</td>  
                            <td align='center'>".$edit.$dele."</td>  
                         </tr>";
             }                                         
             $data .= "</tbody></table>";                                   
        return $data;                    
    } */
	
	public function load_subcategory($id){
       /*  $results = $this->db->select('id,name,parent_id,status')
                  ->where('parent_id',$id)
                  ->get('categories_professionals')
                  ->result_array(); */
					
		$this->db->select('c1.id as id1,c1.name as name1,c1.parent_id as parent1,c1.status as status1,c2.name as name2,c2.parent_id as parent2,c2.status as status2');
		$this->db->from('categories_professionals c1');
		$this->db->join('categories_professionals c2', 'c1.parent_id =c2.id', 'left');
		$this->db->where('c1.parent_id',$id);
		$results = $this->db->get();
		$records = $results->result_array();
		
		
        $data = "";
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
       
            $data .= "<table class='table table-hover table-bordered adminmenu_list'>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th >Sub-category Name</th>                              
                                <th>Status</th>
                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody>";
             foreach($records as $rs){
					if ($rs['status1'] == 'Y') {
						$data_type = 'inact';
						$class = 'i-checkmark-3 green';
						$msg = 'Do you want to inactive this?';
						$title = "active";
						//$status = anchor(base_url() . 'categories_professionals/change_subcategory_status/' . $rs['id1'].'/inact/'.$rs['status1'].'/s', '&nbsp;', $atr4);                
					} else {
						$data_type = 'act';
						$class = 'i-checkmark-3 red';
						$msg = "Do you want to active this?";
						$title = "inactive";
						//$status = anchor(base_url() . 'categories_professionals/change_subcategory_status/' . $rs['id1'].'/act/'.$rs['status1'].'/s', '&nbsp;', $atr3);
					}
                $attr = array('onclick' => "javascript: return confirm('Do you want to delete?');",'class' => 'i-cancel-circle-2 red','title' => 'Delete','style' => 'text-decoration:none');
                $atr1 = array('class' => 'i-plus-circle-2', 'title' => 'Add', 'style' => 'text-decoration:none',);
                $atr2 = array('class' => 'i-highlight', 'title' => 'Edit', 'style' => 'text-decoration:none',);
                $edit = anchor(base_url() . 'categories_professionals/edit/' . $rs['id1'], '&nbsp;', $atr2);
                //$dele = anchor(base_url() . 'categories_professionals/delete_category/' . $rs['id1'], '&nbsp;', $attr);
                $dele = '<a href="javascript:;" class="i-cancel-circle-2 red delete_category" title="Delete" data-id="'.$rs['id1'].'">&nbsp;</a>';
                $data .= "<tr id='row_".$rs['id1']."'>
                            <td>".$rs['id1']."</td>
                            <td>".$rs['name1']."</td>
                            <td align='center' id='category_sub_status_".$rs['id1']."'><a href='javascript:;' class='". $class ." change_sub_category_status'  title='".$title."' data-msg='".$msg."'  data-type='".$data_type."' data-status ='".$rs['status1']."' data-id='".$rs['id1']."'>&nbsp;</a></td>  
                            <td align='center'>".$edit.$dele."</td>  
                         </tr>";
             }                                         
             $data .= "</tbody></table>";                                   
        return $data;                    
    }
    
}
