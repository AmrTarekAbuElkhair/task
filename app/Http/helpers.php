<?php
use Illuminate\Support\Facades\Config;
function success()
{
    return 'success';
}

function error()
{
    return 'error';
}

function failed()
{
    return 'failed';
}

function res($lang,$status,$code,$key,$data=null)
{
    $response['code'] = $code;
    $response['status']=$status;
    $response['msg'] = Config::get('response.'.$key.'.'.$lang);
    if ($data!=null){
        $response['data'] = $data;
    }else{
        $response['data'] = [];
    }
    return $response;
}

function res_msg($lang,$status,$code,$key)
{
    $response['code'] = $code;
    $response['status']=$status;
    $response['msg'] = Config::get('response.'.$key.'.'.$lang);
    return $response;
}
