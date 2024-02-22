<?php
if(isset($_POST['submit'])){
    $email = $_POST['adminUsername'];
    $pass = $_POST['adminPassword'];
    $invalid = false;
    if($email=="rajatghanate7@gmail.com" && $pass == "rajat123g"){
        //echo $email;
        //echo $pass;
        echo "new page";
    }
    else{
        $invalid = true;
    }
}

?>