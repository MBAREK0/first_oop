
<?php 

require 'conect.php';
// /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                Create database-conection class //    
// /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
            
                $_SESSION['email'] = $row['useremail'] ;
                $_SESSION['userid'] = $row['id'] ;
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
            return "Error executing query: " . $this->connection->error;
        }
    }
    // ......................................................getAllJobOffers
    public function getAllJobOffers() {
        $sql = "SELECT * FROM job_offers";
        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }
    // ......................................................deleteOffer
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
    // ......................................................updateOffer

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
    // ......................................................getJobOffer
    public function getJobOffer($id) {
        $upsql = "SELECT * FROM job_offers WHERE id =$id";
        $upresult = $this->connection->query($upsql);

        if ($upresult->num_rows > 0) {
            return $upresult->fetch_assoc();
        } else {
            return [];
        }
    }
    // ......................................................searchOffer
    public function searchOffer($search){
        $S_sql = "SELECT * FROM job_offers WHERE title LIKE '%$search%' OR description LIKE '%$search%' OR company LIKE '%$search%' OR location LIKE '%$search%'";
        $S_result = $this->connection->query($S_sql);
        if ($S_result->num_rows > 0) {
            return $S_result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }
    // ......................................................applyOffer
    public function applyOffer($user_id,$job_id){
        $check_app_sql = "SELECT * FROM `job_applications` WHERE `user_id`='$user_id' AND `job_offer_id`='$job_id'";
        $chack_req = $this->connection->query($check_app_sql);
        if ($chack_req !== false) {
            if ($chack_req->num_rows == 0) {
                $app_sql = "INSERT INTO `job_applications`(`user_id`, `job_offer_id`) VALUES ('$user_id','$job_id')";
        
                if ($this->connection->query($app_sql)) {
                    return true;
                } else {
                    return false;
                }
              
            } else {
                return 'you are alrady applyed this jop ';
            }
        } else {
            // Handle the SQL error, for example:
            return "Error executing query: " . $this->connection->error;
        }
    }
    // ......................................................getUserNontification
    public function getUserNontification($user_id) {
      
        $get_nonti_req="SELECT `title`,`company` FROM `job_offers` J INNER JOIN `job_applications` A ON J.id = A.job_offer_id WHERE A.user_id =$user_id AND A.status='accept'";
        $Nonti = $this->connection->query($get_nonti_req);

        if ($Nonti !== false) {
            if ($Nonti->num_rows > 0) {
                return $Nonti->fetch_all(MYSQLI_ASSOC);
            } else {
                return [];
            }
        } else {
            // Handle the SQL error, for example:
            return "Error executing query: " . $this->connection->error;
        }
    }
    // ......................................................getAdminNontification
    public function getAdminNontification() {
      
        $get_non_req="SELECT username,title,status,job_offer_id,user_id FROM `job_applications`  JA INNER JOIN job_offers JO INNER JOIN users U ON JA.job_offer_id = JO.id AND JA.user_id = u.id WHERE visibility=1";
        $Non = $this->connection->query($get_non_req);

        if ($Non !== false) {
            if ($Non->num_rows > 0) {
                return $Non->fetch_all(MYSQLI_ASSOC);
            } else {
                return [];
            }
        } else {
            // Handle the SQL error, for example:
            return "Error executing query: " . $this->connection->error;
        }
    }
    // ......................................................updateStatus
    public function updateStatus($usid, $jbid){
        $update_app_sql = "UPDATE `job_applications` SET `status`='accept',`visibility`='0' WHERE `user_id`='$usid' AND `job_offer_id`='$jbid'";

        $update_app_result = $this->connection->query($update_app_sql);
        if ($update_app_result){
            return true;
        }
        else {
            return false;
        }

    } 
    // ......................................................deleteStatus
    public function deleteStatus($usid, $jbid){
        $delete_app_sql = "DELETE FROM `job_applications`  WHERE `user_id`='$usid' AND `job_offer_id`='$jbid'";

        $delete_app_result = $this->connection->query($delete_app_sql);
        if ($delete_app_result){
            return true;
        }
        else {
            return false;
        }

    } 
    

}// -----------------------------------------------------------------------------------------------------------------</ CLOSE CLASS >

     // :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::> REGISTER <:::::::::::

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

     // ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::> LOGIN WITH CHECK ROLE <:::::::::::

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
    $database->closeConnection();
}

     // :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::> CREATE A JOB OFFER <:::::::::::
 

