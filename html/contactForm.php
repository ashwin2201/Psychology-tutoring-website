<?php

if (isset($_POST['g-recaptcha-response'])){
    $captcha = $_POST['g-recaptcha-response'];
  }
  if (!$captcha) {
    echo '<h2>Please check the the captcha form.</h2>';
    exit;
  }

  $secretKey = "6LdgzR0aAAAAAP5dY17stcsEAK2P1VCNLht_hAGR";
  $ip = $_SERVER['REMOTE_ADDR'];
  // post request to server
  $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
  $response = file_get_contents($url);
  $responseKeys = json_decode($response,true);
  // should return JSON with success as true
  if (!$responseKeys["success"]) {
        echo '<h2>Bot activity recognised. Please go back and try again if this was a mistake.</h2>';
  }


if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $identity = $_POST['identity'];
    $studentsLevel = $_POST['level'];
    $grade = $_POST['grade'];
    $address = $_POST['address'];
    $postalCode = $_POST['postalCode'];
    $frequency = $_POST['frequency'];
    $sDate = $_POST['startDate'];
    $eDate = $_POST['endDate'];

    $sDate = str_replace('/', '-', $sDate);
    $eDate = str_replace('/', '-', $eDate);
    $startDate = date('Y-m-d', strtotime($sDate));
    $endDate = date('Y-m-d', strtotime($eDate));

    $timing = $_POST['prefTiming'];
    $specialNeeds = $_POST['specialNeeds'];
    $description = $_POST['description'];

    $mailTo = "ashwin2201619@gmail.com";
    $mailAlso = "sgraffles@gmail.com";

    $host = "ibpsychologytutor.sg";
    $dbUsername = "ash2201";
    $dbPassword = "zs41k&7D";
    $dbName = "ibpsychtutordb";

	$subject = 'Someone booked a tutor';
	$message = $name." wants a trial class. Identity: ".$identity." Level ".$level." Grade: ".$grade." Address: ".$address." Postal code: ".$postalCode." Freuqency: ".$frequency." Startdate: ".$startDate." Enddate ".$endDate." Timing: ".$timing." Special needs: ".$specialNeeds." Description: ".$description;
    $headers = "From: ".$name;
    
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

    if (mysqli_connect_error()) {
        die ("Connect error(".mysqli_connect_error().')'.mysqli_connect_error());
    } else {
        $INSERT = "INSERT Into person (name, identity, students_level, grade, address, postal_code, frequency, start_date, end_date, timing, special_needs, description) values (?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $conn->prepare($INSERT);
        $stmt->bind_param("ssiisissssss", $name, $identity, $studentsLevel, $grade, $address, $postalCode, $frequency, $startDate, $endDate, $timing, $specialNeeds, $description);
        $stmt->execute();
            
            if (mail($mailTo, $subject, $message, $headers) && mail($mailAlso, $subject, $message, $headers)) {
                echo "Mail sent successfully!";
                header("Location: syllabus.html");
            } else {
                echo "Unsuccessful in sending mail";
            }
            
           $stmt->close();
           $conn->close();
    }
}

?>
