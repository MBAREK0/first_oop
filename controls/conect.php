
<?php
class conection {
    static function   getCon() {
        return mysqli_connect('localhost','root','','jop');
    }
}




//   $AfficherResult_student_name = mysqli_query(conection::getCon(), "SELECT user_name FROM personne WHERE user_id=1");
//   echo print_r($AfficherResult_student_name) ;
 