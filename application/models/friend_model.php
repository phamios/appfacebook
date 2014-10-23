<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class friend_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function _list() { 
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('tbl_friend');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function check_exit($friend_id) { 
        $this->db->where('friend_id', $friend_id);
        $query = $this->db->get('tbl_friend');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function _add($fb_id,$friend_id,$friend_name) {
        $data = array(
            'fb_id' => $fb_id,
            'friend_id' => $friend_id,
            'friend_name' => $friend_name
        );
        if ($this->check_exit($friend_id) == false) {
            $this->db->insert('tbl_friend', $data);
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function del_api($userid, $apiid) {
        $this->load->database();
        $this->db->where('userid', $userid);
        $this->db->where('id', $apiid);
        $this->db->delete('tbl_friend');
    }

}
