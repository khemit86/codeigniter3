<?php

if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class autoload_model extends BaseModel
{

    public function __construct ()
    {
        parent::__construct ();
    }
    public function getFeild ($select, $table, $feild, $value)
    {
        $this->db->select ($select);
        $rs = $this->db->get_where ($table, [$feild => $value]);
        $data = '';
        foreach ($rs->result () as $row)
        {
            $data = $row->$select;
        }
		$this->db->close();
        return $data;
    }

    public function getalldata ($attr, $table, $by, $value)
    {
        $this->db->select ($attr);
        $rs = $this->db->get_where ($table, [$by => $value]);
        $data = '';
        foreach ($rs->result () as $key => $row)
        {
            $data["'" . $key . "'"] = $row;
        }

        return $data;
    }
   

   
    

   

//end function

    /*     * ***************************
     * Global function to get a record row
     * @params $table, $key='', $keyValue=''
     * return $query->row()
     *
     * *************************** */

    public function get_table_row ($table, $array)
    {
        if ($array != '')
        {
            $query = $this->db->get_where ($table, $array);
            if ($query)
            {
				$row =  $query->row ();
                $this->db->close();
				return $row;
            }
        }
    }

}

//end model