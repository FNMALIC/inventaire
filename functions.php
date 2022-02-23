<?php

// Other settings
session_start();

// Connect to the database
class Database
{
    public $DATABASE_HOST = 'localhost';
    private $DATABASE_USER = 'root';
    private $DATABASE_PASS = '';
    public $DATABASE_NAME = 'inventaire';
    public function pdo_connect_mysql()
    {
        try {
            return new PDO("mysql:host=$this->DATABASE_HOST;dbname=$this->DATABASE_NAME", $this->DATABASE_USER, $this->DATABASE_PASS);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}

// Functions for managing users
class USER
{
    private $conn;
    public function __construct()
    {
        $database = new Database();
        $db = $database->pdo_connect_mysql();
        $this->conn = $db;
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

    public function register($uname, $umail, $upass)
    {
        try {
            $super = 0;
            $new_password = password_hash($upass, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("INSERT INTO users(user_name,Super_admin,user_email,user_pass) VALUES(:uname,:sup, :umail, :upass)");
            $stmt->bindparam(":uname", $uname);
            $stmt->bindparam(":sup", $super);
            $stmt->bindparam(":umail", $umail);
            $stmt->bindparam(":upass", $new_password);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo $e->getMessage();

        }
    }
    public function register_sup($uname, $umail, $upass)
    {
        try {
            $super = 1;
            $new_password = password_hash($upass, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("INSERT INTO users(user_name,Super_admin,user_email,user_pass) VALUES(:uname,:sup, :umail, :upass)");
            $stmt->bindparam(":uname", $uname);
            $stmt->bindparam(":sup", $super);
            $stmt->bindparam(":umail", $umail);
            $stmt->bindparam(":upass", $new_password);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo $e->getMessage();

        }
    }

    public function isSuper($e)
    {
        $query = $this->conn->prepare('SELECT Super_admin FROM users WHERE user_id=:user_id');
        $query->execute([":user_id" => $e]);
        $super = $query->fetch(PDO::FETCH_ASSOC);

        foreach ($super as $key) {
            if ($key) {
                return true;
            }
        }
    }

    public function doLogin($uname, $umail, $upass)
    {
        try {
            $stmt = $this->conn->prepare("SELECT user_id, user_name, user_email, user_pass FROM users WHERE user_name=:uname OR user_email=:umail ");
            $stmt->execute(array(':uname' => $uname, ':umail' => $umail));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($stmt->rowCount() == 1) {
                if (password_verify($upass, $userRow['user_pass'])) {
                    $_SESSION['user_session'] = $userRow['user_id'];
                    return true;
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function is_loggedin()
    {
        if (isset($_SESSION['user_session'])) {
            return true;
        }
    }

    public function redirect($url)
    {
        header("Location: $url");
        exit;
    }
    public function doLogout()
    {
        unset($_SESSION['user_session']);
        return true;
    }
}

function template_footer()
{
    echo <<<EOT
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="script.js"></script>
    </body>
</html>
EOT;
}
