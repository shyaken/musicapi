<?php

/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AppController', 'Controller');


define('SECRETKEY', 'nct@mobile_service');
define('USERNAME','shyaken');
define('PASSWORD', 'bloodheart');

define('USER1', '1');
define('USER2', '2');
define('MOBILE_SERVICE', '3');
define('SONG', '4');
define('SONGLYRIC', '5');
define('RELATEDSONG', '6');
define('SEARCH', '7');
define('HOTPLAYLIST', '8');
define('PLAYLISTINFO', '9');

define('DEVICEINFO', 'deviceinfo={"DeviceID":"yyyOjF7sY7MZs//v0+qQI58HeGU=","OsName":"WindowsPhone7","OsVersion":"8.0.10328.0","AppName":"NhacCuaTui","AppVersion":"2.0.0.0","UserInfo":"shyaken","LocationInfo":""}');

$token = array('null','4833C7C173B1B1E57CB248013343DAF0','9A65A480F9E4FF780404DCAC7E7D0283','4735AFCFCBE9217DEBD0329E5399146F',
        '9228BBC2B2BE53236132F31BD7C9D585','2B35E58A7833856F6F5988184F2E2BD2','1C570786DBDB6975669BD12A737F4337',
        '890A956AE870CC3711EFFEC8E4BD6CA7','890A956AE870CC3711EFFEC8E4BD6CA7','1E95F34877FA4711AC68C8C89C240751'
    );

$action = array('');
/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class ApiController extends AppController {

    /**
     * This controller does not use a model
     *
     * @var array
     */
    public $uses = array();
    
    public $components = array('Api','Song','Playlist','Artist','User');

    /**
     * Displays a view
     *
     * @param mixed What page to display
     * @return void
     * @throws NotFoundException When the view file could not be found
     *  or MissingViewException in debug mode.
     */
    public function index() {
        die("say something");
    }

    function getRequestParam($request){
        $r = array();
        foreach ($request as $key => $value) {
            # code...
            $r[mysql_escape_string($key)] = mysql_escape_string($value);
        }
        return $r;
    }

    function getClientInfor($server){
        $r = array();
        foreach ($server as $key => $value) {
            # code...
            $r[mysql_escape_string($key)] = mysql_escape_string($value);
        }
        return $r;
    }

    public function getData(){
        $request = $this->getRequestParam($_REQUEST);
        $client = $this->getClientInfor($_SERVER);
        $data = "";
        //foreach($client as $key => $value){
        //    $data .= $key." => ".$value." <br/>";
        //}
        //$data = json_encode($client);
        //$this->set('data',$data);
        //$this->render('json_data');
        return array_merge($request,$client);
    }

    public function getHostPlaylist() {
        echo "get";
        $a = $this->Api->doComplexOperation(2,3);
        echo $a;
        die();
    }
    
    public function getPlaylist($pId) {
        
    }
    
    public function getSong($sId) {
        
    }
    
    public function getSongLyric() {
        
    }

    public function search(){
        //die('hered');
        $params = $this->getData();
        $data = array();
        if(isset($params['q'])){
            $data = $this->Api->searchForQuery($params);
        }
        $dataStr = json_encode($data);
        $this->set('data',$dataStr);
        $this->render("json_data");
    }
    
}
