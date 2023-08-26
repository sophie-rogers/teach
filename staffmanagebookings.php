<?php

/*establishes connection to the database*/

include('DBconnect.php');
session_start();

/*checks if userID session variable is set, if not, no one is logged in and so the arrays are searched for
the input password and username. If they exist in the database, password verification is checked to ensure
the hash for the input password matches that in the database, and the user is logged in, with session
variables set. Otherwise, a JS error message is shown and they are returned to the login screen.
*/

if (!isset ($_SESSION["foundID"])) {

   $pass = array_search($_POST["inputName"], $_SESSION['combinedArrayStaff']);
   $ID = array_search($_POST["inputName"], $_SESSION['combinedLoginsStaff']);

   $isPasswordCorrect = password_verify($_POST['inputPassword'], $pass);

     if ($isPasswordCorrect) {

       $_SESSION["foundID"] = $ID;
       $_SESSION["username"] = htmlspecialchars($_POST['inputName']);
       $_SESSION["password"] = $pass;


     } else {

       ?>
       <script type="text/javascript">
       alert("Please enter a valid username and password");
             location="stafflogin.php";
   </script>
   <?php

     }

   }    else {

?>

<head>
 <meta charset="utf-8">
 <meta content="width=device-width, initial-scale=1.0" name="viewport">

 <title>Manage Bookings</title>
 <meta content="" name="description">
 <meta content="" name="keywords">

 <!-- Favicons -->
 <link href="assets/img/favicon.png" rel="icon">
 <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

 <!-- Google Fonts -->
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

 <!-- Vendor CSS Files -->
 <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
 <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
 <link href="assets/vendor/aos/aos.css" rel="stylesheet">
 <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
 <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

 <!-- Template Main CSS File -->
 <link href="assets/css/main.css" rel="stylesheet">

 <!-- =======================================================
 * Template Name: Impact - v1.0.0
 * Template URL: https://bootstrapmade.com/impact-bootstrap-business-website-template/
 * Author: BootstrapMade.com
 * License: https://bootstrapmade.com/license/
 ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <section id="topbar" class="topbar d-flex align-items-center">
    <div class="container d-flex justify-content-center justify-content-md-between">
      <div class="contact-info d-flex align-items-center">
        <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:sr652@kent.ac.uk">sr652@kent.ac.uk</a></i
      </div>
    </div>
  </section><!-- End Top Bar -->

  <header id="header" class="header d-flex align-items-center">

    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1>Teach<span>.</span></h1>
      </a>
      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="studentlogin.php">Student Login</a></li>
          <li><a href="stafflogin.php">Tutor Login</a></li>
          <li><a href="logout.php">Logout</a></li>            <ul>
        </ul>
      </nav><!-- .navbar -->



    </div>
  </header><!-- End Header -->
  <!-- End Header -->


 <main id="main">

   <!-- ======= Breadcrumbs ======= -->
   <div class="breadcrumbs">
     <div class="page-header d-flex align-items-center" style="background-image: url('');">
       <div class="container position-relative">
         <div class="row d-flex justify-content-center">
           <div class="col-lg-6 text-center">
             <h1>Manage Bookings</h1>
             <p>View below your current booked sessions, cancel these, or submit your availability for session booking. </p>
           </div>
         </div>
       </div>
     </div>

   </div><!-- End Breadcrumbs -->

   <section class="sample-page">
     <div class="container" data-aos="fade-up">


   </h1>

   <form method="post" action="cancel.php">


   <table id="bookingTable">
     <tr>
       <th>Student</th>
       <th>Date</th>
       <th>Time</th>
       <th>Cancel</th>
     </tr>

   <?php


          try {

            /*sql query selects data from the bookings table, joined to the student table. variables are set by
            iterating through the rows, and are displayed in a table. The variables are stored in a string which
            is posted to the cancellation page when the cancel button is clicked for any of the existing bookings.*/

              $conn = connect();

              $sql = "SELECT tutorId, Student.studentId, Student.name, onDate, atTime FROM Bookings JOIN Student ON Bookings.studentId = Student.studentId WHERE tutorId LIKE '%{$_SESSION['foundID']}%'";

              $handle = $conn->prepare($sql);
              $handle->execute();
              $conn = null;

              $res = $handle->fetchAll();

              foreach($res as $row) {

                $studentId = $row['studentId'];
                $onDate = $row['onDate'];
                $atTime = $row['atTime'];

                $string = $studentId.".".$onDate.".".$atTime;
                echo

                "<td>".
                $row["name"].
                "</td>".

                "<td>".
                $row["onDate"].
                "</td>".

                "<td>".
                $row["atTime"].
                "</td>".

                "<td>";
                ?>

         <input type="submit" name="action" value="<?php echo $string; ?>" style=" font-size: 0; width: 20px; height: 20px; border-radius:10px; border: none; background-color: #f96f59"/>

                <?php echo "</td>".

            "</tr>";

            }

            /*PDO error reporting*/
           } catch (PDOException $e) {
              echo $e->getMessage();
              }
            }

     ?>

