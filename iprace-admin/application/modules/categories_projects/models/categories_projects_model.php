<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Categories_projects_model extends BaseModel {

    public function __construct() {
        return parent::__construct();
    }

    
    public function add_category($data) {
        return $this->db->insert('categories_projects', $data);
    }
	
	
    
    public function update_category($post,$id) {
		if($post['status'] == 'N' && $post['parent_id'] != '0'){
			$this->update_project_child_category_tracking_data($id);
		}
        $this->db->where('id', $id);
        return $this->db->update('categories_projects', $post);
    }


   
    public function delete_category($id) {
        // update sub-category into project category listing tracking table
        $project_category_listing = $this->db->get_where('projects_categories_listing_tracking', ['project_category_id' => $id])->result_array();
        if(!empty($project_category_listing)) {
            foreach($project_category_listing as $val) {
                $project_category_data = [
                    'project_category_id' => $val['project_parent_category_id'],
                    'project_parent_category_id' => 0
                ];
                $this->db->update('projects_categories_listing_tracking', $project_category_data, ['id' => $val['id']]);
            }
        }
        
        return $this->db->delete('categories_projects', array('id' => $id));
    }
    
    public function delete_parent_child_categories($id) {
        $results = $this->db->where('parent_id',$id)->get('categories_projects')->result_array();
        foreach($results as $rs){
            $deled = $this->db->delete('categories_projects', array('id' => $rs['id']));
        }   
        // remove entries from project category tracking table
        $this->db->where('project_parent_category_id', $id);
        $this->db->or_where('project_category_id', $id);
        $this->db->delete('projects_categories_listing_tracking');
        // remove entry from projects_professionals_categories_mapping_tracking table when projects category removed by admin
        $this->db->delete('projects_professionals_categories_mapping_tracking', ['projects_category_id' => $id]);

        return $this->db->delete('categories_projects', array('id' => $id));
    }
    
	
    public function get_categories() {
        $this->db->select('id,name,parent_id,status');
        $this->db->order_by("name", "ASC");
        $rs = $this->db->get_where('categories_projects', array('parent_id' => '0'));
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
	
	

	
	/*
		This function is used to check that project child category is exists or not
	*/
	function check_project_child_category_exist($parent_category_id,$child_category_id){
	
		/* $child_category_detail = $this->db->get_where('categories_projects', ['id' => $child_category_id,'parent_id'=>$parent_category_id,'status'=>'Y'])->row_array();
		if(!empty($child_category_detail)){
			return true;
		}
		return false; */
		
		$project_child_category_count = $this->db
			->select ('id')
		->from ('categories_projects')
		->where ('id',$child_category_id)
		->where ('parent_id',$parent_category_id)
		->where ('status','Y')
		->get ()->num_rows ();
		if($project_child_category_count != 0){
			return true;
		}
		return false;
		
	
	}
	
    
    /// Get Child menu list ////////////////////////////
    public function get_child_categories_id($id) {
        $this->db->select('id,name,parent_id,status');
        $rs = $this->db->get_where('categories_projects', array('parent_id' => $id));
        $data = array();
        foreach ($rs->result() as $row) {
            $data[] = $row;
        }
        return $data;
    }
	public function updatecategory($data, $id, $type)
	{

        if($data['status'] == 'N') {
			$category_detail = $this->db->get_where('categories_projects', array('id' => $id))->row_array();
			if($category_detail['parent_id'] != '0'){
				$this->update_project_child_category_tracking_data($id);
			}
            if($type == 's') {
                // update sub-category into project category listing tracking table
                $project_category_listing = $this->db->get_where('projects_categories_listing_tracking', ['project_category_id' => $id])->result_array();
                if(!empty($project_category_listing)) {
                    foreach($project_category_listing as $val) {
                        $project_category_data = [
                            'project_category_id' => $val['project_parent_category_id'],
                            'project_parent_category_id' => 0
                        ];
                        $this->db->update('projects_categories_listing_tracking', $project_category_data, ['id' => $val['id']]);
                    }
                }
            } else if($type == 'c') {
                $this->db->where('project_parent_category_id', $id);
                $this->db->or_where('project_category_id', $id);
                $this->db->delete('projects_categories_listing_tracking');

                // remove entry from projects_professionals_categories_mapping_tracking table when projects category disabled by admin
                $this->db->delete('projects_professionals_categories_mapping_tracking', ['projects_category_id' => $id]);
            }
        }
        
		$this->db->where('id',$id);
		return $this->db->update('categories_projects',$data);
		
	} 
	
    public function get_subcategories() {
        $this->db->select('id,name,parent_id,status');
        $this->db->order_by("parent_id", "ASC");
        $rs = $this->db->get_where('categories_projects', array('parent_id !=' => '0'));
        $data = array();
        foreach ($rs->result() as $row) {
            $data[] = array(
                'cat_id' => $row->id,
                'cat_name' => $row->name,
                'parent_id' => $row->parent_id,
                'status' => $row->status,
                'parents' => $this->get_parent_categories($row->parent_id)
            );
        }
        return $data;
    }
    
	public function get_parent_categories($id) {
        $this->db->select('name');
        $rs = $this->db->get_where('categories_projects', array('id' => $id));
        $data = $rs->result();
        if(!empty($data)){
            $result = $data[0]->name;    
        }else{
            $result = "";
        }
               
        return $result;
    }
    
    public function get_category_list()
	{
		$this->db->order_by('name', "asc");  
        $this->db->where('parent_id', "0");                          
        $rs = $this->db->get("categories_projects");
        $data = array();        
            foreach ($rs->result() as $row) {
                $data[] = array(
                    'id'   => $row->id,
                    'name' => $row->name                    
                );
            }
            return $data;
       	
	}
    
    public function load_subcategory($id){
		$this->db->select('c1.id as id1,c1.name as name1,c1.parent_id as parent1,c1.status as status1,c2.name as name2,c2.parent_id as parent2,c2.status as status2');
		$this->db->from('categories_projects c1');
		$this->db->join('categories_projects c2', 'c1.parent_id =c2.id', 'left');
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
						$status = anchor(base_url() . 'categories_projects/change_subcategory_status/' . $rs['id1'].'/inact/'.$rs['status1'].'/s', '&nbsp;', $atr4);                
					} else {                
						$status = anchor(base_url() . 'categories_projects/change_subcategory_status/' . $rs['id1'].'/act/'.$rs['status1'].'/s', '&nbsp;', $atr3);
					}
				}
                $attr = array('onclick' => "javascript: return confirm('Do you want to delete?');",'class' => 'i-cancel-circle-2 red','title' => 'Delete','style' => 'text-decoration:none');
                $atr1 = array('class' => 'i-plus-circle-2', 'title' => 'Add', 'style' => 'text-decoration:none',);
                $atr2 = array('class' => 'i-highlight', 'title' => 'Edit', 'style' => 'text-decoration:none',);
                $edit = anchor(base_url() . 'categories_projects/edit/' . $rs['id1'], '&nbsp;', $atr2);
                $dele = anchor(base_url() . 'categories_projects/delete_category/' . $rs['id1'], '&nbsp;', $attr);
                $data .= "<tr class='pointer_class'>
                            <td>".$rs['id1']."</td>
                            <td>".$rs['name1']."</td>
                            <td align='center'>".$status."</td>  
                            <td align='center'>".$edit.$dele."</td>  
                         </tr>";
             }                                         
             $data .= "</tbody></table>";                                   
        return $data;                    
    }
	
	// this function is used to update temp_projects_categories_listing_tracking,draft_projects_categories_listing_tracking,awaiting_moderation_projects_categories_listing_tracking,projects_categories_listing_tracking table is admin deactive the subcategory or delete the subcategory
	public function update_project_child_category_tracking_data($id){
	
		###################### update the temp_projects_categories_listing_tracking table start #####
		$this->db->from('temp_projects_categories_listing_tracking');
		$this->db->where('temp_project_category_id',$id);
		$this->db->order_by('id',"asc");
		$temp_project_category_result = $this->db->get();
		$temp_project_category_data = $temp_project_category_result->result_array();
		if(!empty($temp_project_category_data)){
		
			foreach($temp_project_category_data as $category_key => $category_value){
			
				if(!empty($category_value['temp_project_category_id'])){
					
					$upate_data = array(
					'temp_project_category_id' => $category_value['temp_project_parent_category_id'],
					'temp_project_parent_category_id' => 0
					);
					$this->db->where('id', $category_value['id']);
					$this->db->update('temp_projects_categories_listing_tracking', $upate_data);
				}
			}
		}
		###################### update the draft_projects_categories_listing_tracking table start #####
		$this->db->from('draft_projects_categories_listing_tracking');
		$this->db->where('draft_project_category_id',$id);
		$this->db->order_by('id',"asc");
		$draft_project_category_result = $this->db->get();
		$draft_project_category_data = $draft_project_category_result->result_array();
		if(!empty($draft_project_category_data)){
		
			foreach($draft_project_category_data as $category_key => $category_value){
			
				if(!empty($category_value['draft_project_parent_category_id'])){
					
					$upate_data = array(
					'draft_project_category_id' => $category_value['draft_project_parent_category_id'],
					'draft_project_parent_category_id' => 0
					);
					$this->db->where('id', $category_value['id']);
					$this->db->update('draft_projects_categories_listing_tracking', $upate_data);
				}
			}
		}
		
		
		###################### update the awaiting_moderation_projects_categories_listing_tracking table start #####
		$this->db->from('awaiting_moderation_projects_categories_listing_tracking');
		$this->db->where('awaiting_moderation_project_category_id',$id);
		$this->db->order_by('id',"asc");
		$awaiting_moderation_project_category_result = $this->db->get();
		$awaiting_moderation_project_category_data = $awaiting_moderation_project_category_result->result_array();
		if(!empty($awaiting_moderation_project_category_data)){
		
			foreach($awaiting_moderation_project_category_data as $category_key => $category_value){
			
				if(!empty($category_value['awaiting_moderation_project_parent_category_id'])){
					
					$upate_data = array(
					'awaiting_moderation_project_category_id' => $category_value['awaiting_moderation_project_parent_category_id'],
					'awaiting_moderation_project_parent_category_id' => 0
					);
					$this->db->where('id', $category_value['id']);
					$this->db->update('awaiting_moderation_projects_categories_listing_tracking', $upate_data);
				}
			}
		}
		###################### update the projects_categories_listing_tracking table start #####
		$this->db->from('projects_categories_listing_tracking');
		$this->db->where('project_category_id',$id);
		$this->db->order_by('id',"asc");
		$project_category_result = $this->db->get();
		$project_category_data = $project_category_result->result_array();
		if(!empty($project_category_data)){
		
			foreach($project_category_data as $category_key => $category_value){
			
				if(!empty($category_value['project_parent_category_id'])){
					
					$upate_data = array(
					'project_category_id' => $category_value['project_parent_category_id'],
					'project_parent_category_id' => 0
					);
					$this->db->where('id', $category_value['id']);
					$this->db->update('projects_categories_listing_tracking', $upate_data);
				}
			}
		}
	}
	
	// this function is used to remove the parent category related all data from  temp_projects_categories_listing_tracking,draft_projects_categories_listing_tracking,awaiting_moderation_projects_categories_listing_tracking,projects_categories_listing_tracking table whe  active deactive/ delete the parent category
	public function delete_project_parent_category_tracking_data($id){
		
		
		$this->db->where('temp_project_category_id', $id);
        $this->db->or_where('temp_project_parent_category_id', $id);
        $this->db->delete('temp_projects_categories_listing_tracking');
		
		$this->db->where('draft_project_category_id', $id);
        $this->db->or_where('draft_project_parent_category_id', $id);
        $this->db->delete('draft_projects_categories_listing_tracking');
		
		$this->db->where('awaiting_moderation_project_category_id', $id);
        $this->db->or_where('awaiting_moderation_project_parent_category_id', $id);
        $this->db->delete('awaiting_moderation_projects_categories_listing_tracking');
		$this->db->where('project_category_id', $id);
        $this->db->or_where('project_parent_category_id', $id);
        $this->db->delete('projects_categories_listing_tracking');
	}
    
}
