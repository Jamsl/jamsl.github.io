<?php
    require_once 'functions.php';
    
   


    if (!isset($_GET['screening'])) {$screening = '';}	
	ELSE IF ($_GET['screening'] >=1 AND $_GET['screening'] <= 5)    {
        $screening = $_GET['screening'];
		$title = get_screening_title($screening);
		$date = get_screening_date($screening);
		$venue = get_venue($screening);
		$films = make_films_list($screening);		
    } 
	ELSE {$screening = '';}
    
    /*if $_GET['screening'] >= 1 and $_GET['screening'] <= 5 { $screening = $_GET['screening'] } ELSE {$screening = 1};
    /**
     * $screening = 1;
     */

    
    
?>
<html>

	<head>

    <?php include 'header.php' ?>

	</head>

	<body onload="setCurrentPage('screenings')">

		<h1>the dog movement </h1>


        <?php include 'menu_block.php' ?>


		<div class="mainpane">
			<?php if (!$screening) {echo list_of_screenings();} else {echo screening_info($screening);} ?>

			</div>



	</body>
</html>