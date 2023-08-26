<?php

/*basic file called on through the exit button on the booking page.
Destroys the current session and redirects back to the index page. */

session_start();
session_destroy();

header("location:index.php?msg=logout");

?>
