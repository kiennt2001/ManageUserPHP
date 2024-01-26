<!-- Thêm người dùng -->
<?php
    if(!defined('_CODE')){
        die('Access denied.....');
    }

    $data = [
        'pageTitle'=> 'Thêm người dùng',
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

            $dataInsert = [
                'fullname' => $filterAll['fullname'],
                'email' => $filterAll['email'],
                'phone' => $filterAll['phone'],
                'password' => password_hash($filterAll['password'], PASSWORD_DEFAULT),
                'status' => $filterAll['status'],
                'create_at' => date('Y-m-d H:i:s'),
            ];
            $inserStatus = insert('users', $dataInsert);
            if($inserStatus){
                setFlashData('smg', 'Thêm mới người dùng thành công!!');
                setFlashData('smg_type', 'success');
                redirect('?module=users&action=list');
            }else{
                setFlashData('smg', 'Thêm mới người dùng không thành công. Vui lòng thử lại sau!!');
                setFlashData('smg_type', 'danger');
                redirect('?module=users&action=add');
            }
        }else{
            setFlashData('smg', 'Vui lòng kiểm tra lại dữ liệu!!');
            setFlashData('smg_type', 'danger');
            setFlashData('errors',$errors);
            setFlashData('old',$filterAll);
            redirect('?module=users&action=add');
        }
    }
    layouts('header-login', $data);

    $smg = getFlashData('smg');
    $smg_type = getFlashData('smg_type');
    $errors = getFlashData('errors');
    $old = getFlashData('old');
?>

<div class="container">
    <div class="row" style="margin: 50px auto;">
        <h2 class="text-center text-uppercase">Thêm tài khoản users</h2>
        <?php
            if(!empty($smg)){
                getSmg($smg, $smg_type);
            }
        ?>
        <form action="" method="post">
            <div class="row">
                <div class="col">
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
                </div>
                <div class="col">
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
                    <div class="form-group">
                        <label for="">Trạng thái</label>
                        <select name="status" id="" class="form-control">
                            <option value="0" <?php echo (old('status', $old)==0)?'selected':false; ?>>Chưa kích hoạt</option>
                            <option value="1" <?php echo (old('status', $old)==1)?'selected':false; ?>>Đã kích hoạt</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="mg-btn btn btn-primary btn-block">Thêm người dùng</button>
            <a href="?module=users&action=list" class="mg-btn btn btn-success btn-block">Back</a>

            <hr>
        </form>
    </div>
</div>

<?php
    layouts('footer-login');
?>