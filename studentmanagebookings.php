<?php

      /*establishes connection to database*/

       include('DBconnect.php');
          session_start();

          /*if userID isnt set, therefore user isnt logged in, redirect to login page*/
        if(!isset($_SESSION["foundID"])){
          header("Location:studentlogin.php");
          exit();
        }

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
              <h1>My Bookings</h1>
              <p>View your existing tutoring sessions, and cancel any as necessary.</p>
              <p> Alternatively, click the button below to create a new booking</p>
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
        <th>Tutor</th>
        <th>Date</th>
        <th>Time</th>
        <th>Cancel</th>
      </tr>

    <?php

          /*establishes connection to database and selects booking data from the Bookings table, pulling information about the
          associated tutor from the Tutor table. Assigns the values to variables for use, and stores them in a concatenated
          string. A table is shown with the booking data in, and the string is passed through the submit action in the form.
          The action is brought through the cancel buttons printed to each row, and the cancellation data is passed to the
          cancel form through this*/

           try {

               $conn = connect();

               $sql = "SELECT studentId, Tutor.tutorId, Tutor.name, onDate, atTime FROM Bookings JOIN Tutor ON Bookings.tutorId = Tutor.tutorId WHERE studentId LIKE '%{$_SESSION['foundID']}%'";

               $handle = $conn->prepare($sql);
               $handle->execute();
               $conn = null;

               $res = $handle->fetchAll();

               foreach($res as $row) {

                 $tutorId = $row['tutorId'];
                 $onDate = $row['onDate'];
                 $atTime = $row['atTime'];

                 $string = $tutorId.".".$onDate.".".$atTime;
                 echo

                 "<tr>".

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

      ?>

</table>
</form>

    <!--HTML button redirecting to logout.php, a page which ends the session and redirects back to index.php-->
    <a href="tutorselection.php" id="exitbutton"> <input id="exit" class="button" value="Book New Session" style="
      font-family: var(--font-primary);
      text-align:center;
      width: 15em;
      margin-left: 80%;
      margin-top:30px;
      font-weight: 500;
      font-size: 14px;
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



</body>

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
