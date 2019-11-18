<?php 

   

$name = "rashed";
$umID = "";
$fName = "";
$lName = "";
$title = "";
$email = "";
$phone = "";
$error = "";
$success = "";
$slots = 0;
$goodFields = false;
$added = false;
$update = "";
$cancelUpdate = "";
$mySqlError = "";

    // conect to a database

    $conn = mysqli_connect('localhost', 'mohammad', 'test1234', 'registration' );

    //check connection 

    if(!$conn){
        echo 'connection error: ' . mysqli_connect_error();
    }

    //write query for all slots

    $sql = 'SELECT slots FROM timeslots';

    $result = mysqli_query($conn, $sql);
    //fetch resultion rows an an array 

    $recievedSlots = mysqli_fetch_all($result, MYSQLI_ASSOC);

    




    if (!empty($_POST['register-form'])){
         


        if(preg_match("/^\d+$/", $_POST['umID']) and strlen($_POST['umID']) == 8 ){
            $umID = $_POST['umID'];
            
        }else{
            $error .= "<li style = 'padding-top : 5px; padding-bottom: 5px;'> UMID Error - UMID should be all numbers and 8 digits </li>" ;
        }

        if(!preg_match("/[0-9]/", $_POST['fName']) and !preg_match("/[0-9]/", $_POST['lName']) ){
            $fName = $_POST['fName'];
            $lName = $_POST['lName'];
        }else{
            $error .= "<li style = 'padding-top : 5px; padding-bottom: 5px;'> Names Error - Names should be alphabets only </li>" ;
        }

        $title = $_POST['title'];

        if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL )){
            $email = $_POST['email'];
        }else{
            $error .= "<li style = 'padding-top : 5px; padding-bottom: 5px;'> Email Error - Incorrect Format Ex- abc@gmail.com </li>" ;
        }

        if(preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['phone'])){
            $phone = $_POST['phone'];
        }else{
            $error .= "<li style = 'padding-top : 5px; padding-bottom: 5px;'> Phone Number Error - Incorrect Format Ex- 000-000-0000 </li>" ;
        }

        if(isset($_POST['timeSlots'])){
            $slots = $_POST['timeSlots'];
        }

        if($error == ""){
           
            $added = addToMySql($umID, $fName, $lName, $title, $email, $phone, $slots, $conn);
            if ($added){
                $success = "<li style = 'padding-top : 5px; padding-bottom: 5px;'>Registration Successfull</li>";
                header("Refresh: 5;");
            }


        }else{
            $success = "";
            
        }

      
    }

    function addToMySql($umID, $fName, $lName, $title, $email, $phone, $slots, $conn){
      
        
        
        $regQuery = "INSERT INTO  `registers` (`umID`, `fName`, `lName`, `title`, `email`, `phone`, `timeslots_ID`) VALUES('$umID', '$fName', '$lName', '$title', '$email', '$phone', '$slots')" ; 
        $res = mysqli_query($conn, $regQuery);
        if(!$res){
            global $mySqlError;
            $mySqlError =  mysqli_error($conn);
           
            return false;
        }else {
            $timeSlotQuery = "UPDATE `timeslots` SET slots = slots - 1 WHERE id = '$slots'";
            mysqli_query($conn, $timeSlotQuery);
            
            return true;
        }
        
      
       
    }

    if(preg_match('/\bDuplicate entry\b/', $mySqlError )){

        $error = "<span style = 'padding-top : 5px; padding-bottom: 5px;'> Student with this UMID: " . $umID . " already have registered. Do you want to update registration with current submission? </span> " ;
        $update = "<div id = 'registerButton'>
                    <input  class='button' type = 'submit' value = 'Yes' name = 'updateButton' ></button>
                    </div>";
        $cancelUpdate = "<div id = 'registerButton'>
                        <input  class='button' type = 'submit' value = 'No' name = 'updateCancelButton' ></button>
                        </div>";

    }

    if(!empty($_POST['updateButton'])){


            $umID = $_POST['umID'];
            $fName = $_POST['fName'];
            $lName = $_POST['lName'];
            $title = $_POST['title'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $slots = $_POST['timeSlots'];
        
        //echo $error;
        updateReg($umID, $fName, $lName, $title, $email, $phone, $slots, $conn);
    }  
    
    if(!empty($_POST['updateCancelButton'])){
        $success = "Updates Canceled.";
        header("Refresh: 5;");
    }

    

    function updateReg($umID, $fName, $lName, $title, $email, $phone, $slots, $conn){
        $delQuery = "DELETE FROM `registers` WHERE umID = '$umID'";
        $getQuery = "SELECT timeslots_ID FROM `registers` WHERE umID = '$umID'";
       
        $result = mysqli_query($conn, $getQuery);
        $recievedTimeSlot =  mysqli_fetch_all($result, MYSQLI_ASSOC);
        global $success;
        $upSlots = 0;

        if($result){
            if(mysqli_num_rows($result) > 0){
                echo $recievedTimeSlot[0]['timeslots_ID'];
                $upSlots = $recievedTimeSlot[0]['timeslots_ID'];
               
            }
            $updateSlots = "UPDATE `timeslots` SET slots = slots + 1 WHERE id = '$upSlots'";
            $delResult = mysqli_query($conn, $delQuery);
            if($delResult){
                $updateSlotsResult = mysqli_query($conn, $updateSlots);

                if($updateSlotsResult){
                   $success = addToMySql($umID, $fName, $lName, $title, $email, $phone, $slots, $conn);
                   if($success){
                    $success = "<span style = 'padding-top : 5px; padding-bottom: 5px;'>Upadate Successfull</span>";
                    header("Refresh: 5;");
                   }else{
                       $success = "";
                   }
                }
            }


        }
       
    }
    
?>

<!DOCTYPE html>
<html>
<head>
    <link rel = "stylesheet"  href = "./stylesheet.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed&display=swap" rel="stylesheet">
    <title></title>

</head>
<body>

    <div id = "mainContainer">
        
        <div id = "formContainer">

        <div id = "picture">
            
        </div>
            <div id = "formElements">
                <div style = "display: flex; width: 100%; justify-content: center; color: #072B5F; margin-bottom: 5px;">
                    <span style = "font-size: 35px">Project Registration</span>
                </div>
                <div id = "info">
                    <ul id = "error">
                        <?php echo $error; ?>
                    </ul>

                    <ul id = "success">
                        <?php echo $success; ?>
                    </ul>
                    
                        
                

                </div>
                <form id = "registerForms" method = "post" name = "reg">
                        <div style = "display: flex;"> 
                            <?php echo $update; echo $cancelUpdate; ?> 
                        </div> 
                        
                    <div id = "umID" >
                        <input type = "text" name = "umID" placeholder="UMID" value = '<?php echo $umID; ?>'  required>
                    </div>
                    <div id = "name" >
                            <input type = "text" name = "fName" placeholder="First Name" value = '<?php echo $fName; ?>' required >
                            <input type = "text" name = "lName" placeholder="Last Name" value = '<?php echo $lName; ?>' required>
                    </div>
                    <div id = "projectTitle" >
                            <input type = "text" name = "title" placeholder="Project Title" value = '<?php echo $title; ?>' required>
                    </div>
                    <div id = "email" >
                            <input type = "text" name = "email" placeholder="Email" value = '<?php echo $email; ?>' required>
                    </div>
                    <div id = "phone" >
                            <input type = "text" name = "phone" placeholder="Phone: 000-000-0000" value = '<?php echo $phone; ?>' required>
                    </div>

                    <div id = slots>

                            <div>
                                <input type="radio" name="timeSlots" <?php if (isset($slots) && $slots== 1 ) echo "checked";?>  value="1" required> <span class = "timeSlotTExt">1. 12/9/19, 6:00 PM - 7:00 PM,</span>  
                            </div>   
                            
                            <div>
                                <input type="radio" name="timeSlots" <?php if (isset($slots) && $slots== 2 ) echo "checked";?> value="2"> <span class = "timeSlotTExt">2. 12/9/19, 7:00 PM - 8:00 PM,</span>  
                            </div> 
                            
                            <div>
                                <input type="radio" name="timeSlots" <?php if (isset($slots) && $slots== 3 ) echo "checked";?> value="3"> <span class = "timeSlotTExt" >3. 12/9/19, 8:00 PM - 9:00 PM,</span>  
                            </div> 
                            
                            <div>
                                <input type="radio" name="timeSlots" <?php if (isset($slots) && $slots== 4 ) echo "checked";?> value="4"> <span class = "timeSlotTExt" >4. 12/10/19, 6:00 PM - 7:00 PM,</span>  
                            </div> 
                            
                            <div>
                                <input type="radio" name="timeSlots" <?php if (isset($slots) && $slots== 5 ) echo "checked";?> value="5"> <span class = "timeSlotTExt">5. 12/10/19, 7:00 PM - 8:00 PM, </span> 
                            </div> 
                        
                            <div>
                                <input type="radio" name="timeSlots" <?php if (isset($slots) && $slots== 6 ) echo "checked";?> value="6"> <span class ="timeSlotTExt">6. 12/10/19, 8:00 PM - 9:00 PM,</span>  
                            </div> 
                        
                    
        
                        </div>

                        <div id = "registerButton">
                            <input class="button" type = "submit" name = "register-form" value = "Register" />
                        </div>
                        

                </form>

            </div>
           

            
            
           


        </div>
    
    </div>
    
    







    <script type = "text/javascript"> 
        
       document.getElementById("mainContainer").style.height = window.innerHeight.toString() + "px";
     
     

       let slots = <?php echo json_encode($recievedSlots) ?>;
       console.log(slots);
       let test = document.reg.timeSlots.length;
       console.log(test);
       console.log(document.getElementsByClassName('timeSlotTExt')[0].innerHTML);

       for(let i = 0; i < document.getElementsByClassName('timeSlotTExt').length; i++){
        document.getElementsByClassName('timeSlotTExt')[i].innerHTML = document.getElementsByClassName('timeSlotTExt')[i].innerHTML + " " + slots[i].slots + " seats remaining";
        if(slots[i].slots == "0"){
                document.reg.timeSlots[i].disabled = true;
        }
        }


              
    </script>

</body>
</html>