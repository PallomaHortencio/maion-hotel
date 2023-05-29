<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './include/PHPMailer/src/Exception.php';
require './include/PHPMailer/src/PHPMailer.php';
require './include/PHPMailer/src/SMTP.php';

$GLOBALS['debugOutput'] = [];

$data = json_decode(file_get_contents("php://input"));

$name = $data->name;
$email = $data->email;
$celular = $data->celular;
$dataIn = $data->dataIn;
$dataOut = $data->dataOut;
$people = $data->people;

if(!empty($name) && !empty($email)) {
    try {
        $mail = new PHPMailer(true);

        $body = "<h2>Pedido de Reserva</h2>";
        $body .= "Nome: $name<br />";
        $body .= "Email: $email<br />";
        $body .= "Celular: $celular <br />";
        $body .= "Data Entrada: $dataIn <br />";
        $body .= "Data Sa&#237;da: $dataOut <br />";
        $body .= "N&#250;mero de pessoasl: $people";
        $body .= "<br />";
        $body .= "--------------------------------------------------------";
        $body .= "<br />";
        $body .= "Hora e data de envio: " . date("h:m:i d/m/y");
        $body .= "<br />";
        $body .= "--------------------------------------------------------";
        $body .= "<br />";

        // Maion Server
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPDebug = 4;
        $mail->Port = 587;
        $mail->Host = "smtp.hostinger.com.br";
        $mail->Username = "contato@maionhotel.com.br";
        $mail->Password = "ct20mhbEM";
        $mail->CharSet = 'UTF-8';
        $mail->Debugoutput = function($debugOutputLine, $level) {
            $GLOBALS['debugOutput'][] = $debugOutputLine;
        };

        // Gmail Server
        // $mail->IsSMTP();
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        // $mail->Host = 'smtp.gmail.com';
        // $mail->Port = 587;
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        // $mail->SMTPAuth = true;
        // $mail->Username = 'drummond.daniel@gmail.com';
        // $mail->Password = 'e83!26ln';

        $mail->IsSendmail();

        $mail->AddReplyTo($email, $name);
        $mail->From = $email;
        $mail->FromName = $name;

        $to = "contato@maionhotel.com.br";
        $mail->AddAddress($to);
        $mail->Subject = "Pedido de Reserva - Website";

        $mail->MsgHTML($body);
        $mail->IsHTML(true);

        $mail->Send();

        http_response_code(201);

        echo json_encode(array(
            "name" => $name,
            "email" => $email,
            "dataIn" => $dataIn,
            "dataOut" => $dataOut,
            "people" => $people
        ));

    } catch (phpmailerException $e) {
        $debug_output = implode("\n", $GLOBALS['debugOutput']);
        echo $debugOutput;

        http_response_code(400);

        echo json_encode(array("message" => $debugOutput));
    }
} else {
    echo 'Error';
    $debug_output = implode("\n", $GLOBALS['debugOutput']);
    echo $debugOutput;

    http_response_code(400);

    echo json_encode(array("message" => $debugOutput));
}
?>