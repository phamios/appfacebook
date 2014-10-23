<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once( 'Facebook/FacebookSession.php' );
require_once( 'Facebook/FacebookRedirectLoginHelper.php' );
require_once( 'Facebook/FacebookRequest.php' );
require_once( 'Facebook/FacebookResponse.php' );
require_once( 'Facebook/FacebookSDKException.php' );
require_once( 'Facebook/FacebookRequestException.php' );
require_once( 'Facebook/FacebookAuthorizationException.php' );
require_once( 'Facebook/GraphObject.php' );
require_once('Facebook/Entities/AccessToken.php');
require_once('Facebook/HttpClients/FacebookHttpable.php');
require_once('Facebook/HttpClients/FacebookCurl.php');
require_once('Facebook/HttpClients/FacebookCurlHttpClient.php');
require_once('Facebook/FacebookPermissionException.php');

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;

// added in v4.0.5
use Facebook\FacebookHttpable;
use Facebook\FacebookCurl;
use Facebook\FacebookCurlHttpClient;



class Facebook {

    protected $permission;
    protected $redirect_url;
    protected $appID;
    protected $appSecret;
    protected $session;

    public function __construct($params = array()) {
        if (count($params) > 0) {
            $this->permission = $params['permission'];
            $this->redirect_url = $params['redirect'];
            $this->appID = $params['appid'];
            $this->appSecret = $params['apptoken'];
        }
    }

    //Authentication application facebook ID
    public function _auth() {
        FacebookSession::setDefaultApplication($this->appID, $this->appSecret);
        $helper = new FacebookRedirectLoginHelper($this->redirect_url);

        try {
            $this->session = $helper->getSessionFromRedirect();
        } catch (FacebookRequestException $ex) {
            print_r($ex);
        } catch (Exception $ex) {
            print_r($ex);
        }
        if (isset($this->session)) { 
            // graph api request for user data
            $request = new FacebookRequest($this->session, 'GET', '/me');
            $response = $request->execute();
            $graphObject = $response->getGraphObject()->asArray();
            return $graphObject;
        } else {
            return '<a href="' . $helper->getLoginUrl($this->permission) . '">Login</a>';
        }
    }

    public function _post_comment($msg = null) {
        if (isset($this->session)) {
            try {
                $request = (new FacebookRequest($this->session, 'POST', '/me/feed', array('message' => $msg)))->execute();
                $response = $request->getGraphObject()->asArray();
                if ($response) {
                    return true;
                }
            } catch (FacebookApiException $e) {
                return $e->getMessage();
            }
        } else {
            return false;
        }
    }


    public function _getFriendList(){
        if (isset($this->session)) {
           try {
            $request = new FacebookRequest( $this->session, 'GET', '/me/friends');
            $response = $request->execute();
            $graphObject = $response->getGraphObject();
            return $graphObject ;
        } catch(FacebookRequestException $e) {
            echo "Exception occured, code: " . $e->getCode();
            echo " with message: " . $e->getMessage();
        }
    } else {
        return 0;
    }
}


public function _send_invite(){
    if (isset($this->session)) {

        $request = new FacebookRequest($this->session,'GET','/me/friends');
        $response = $request->execute();
        $graphObject = $response->getGraphObject()->asArray();
        /* handle the result */
        return $graphObject ;
    } else {
        return false;
    }
}

}

?>