<?php
class login extends model
{
    public function valid_login($username, $password)
    {
        $query = "SELECT id, password FROM user WHERE username = ?";

        if($stmt = $this->db->link()->prepare($query))
        {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();
            $num_row = $stmt->num_rows;
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();
            $stmt->close();
        }

        if ($num_row > 0 && password_verify($password, $hashed_password))
        {
           $_SESSION['loggedin'] = true;
           $_SESSION['user_id'] = $user_id;

           $response['status'] = "success";
        }
        else
        {
            $response['status'] = "error";
        }

        header('Content-type: application/json');
        echo json_encode($response);

    }

    private function unique_username($username)
    {
        $query = "SELECT username FROM user WHERE username = ?";

        if($stmt = $this->db->link()->prepare($query))
        {
            $stmt->bind_param("s",$username);
            $stmt->execute();
            $stmt->store_result();
            $num_row = $stmt->num_rows;
            $stmt->fetch();
            $stmt->close();
        }
        return ($num_row > 0) ? false : true;
    }

    public function register($username, $password)
    {
        if(self::unique_username($username))
        {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query =
                "
                    INSERT IGNORE INTO user (username, password)
                    VALUES (?,?)
                ";
            if($stmt = $this->db->link()->prepare($query))
            {
                $stmt->bind_param("ss",$username,$hashed_password);
                $stmt->execute();
                $stmt->close();
            }
            $response['status'] = "success";
        }
        else
        {
            $response['status'] = "error";
        }

        header('Content-type: application/json');
        echo json_encode($response);
    }
}