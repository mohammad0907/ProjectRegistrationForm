<?php 



    $conn = mysqli_connect('localhost', 'mohammad', 'test1234', 'registration' );
    if(!$conn){
        echo 'connection error: ' . mysqli_connect_error();
    }

    $sql = 'SELECT `umID`,`fName`,`lName`,`title`,`email`,`phone`, timeslots.date FROM `registers` INNER JOIN timeslots ON registers.timeslots_ID = timeslots.id';

    $result = mysqli_query($conn, $sql);
    //fetch resultion rows an an array 

    $recievedTable = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    

    

?>




<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed&display=swap" rel="stylesheet">
    <title></title>

    <style>
        body{
            margin: 0;
            font-family: 'Roboto Condensed', sans-serif;
        }

        #mainContainer{
            width : 100%;
            
            background-color: #072B5F;
            display: flex;
            flex-direction: column;
            
            align-items:  center;
            
        }

        #tableContainer{
            display: flex;
            flex-direction: column;
          
            
            background-color:#F6D23C;
            align-items: center;
            padding: 10px;
            -webkit-box-shadow: 2px 2px 25px -3px rgba(0,0,0,0.75);
             -moz-box-shadow: 2px 2px 25px -3px rgba(0,0,0,0.75);
            box-shadow: 2px 2px 25px -3px rgba(0,0,0,0.75); 
        }
        table {
        border-collapse: collapse;
        width: 100%;
        }

        th, td {
        text-align: left;
        padding: 8px;
        }

        tr:nth-child(even) { background-color: #072B5F; color: white;}


    </style>

</head>
<body>

    <div id = "mainContainer">
        <h1 style = "margin-top: 60px; color: white;">Registration List</h1>
        <div id = "tableContainer">
           
             <div style="overflow-x:auto;">
                <table id = "dispTable">
                    <tr>
                    <th>UMID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Project Title</th>
                    <th>Email Address</th>
                    <th>Phone Number</th>
                    <th>Time Slot</th>
                    
                    
                    
                    </tr>
                    
                    
                </table>

            
            
           


        </div>
    
    </div>
    
    







    <script type = "text/javascript"> 
        
      document.getElementById("mainContainer").style.height = window.innerHeight.toString() + "px";
      // document.getElementsByClassName("button")[1].innerHTML = "Change Time Slots"
      let tableRow = <?php echo json_encode($recievedTable) ?>;
      console.log(tableRow);
       let table =  document.getElementById("dispTable");

       for(let i = 0; i < tableRow.length; i++){
            let row = table.insertRow(i+1)
            let umID = row.insertCell(0);
            let fName = row.insertCell(1);
            let lName = row.insertCell(2);
            let title = row.insertCell(3);
            let email = row.insertCell(4);
            let phone = row.insertCell(5);
            let slot = row.insertCell(6);

            umID.innerHTML = tableRow[i].umID;
           
             fName.innerHTML = tableRow[i].fName;
             lName.innerHTML = tableRow[i].lName;
             title.innerHTML = tableRow[i].title;
             email.innerHTML = tableRow[i].email;
             phone.innerHTML = tableRow[i].phone;
             slot.innerHTML = tableRow[i].date;
       }

              
    </script>

</body>
</html>