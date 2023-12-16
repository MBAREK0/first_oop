
<?php
    include '../controls/userinfo.php';
    // Create an object of DatabaseOffer
    $dbsOffer = new DatabaseOffer;
    
    // Fetch all job offers
    $jobOffers = $dbsOffer->getJobOffer($_GET['upofferid']);
?>    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update</title>
    <link rel="stylesheet" href="styles/style.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
<div class="container mt-5">
        <div class="modal-content" >
            <form   action="userinfo.php?updateid=<?php echo $_GET['upofferid'];?>" method="POST" enctype="multipart/form-data">
                <!-- 2 column grid layout with text inputs for the first and last names -->
               
                <div class="mb-4">
                      <label class="form-label" >Offre title</label>
                      <input type="text" class="form-control task-desc"  value="<?php echo $jobOffers['title']; ?>"name ="title">
                    
                </div>
                 <div class="mb-4">
                <label for="image">Select Image:</label>
                <input type="file" name="file" id="file-inp"  accept=".jpg, .png,.jpeg,.gif" value="<?php echo $jobOffers['img']; ?>" style ="background-color:grey;"> <!-- u muse now how can you put the value of img here  -->
                                                                                   
                </div> 
                <div class="mb-4">
                      <label class="form-label" >Offre description</label>
                      <input type="text" class="form-control task-desc" value="<?php echo $jobOffers['description']; ?>"name ="description" >
                    
                </div>
                <div class="mb-4">
                      <label class="form-label" >Offre company</label>
                      <input type="text" class="form-control task-desc" value="<?php echo $jobOffers['company']; ?> "name ="company" >
                    
                </div>
                <div class="mb-4">
                      <label class="form-label" >Offre Location</label>
                      <input type="text" class="form-control task-desc" value="<?php echo $jobOffers['location']; ?> " name="Location">
                    
                </div>
           

                <div class="d-flex w-100 justify-content-center">
               
               
               <input type="Submit" class="btn  btn-block mb-4 " value="update" name="updateoffersubmit" style="background-color:#20c997; margin-right:10px;">
				
               
                <div class="btn btn-danger btn-block mb-4 annuler">Annuler</div>
                </div>
             
              </form>
                
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</html>