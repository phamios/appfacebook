<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function _list() { 
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('fb_user');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function check_exit($email) { 
        $this->db->where('uemail', $email);
        $query = $this->db->get('fb_user');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function _add($id, $email, $birth, $name, $sex, $location, $link, $verify) {
        $data = array(
            'uid' => $id,
            'uemail' => $email,
            'ubirthday' => $birth,
            'uname' => $name,
            'usex' => $sex,
            'ulocation' => $location,
            'uverify' => $link,
            'ulink' => $verify,
            'timeupdate' => date("Y-m-d h:s:m"),
        );
        if ($this->check_exit($email) == false) {
            $this->db->insert('fb_user', $data);
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
        $this->db->delete('tbl_api');
    }

}
