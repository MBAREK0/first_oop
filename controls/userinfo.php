
<?php 

require 'conect.php';

class Database extends conection {

    public $connection;  //you should be make this varible privat after finiched the project 
    public function __construct() {
        $this->connection = conection::getCon();
    }

    public function closeConnection() {
        $this->connection->close();
    }
}// -----------------------------------------------------------------------------------------------------------------</ CLOSE CLASS >

// /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                          Create DatabaseAuthantification class //    
// /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

class DatabaseAuthantification  extends Database{
    
    public $role;
 

    public function insertRegister($username, $email, $password, $role) {
        $sql = "INSERT INTO users (username, useremail, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
    
        // Check if prepared statement creation was successful
        if ($stmt == false) {
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
             echo'-------------------------->'.$row['role'];
                $_SESSION['email'] = $row['useremail'] ;
                $_SESSION['role'] = $this->role ;

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
    


}// -----------------------------------------------------------------------------------------------------------------</ CLOSE CLASS >

// /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                     Create DatabaseOffer class //    
// /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

class DatabaseOffer extends Database{
        
public function insertJobOffer($title, $description, $company, $location,$filename) {
        $sql = "INSERT INTO job_offers (title, description, company, location,img) VALUES ('$title', '$description', '$company', '$location','$filename')";
        
        if ($this->connection->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllJobOffers() {
        $sql = "SELECT * FROM job_offers";
        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }
    public function deleteOffer($id){
        $del_req  = "DELETE FROM job_offers WHERE id =$id";
        $del_result = $this->connection->query($del_req);
        if ($del_result){
            return true;
        }
        else {
            return false;
        }
    }
    public function updateOffer($title, $description, $company, $location,$filename,$id){
        $update_sql = "UPDATE job_offers SET title = '$title', description = '$description', company = '$company', location = '$location', img = '$filename' WHERE id = $id";

        $update_result = $this->connection->query($update_sql);
        if ($update_result){
            return true;
        }
        else {
            return false;
        }
        
    
    }
    public function getJobOffer($id) {
        $upsql = "SELECT * FROM job_offers WHERE id =$id";
        $upresult = $this->connection->query($upsql);

        if ($upresult->num_rows > 0) {
            return $upresult->fetch_assoc();
        } else {
            return [];
        }
    }

}// -----------------------------------------------------------------------------------------------------------------</ CLOSE CLASS >


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

        $database = new DatabaseAuthantification();

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
    $database = new DatabaseAuthantification();

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

    $rep = "";
    $folder = "uploads/";
    
    if (!empty($_FILES['file']['name'])) {
        $image = basename($_FILES['file']['name']);
        $filename = uniqid() . $image;
        $filePath = $folder . $filename;
        $fileType = pathinfo($image,PATHINFO_EXTENSION);
        $formats = array('jpg','png','jpeg','gif');
        if (in_array($fileType,$formats)) {
            if (move_uploaded_file($_FILES['file']['tmp_name'],$filePath)) {
                echo'ok';
                
              
            }
        }
    }

    $database = new DatabaseOffer();

    if ($database->insertJobOffer($title, $description, $company, $location,$filename)) {
        echo "Job offer inserted successfully.";
    } else {
        echo "Error inserting job offer.";
    }

    $database->closeConnection();
}

// /////////////////////////////////////////////////////////////////
//                                            delete a job offer  //    
// /////////////////////////////////////////////////////////////////

    if(isset($_GET['offerid'])){
        $del_id=$_GET['offerid'];
        $deletOffer= new DatabaseOffer();
       if( $deletOffer->deleteOffer($del_id)){
        header("Location:../jobease-php-oop-master/index.php");
        exit();
       }
    }

// /////////////////////////////////////////////////////////////////
//                                            update a job offer  //    
// /////////////////////////////////////////////////////////////////

    if ( isset($_POST['updateoffersubmit'])) {
      
        $title = $_POST['title'];
        $description = $_POST['description'];
        $company = $_POST['company'];
        $location = $_POST['Location'];
    
        $rep = "";
        $folder = "uploads/";
        
        if (!empty($_FILES['file']['name'])) {
            $image = basename($_FILES['file']['name']);
            $filename = uniqid() . $image;
            $filePath = $folder . $filename;
            $fileType = pathinfo($image,PATHINFO_EXTENSION);
            $formats = array('jpg','png','jpeg','gif');
            if (in_array($fileType,$formats)) {
                if (move_uploaded_file($_FILES['file']['tmp_name'],$filePath)) {
                    echo'ok';
                    
                  
                }
            }
        }
    
        $updateOffer = new DatabaseOffer();
       if (isset($_GET['updateid'])){
        $update_id=$_GET['updateid'];

       }
        if ($updateOffer->updateOffer($title, $description, $company, $location,$filename,$update_id)) {
            echo "Job offer updated successfully.";
        } else {
            echo "Error inserting job offer.";
        }
    
        $updateOffer->closeConnection();
    }
    
?>

    
   

