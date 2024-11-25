<?php

    //Function used to check if user is logged in at every page (display in console)
    function checkLogIn(){
    if(!isset($_SESSION['loginID'])){
        //If the user is logged in, log to the console
        echo "<script>console.log('User Loggied In. LoginID: " . $_SESSION['$loginID'] . "');</script>";
    } else {
        echo "<script>console.log('User is not logged in');</script>";
    }
}