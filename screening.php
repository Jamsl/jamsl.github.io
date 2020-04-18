<?php
    require_once 'functions.php';
    
   


    if (!isset($_GET['screening'])) {$screening = 1;} ELSE IF ($_GET['screening'] >=1 AND $_GET['screening'] <= 5)    {
        $screening = $_GET['screening'];
    } ELSE {$screening = 1;}
    
    /*if $_GET['screening'] >= 1 and $_GET['screening'] <= 5 { $screening = $_GET['screening'] } ELSE {$screening = 1};
    /**
     * $screening = 1;
     */
    $title = get_screening_title($screening);
    $date = get_screening_date($screening);
    $venue = get_venue($screening);
    $films = make_films_list($screening);
    
    
?>
<html>

	<head>

    <?php include 'header.php' ?>

	</head>

	<body onload="setCurrentPage('screenings')">

		<h1>the dog movement </h1>


        <?php include 'menu_block.php' ?>


		<div class="mainpane">

			
            <?php echo "<h3>" . $title . "</h3>" ?>
			<p>

				<?php echo $date ?>

			</p>

			
            <p><?php echo ($films) ?></p> 
			<p>

				<a href="<?php echo $venue['link']?>" class="textlink"><?php echo $venue['address'] ?></a>

			</p>


			<p class="nextprev">
            <a href="screening.php?screening=<?php echo ($screening - 1)?>" class="textlink" <?php if ($screening == 1) { echo 'style="display: none"'; } ?> >prev</a> |
            <a href="screening.php?screening=<?php echo ($screening + 1)?>" class="textlink" <?php if ($screening == 5) { echo 'style="display: none"'; } ?> >next</a></p>

			</div>



	</body>
</html>