</table>
</form>

 <form name="tutorForm" id="availabilitySubmit" onsubmit="return validateForm()" action="staffavailability.php" method="post">
   <p>Select date to submit availability</p>
   <!--Date input for user selection, defaults to 2017-02-25 if unselected-->

   <input  type="date" id="ondate" name="ondate" value="<?php echo date('y-m-d'); ?>" style="
   border-radius: 20px;
   border:none;
   background-color: white;
   text-align:center;
   align-content:center;
   padding: 10px;
   padding-right:0px;
   padding-left:15px;">

   <input class="button" action="studentmakebooking.php" value="➜" type="submit" id='selection' style="
     font-family: var(--font-primary);
     margin-right: 1em;
     font-weight: 500;
     font-size: 15px;
     letter-spacing: 1px;
     display: inline;
     padding: 10px 20px;
     border-radius: 50px;
     transition: 0.3s;
     color: black;
     background: #CBDFBD;
     box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
     border: 2px solid rgba(255, 255, 255, 0.1);
   ">


 </form>

     </div>
   </section>

 </main><!-- End #main -->

 <!-- ======= Footer ======= -->
 <footer id="footer" class="footer">

   <div class="container">
     <div class="row gy-4">
       <div class="col-lg-5 col-md-12 footer-info">
         <a href="index.php" class="logo d-flex align-items-center">
           <span>Teach.</span>
         </a>
         <p>Teach. is an online tutoring booking service, which allows students looking for a tutor to do so and book sessions, and tutors looking for work to manage their bookings and availability.</p>

       </div>


       <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
         <h4>Contact Us</h4>
         <p>
           University of Kent <br>
           Giles Lane<br>
           Canterbury, CT2 7NZ <br><br>
           <strong>Email:</strong> sr652@kent.ac.uk<br>
         </p>

       </div>

     </div>
   </div>

   <div class="container mt-4">
     <div class="copyright">
       &copy; Copyright <strong><span>Impact</span></strong>. All Rights Reserved
     </div>
     <div class="credits">
       <!-- All the links in the footer should remain intact. -->
       <!-- You can delete the links only if you purchased the pro version. -->
       <!-- Licensing information: https://bootstrapmade.com/license/ -->
       <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/impact-bootstrap-business-website-template/ -->
       Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
     </div>
   </div>

 </footer><!-- End Footer -->

 <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

 <div id="preloader"></div>

 <!-- Vendor JS Files -->
 <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
 <script src="assets/vendor/aos/aos.js"></script>
 <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
 <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
 <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
 <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
 <script src="assets/vendor/php-email-form/validate.js"></script>

 <!-- Template Main JS File -->
 <script src="assets/js/main.js"></script>

</body>

</html>
