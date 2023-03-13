<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Auto_model extends BaseModel {
    function __construct() {
        parent::__construct();
    }

   

    function leftPannel() {
        $result = [];
        $this->db->order_by('ord', 'asc');
        $query = $this->db->get_where('adminmenu', ['status' => 'Y', 'parent_id' => 0]);
        $i     = 1;

        foreach ($query->result() as $row) {
            $result[$i]['id']          = @$row->id;
            $result[$i]['name']        = @$row->name;
            $result[$i]['url']         = @$row->url;
            $result[$i]['parent_id']   = @$row->parent_id;
            $result[$i]['status']      = @$row->status;
            $result[$i]['title']       = @$row->title;
            $result[$i]['style_class'] = @$row->style_class;
            $i++;
        }

        return $result;
    }

    function leftpanelchild($id) {
        $result = [];
        $this->db->order_by('ord', 'asc');
        $query  = $this->db->get_where('adminmenu', ['status' => 'Y', 'parent_id' => $id]);
        $i      = 1;

        foreach ($query->result() as $row) {
            $result[$i]['id']          = $row->id;
            $result[$i]['name']        = $row->name;
            $result[$i]['url']         = $row->url;
            $result[$i]['parent_id']   = $row->parent_id;
            $result[$i]['status']      = $row->status;
            $result[$i]['title']       = $row->title;
            $result[$i]['style_class'] = $row->style_class;
            $i++;
        }

        return $result;
    }

    function get_current_controller($id) {
        $this->db->select();
        $query      = $this->db->get_where('adminmenu', ['status' => 'Y', 'parent_id' => $id], 1, 0);
        $parent_url = '';

        foreach ($query->result() as $row) {
            $parent_url = explode('/', $row->url);
            $parent_url = $parent_url[0];
            break;
        }

        $result['parent_url'] = $parent_url;

        return $result;
    }

    function getFeild($select, $table, $field = '', $value = '', $where = NULL) {
        $this->db->select($select);

        if (($value != '' && $field != '')) {
            $rs = $this->db->get_where($table, [$field => $value]);
        } else {
            $rs = $this->db->get_where($table, $where);
        }

        $data = NULL;

        foreach ($rs->result() as $row) {
            $data = $row->$select;
        }

        return $data;
    }

    function showdate($date, $format = 'Y-m-d H:i:s') {
        /*if ($format == 'Y-m-d H:i:s') {
            if ($date) {
                $e = @explode( '-', $date );
                $time = @explode( ':', @substr( $e[2], 2 ) );
                substr;
                $e[2];
            }
        }
        @( 0, 2 );
        $day = ;
        $e[0];*/
    }

    function sqldate($date) {
        if ($date) {
            $date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $date)));
        }

        return;
    }

    function getcleanurl($name) {
        return strtolower(str_replace('&', '-', str_replace(',', '-', str_replace('\'', '', str_replace(' ', '-', str_replace('/', '-', str_replace('-', '', $name)))))));
    }
    
    function getaldata($attr, $table, $by, $value) {
        $this->db->select($attr);
        $rs   = $this->db->get_where($table, [$by => $value]);
        $data = [];
        foreach ($rs->result() as $row) {
            $data[] = ['body' => $row->template, 'subject' => $row->subject];
            break;
        }

        return $data;
    }

    function getskill($pid) {
        $this->db->select('id,skill_name');
        $con = ['parent_id' => $pid, 'status' => 'Y'];
        $this->db->order_by('skill_name');
        $res  = $this->db->get_where('skills', $con);
        $data = [];
        foreach ($res->result() as $row) {
            $data[] = ['id' => $row->id, 'skill_name' => $row->skill_name];
            break;
        }

        return $data;
    }

    function getcategory($pid) {
        $this->db->select('cat_id,cat_name');
        $con = ['parent_id' => $pid, 'status' => 'Y'];
        $this->db->order_by('cat_name');
        $res  = $this->db->get_where('categories', $con);
        $data = [];
        foreach ($res->result() as $row) {
            $data[] = ['cat_id' => $row->cat_id, 'cat_name' => $row->cat_name];
            break;
        }

        return $data;
    }

    function getnumsubcat($pid) {
        $this->db->select('cat_id');
        $con = ['parent_id' => $pid, 'status' => 'Y'];
        $this->db->where($con);
        $this->db->from('categories');

        return $this->db->count_all_results();
    }

    function get_results($table_name, $query = [], $cols = '*', $offset = '', $limit = '0', $order_by = []) {
        /*$this->db->select( $cols )->from( $table_name );
        if (( $query && is_array( $query ) )) {
            $this->db->where( $query );
            (  && 0 < count( $order_by ) );
            is_array;
            $order_by;
        }
        (  );
        if ((bool)) {
            foreach ($order_by as $key => $value) {
                $this->db->order_by( $key, $value );
                break;
            }
            if (( is_numeric( $offset ) && is_numeric( $limit ) )) {
                $this->db->limit( $offset, $limit );
                $rs = $this->db->get(  );
                $rs->result_array;
            }
        }
        (  );
        $rs = ;
        return $rs;*/
    }

    function truncate($text, $length = 100, $ending = '...', $exact = FALSE, $considerHtml = TRUE) {
        /*if ($considerHtml) {
            if (strlen( preg_replace( '/<.*?>/', '', $text ) ) <= $length) {
                return $text;
                preg_match_all( '/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER );
                $total_length = strlen( $ending );
                $open_tags = array(  );
                $truncate = '';
                foreach ($lines as ) {
                }
            }
            $line_matchings = ;
            if (!empty( $line_matchings[1] )) {
                if (preg_match( '/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1] )) {
                    if (preg_match( '/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings )) {
                        $pos = array_search( $tag_matchings[1], $open_tags );
                        if ($pos !== false) {
                            unset( $open_tags[$pos] );
                        }
                    }
                    if ($length < ) {
                        $left = $length - $total_length;
                        $entities_length = 9;
                        if (preg_match_all( '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE )) {
                            foreach ($entities[0] as $entity) {
                                if ($entity[1] + 1 - $entities_length <= $left) {
                                }
                                --$left;
                            }
                        }
                    }
                }
            }
        }
        while (true) {
            $entities_length += strlen( $entity[0] );
        }
        break;
        $truncate .= substr( $line_matchings[2], 0, $left + $entities_length );
        break;
        $truncate .= $line_matchings[2];
        $total_length += $length;
        if ($length <= $total_length) {
            while (true) {
                break;
                jmp;
                if (strlen( $text ) <= $length) {
                }
                return $text;
                $truncate = substr( $text, 0, $length - strlen( $ending ) );
                if (!$exact) {
                    $spacepos = strrpos( $truncate, ' ' );
                    if (empty( $$spacepos )) {
                        substr( $truncate, 0, $spacepos );
                    }
                    $truncate = ;
                    $truncate .= $tag_matchings;
                    if ($considerHtml) {
                        foreach ($open_tags as ) {
                        }
                    }
                    $tag = ;
                    '</' . $tag . '>';
                }
                $truncate .= ;
            }
            return $truncate;
        }
    }*/
    }
}
