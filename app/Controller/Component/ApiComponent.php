<?php


//define constants

define('METHOD_POST', 1);
define('METHOD_GET', 2);


//define urls

define('LASTFM_API_URL', 'http://ws.audioscrobbler.com/2.0/');
define('NHACCUATUI_API_URL','http://api.m.nhaccuatui.com/v4/api/');

// define tokens

define('SEARCH_ACCESS_TOKEN','890A956AE870CC3711EFFEC8E4BD6CA7');
define('GET_SONG_ACCESS_TOKEN','');
define('LASTFM_ACCESS_TOKEN','5e69115a4f589e8992f11dac71266375');

// define const

define('DEFAULT_PAGE_SIZE',10);
define('DEFAULT_PAGE_INDEX',1);


//end define




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
        //CakeLog::write('error',$return_data);
        //CakeLog::write('error',$url);
        //CakeLog::write('error',$fields_string);
        $data['status'] = false;
        $data['return_data'] = "";
        if($return_data == ""){
            $data['status'] = false;
            CakeLog::write("error","something went wrong, return data is null");
        }
        else {
            $data['status'] = true;
            $data['return_data'] = $return_data;
        }
        $data['request'] = $fields_string;
        return $data;
    }

    function searchForQuery($params){
        $data['method'] = METHOD_POST;
        $data['url'] = NHACCUATUI_API_URL.'search';
        $data['params'] = array(
            'action' => 'getSearchData',
            'type' => 1,
            'keyword' => $params['q'],
            'secretkey' => 'nct@mobile_service',
            'username' => '',
            'deviceinfo' => '',
            'token' => '890A956AE870CC3711EFFEC8E4BD6CA7',
            'pageindex' => isset($params['page']) ? $params['page'] : DEFAULT_PAGE_INDEX,
            'pagesize' => isset($params['limit']) ? $params['limit'] : DEFAULT_PAGE_SIZE,
            );
        $return_data = $this-> getJsonFromOtherApi($data);
        $returnStr = $return_data['return_data'];
        $rawData = json_decode($returnStr);
        //CakeLog::write('error',print_r($rawData,1));
        $status = $return_data['status'];
        $hasData = false;
        $continue = false;
        $totalResult = 0;
        if(isset($rawData->Items)){
            $status = true;
            if(count($rawData->Items) > 0){
                $hasData = true;
                $totalResult = $rawData->TotalRecords;
            } else {
                $hasData = false;
            }
            if(isset($rawData->GetMore)){
                $continue = $rawData->GetMore;
            } else{
                $continue = 'no';
            }
        } else {
            $status = false;
            $hasData = false;
        }
        $returnData = array(
            'status' => $status,
            'hasData' => $hasData,
            'continue' => $continue,
            'totalResult' => $totalResult
            );
        if($hasData){
            $returnData['data'] = $rawData->Items;
        }
        $returnData['request'] = $return_data['request'];
        return $returnData;
    }

    function getSongInfoLastfm($params){
        $data['method'] = METHOD_GET;
        $data['url'] = LASTFM_API_URL;
        $data['params'] = array(
            'method' => 'track.getInfo',
            'api_key' => LASTFM_ACCESS_TOKEN,
            'artist' => $params['artist'],
            'format' => 'json',
            'track' => $params['name'],
            );
        $returnStr = $this-> getJsonFromOtherApi($data);
        $rawData = json_decode($returnStr);
        //CakeLog::write('error',print_r($rawData,1));
        $status = false;
        $hasData = false;
        $continue = false;
        $totalResult = 0;
        if(isset($rawData->Items)){
            $status = true;
            if(count($rawData->Items) > 0){
                $hasData = true;
                $totalResult = $rawData->TotalRecords;
            } else {
                $hasData = false;
            }
            if(isset($rawData->GetMore)){
                $continue = $rawData->GetMore;
            } else{
                $continue = 'no';
            }
        } else {
            $status = false;
            $hasData = false;
        }
        $returnData = array(
            'status' => $status,
            'hasData' => $hasData,
            'continue' => $continue,
            'totalResult' => $totalResult
            );
        if($hasData){
            $returnData['data'] = $rawData->Items;
        }
        return $returnData;
    }
}
?>
