<?php
    if(!defined('_CODE')){
        die('Access denied.....');
    }

    $data = [
        'pageTitle'=> 'Danh sách người dùng',
    ];

    //Nhúng file header thông qua hàm trong file function.php
    layouts('header', $data);

    if(!isLogin()){
        redirect('?module=auth&action=login');
    }

    //Truy vấn dữ liệu từ database
    $listUsers = getRaw("SELECT * FROM users ORDER BY update_at");
    // echo '<pre>';
    // print_r($listUsers);
    // echo '</pre>';

    $smg = getFlashData('smg');
    $smg_type = getFlashData('smg_type');

?>

<div class="container">
    <hr>
    <h2>Quản lý người dùng</h2>
    <p>
        <a href="?module=users&action=add" class="btn btn-success btn-sm">Thêm người dùng <i class="fa-solid fa-plus"></i></a>
    </p>
    <?php
            if(!empty($smg)){
                getSmg($smg, $smg_type);
            }
        ?>
    <table class="table table-bordered">
        <thead>
            <th>STT</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Trạng thái</th>
            <th width="5%">Sửa</th>
            <th width="5%">Xóa</th>
        </thead>
        <tbody>
            <?php 
                if(!empty($listUsers)):
                    $count = 0; //Lấy STT
                    foreach($listUsers as $user):
                        $count++;
            ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo $user['fullname']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['phone']; ?></td>
                            <td><?php echo $user['status']?'<button class="btn btn-success btn-sm">Đã kích hoạt</button>'
                            :'<button class="btn btn-danger btn-sm">Chưa kích hoạt</button>'; ?></td>
                            <td><a href="<?php echo _WEB_HOST.'?module=users&action=edit&id='.$user['id'] ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a></td>
                            <td><a href="<?php echo _WEB_HOST.'?module=users&action=delete&id='.$user['id'] ?>" onclick="return confirm('Bạn có chắc chắn xóa?')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a></td>
                        </tr>
            <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td colspan="7"><div class="alert alert-danger text-center">Không có người dùng nào!</div></td>
                    </tr>
                    <?php
                endif;
            ?>
        </tbody>
    </table>
</div>
<?php 
    layouts('footer');
?>