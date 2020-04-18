<?php

 
    require_once 'login.php';
    $db_server = mysql_connect($db_hostname, $db_username, $db_password);
    if(!$db_server) die("Unable to connect to MySQL: " . mysql_error());
    mysql_select_db($db_database) or die("couldn't connect to db");
    $mysqli = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);

    function make_header(){
        return		"<title>the dog movement</title>

		<link rel='stylesheet' type='text/css' href='style.css'>

		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

		<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>

		<script type='text/javascript' src='script.js'></script>";
    }    

    function get_screening_title($screening_id)
    {    
        global $mysqli;

        $res = mysqli_query($mysqli, "select name from screening where id = $screening_id");
        $row = mysqli_fetch_assoc($res);
        return $row['name'];
    }   
    
    function get_screening_date($screening_id)
    {
        global $mysqli;

        $res = mysqli_query($mysqli, "select date_format(date, '%W %e %M %l%p %Y') as d1 from screening where id = $screening_id");
        $row = mysqli_fetch_assoc($res);
        $retstr = str_replace('PM', 'pm', $row['d1']);
        return str_replace('PM', 'pm', $row['d1']);
    }
    
    function get_venue($screening_id)
    {
        global $mysqli;

        $res = mysqli_query($mysqli, "select location.address, location.link from screening, location where screening.id = $screening_id and screening.locationid = location.id");
        $row = mysqli_fetch_assoc($res);
        return array('address' => $row['address'], 'link' => $row['link']);
    }
    
    function make_films_list($screening_id)
    {
        global $mysqli;
        
        $res = mysqli_query($mysqli, "SELECT f.title, d.name, d.link, f.year, f.sound, f.format, f.colour, f.length FROM film f, screening s, screening_films sf, director d WHERE f.directorid = d.id AND sf.filmid = f.id AND sf.screeningid = s.id and s.id = $screening_id ORDER BY sf.sequence");
        $films = array();
        while($row = $res->fetch_assoc()) 
        {
            $films[] = $row;
        }
        
        $film_list = '<ul>';
        
        foreach($films as $item)
        {
            
            $film_list .= '<li>' . $item['title'] . ' by ' . '<a href="' . $item['link'] . '" class="textlink">'. $item['name'] . '</a>' . '<br><div class="smallinfo">(' .$item['year'] . ', ' . $item['format'] . ', ' . $item['colour'] . ', ' . $item['sound'] . ', ' . $item['length']. 'min)</div></li>';
        }
        
        $film_list .= '</ul>';        
        return $film_list;
        
    }
    
	function screening_info($screening_id) {
	        global $mysqli;
			
			$res = mysqli_query($mysqli, "select screening.name, date_format(screening.date, '%W %e %M %l%p %Y') as d1, location.address as venue, location.link as venue_link from screening, location where screening.id = $screening_id and screening.locationid = location.id");
			$row = mysqli_fetch_assoc($res);
			$s_title = $row['name'];
			$s_date = str_replace('PM', 'pm', $row['d1']);
			$s_venue = $row['venue'];
			$s_venue_link = $row['venue_link'];
			$films = make_films_list($screening_id);
			$prev_screening = $screening_id - 1;
			$next_screening = $screening_id + 1;
			
			$retstr = "<h3>{$s_title}</h3><p>{$s_date}</p><p>{$films}</p><p><a href='{$s_venue_link}' class='textlink'>{$s_venue}</a></p>";
			
			$nextprev = "<p class='nextprev'><a href='screenings.php?screening={$prev_screening}' class='textlink'";
			if ($screening_id == 1) { $nextprev .= "style='display: none'"; };
			$nextprev .= ">prev</a> | <a href='screenings.php?screening={$next_screening}' class='textlink'";
			if ($screening_id == 5) { $nextprev .= "style='display: none'"; };
			$nextprev .= ">next</a></p>";
			$retstr = $retstr . $nextprev;
			
			return $retstr;
	
	}
	
    function list_of_screenings() {
        global $mysqli;
        
        $res = mysqli_query($mysqli, "SELECT name, id FROM `screening` ORDER by id");
        $screenings = array();
        $retstr = '<table>';
        while($row = $res->fetch_assoc())
        {
            $retstr .= '<tr><td class="notlink" onmouseover="hoverScreening(' . $row['id'] . ')" onmouseout="blankScreening()"><a href="screenings.php?screening=' . $row['id'] . '" class="notlink">';
            $retstr .= $row['name'];
            $retstr .= '</a></td></tr>';
        }        
        $retstr .= '</table>';
        return $retstr;
    }
	
	function make_column($text, $link = '') {
		if ($link) {
			$retstr = '<td><a href="' . $link . '" class="tablelink">' . $text . '</a></td>';
		}
		else { $retstr = '<td>' . $text . '</td>' ;}
		
		return $retstr;	
	}
	
	function make_films_table() {
		global $mysqli;
		
		$retstr = '<table id="allFilms" class="tablesorter"><thead><tr><th>Film</th><th> Director</th><th>Year</th><th>Format</th><th>Colour</th><th>Sound</th><th>Length</th><th>Screening</th><th>Date</th><th>Location</th></tr></thead><tbody>';
		
		$res = mysqli_query($mysqli, "SELECT f.title, d.name AS director_name, d.link AS director_link, f.year, f.format, f.colour, f.sound, f.length, s.name AS screening_name, CONCAT('screenings.php?screening=',s.id) AS screening_link, DATE_FORMAT(s.date, '%e-%b-%Y') AS date, l.venue, l.link AS venue_link FROM screening_films sf, film f, screening s, director d, location l WHERE sf.filmid = f.id and sf.screeningid = s.id and f.directorid = d.id and s.locationid = l.id");
		
		while($row = $res->fetch_assoc()) {
			$rowstr = '<tr>' .  make_column($row['title']) . make_column($row['director_name'], $row['director_link']) . make_column($row['year']) . make_column($row['format']) . make_column($row['colour']) . make_column($row['sound']) . make_column($row['length']) . make_column($row['screening_name'], $row['screening_link']) . make_column($row['date']) . make_column($row['venue'], $row['venue_link']) . '</tr>';
			$retstr .= $rowstr;
		}
		
		$retstr .= '</tbody></table>';
	
		return $retstr;	
	}
?>