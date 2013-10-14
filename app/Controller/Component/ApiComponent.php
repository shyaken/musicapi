<?php


//define constants

define('METHOD_POST', 1);
define('METHOD_GET', 2);


define('LASTFM_API_URL', '');
define('NHACCUATUI_API_URL','');


App::uses('Component', 'Controller');
class ApiComponent extends Component {
    public function doComplexOperation($amount1, $amount2) {
        return $amount1 + $amount2;
    }
    
    
    /*
     * @funtion: getJsonFromOtherApi
     * @return: json string from an external Api
     * @param: array(
     *          'url' => target url to send request
     *          'method' => POST or GET
     *          'params' => params want to send to get data
     *          )
     * @author: chunghd
     * @date: 25/9/2013
     */
    public function getJsonFromOtherApi($data){
        if (!isset($data['url']) || !isset($data['params'])){
            return null;
        } 
        $method = METHOD_POST;
        if(isset($data['method'])){
            $method = $data['method'];
        }
        $url = $data['url'];
        rtrim($url,'/');
        $requestFields = $data['params'];
        $fields_string = "";
        foreach($requestFields as $key=>$value) { 
            $fields_string .= $key.'='.$value.'&';
        }
        rtrim($fields_string, '&');
        
        if($method == METHOD_GET){
            $url .= '?'.$fields_string;
        }
        //set up curl to get json data
        
        $ch = curl_init($url);
        if($method == METHOD_POST){
            curl_setopt($ch,CURLOPT_POST, count($requestFields));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $return_data = curl_exec($ch);
        curl_close($ch);
        
        if($return_data == ""){
            CakeLog::write("error","something went wrong, return data is null");
        }
        
        return $return_data;
    }
}
?>
