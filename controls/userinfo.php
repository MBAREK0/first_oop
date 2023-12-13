
<?php 

require 'conect.php';

// /////////////////////////////////////////////////////////////////
//                                          Create database class //    
// /////////////////////////////////////////////////////////////////

class Database {
    private $connection;
    public $role;

    public function __construct() {
        $this->connection = conection::getCon();
    }

    public function insertJobOffer($title, $description, $company, $location) {
        $sql = "INSERT INTO job_offers (title, description, company, location) VALUES ('$title', '$description', '$company', '$location')";
        
        if ($this->connection->query($sql)) {
            return true;
        } else {
            return false;
        }
    }
    public function insertRegister($username, $email, $password, $role) {
        $sql = "INSERT INTO users (username, useremail, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
    
        // Check if prepared statement creation was successful
        if ($stmt === false) {
            return "Error preparing statement: " . $this->connection->error;
        }
    
        $stmt->bind_param("ssss", $username, $email, $password, $role);
    
        // Check if bind_param was successful
        if ($stmt->execute()) {
            return true;
        } else {
            return "Error executing statement: " . $stmt->error;
        }
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE useremail = ?";
        $stmt = $this->connection->prepare($sql);
    
        // Check if prepared statement creation was successful
        if ($stmt === false) {
            return "Error preparing statement: " . $this->connection->error;
        }
    
        $stmt->bind_param("s", $email);
    
        // Check if bind_param was successful
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
    
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                session_start();
                $this->role =$row['role'];
             
                $_SESSION['email'] = $row['useremail'] ;

                // Verify the password
                if (password_verify($password, $row['password'])) {
                    return true; // Successful login
                } else {
                    return "Invalid password.";
                }
            } else {
                return "User not foundx.";
            }
        } else {
            return "Error executing statement: " . $stmt->error;
        }
    }
    


    public function closeConnection() {
        $this->connection->close();
    }
}


// /////////////////////////////////////////////////////////////////
//                   save the info of user in data base 'Register'//    
// /////////////////////////////////////////////////////////////////
if (isset($_POST['Register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password_1 = $_POST['password-1'];
    $password_2 = $_POST['password-2'];
    $role ='candidate';

    // Check if passwords match
    if ($password_1 === $password_2) {
        // Use prepared statement to prevent SQL injection
        $connection = conection::getCon();
        $password = password_hash($password_1, PASSWORD_DEFAULT);

        $database = new Database();

        $result = $database->insertRegister($username, $email, $password, $role);

        if ($result === true ) {
            echo "User inserted successfully.";
            } else {
             echo "Error inserting user: " . $result;
                }
    } else {
        echo "Error: Passwords do not match.";
    }
}

// /////////////////////////////////////////////////////////////////
//                            the code of login with check role   //    
// /////////////////////////////////////////////////////////////////


if (isset($_POST['Login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $database = new Database();

    $result = $database->login($email, $password);

    if ($result == true) {
        if ($database->role == 'admin') {
            header("Location:../jobease-php-oop-master/dashboard/dashboard.php");
            exit();
        } elseif ($database->role == 'candidate') {
            header("Location:../jobease-php-oop-master/index.php");
            exit();
        }
    } else {
        echo $result; // Output error message
    }
}

    // /////////////////////////////////////////////////////////////////
    //                                            create a job offer  //    
    // /////////////////////////////////////////////////////////////////
 


if ( isset($_POST['addoffersubmit'])) {
    session_start();
    $_SESSION['check']='secces';
    $title = $_POST['title'];
    $description = $_POST['description'];
    $company = $_POST['company'];
    $location = $_POST['Location'];

    $database = new Database();

    if ($database->insertJobOffer($title, $description, $company, $location)) {
        echo "Job offer inserted successfully.";
    } else {
        echo "Error inserting job offer.";
    }

    $database->closeConnection();
}
?>

    
   

