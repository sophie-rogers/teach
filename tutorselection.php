<?php
/*Establishes database connection*/
 include('DBconnect.php');
 session_start();

 try {
 $conn = connect();

 //sql query to select values from the Tutor table
  $sql = "SELECT tutorId, name, studyLevels FROM Tutor ORDER By name ASC";

   $handle = $conn->prepare($sql);
   $handle->execute();
   $conn = null;

   $res = $handle->fetchAll();

   /*login validation, searches arrays of existing users in the tutor table of the database,
   if the username and password are in the database, password verification is used to check that the hash
   matches that in the database for the input password. If password is incorrect, or no password is input,
   a JavaScript error message is shown and the user is redirected to the login page, otherwise the session
   variables are set, and the user is logged in until the session ends*/

   if (!isset ($_SESSION["foundID"])) {

      $pass = array_search($_POST["inputName"], $_SESSION['combinedArray']);
      $ID = array_search($_POST["inputName"], $_SESSION['combinedLogins']);

      $isPasswordCorrect = password_verify($_POST['inputPassword'], $pass);

        if ($isPasswordCorrect) {

          $_SESSION["foundID"] = $ID;
          $_SESSION["username"] = htmlspecialchars($_POST['inputName']);
          $_SESSION["password"] = $pass;


        } else {

         ?>
         <script type="text/javascript">
         alert("Please enter a valid username and password");
               location="studentlogin.php";
     </script>

     <?php
          exit();
        }

      }

      else {

?>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Select a Tutor</title>
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

    <!-- ======= Breadcrumbs =======  -->
    <div class="breadcrumbs">
      <div class="page-header d-flex align-items-center" style="background-image: url('');">
        <div class="container position-relative">
          <div class="row d-flex justify-content-center">
            <div class="col-lg-6 text-center">
              <h1>Tutor Selection</h1>
              <p>
                <?php
                echo "<p> Please select a date and a tutor to view available sessions, and then click on the arrow button to proceed.</p>";
                ?>
              </p>

              <!--HTML form for tutor selection-->
              <form name="tutorForm" id="tutorSelect" onsubmit="return validateForm()" action="studentmakebooking.php" method="post">
                <!--Date input for user selection, defaults to today if unselected-->
                <input  type="date" id="ondate" name="ondate" value="<?php echo date('y-m-d'); ?>" style="
                border-radius: 20px;
                border:none;
                text-align:center;
                align-content:center;
                padding: 10px;
                padding-right:0px;
                padding-left:15px;">

<?php
}
/*PDO exception error reporting*/
  } catch (PDOException $e) {
   echo $e->getMessage();
  }
  ?>
  </div>
          </div>
        </div>
      </div>

      <!-- End Breadcrumbs -->

    <section class="sample-page">
      <div class="container" data-aos="fade-up">
        <div class="row d-flex justify-content-center">
          <div class="col-lg-6 text-center">
          <table id="coachTable">

          <?php

        echo  "<tr>";

          /*For each row in the table, the tutorId, name, and study levels offered are printed in their own data cell, as well as a radio button for
          the user to make their selection. This posts the corresponding tutorId for use at the next stage. */

            foreach($res as $row) {

              $tutorId = $row['tutorId'];
              $tutorLevel = $row['studyLevels'];
              $tutorName = $row['name'];
              echo "<td>".$row['name']."<br>"."<input type='radio' method='post' name='selectedTutor' class='boxes' value='($tutorId.$tutorName)'>"."<br>".$tutorLevel."</td>";

          }

?>
<td style ="background-color:white;">

          <input class="button" action="studentmakebooking.php" value="➜" type="submit" id='checkBtn' style="
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

        </td>
      </tr>
<?php

          ?>
          </table>
        </form>
      </div>

      <a href="studentmanagebookings.php"><input type="button" onsubmit=""value="Manage Bookings" style="
      font-family: var(--font-primary);
      font-weight: 500;
      margin-left: 85%;
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
    </div>
  </div>
    </section>

  </main><!-- End #main -->

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

<!-- JavaScript function to ensure all fields in form are set -->
<script>

function validateForm() {
  var x = document.forms["tutorForm"]["ondate"].value;
  if (x == "") {
    alert("Date must be selected");
    return false;
  }
}

</script>
