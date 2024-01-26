<!-- Đăng nhập -->
<?php
    //Không cho truy cập trực tiếp trên đường dẫn mà phải qua get
    if(!defined('_CODE')){
        die('Access denied.....');
    }

    //Đổi title linh động cho file header
    $data = [
        'pageTitle'=> 'Đăng nhập tài khoản',
    ];

    //Nhúng file header thông qua hàm trong file function.php
    layouts('header-login', $data);

    //Kiểm tra đã có đăng nhập chưa
    if(isLogin()){
        redirect('?module=home&action=dashboard');
    }

    //Kiểm tra thông tin đăng nhập
    if(isPost()){
        $filterAll = filter();

        //Đã nhập đủ
        if(!empty(trim($filterAll['email'])) && !empty($filterAll['password'])){
            //Kiểm tra thông tin
            $email = $filterAll['email'];
            $password = $filterAll['password'];

            $userQuery = oneRaw("SELECT password, id FROM users WHERE email = '$email'");
            if(!empty($userQuery)){
                $passwordHash = $userQuery["password"];
                $userId = $userQuery["id"];
                if(password_verify($password, $passwordHash)){

                    //Kiểm tra tài khoản đã đăng nhập ở chỗ khác không
                    $userLogin = getRows("SELECT * FROM tokenlogin WHERE user_Id = $userId");
                    if($userLogin>0){
                        setFlashData('msg','Tài khoản đang được đăng nhập ở một nơi khác!');
                        setFlashData('msg_type','danger');
                        redirect('?module=auth&action=login');
                    }else{
                        //Tạo token login
                        $tokenLogin = sha1(uniqid().time());

                        //Insert vào bảng tokenlogin
                        $dataInsert =[
                            "user_Id"=> $userId,
                            "token"=> $tokenLogin,
                            "create_at" => date("Y-m-d H:i:s"),
                        ];

                        $insertStatus = insert("tokenlogin", $dataInsert);
                        if($insertStatus){
                            //Insert thành công

                            //Lưu tokenlogin vào session
                            setSession("tokenLogin", $tokenLogin);

                            redirect('?module=home&action=dashboard');

                        }else{
                            setFlashData('msg','Không thể đăng nhập. Vui lòng thử lại sau!');
                            setFlashData('msg_type','danger');
                        }
                    }
                }else{
                    setFlashData('msg','Mật khẩu không chính xác!');
                    setFlashData('msg_type','danger');
                }
            }else{
                setFlashData('msg','Email không tồn tại!');
                setFlashData('msg_type','danger');
            }
        }else{
            setFlashData('msg','Vui lòng nhập đầy đủ cả email và mật khẩu!');
            setFlashData('msg_type','danger');            
        }
        redirect('?module=auth&action=login');
    }

    $msg = getFlashData('msg');
    $msgType = getFlashData('msg_type');
?>

<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <h2 class="text-center text-uppercase">Đăng nhập quản lý users</h2>
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
            <div class="form-group mg-form">
                <label for="">Password</label>
                <input name="password" type="password" class="form-control" placeholder="Mật khẩu">
            </div>
            
            <button type="submit" class="mg-btn btn btn-primary btn-block btn-login">Đăng nhập</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=forgot">Quên mật khẩu</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
        </form>
    </div>
</div>

<?php
    layouts('footer-login');
?>