if ( isset($_POST['addoffersubmit'])) {
    session_start();
    $_SESSION['check']='secces';
    $title = $_POST['title'];
    $description = $_POST['description'];
    $company = $_POST['company'];
    $location = $_POST['Location'];


    $folder = "uploads/";
    
    if (!empty($_FILES['file']['name'])) {
        $image = basename($_FILES['file']['name']);
        $filename = uniqid() . $image;
        $filePath = $folder . $filename;
        $fileType = pathinfo($image,PATHINFO_EXTENSION);
        $formats = array('jpg','png','jpeg','gif');
        if (in_array($fileType,$formats)) {
           move_uploaded_file($_FILES['file']['tmp_name'],$filePath);

        }
    }

    $database = new DatabaseOffer();

    if ($database->insertJobOffer($title, $description, $company, $location,$filename)) {
        header("Location:../jobease-php-oop-master/dashboard/dashboard.php");
        exit();
    } else {
        return "Error executing query: " . $database->connection->error;
    }

    $database->closeConnection();
}

     // :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::> DELETE A JOB OFFER <:::::::::::

    if(isset($_GET['offerid'])){
        $del_id=$_GET['offerid'];
        $deletOffer= new DatabaseOffer();
       if( $deletOffer->deleteOffer($del_id)){
        header("Location:../jobease-php-oop-master/index.php");
        exit();
       }
  
    }

     // :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::> UPDATE A JOB OFFER <:::::::::::

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
            header("Location:../jobease-php-oop-master/index.php");
            exit();
        } else {
            echo "Error updating job offer.";
        }
    
        $updateOffer->closeConnection();
    }
    
    // :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::> SEARCH <:::::::::::
    $S_Offer = new DatabaseOffer;
    

if(isset($_GET["term"])) {
    $search = $_GET["term"];
    $jobOffers = $S_Offer->searchOffer($search);
    session_start();
    
       
?>
           <?php foreach ($jobOffers as $offer): ?>
			<article class="postcard light green">
				<a class="postcard__img_link" href="#">
					<img class="postcard__img" src="../controls/uploads/<?php echo $offer['img']; ?>" alt="Image Title" />
				</a>
				<div class="postcard__text t-dark">
					<h3 class="postcard__title green"><a href="#"><?php echo $offer['title']; ?></a></h3>
					<div class="postcard__subtitle small">
						<time datetime="2020-05-25 12:00:00">
							<i class="fas fa-calendar-alt mr-2"></i><?php echo $offer['created_at']; ?>
						</time>
					</div>
					<div class="postcard__bar"></div>
					<div class="postcard__preview-txt"><?php echo $offer['description']; ?></div>
					<ul class="postcard__tagbox">
						<li class="tag__item"><i class="fas fa-tag mr-2"></i><?php echo $offer['company']; ?></li>
						<li class="tag__item"><i class="fas fa-clock mr-2"></i>55 mins.</li>
						<?php 
                         
						if($_SESSION['role'] === 'candidate'){
						echo '  <li class="tag__item play green">';
						echo '<a onclick="apply('.$offer['id'].')" ><i class="fas fa-play mr-2"></i>APPLY NOW</a>';
						echo '  </li>';
						}
						?>
						<?php 
                      
						if($_SESSION['role'] === 'admin'){
							echo'<a href="../controls/updateoffer.php?upofferid='.$offer['id'] .' " ><li class="tag__item"><i class="fas fa-clock mr-2"></i>update</li></a>';
							echo'<a href="../controls/userinfo.php?offerid='.$offer['id'] .' " ><li class="tag__item"><i class="fas fa-clock mr-2"></i>delete.</li></a>';
						

						
						}
						?>
					</ul>
				</div>
			</article>
			<?php endforeach;
           
        }
            
     // :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::> SEARAPLLY NOW <:::::::::::           
        if(isset($_GET['applyid'])){
            session_start();
            $user_id = $_SESSION['userid'];
            $job_id  =$_GET['applyid'];
            $app_offer = new DatabaseOffer();
        
            if ($app_offer->applyOffer( $user_id, $job_id)) {
                echo "apply successfully.";
            } else {
                echo "Error applay";
            }

            $database->closeConnection();
                    
        }
 // :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::> MODIFY STATUS <:::::::::::  
        if(isset($_GET['upUsId']) && isset($_GET['upJbId'])){
            $user=$_GET['upUsId'];
            $job=$_GET['upJbId'];
            echo'hahaha hahaha'. $user. $job.'</br>';
            $updateStatus = new DatabaseOffer();
    
        if ($updateStatus->updateStatus($user,$job)) {
            echo "Job offer updated successfully.";
        } else {
            echo "Error updating job offer.";
        }
    
        $updateStatus->closeConnection();
        }
 // :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::> DELETE STATUS <:::::::::::  
        if(isset($_GET['deUsId']) && isset($_GET['deJbId'])){
            $user=$_GET['deUsId'];
            $job=$_GET['deJbId'];
        
            $deleteStatus = new DatabaseOffer();

        if ($deleteStatus->deleteStatus($user,$job)) {
           header("Location:../jobease-php-oop-master/dashboard/candidat.php");
           exit();
        } else {
            echo "Error deleted job offer.";
        }
        $deleteStatus->closeConnection();
        }
        ?>



    
   

