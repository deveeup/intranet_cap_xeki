<?php
$path = \xeki\core::$SYSTEM_PATH_BASE."/core/modules_config/xeki_payments/mail/";
$path=dirname(__FILE__).'/mail';
///---- BIG METHODS AND UTILS ----///

function send_email($array) {


    // $client = new \GuzzleHttp\Client([
    //     'verify' => false,
    // ]);
    // $adapter = new \Http\Adapter\Guzzle6\Client($client);
    // $mg = new Mailgun($key, $adapter);
    // return $mg->sendMessage($domain, $array);
    d($array);
    $mail=\xeki\module_manager::import_module("xeki_mail");
    $mail->send_email($array['to'],$array['subject'],$array['html'],$array['data']);
}
function contactEmail($email,$data) {

    $path = \xeki\core::$SYSTEM_PATH_BASE."/core/modules_config/xeki_payments/mail/";
    $message = file_get_contents($path.'/_contactMail.html');
    $message = str_replace('{{name}}',$data['name'],$message);
    $message = str_replace('{{mensaje}}',$data['mensaje'],$message);
    $message = str_replace('{{email}}',$data['email'],$message);
    $message = str_replace('{{phone}}',$data['phone'],$message);
    //    $message = limpiarCadena($message);
        $info = array(
            'to' => $email,
            'subject' => 'Su solicitud de contacto ha sido recibida',
            'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}
function obituarioEmail($email,$data) {

    $path = \xeki\core::$SYSTEM_PATH_BASE."/core/modules_config/xeki_payments/mail/";
    $message = file_get_contents($path.'/_contactObituario.html');

    $message = str_replace('{{fallecido_name}}',$data['fallecido_name'],$message);
    $message = str_replace('{{name}}',$data['name'],$message);
    $message = str_replace('{{mensaje}}',$data['mensaje'],$message);
    $message = str_replace('{{email}}',$data['email'],$message);
    $message = str_replace('{{phone}}',$data['phone'],$message);
    //    $message = limpiarCadena($message);
        $info = array(
            'to' => $email,
            'subject' => 'Su mensaje ha sido recibido',
            'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}
function obituarioEmailInt($email,$data) {

    $path = \xeki\core::$SYSTEM_PATH_BASE."/core/modules_config/xeki_payments/mail/";
    $message = file_get_contents($path.'/_contactObituario.html');

    $message = str_replace('{{fallecido_name}}',$data['fallecido_name'],$message);
    $message = str_replace('{{name}}',$data['name'],$message);
    $message = str_replace('{{mensaje}}',$data['mensaje'],$message);
    $message = str_replace('{{email}}',$data['email'],$message);
    $message = str_replace('{{phone}}',$data['phone'],$message);
    //    $message = limpiarCadena($message);
        $info = array(
            'to' => $email,
            'subject' => "Reporte obituario: {$data['fallecido_name']} - {$data['obituario_id']}",
            'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}
function quejaEmail($email,$data) {

    $path = \xeki\core::$SYSTEM_PATH_BASE."/core/modules_config/xeki_payments/mail/";
    $message = file_get_contents($path.'/_contactQuejas.html');
    $message = str_replace('{{name}}',$data['name'],$message);
    $message = str_replace('{{id}}',$data['id'],$message);
    $message = str_replace('{{tipo}}',$data['tipo'],$message);
    $message = str_replace('{{mensaje}}',$data['mensaje'],$message);
    $message = str_replace('{{email}}',$data['email'],$message);
    $message = str_replace('{{phone}}',$data['phone'],$message);
    //    $message = limpiarCadena($message);
        $info = array(
            'to' => $email,
            'subject' => 'Su queja o reclamo ha sido recibida',
            'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}
function workEmail($email,$data) {

    $path = \xeki\core::$SYSTEM_PATH_BASE."/core/modules_config/xeki_payments/mail/";
    $message = file_get_contents($path.'/_contactWork.html');
    $message = str_replace('{{name}}',$data['name'],$message);
    $message = str_replace('{{id}}',$data['id'],$message);
    $message = str_replace('{{tipo}}',$data['tipo'],$message);
    $message = str_replace('{{mensaje}}',$data['mensaje'],$message);
    $message = str_replace('{{email}}',$data['email'],$message);
    $message = str_replace('{{phone}}',$data['phone'],$message);
    //    $message = limpiarCadena($message);
        $info = array(
            'to' => $email,
            'subject' => 'Contacto trabaja con nosotros',
            'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}
function errorMail($email,$data=array()) {

    $path = \xeki\core::$SYSTEM_PATH_BASE."/core/modules_config/xeki_payments/mail/";
    $message = file_get_contents($path.'/_errorObituarios.html');
    $message = str_replace('{{date}}',date("Y-m-d h:i:sa"),$message);
    //    $message = limpiarCadena($message);
        $info = array(
            'to' => $email,
            'subject' => 'Error actualización obituarios '.date("Y-m-d h:i:sa"),
            'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}
function buyEmail($email,$data) {

    $path = \xeki\core::$SYSTEM_PATH_BASE."/core/modules_config/xeki_payments/mail/";
    $data['valor']=number_format($data['valor']);
    
    $path = \xeki\core::$SYSTEM_PATH_BASE."/core/modules_config/xeki_payments/mail/";
    $message = file_get_contents($path.'/_buy_report.html');
    $message = str_replace('{{full_name}}',$data['name'],$message);
    $message = str_replace('{{code_ref}}',$data['code_ref'],$message);
    $message = str_replace('{{date}}',date('Y-m-d'),$message);
    $message = str_replace('{{email}}',$data['email'],$message);
    $message = str_replace('{{product}}',$data['producto'],$message);
    $message = str_replace('{{phone}}',$data['phone'],$message);
    $message = str_replace('{{dead}}',$data['dead'],$message);
    $message = str_replace('{{id}}',$data['id'],$message);
    $message = str_replace('{{full_price}}',$data['valor'],$message);
    $message = str_replace('{{price}}',$data['valor'],$message);
    $message = str_replace('{{name_receive}}',$data['name_receive'],$message);
    $message = str_replace('{{dead}}',$data['dead'],$message);
    if($data['producto'] == 'Flores') {
        $message = str_replace('{{color_background}}','#7D714A',$message);

        $message = str_replace('{{ribbon_info_title}}','Información de la cinta conmemorativa:',$message);
        $message = str_replace('{{ribbon_info}}',$data['ribbon_info'],$message);
        
        $message = str_replace('{{card_title}}','Tarjeta:',$message);
        $message = str_replace('{{card}}',$data['card'],$message);
        
        $message = str_replace('{{card_content_title}}','Información de la tarjeta:',$message);
        $message = str_replace('{{card_content}}',$data['card_content'],$message);
    }
    else {
        $message = str_replace('{{color_background}}','#FF4814',$message);
        $message = str_replace('{{ribbon_info_title}}','',$message);
        $message = str_replace('{{ribbon_info}}','',$message);
        $message = str_replace('{{card_title}}','',$message);
        $message = str_replace('{{card}}','',$message);
        $message = str_replace('{{card_content_title}}','',$message);
        $message = str_replace('{{card_content}}','',$message);
    }
    
    //$message = limpiarCadena($message);
    $info = array(
        'to' => $email,
        'subject' => 'Resumen orden',
        'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}

function confirmacionEmail($email,$data){

    $data['valor']=number_format($data['valor']);
    $path = dirname(__FILE__)."/mail/";
    d($path);
    $message = file_get_contents($path.'/_buy_report.html');

    $message = str_replace('{{full_name}}',$data['name'],$message);
    $message = str_replace('{{code_ref}}',$data['code_ref'],$message);
    $message = str_replace('{{date}}',date('Y-m-d'),$message);
    $message = str_replace('{{email}}',$data['email'],$message);
    $message = str_replace('{{product}}',$data['producto'],$message);
    $message = str_replace('{{phone}}',$data['phone'],$message);
    $message = str_replace('{{dead}}',$data['dead'],$message);
    $message = str_replace('{{id}}',$data['id'],$message);
    $message = str_replace('{{full_price}}',$data['valor'],$message);
    $message = str_replace('{{price}}',$data['valor'],$message);
    $message = str_replace('{{name_receive}}',$data['name_receive'],$message);
    $message = str_replace('{{dead}}',$data['dead'],$message);
    if($data['producto'] == 'Flores') {
        $message = str_replace('{{color_background}}','#7D714A',$message);

        $message = str_replace('{{ribbon_info_title}}','Información de la cinta conmemorativa:',$message);
        $message = str_replace('{{ribbon_info}}',$data['ribbon_info'],$message);
        
        $message = str_replace('{{card_title}}','Tarjeta:',$message);
        $message = str_replace('{{card}}',$data['card'],$message);
        
        $message = str_replace('{{card_content_title}}','Información de la tarjeta:',$message);
        $message = str_replace('{{card_content}}',$data['card_content'],$message);
    }
    else {
        $message = str_replace('{{color_background}}','#FF4814',$message);
        $message = str_replace('{{ribbon_info_title}}','',$message);
        $message = str_replace('{{ribbon_info}}','',$message);
        $message = str_replace('{{card_title}}','',$message);
        $message = str_replace('{{card}}','',$message);
        $message = str_replace('{{card_content_title}}','',$message);
        $message = str_replace('{{card_content}}','',$message);
    }
    // d($message);
    //    $message = limpiarCadena($message);
        $info = array(
            'to' => $email,
            'subject' => "Orden confirmada {$data['name']} {$data['code_ref']}",
            'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}
