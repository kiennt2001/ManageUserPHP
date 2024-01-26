<!-- Các hàm xử lý liên quan đến database -->
<?php
    if(!defined('_CODE')){
        die('Access denied.....');
    }

    function query($sql, $data =[], $check =false){
        global $connect;
        $result = false;
        try{
            $statement = $connect -> prepare($sql); //Biến $connect là 1 object PDO
    
            if(!empty($data)){
                $result = $statement -> execute($data);
            }else{
                $result = $statement -> execute();
            }
        }catch(Exception $ex){
            echo $ex->getMessage().'<br>';
            echo 'File: '.$ex->getFile().'<br>';
            echo 'Line: '.$ex->getLine().'<br>';
            die();
        }
        //$check là true nếu câu truy vấn là select
        if($check){
            return $statement;
        }
        return $result;
    }

    function insert($table, $data){
        /*
            $sql = "INSERT INTO tên_bảng(tên_trường_1, tên_trường_2) VALUES(:tên_trường_1, :tên_trường_2)";
            $data = [
                'tên trường' => Giá trị
            ]
        */
        $key = array_keys($data); // Mảng các tên trường
        $truong = implode(',', $key); //Nối các tên trường thành chuỗi cách bởi dấu ','
        $valueTable = ':'.implode(',:', $key); // Nối các giá trị thành dạng :[trường 1],:[trường 2] để đưa vào values

        $sql = "INSERT INTO $table($truong) VALUES($valueTable)";
        $result = query($sql, $data);
        return $result;
    }

    function update($table, $data, $where = ''){
        $update = '';
        foreach($data as $key => $value){
            $update .= $key .'= :'. $key .',';
        }
        $update = trim($update,',');

        if(!empty($where)){
            $sql = "UPDATE $table SET $update WHERE $where";
        }else{
            $sql = "UPDATE $table SET $update";
        }

        $result = query($sql, $data);
        return $result;
    }

    function delete($table, $where = ""){
        if(empty($where)){
            $sql = "DELETE FROM $table";
        }else{
            $sql = "DELETE FROM $table WHERE $where";
        }

        $result = query($sql);
        return $result;
    }

    //Lấy nhiều dòng dữ liệu
    function getRaw($sql){
        $result = query($sql, '', true);
        if(is_object($result)){
            $dataFetch = $result -> fetchAll(PDO::FETCH_ASSOC);
        }
        return $dataFetch;
    }

    //Lấy 1 dòng dữ liệu
    function oneRaw($sql){
        $result = query($sql, '', true);
        if(is_object($result)){
            $dataFetch = $result -> fetch(PDO::FETCH_ASSOC);
        }
        return $dataFetch;
    }

    //Đếm số dòng dữ liệu
    function getRows($sql){
        $result = query($sql, '', true);
        if(!empty($result)){
            return $result -> rowCount();
        }
        return 0;
    }
?>