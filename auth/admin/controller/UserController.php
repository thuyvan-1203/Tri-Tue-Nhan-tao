<?php
    require_once(__DIR__."/../../../database/connect.php");
    class UserController{
        public function getAll(){
            global $conn;
            $sql = "select * from User"; 
            return mysqli_query($conn,$sql);
        }
        public function getById($id){
            global $conn;
            $sql = "select * from User where id=$id"; 
            return mysqli_query($conn,$sql)->fetch_assoc();
        }
        public function deleteById($id){
            global $conn;
            $sql = "delete from User where id=$id"; 
            mysqli_query($conn,$sql);
        }
        public function updateById($id, $fullname, $email, $phonenumber, $address, $role){
            global $conn;
            $sql = "UPDATE User SET full_name='$fullname', email='$email', phone_number='$phonenumber', address='$address', role_id=$role WHERE id=$id"; 
            mysqli_query($conn, $sql);
        }
    }
?>