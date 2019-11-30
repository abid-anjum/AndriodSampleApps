public function StoreUserInfo($name, $email, $password, $gender, $age) {
        $hash = $this->hashFunction($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
 
        $stmt = $this->conn->prepare("INSERT INTO android_php_post(name, email, encrypted_password, salt, gender, age) VALUES(?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $encrypted_password, $salt, $gender, $age);
        $result = $stmt->execute();
        $stmt->close();
 
        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT name, email, encrypted_password, salt, gender, age FROM android_php_post WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt-> bind_result($token2,$token3,$token4,$token5,$token6,$token7);
 
            while ( $stmt-> fetch() ) {
               $user["name"] = $token2;
               $user["email"] = $token3;
               $user["gender"] = $token6;
               $user["age"] = $token7;
            }
            $stmt->close();
            return $user;
        } else {
          return false;
        }
    }
 
    public function hashFunction($password) {
 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }