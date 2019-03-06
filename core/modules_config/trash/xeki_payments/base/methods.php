<?php

## methods post
## recibe action post in agent
require_once('./require.php');
require_once('./mail.php');
if (ag_isACTION()) {
    $vl_action = ag_getAction();
    $vl_values = ag_getValuesPost();
    if (false) {
        
    } else if ($vl_action == 'buyPayU') {
        $data = array(
            'id_number' => $vl_values['id'],
            'name' => $vl_values['buyerFullName'],
            'lastName' => $vl_values['lastName'],
            'email' => $vl_values['buyerEmail'],
        );
        $res = $sql->insert('customer', $data);
        
        $data = array(
        'id_number' => $vl_values['id'],
        'name' => $vl_values['buyerFullName'],
        'lastName' => $vl_values['lastName'],
        'email' => $vl_values['buyerEmail'],
        );
        $sql->update('customer', $data, "email={$vl_values['buyerEmail']}");
        
        # create buy
        $data = array(
            'code' => $vl_values['referenceCode'],
            'price' => $vl_values['amount'],
            'name' => $vl_values['buyerFullName'] . ' ' . $vl_values['lastName'],
            'user' => $res,
            'plan' => $vl_values['plan'],
            //  'payment' => $vl_values['payment'],
            //  'state' => $vl_values['state'],
            'merchantId' => $vl_values['merchantId'],
            'description' => $vl_values['description'],
            'amount' => $vl_values['amount'],
            'currency' => $vl_values['currency'],
            'lng' => $vl_values['lng'],
            'buyerEmail' => $vl_values['buyerEmail'],
            'confirmationUrl' => $vl_values['confirmationUrl'],
            'responseUrl' => $vl_values['responseUrl'],
            'referenceCode' => $vl_values['referenceCode'],
            'signature' => $vl_values['signature'],
            'fallecido' => $vl_values['fallecido'],
            'buyerPhone' => $vl_values['buyerPhone'],
            'name_receive' => $vl_values['name_receive'],
            'card' => $vl_values['card'],
            'cardContent' => $vl_values['card_content'],
            'ribbonInfo' => $vl_values['ribbon_info'],

            'fallecido' => $vl_values['dead'],
            'buyerPhone' => $vl_values['buyerPhone'],
        );
        $res = $sql->insert('customer_buy', $data);
        
        $data = array(
            'name' => $vl_values['buyerFullName'],
            'code_ref' => $vl_values['referenceCode'],
            'email' => $vl_values['buyerEmail'],
            'producto' => $vl_values['plan'],
            'phone' => $vl_values['buyerPhone'],
            'dead' => $vl_values['fallecido'],
            'id' => $vl_values['id'],
            'valor' => $vl_values['amount'],
            'dead' => $vl_values['dead'],
            'name_receive' => $vl_values['name_receive'],

            'ribbon_info' => $vl_values['ribbon_info'],
            'card' => $vl_values['card'],
            'card_content' => $vl_values['card_content'],
        );
        buyEmail($vl_values['buyerEmail'], $data);
        //buyEmail("liuspatt@gmail.com", $data);
        // buyEmail("jossedaviid1@gmail.com", $data);
        //        contactEmail("asesorvirtual@capillasdelafe.com", $data);
        ag_pushMsg("Gracias por escribirnos su mensaje ha sido enviado.");
        $_SESSION['reference_sale'] = $vl_values['referenceCode'];
        ag_RedirectTo('payU_proccess');
    } else if ($vl_action == 'contacto') {
        $data = array(
        'name' => $vl_values['name'],
        'email' => $vl_values['email'],
        'phone' => $vl_values['phone'],
        'mensaje' => $vl_values['message'],
        );
        contactEmail($vl_values['email'], $data);
        contactEmail("liuspatt@gmail.com", $data);
        contactEmail("asesorvirtual@capillasdelafe.com", $data);
        ag_pushMsg("Gracias por escribirnos su mensaje ha sido enviado.");
    } else if ($vl_action == 'contacto-obituario') {
        //        d($vl_values);
        
        $data = array(
        'obituarios_Ref' => $vl_values['obituario_id'],
        'nombre' => $vl_values['name'],
        'mensaje' => $vl_values['message'],
        'ciudad' => $vl_values['ciudad'],
        'fecha' => date('y-m-d'),
        );
        $res = $sql->insert('obituarios_mensajes', $data);
        
        if(isset($vl_values['email'])){
            $data = array(
            'obituario_id' => $vl_values['obituario_id'],
            'fallecido_name' => $vl_values['fallecido_name'],
            'name' => $vl_values['name'],
            'email' => $vl_values['email'],
            'phone' => $vl_values['phone'],
            'mensaje' => $vl_values['message'],
            );
            
            
            //        d($data);
            obituarioEmail($vl_values['email'], $data);
            obituarioEmailInt("liuspatt@gmail.com", $data);
            obituarioEmailInt("obituarios@capillasdelafe.com", $data);
            ag_pushMsg("Gracias por escribirnos su mensaje ha sido enviado.");
        }
    } else if ($vl_action == 'queja-reclamos') {
        $data = array(
        'name' => $vl_values['name'],
        'email' => $vl_values['email'],
        'phone' => $vl_values['phone'],
        'mensaje' => $vl_values['message'],
        'id' => $vl_values['id'],
        'tipo' => $vl_values['tipo'],
        );
        quejaEmail($vl_values['email'], $data);
        quejaEmail("liuspatt@gmail.com", $data);
        quejaEmail("juridica1@capillasdelafe.com", $data);
        quejaEmail("ssuarez@capillasdelafe.com", $data);
        ag_pushMsg("Gracias por escribirnos su mensaje ha sido enviado.");
    } else if ($vl_action == 'work-with-us') {
        $data = array(
        'name' => $vl_values['name'],
        'email' => $vl_values['email'],
        'phone' => $vl_values['phone'],
        'mensaje' => $vl_values['message'],
        'id' => $vl_values['id'],
        'tipo' => $vl_values['tipo'],
        );
        workEmail($vl_values['email'], $data);
        workEmail("liuspatt@gmail.com", $data);
        workEmail("seleccionrrhh68@gmail.com", $data);
        ag_pushMsg("Gracias por escribirnos su mensaje ha sido enviado.");
    } else if ($vl_action == 'certificados') {
        $folder = '';
        if ($vl_values['tipo'] == 'ica')
            $folder = 'ica';
        else if ($vl_values['tipo'] == 'iva')
            $folder = 'iva';
        else if ($vl_values['tipo'] == 'rtf')
            $folder = 'rtf';
        
        $file = 'files_certificados/' . $folder . '/' . $vl_values['anio'] . '/' . $vl_values['id'] . '.pdf';
        if (file_exists($file)) {
            ag_RedirectTo($file);
            // header('Content-Type: application/pdf');
            
            
            header("Content-Disposition: attachment; filename=certificado.pdf");
            header("Content-Type: application/octet-stream");
            // header("Content-Type: application/download");
            header("Content-Description: File Transfer");
            header("Content-Length: " . filesize($file));
            flush(); // this doesn't really matter.
            $fp = fopen($file, "r");
            while (!feof($fp))
            {
                echo fread($fp, 65536);
                flush(); // this is essential for large downloads
            }
            fclose($fp);
            die();
            
            // header('Content-Type: application/pdf');
            // header('Content-Disposition: attachment; filename=certificado.pdf');
            // header('Pragma: no-cache');
            // readfile($file);
            
        } else {
            ag_pushMsg("El certificado no ha sido encontrado.");
        }
    } else if ($vl_action == 'addProduct') {
        
        if($_POST['price_personalized'] == 'NA') {

            $_SESSION['condition_product'] = $vl_values['price_personalized'];

            $_SESSION['product_final_id'] = $vl_values['product_final_id'];
            setcookie("product_final_id", $vl_values['product_final_id'], time() + 30);

            //VALOR
            $_SESSION['product_price'] = $vl_values['product_price'];
            setcookie("product_price", $vl_values['product_price'], time() + 30);
        }
        else if($_POST['price_personalized'] == 'SA') {

            $_SESSION['condition_product'] = $vl_values['price_personalized'];

            $_SESSION['product_final_id'] = $vl_values['price_final_bono'];
            setcookie("product_final_id", $vl_values['price_final_bono'], time() + 30);

            //VALOR
            $_SESSION['product_price'] = $vl_values['product_price'];
            setcookie("product_price", $vl_values['product_price'], time() + 30);
        }
        ag_RedirectTo('buy');
    } else if ($vl_action == 'update_user_and_buy') {
        
        $mail = $vl_values['email'];
        $_SESSION['save_action'] = 'apartar';
        $_SESSION['id_user'] = '';
        $_SESSION['id_combo'] = $vl_values['combo'];
        $_SESSION['id_model'] = $vl_values['product'];
        $_SESSION['id_color'] = $vl_values['color'];
        $query = "SELECT * FROM customer where email='$mail'";
        $res = $sql->query($query);
        if (count($res) == 0) {
            $data = array(
            'name' => limpiarCadena($vl_values['name']),
            'lastName' => limpiarCadena($vl_values['lastName']),
            'phone' => limpiarCadena($vl_values['phone']),
            'id_user' => limpiarCadena($vl_values['id_user']),
            'email' => limpiarCadena($vl_values['email']),
            );
            $res = $sql->insert('customer', $data);
            $_SESSION['id_user'] = $res;
        } else {
            $_SESSION['id_user'] = $res[0]['id'];
        }
        ag_RedirectTo('terms');
    } else if ($vl_action == 'mensaje-form') {
        
        $data = array(
        'name' => $vl_values['name'],
        'email' => $vl_values['email'],
        'mensaje' => $vl_values['message'],
        'tel' => $vl_values['tel'],
        );
        contactEmail($vl_values['email'], $data);
        contactEmail("liuspatt@gmail.com", $data);
        
        //        ag_RedirectTo('confirm-test-drive');
        ag_pushMsg('Recibimos su mensaje en breve nos comunicaremos con usted.');
        //        require_once('cm_zend_desk.php');
        //        newTicket($vl_values['email'],$vl_values['message']);
    }
}