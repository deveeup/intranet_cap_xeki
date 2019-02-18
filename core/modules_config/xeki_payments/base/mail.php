<?php
$path=dirname(__FILE__).'/mail';
///---- BIG METHODS AND UTILS ----///
use Mailgun\Mailgun;
$domain ='sandbox2a81bf86bc034877a6ebd845cc489df4.mailgun.org';
$from   ='Capillas de la fe <contacto@capillasdelafe.com>';
function send_email($array) {
    global $from;
    global $path;
    global $domain;
    
	require_once dirname(__FILE__).'/libs/vendor/autoload.php';
    $domain = "capillasdelafe.com";
    $key = "key-2c30c0b1f143848307408ebcfe1f5508";

    $client = new \GuzzleHttp\Client([
        'verify' => false,
    ]);
    $adapter = new \Http\Adapter\Guzzle6\Client($client);
    $mg = new Mailgun($key, $adapter);
    return $mg->sendMessage($domain, $array);
    

//    $headers = "From: " . $from . "\r\n";
//    $headers .= "Reply-To: ". $from . "\r\n";
// //    $headers .= "CC: susan@example.com\r\n";
//    $headers .= "MIME-Version: 1.0\r\n";
//    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
//    mail($array['to'], $array['subject'], $array['html'], $headers);
}
function contactEmail($email,$data) {
    global $from;
    global $path;
    $message = file_get_contents($path.'/_contactMail.html');
    $message = str_replace('{{name}}',$data['name'],$message);
    $message = str_replace('{{mensaje}}',$data['mensaje'],$message);
    $message = str_replace('{{email}}',$data['email'],$message);
    $message = str_replace('{{phone}}',$data['phone'],$message);
    //    $message = limpiarCadena($message);
        $info = array(
            'from' => $from,
            'to' => $email,
            'subject' => 'Su solicitud de contacto ha sido recibida',
            'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}
function obituarioEmail($email,$data) {
    global $from;
    global $path;
    $message = file_get_contents($path.'/_contactObituario.html');

    $message = str_replace('{{fallecido_name}}',$data['fallecido_name'],$message);
    $message = str_replace('{{name}}',$data['name'],$message);
    $message = str_replace('{{mensaje}}',$data['mensaje'],$message);
    $message = str_replace('{{email}}',$data['email'],$message);
    $message = str_replace('{{phone}}',$data['phone'],$message);
    //    $message = limpiarCadena($message);
        $info = array(
            'from' => $from,
            'to' => $email,
            'subject' => 'Su mensaje ha sido recibido',
            'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}
function obituarioEmailInt($email,$data) {
    global $from;
    global $path;
    $message = file_get_contents($path.'/_contactObituario.html');

    $message = str_replace('{{fallecido_name}}',$data['fallecido_name'],$message);
    $message = str_replace('{{name}}',$data['name'],$message);
    $message = str_replace('{{mensaje}}',$data['mensaje'],$message);
    $message = str_replace('{{email}}',$data['email'],$message);
    $message = str_replace('{{phone}}',$data['phone'],$message);
    //    $message = limpiarCadena($message);
        $info = array(
            'from' => $from,
            'to' => $email,
            'subject' => "Reporte obituario: {$data['fallecido_name']} - {$data['obituario_id']}",
            'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}
function quejaEmail($email,$data) {
    global $from;
    global $path;
    $message = file_get_contents($path.'/_contactQuejas.html');
    $message = str_replace('{{name}}',$data['name'],$message);
    $message = str_replace('{{id}}',$data['id'],$message);
    $message = str_replace('{{tipo}}',$data['tipo'],$message);
    $message = str_replace('{{mensaje}}',$data['mensaje'],$message);
    $message = str_replace('{{email}}',$data['email'],$message);
    $message = str_replace('{{phone}}',$data['phone'],$message);
    //    $message = limpiarCadena($message);
        $info = array(
            'from' => $from,
            'to' => $email,
            'subject' => 'Su queja o reclamo ha sido recibida',
            'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}
function workEmail($email,$data) {
    global $from;
    global $path;
    $message = file_get_contents($path.'/_contactWork.html');
    $message = str_replace('{{name}}',$data['name'],$message);
    $message = str_replace('{{id}}',$data['id'],$message);
    $message = str_replace('{{tipo}}',$data['tipo'],$message);
    $message = str_replace('{{mensaje}}',$data['mensaje'],$message);
    $message = str_replace('{{email}}',$data['email'],$message);
    $message = str_replace('{{phone}}',$data['phone'],$message);
    //    $message = limpiarCadena($message);
        $info = array(
            'from' => $from,
            'to' => $email,
            'subject' => 'Contacto trabaja con nosotros',
            'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}
function errorMail($email,$data=array()) {
    global $from;
    global $path;
    $message = file_get_contents($path.'/_errorObituarios.html');
    $message = str_replace('{{date}}',date("Y-m-d h:i:sa"),$message);
    //    $message = limpiarCadena($message);
        $info = array(
            'from' => $from,
            'to' => $email,
            'subject' => 'Error actualización obituarios '.date("Y-m-d h:i:sa"),
            'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}
function contacto($email,$data) {
	global $from;
    global $path;
	$message = file_get_contents($path.'/_cotizacion.html');
	$message = str_replace('{{code}}',$data['code_url'],$message);
	$message = str_replace('{{name}}',$data['name'],$message);
    $message = str_replace('{{model}}',$data['model'],$message);
    //	$message = limpiarCadena($message);
    //	d($message );
    	$info = array(
    		'from' => $from,
    		'to' => $email,
    		'subject' => 'Su cotizacion ya está lista',
    		'html' => $message,
    //		'o:campaign' => 'f3je4',
    	);
    //    d($message);
    	return send_email($info);
}

function buyEmail($email,$data) {
    global $from;
    global $path;
    $data['valor']=number_format($data['valor']);
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
        'from' => $from,
        'to' => $email,
        'subject' => 'Resumen orden',
        'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}

function confirmacionEmail($email,$data){
    global $from;
    global $path;
    $data['valor']=number_format($data['valor']);
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
            'from' => $from,
            'to' => $email,
            'subject' => "Orden confirmada {$data['name']} {$data['code_ref']}",
            'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}

function testDriveEmail($email,$data) {
    global $from;
    global $path;
    $message = file_get_contents($path.'/_testDrive.html');
    $message = str_replace('{{code}}',$data['code_url'],$message);
    $message = str_replace('{{name}}',$data['userName'],$message);
    $message = str_replace('{{model}}',$data['model'],$message);
    //    $message = limpiarCadena($message);
        $info = array(
            'from' => $from,
            'to' => $email,
            'subject' => 'Su solicitud de test drive ha sido recibida',
            'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}

function cambioVehiculoEmail($email,$data) {
    global $from;
    global $path;
    $message = file_get_contents($path.'/_cambio_vehiculo.html');
    $message = str_replace('{{code}}',$data['code_url'],$message);
    $message = str_replace('{{name}}',$data['userName'],$message);
    $message = str_replace('{{model}}',$data['model'],$message);
    //    $message = limpiarCadena($message);
        $info = array(
            'from' => $from,
            'to' => $email,
            'subject' => 'Su solicitud de cambio de vehiculo ha sido recibida',
            'html' => $message,
    //		'o:campaign' => 'f3je4',
        );
        return send_email($info);
}

function sendQueryEmpty($data) {

	$message = implode(" ", $data);
	$input = $_REQUEST;
	$output = implode(', ', array_map(function ($v, $k) {return sprintf("%s='%s'", $k, $v);}, $input, array_keys($input)));
	$serverInfo = $_SERVER;
	$output2 = implode(', ', array_map(function ($v, $k) {return sprintf("%s='%s'", $k, $v);}, $serverInfo, array_keys($serverInfo)));

	$subject = $message . '  - ByP Error search empty';
	$message = $message . '<br><br>' . $output . '<br><br>' . $output2;

	$headers = "From: error@bebesypanales.com \r\n";
	$headers .= "Reply-To:  \r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	$to = 'liuspatt@gmail.com';
	mail($to, $subject, $message, $headers);
	$to = 'juansedu@gmail.com';
	mail($to, $subject, $message, $headers);
}