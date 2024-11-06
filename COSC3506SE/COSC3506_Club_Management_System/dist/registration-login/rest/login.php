<?php
class Login {

    private $conn;
    private $categoryTable;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->tableName = 'users';
    }

    public function validate($email, $password) {

        $error = false;
        $errMsg = null;

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
   
    public function getLogin() {

       
        $data = json_decode(file_get_contents("php://input"), true);
       
        $email = $data['email'];
        $password = $data['password'];
    
        $validate = $this->validate($email, $password);

        if (!$validate['error']){

           
            $query = "SELECT id, firstName, lastName, gender FROM ";
            $query .= $this->tableName; 
            $query .= " WHERE email=? AND password=?";

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ss", $email, $password);
        
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $rows= $result->fetch_assoc();
            
                if ($result->num_rows > 0) {
                    $token = md5(uniqid());

                    $query = "UPDATE ";
                    $query .= $this->tableName; 
                    $query .= " SET token =? ";
                    $query .= " WHERE id = ?";
        
                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param("ss", $token, $rows['id']);
                    $stmt->execute();

                    $status = 200;
                    $msg = "Logged in successfully";
                    $data = $rows;
                    $data = [
                        'status' => 200,
                        'msg' => "Logged in successfully",
                        'data' => $rows
                    ];
                    
                } else {

                    $data = [
                        'status' => 404,
                        'msg' => "Invalid User",
                        'data' => []
                    ];
                }
            } 

        } else {
            $data = [
                'status' => 401,
                'msg' => $validate['errMsg'],
                'data' => []
            ];
        }

        return json_encode($data);
    }
 
    
}



?>