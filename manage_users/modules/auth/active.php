<!-- Kích hoạt tài khoản -->
<?php
    if(!defined('_CODE')){
        die('Access denied.....');
    }

    $data = [
        'pageTitle'=> 'Kích hoạt tài khoản',
    ];

    //Nhúng file header thông qua hàm trong file function.php
    layouts('header-login', $data);

    $token = filter()['token']; //token này lấy được trên url khi ấn vào link kích hoạt được gửi trong mail
    if(!empty($token)){
        $tokenQuery = oneRaw("SELECT id FROM users WHERE activeToken='$token'");
        if(!empty($tokenQuery)){
            $userId = $tokenQuery['id'];
            $dataUpdate = [
                'status' => 1,
                'activeToken' => null
            ];
            $updateStatus = update('users', $dataUpdate, "id=$userId");
            if($updateStatus){
                setFlashData("msg","Kích hoạt tài khoản thành công. Bạn có thể đăng nhập ngay bây giờ!");
                setFlashData("msg_type","success");
            }else{
                setFlashData("msg","Kích hoạt tài khoản không thành công. Vui lòng liên hệ admin!");
                setFlashData("msg_type","danger");
            }
            redirect('?module=auth&action=login');
        }else{
            getSmg('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
        }
    }else{
        getSmg('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
    }
?>

<h1>ACTIVE</h1>

<?php
    layouts('footer-login');
?>