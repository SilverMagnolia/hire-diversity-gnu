
<?php
if (!defined('_GNUBOARD_')) exit;
//SIMA 20190218
//include_once(GML_PHPMAILER_PATH.'/class.phpmailer.php');
include_once(GML_ADMIN_PATH.'/class.phpmailer.php'); // adm 폴더 안으로 class.phpmailer.php 를 배치한 예제임.
include_once(GML_ADMIN_PATH.'/class.smtp.php'); // adm 폴더 안으로 class.smtp.php 를 배치한 예제임.

// 메일 보내기 (파일 여러개 첨부 가능)
// type : text=0, html=1, text+html=2
function mailer($fname, $fmail, $to, $subject, $content, $type=0, $file="", $cc="", $bcc="")
{
    global $config;
    global $g5;

    // 메일발송 사용을 하지 않는다면
    if (!$config['cf_email_use']) return;

    if ($type != 1)
        $content = nl2br($content);

						try
						{
							$mail->Host = "For_Abuser"; // 예외를 고려하여, Host 명을 임의의 값으로 설정함.

							$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
							if (defined('GML_SMTP') && GML_SMTP) {
							$mail->isSMTP();
							$mail->SMTPAuth = true;
							$mail->SMTPSecure = "ssl";
							$mail->Host = GML_SMTP;
							$mail->Port = GML_SMTP_PORT;
							$mail->Username = $fmail;
							$mail->Password = "Bluepizza";
							}
							//$mail->SMTPDebug  = 2; // enables SMTP debug information, 오류 메시지를 보기 위해서는 주석을 해제하고 2 로 설정할 것. 오류 메시지는 "회원메일발송 > 테스트" 버튼을 실행했을 때에만 나타남.
							$mail->SetFrom("$fmail", 'Hirediversity Team');
							$mail->AddReplyTo("$fmail", 'Hirediversity Team');
							$mail->CharSet = "UTF-8"; // class.phpmailer.php 의 기본값이 iso-8859-1 이므로, UTF-8 로 변경함.
							$mail->Encoding = "base64"; // 기본값이 8bit 이므로, base64로 변경함.
							$mail->Subject = $subject;
							$mail->AltBody = ""; // optional, comment out and test
							$mail->MsgHTML($content);
							$mail->AddAddress($to);

							if ($cc)
								$mail->AddCC($cc);
							if ($bcc)
								$mail->AddBCC($bcc);
							//print_r2($file); exit;
							if ($file != "") {
								foreach ($file as $f) {
									$mail->AddAttachment($f['path'], $f['name']);
								}
							}

							if($mail->Send())
							{
							  $message = "메일을 발송했습니다.<p></p>\n";
							  return true;
							}
						}

						catch(phpmailerException $e)
						{
						  $error = $e->errorMessage(); //Pretty error messages from PHPMailer
						  return false;
						}
						catch (Exception $e)
						{
						  $error = $e->getMessage(); //Boring error messages from anything else!
						  return false;
						}

						return false;


}

// 파일을 첨부함
function attach_file($filename, $tmp_name)
{
    // 서버에 업로드 되는 파일은 확장자를 주지 않는다. (보안 취약점)
    $dest_file = GML_DATA_PATH.'/tmp/'.str_replace('/', '_', $tmp_name);
    move_uploaded_file($tmp_name, $dest_file);

    $tmpfile = array("name" => $filename, "path" => $dest_file);
    return $tmpfile;
}


?>
