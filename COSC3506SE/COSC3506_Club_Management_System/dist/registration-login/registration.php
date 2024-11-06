<?php
class Registration {

    private $conn;
    private $categoryTable;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->tableName = 'users';
    }

    public function validate($firstName, $lastName, $gender, $email, $password) {

        $error = false;
        $errMsg = null;

        if(empty($firstName)) {
            $errMsg = "First Name is empty";
            $error = true;
        }
        if(empty($lastName)) {
            $errMsg = "Last Name is empty";
            $error = true;
        }
        if(empty($gender)) {
            $errMsg = "Gender is empty";
            $error = true;
        }

        if(empty($email)) {
            $errMsg = "Email is empty";
            $error = true;
        }
        if(empty($password)) {
            $errMsg = "Password is empty";
            $error = true;
        } 
        $errorInfo = [
            "error" => $error,
            "errMsg" => $errMsg
        ];

        return $errorInfo;
    }

    public function create() {

        $data = json_decode(file_get_contents("php://input"), true);
      
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $gender = $data['gender'];
        $email = $data['email'];
        $password = $data['password'];
        $check = $this->getByEmail($email);
        if(!$check) {
        $validate = $this->validate($firstName, $lastName, $gender, $email, $password);
        $success = false;

        if (!$validate['error']){

            $query = "INSERT INTO ";
            $query .= $this->tableName; 
            $query .= " (firstName, lastName, gender, email, password ) ";
            $query .= " VALUES (?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssss", $firstName, $lastName, $gender, $email, $password);
            
            if ($stmt->execute()) {
                $status = 200;
                $msg = "You are registered successfully";
            } else{
                $status = 400;
                $msg = $this->conn->error;
            }

        } else {
          $status = 401;
          $msg = $validate['errMsg'];
        }
         
    
        } else{
             $status = 401;
             $msg = "This email already registered";
        }

        $data = [
            'status' => $status,
            'msg' => $msg,
            'data' => $data
         ];
         return json_encode($data);
    }

   
    public function getByEmail($email) {

        $isExist = false;
    
        $query = "SELECT email FROM ";
        $query .= $this->tableName; 
        $query .= " WHERE email=?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
       
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            $rows= $result->fetch_assoc();
           
            if ($result->num_rows > 0) {
                $isExist = true;
            }
        } 
        
        return $isExist;
    }
 
    
}



?>