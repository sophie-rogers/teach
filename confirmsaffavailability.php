<!DOCTYPE html>
      <html>
      <head>
   <link rel="stylesheet" href="styles.css">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    </head>
      <body>

         <h1>

           <?php

           /*begins or resumes session and sets session variables, and calls radio_value from the post value
           of the form on the previous page*/

       session_start();

       print "Tennis COMP8870";

       $bookedDate = $_SESSION['setDate'];
       $bookedUser = $_SESSION["foundID"];
       $radio_value = $_POST['tutorButton'];

          ?>

         </h1>


        <?php

        /*establishes connection to database*/

         include('DBconnect.php');

         try {
             $conn = connect();

             if(!empty($_POST['check_list']))
              {
                foreach($_POST['check_list'] as $value){
                  $finalTime = $value;

                  $sql = "INSERT INTO Bookings (studentId, onDate, atTime, tutorId)
                      VALUES ('1', '$bookedDate', '$finalTime', '$bookedUser')";

                         print "<br>"."Summary: Busy at". $bookedDate ." at ". $finalTime;

                }
              }

    $implodedcoaches = implode(' ', $coaches);

             /*splits up the radio_value string passed from coach as the value. Splits based on the '-'
             used, and divides into 3 separate strings, for time, coach name, and coachID selected by the user*/

             //list($finalTime, $finalID) = explode("-", $radio_value,2);


             /*sql insert query, inserting the new strings, and the session variable for date and user ID, into the
             training table as a new booking */

             $sql = "INSERT INTO Bookings (studentId, onDate, atTime, tutorId)
                 VALUES ('1', '$bookedDate', '$finalTime', '$bookedUser')";


               $handle = $conn->prepare($sql);
               $handle->execute();
               $conn = null;

               /*prints confirmation, still using the same variables, to confirm the booking success*/

                    //   print "<br>"."Thank you ".$_SESSION['username']." , your booking was successful!";



          /*PDO error reporting*/
        } catch (PDOException $e) {
              echo $e->getMessage();
            }


        ?>

        <a href="studentmanagebookings.php">Manage bookings</a>

        <!--HTML button redirecting to logout.php, a page which ends the session and redirects back to index.php-->
          <a href="logout.php" id="exitbutton">  <input id="exit" class="button" value="Exit"></a>

      </body>
