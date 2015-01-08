<?php
header('Content-Type: text/html; charset=utf-8');
require_once('captcha/recaptchalib.php');

$secret = "6LfNAPgSAAAAAEw1BKLKWJfvx9rwqMvTMiDUZIsD"; //Change it
$resp = null;
$error = null;
$reCaptcha = new ReCaptcha($secret);

if ($_POST["g-recaptcha-response"]) {
    $resp = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
}

if ($resp != null && $resp->success) {
    
    require("mail/class.phpmailer.php");
    require("mail/class.smtp.php");

    $to  = "_______";
    $bbc = "_______";
    
    $name               = $_POST['name'];
    $email              = $_POST['email'];
    $phone              = $_POST['phone'];
    $subject            = $_POST['subject'];
    $comments           = $_POST['comments'];

    $mail = new PHPMailer();
    $mail->Mailer = "smtp";
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = "mail._________"; //URL del sitio: mail.misitio.com o misitio.mx (depende)
    $mail->Port = 25; //El más común tambien puede ser 26
    $mail->Username = "__________";
    $mail->Password = "_________";
    $mail->From = "__________"; //Quien envia el correo, igual que el de $mail->Username.
    $mail->FromName = iconv('utf-8', 'iso-8859-1',$name)." | _________"; //Nombre del negocio, por ejemplo Mucha Web
    $mail->Subject = "Nuevo mensaje de contacto | ".$subject; //Puede variar, ya depende del formulario
    $mail->AddAddress($to, "_________"); //A quien se lo voy a enviar
    $mail->AddBCC($bbc); //Copia Oculta
    $mail->AddReplyTo($email, iconv('utf-8', 'iso-8859-1',$name)); //Responder a: (en este caso a quien solicita info por medio del contacto)
    
    $body  = iconv('utf-8', 'iso-8859-1', "<p><strong>Nombre:</strong> ".$name."</p>
                                           <p><strong>Correo:</strong> ".$email."</p>
                                           <p><strong>Tel:</strong> ".$phone."</p>
                                           <p><strong>Comentario:</strong> ".$comments."</p>");
    $mail->Body = $body;
    $mail->IsHTML(true);

    if (!$mail->Send()) {
        echo "<script>window.location.href = 'error.php';</script>";
    } else {
        echo "<script>window.location.href = 'gracias.php';</script>";
    } //Fin del email

} else {
    echo "<script>window.location.href = 'error.php';</script>";
    
}// Fin validación captcha
?>