<?php

/*establishes connection to database*/

include('DBconnect.php');
session_start();
 ?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Booking</title>
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

    <?php

try {
 /*connects to database. SQL select query getting data from the booking and tutor tables joined,
 combining times where tutors have multiple sessions on the same day, filtering by the date selected by the user.
 These values are stored.*/

 $conn = connect();

    $tutorVal = trim($_POST['selectedTutor'], '()');
    $_SESSION["setDate"] = $_POST['ondate'];

    list($tutorIDSelected, $tutorNameSelected) = explode('.', $tutorVal, 2);

     $sql = "SELECT studentId, onDate, GROUP_CONCAT(atTime), name, Tutor.tutorId
     FROM Bookings JOIN Tutor ON Bookings.tutorId = Tutor.tutorId
     WHERE Bookings.onDate LIKE '%{$_POST['ondate']}%' AND Bookings.tutorId LIKE '%$tutorIDSelected%'
     GROUP BY name, onDate, tutorId, studentId";

       $handle = $conn->prepare($sql);
       $handle->execute();
       $conn = null;
       $res = $handle->fetchAll();

    ?>

    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
      <div class="page-header d-flex align-items-center" style="background-image: url('');">
        <div class="container position-relative">
          <div class="row d-flex justify-content-center">
            <div class="col-lg-6 text-center">
              <h1>Session Booking</h1>
              <?php
              echo "Please select a time slot to book a session with ".$tutorNameSelected." on ".$_SESSION['setDate'].".";
              ?>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Breadcrumbs -->

    <section class="sample-page">
      <div class="container" data-aos="fade-up">




        <table id ="timeTable">

            <tr>

              <th>10:00</th>
              <th>12:00</th>
              <th>14:00</th>
              <th>16:00</th>
              <th>18:00</th>
            </tr>

            <!--form to post user selections to book.php, calling validateForm() JS function
                to make sure at least one option is selected-->

        <form method="post" name="radioForm" action="confirmstudentbooking.php" onsubmit="return validateForms()">


        <?php

        /*Iterates through the rows of the SELECT query data pulled form the tables*/

        foreach($res as $row) {

          echo "<tr>";

          $explodedtimes = explode(', ', $row['GROUP_CONCAT(atTime)']);
          $implodedtimes = implode(', ', $explodedtimes);

          /*if the implodedtimes string above, a string containing all times in a row from the table pulled
          from the query, contains 10:00, 12:00, 14:00, 16:00 or 18:00, or if the given student ID is contained within the row
          being selected either. If either of these condtiions are true, the radio button is echoed as disabled
          in its own data cell, as the slot has been booked.

          Otherwise, a selectable radio button is printed, and a set of variables are set to the specific slot in each
          iteration of the foreach loop, and these are combined together into a string called $poststring, divided by a -,
          which is passed to the booking confirmation page as the input value, if this radio button is selected.*/

                  if ((strpos($implodedtimes, '10:00')!== false)||((strpos($implodedtimes, '10:00')!== false) && ($row['studentId'] ==   $_SESSION["foundID"]))){

                          echo "<td>"."<input type='radio' disabled>"."</td>";

                    }

                    else {

                        $time = "10:00";
                        $bookTutor = $row['name'];
                        $bookTutorId = $row['tutorId'];
                        $poststring = $time."-".$bookTutor."-".$bookTutorId;

                              echo "<td>"."<input type='radio' name='tutorButton' id='10:00' value='".$poststring."'required>"."</td>";

                  }

                  if ((strpos($implodedtimes, '12:00')!== false)||((strpos($implodedtimes, '12:00')!== false) && ($row['studentId'] ==   $_SESSION["foundID"]))){

                          echo "<td>"."<input type='radio' disabled>"."</td>";

                    }

                    else {

                        $time = "12:00";
                        $bookTutor = $row['name'];
                        $bookTutorId = $row['tutorId'];
                        $poststring = $time."-".$bookTutor."-".$bookTutorId;

                              echo "<td>"."<input type='radio' name='tutorButton' id='12:00' value='".$poststring."'required>"."</td>";

                  }

                  if ((strpos($implodedtimes, '14:00')!== false)||((strpos($implodedtimes, '14:00')!== false) && ($row['studentId'] ==   $_SESSION["foundID"]))){

                          echo "<td>"."<input type='radio' disabled>"."</td>";

                    }

                    else {

                        $time = "14:00";
                        $bookTutor = $row['name'];
                        $bookTutorId = $row['tutorId'];
                        $poststring = $time."-".$bookTutor."-".$bookTutorId;

                              echo "<td>"."<input type='radio' name='tutorButton' id='14:00' value='".$poststring."'required>"."</td>";

                  }

                  if ((strpos($implodedtimes, '16:00')!== false)||((strpos($implodedtimes, '16:00')!== false) && ($row['studentId'] ==   $_SESSION["foundID"]))){

                          echo "<td>"."<input type='radio' disabled>"."</td>";

                    }

                    else {

                        $time = "16:00";
                        $bookTutor = $row['name'];
                        $bookTutorId = $row['tutorId'];
                        $poststring = $time."-".$bookTutor."-".$bookTutorId;

                              echo "<td>"."<input type='radio' name='tutorButton' id='16:00' value='".$poststring."'required>"."</td>";

                  }

                  if ((strpos($implodedtimes, '18:00')!== false)||((strpos($implodedtimes, '18:00')!== false) && ($row['studentId'] ==   $_SESSION["foundID"]))){

                          echo "<td>"."<input type='radio' disabled>"."</td>";

                    }

                    else {

                        $time = "18:00";
                        $bookTutor = $row['name'];
                        $bookTutorId = $row['tutorId'];
                        $poststring = $time."-".$bookTutor."-".$bookTutorId;

                              echo "<td>"."<input type='radio' name='tutorButton' id='18:00' value='".$poststring."'required>"."</td>";

                  }

                  echo "</tr>";
                }

                if (!isset($poststring)){
                    $array = array("10:00", "12:00", "14:00", "16:00", "18:00");

                  echo  "<tr>";

                    foreach($array as $val){

                      $time = $val;
                      $bookTutor = $tutorNameSelected;
                      $bookTutorId = $tutorIDSelected;
                      $poststring = $time."-".$bookTutor."-".$bookTutorId;

                            echo "<td>"."<input type='radio' name='tutorButton' id='18:00' value='".$poststring."'required>"."</td>";

                }
                echo "</tr>";
              }

        ?>

        </table>


  <!--Table is closed and form values are posted to book.php-->
          <input method='post' type='hidden' name='timeButton'>

          <input class="button" value="➜" type="submit" id='checkBtn' style="
            font-family: var(--font-primary);
            margin-left: 90%;
            font-weight: 500;
            font-size: 15px;
            margin-top: -100px;
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

        <a href="tutorselection.php"><input type="button" onsubmit=""value="Back to Tutor Selection" style="
        font-family: var(--font-primary);
        font-weight: 500;
        margin-left: 0;
        font-size: 15px;
        letter-spacing: 1px;
        display: inline;
        padding: 10px 20px;
        border-radius: 50px;
        transition: 0.3s;
        color: black;
        background: #ffce8f;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
        border: 2px solid rgba(255, 255, 255, 0.1);

          "></a>


  <?php
  /*PDO exception error reporting*/
  } catch (PDOException $e) {
       echo $e->getMessage();
      }
      ?>

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

<script>
