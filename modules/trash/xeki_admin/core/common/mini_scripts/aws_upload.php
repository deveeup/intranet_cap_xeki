<?php
require_once dirname(__FILE__) . "/../../libs/vendor/autoload.php";
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

// for handling efficienty depencencies
//$config_example = array(
//    'credentials' => array(
//        'key'    => "",
//        'secret' => "",
//    ),
//    'region' => "us-west-2",
//    'version' => 'latest',
//    // 'scheme'  => 'http',
//    'http'    => [
//        'verify' => false
//    ],
//    'bucket'=>"bucket",
//);

function upload_aws($file_name,$file_route,$config){
    $client = S3Client::factory($config);

    $res = $client->putObject(array(
        'Bucket'=>$config['bucket'],
        'Key' =>  $file_name,//time().$file['name']
        'SourceFile' => $file_route,
//        'StorageClass' => 'REDUCED_REDUNDANCY'
    ));
    return $res['ObjectURL'];

}