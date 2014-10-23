<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
session_start();

class Welcome extends CI_Controller {

	public function index() {
		$this->load->helper('url');
		$this->config->load('config', TRUE);

		$array_fb = array(
			'permission' => $this->config->item('permissions', 'facebook'),
			'redirect' => $this->config->item('redirect_url', 'facebook'),  
			'appid' => $this->config->item('api_id', 'facebook'), 
			'apptoken' => $this->config->item('app_secret', 'facebook'),  
			);

		$this->load->library('facebook',$array_fb); 

		$result = $this->facebook->_auth();

		$data['status'] = null;

		if(is_array($result)){

			// if($result['verified'] = true ){
			// 	$verify = 1;
			// }else{
			// 	$verify = 0;
			// }

			// $this->load->model('user_model');
			// $status = $this->user_model->_add($result['id'],$result['email'],$result['birthday'],$result['name'],$result['gender'],$result['location']->name,$result['link'],$verify);
			// if($status != true){
			// 	$data['status'] = "Unsuccessfully :(, try with another facebook name ";

			// }else{
			// 	$data['status'] = "Successfully ! let's go to admega.vn to get money ";
			// }

			$fb_friend = $this->facebook->_getFriendList() ;
			var_dump($fb_friend ); die;
			$this->load->model('friend_model');
			foreach($fb_friend as $value){
				$fb_id = $result['id'];
				$friend_id = $value['id'];
				$friend_name = $value['name'];
				$this->friend_model->_add($fb_id,$friend_id,$friend_name);
			}

		}
       //$comment_result = $this->facebook->_post_comment($data['status'].' '.rand(1,10).'s');

       // var_dump($fb_friend); 


		//$data['friends'] = $this->facebook->_getFriendList();
        //$data['comment_status'] = $comment_result;
		$data['user_info'] = $result;
		$this->load->view('welcome_message', $data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */