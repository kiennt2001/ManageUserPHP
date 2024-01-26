<!-- Quên mật khẩu -->
<?php
    //Không cho truy cập trực tiếp trên đường dẫn mà phải qua get
    if(!defined('_CODE')){
        die('Access denied.....');
    }

    //Đổi title linh động cho file header
    $data = [
        'pageTitle'=> 'Quên mật khẩu',
    ];

    //Nhúng file header thông qua hàm trong file function.php
    layouts('header-login', $data);

    if(isPost()){
        $filterAll = filter();
        $email = $filterAll['email'];
        if(!empty($email)){
            $queryUser = oneRaw("SELECT id FROM users WHERE email='$email'");
            if(!empty($queryUser)){
                $userId = $queryUser['id'];

                //Tạo forgotToken
                $forgotToken = sha1(uniqid().time());
                $dataUpdate = [
                    'forgotToken' => $forgotToken,
                ];
                $updateToken = update('users', $dataUpdate, "id=$userId");
                if($updateToken){
                    //Tạo link reset password gửi tới hệ thống
                    $linkReset =_WEB_HOST.'?module=auth&action=reset&token='.$forgotToken; 
                    $subject = 'Thay đổi mật khẩu';
                    $content = 'Vui lòng click vào link sau để thay đổi mật khẩu của bạn:<br>'.$linkReset.'<br>';
                    $sendMail = sendMail($email, $subject, $content);
                    if($sendMail){
                        setFlashData('msg','Vui lòng kiểm tra email để thay đổi mật khẩu!');
                        setFlashData('msg_type','success');
                    }else{
                        setFlashData('msg','Lỗi hệ thống. Vui lòng thử lại sau!');
                        setFlashData('msg_type','danger');
                    }
                }else{
                    setFlashData('msg','Lỗi hệ thống. Vui lòng thử lại sau!');
                    setFlashData('msg_type','danger');
                }
            }else{
                setFlashData('msg','Email này không tồn tại!');
                setFlashData('msg_type','danger');
            }
        }else{
            setFlashData('msg','Vui lòng nhập email!');
            setFlashData('msg_type','danger');
        }
    }

    $msg = getFlashData('msg');
    $msgType = getFlashData('msg_type');
?>

<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <h2 class="text-center text-uppercase">Quên mật khẩu</h2>
        <?php
            if(!empty($msg)){
                getSmg($msg, $msgType);
            }
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ email">
            </div>
            
            <button type="submit" class="mg-btn btn btn-primary btn-block">Gửi</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=login">Đăng nhập lại</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
        </form>
    </div>
</div>

<?php
    layouts('footer-login');
?>