<!-- Các hàm xử lý chung cho project -->
<?php
    if(!defined('_CODE')){
        die('Access denied.....');
    }

    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    function layouts($layoutName = 'header', $data=[]){
        $path = _WEB_PATH_TEMPLATE.'/layout/'.$layoutName.'.php';
        if(file_exists($path)){
            require_once($path);
        }
    }

    //Hàm gửi mail
    function sendMail($to, $subject, $content){
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'kientest2001@gmail.com';                     //SMTP username
            $mail->Password   = 'eizr titz wxik mqcu';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('kientest2001@gmail.com', 'KienAdmin');
            $mail->addAddress($to);     //Add a recipient

            //Content
            $mail -> CharSet = "UTF-8";
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $content;

            //PHPMailer SSL certificate
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $sendMail = $mail->send();
            if($sendMail){
                return $sendMail;
            }          
        } catch (Exception $e) {
            echo "Gửi mail thất bại. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    //Hàm kiểm tra phương thức GET
    function isGet(){
        if($_SERVER["REQUEST_METHOD"]=="GET"){
            return true;
        }
        return false;
    }

     //Hàm kiểm tra phương thức POST
     function isPost(){
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            return true;
        }
        return false;
    }

    //Hàm filter lọc dữ liệu
    function filter(){
        $filterArr = [];
        if(isGet()){
            //Xử lý dữ liệu trước khi hiển thị ra
            if(!empty($_GET)){
                foreach($_GET as $key => $value){
                    $key = strip_tags($key);
                    if(is_array($value)){
                        $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    }else{
                        $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
        if(isPost()){
            //Xử lý dữ liệu trước khi hiển thị ra
            if(!empty($_POST)){
                foreach($_POST as $key => $value){
                    $key = strip_tags($key);
                    if(is_array($value)){
                        $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    }else{
                        $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
        return $filterArr;
    }

    //Kiểm tra email
    function isEmail($email){
        $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
        return $checkEmail;
    }

    //Kiểm tra số nguyên
    function isNumberInt($number){
        $checkInt = filter_var($number, FILTER_VALIDATE_INT);
        return $checkInt;
    }

    //Kiểm tra số thực
    function isNumberFloat($number){
        $checkFloat = filter_var($number, FILTER_VALIDATE_FLOAT);
        return $checkFloat;
    }
    
    //Kiểm tra số điện thoại: Bắt đầu là 0 và 9 số sau là số nguyên
    function isPhone( $phone ){
        $checkZero = false;
        if($phone[0] == '0'){
            $checkZero = true;
            $phone = substr($phone,1);
        }

        $checkNumber = false;
        if(isNumberInt($phone) && strlen($phone)  == 9){
            $checkNumber = true;
        }

        return $checkZero && $checkNumber;
    }

    //Thông báo lỗi hoặc thành công
    function getSmg($smg, $type = 'success'){
        echo '<div class="alert alert-'.$type.'">';
        echo $smg; 
        echo '</div>';
    }

    //Hàm chuyển hướng: Không hiện ra thông báo khi load trang
    function redirect($path = 'index.php'){
        header("Location: $path");
        exit;
    }

    //Hàm thông báo lỗi
    function formError($fileName, $beforeHTML = "", $afterHTML = "", $errors){
        return ((!empty($errors[$fileName])) ? $beforeHTML.reset($errors[$fileName]).$afterHTML : null);
    }

    //Hàm hiển thị dữ liệu cũ
    function old($fileName, $oldData){
        return (!empty($oldData[$fileName])) ? $oldData[$fileName] : null;
    }

    //Kiểm tra trạng thái đăng nhập
    function isLogin(){
        $tokenLogin = getSession("tokenLogin");
        $checkLogin = false;
        if($tokenLogin){
            //Kiểm tra token có giống trong database không
            $queryToken = oneRaw("SELECT user_Id FROM tokenlogin WHERE token = '$tokenLogin'");
            if(!empty($queryToken)){
                $checkLogin = true;
            }else{
                removeSession("tokenLogin");
            }
        }
        return $checkLogin;
    }
?>