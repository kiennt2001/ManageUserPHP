<!-- Đăng ký -->
<?php
    if(!defined('_CODE')){
        die('Access denied.....');
    }

    $data = [
        'pageTitle'=> 'Đăng ký tài khoản',
    ];

    if(isPost()){
        $filterAll = filter();
        // echo '<pre>';
        // print_r($filterAll);
        // echo '</pre>';
        $errors = [];

        if(empty($filterAll['fullname'])){
            $errors['fullname']['required'] = 'Họ tên bắt buộc phải nhập';
        }else{
            if(strlen($filterAll['fullname']) < 5){
                $errors['fullname']['min'] = 'Họ tên phải có ít nhất 5 ký tự';
            }
        }

        if(empty($filterAll['email'])){
            $errors['email']['required'] = 'Email bắt buộc phải nhập';
        }else{
            $email = $filterAll['email'];
            $sql = "SELECT * FROM users WHERE email = '$email'";
            if(getRows($sql) > 0){
                $errors["email"]["unique"] = "Email đã tồn tại";
            }
        }

        if(empty($filterAll['phone'])){
            $errors['phone']['required'] = 'Số điện thoại bắt buộc phải nhập';
        }else{
            if(!isPhone($filterAll['phone'])){
                $errors['phone']['isPhone'] = 'Số điện thoại không hợp lệ';
            }
        }

        if(empty($filterAll['password'])){
            $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
        }else{
            if(strlen($filterAll['password']) < 8){
                $errors['password']['min'] = 'Mật khẩu phải có ít nhất 8 ký tự';
            }
        }

        if(empty($filterAll['password_confirm'])){
            $errors['password_confirm']['required'] = 'Bạn chưa xác nhận lại mật khẩu';
        }else{
            if($filterAll['password_confirm'] != $filterAll['password']){
                $errors['password_confirm']['match'] = 'Không trùng khớp với mật khẩu đã nhập.';
            }
        }
        
        if(empty($errors)){
            $activeToken = sha1(uniqid() . time());
            $dataInsert = [
                'fullname' => $filterAll['fullname'],
                'email' => $filterAll['email'],
                'phone' => $filterAll['phone'],
                'password' => password_hash($filterAll['password'], PASSWORD_DEFAULT),
                'activeToken' => $activeToken,
                'create_at' => date('Y-m-d H:i:s'),
            ];
            $inserStatus = insert('users', $dataInsert);
            if($inserStatus){
                //Tạo link kích hoạt
                $linkActive = _WEB_HOST.'?module=auth&action=active&token='.$activeToken;
                //Gửi mail kích hoạt
                $subject = '[Manager_Users]-'.$filterAll['fullname'].'-Kích hoạt tài khoản';
                $content = 'Chúc mừng '.$filterAll['fullname'].' đã đăng ký tài khoản thành công! <br>';
                $content .= 'Vui lòng click vào link sau để kích hoạt tài khoản:<br>';
                $content .= $linkActive.'<br>';
                $content .= 'Trân trọng cám ơn.<br>';
                //Gửi mail
                $sendMail = sendMail($filterAll['email'], $subject, $content);
                if($sendMail){
                    setFlashData('smg', 'Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt tài khoản');
                    setFlashData('smg_type', 'success');
                }else{
                    setFlashData('smg', 'Hệ thống đang gặp sự cố! Vui lòng thử lại sau.');
                    setFlashData('smg_type', 'danger');
                }
            }else{
                setFlashData('smg', 'Đăng ký không thành công!!');
                setFlashData('smg_type', 'danger');
            }
            redirect('?module=auth&action=login');
        }else{
            setFlashData('smg', 'Vui lòng kiểm tra lại dữ liệu!!');
            setFlashData('smg_type', 'danger');
            setFlashData('errors',$errors);
            setFlashData('old',$filterAll);
            redirect('?module=auth&action=register');
        }
    }
    layouts('header-login', $data);

    $smg = getFlashData('smg');
    $smg_type = getFlashData('smg_type');
    $errors = getFlashData('errors');
    $old = getFlashData('old');
?>

<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <h2 class="text-center text-uppercase">Đăng ký tài khoản users</h2>
        <?php
            if(!empty($smg)){
                getSmg($smg, $smg_type);
            }
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Họ tên</label>
                <input name="fullname" type="fullname" class="form-control" placeholder="Họ tên" value="<?php
                echo old('fullname', $old) ?>">
                <?php echo formError('fullname', '<span class="error">', '</span>', $errors) ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ email" value="<?php
                echo old('email', $old) ?>">
                <?php echo formError('email', '<span class="error">', '</span>', $errors) ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Số điện thoại</label>
                <input name="phone" type="number" class="form-control" placeholder="Số điện thoại" value="<?php
                echo old('phone', $old) ?>">
                <?php echo formError('phone', '<span class="error">', '</span>', $errors) ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Password</label>
                <input name="password" type="password" class="form-control" placeholder="Mật khẩu">
                <?php echo formError('password', '<span class="error">', '</span>', $errors) ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Nhập lại password</label>
                <input name="password_confirm" type="password" class="form-control" placeholder="Nhập lại mật khẩu">
                <?php echo formError('password_confirm', '<span class="error">', '</span>', $errors) ?>
            </div>
            
            <button type="submit" class="mg-btn btn btn-primary btn-block">Đăng ký</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=login">Đăng nhập</a></p>
        </form>
    </div>
</div>

<?php
    layouts('footer-login');
?>