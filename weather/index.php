<?php
SESSION_START();
include 'plugins.php';

if(array_key_exists('submit', $_POST)){
  if(empty($_POST['city'])){
    $_SESSION['error'] = "Sorry, Textfield Empty! Please Input City.";
  } else {
    $_SESSION['error'] = "";
    $city = htmlspecialchars($_POST['city']);
    
    // Fetch weather data
    $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q=".$city."&appid=f124145f94295265c8ce2e2c03987a07";
    $apiData = @file_get_contents($apiUrl);
    // Avy
    if($apiData !== FALSE){
      $weatherArray = json_decode($apiData, true);
      
      // Check if response is valid
      if($weatherArray['cod'] == 200){
        $weather = $weatherArray['weather'][0]['description'];
        $temp = $weatherArray['main']['temp'] - 273;
        $pressure = $weatherArray['main']['pressure'];
        $windspeed = $weatherArray['wind']['speed'];
        $clouds = $weatherArray['clouds']['all'];
        
        // Set timezone and time
        date_default_timezone_set('Asia/Karachi');
        $timezone = $weatherArray['timezone'];
        $time = date('F j, Y, g:i a');
      } else {
        $_SESSION['error'] = "City not found, please try again.";
      }
    } else {
      $_SESSION['error'] = "Error fetching weather data.";
    }
  }
}
?>
<!--JC-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Report</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" style="color: #FFFFFF" href="#">Weather Report</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse d-flex align-items-center" id="navbarSupportedContent" style="padding-top:15px;">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>
      <form action="" method="POST">
        <input class="form-control me-2" type="text" name="city" placeholder="Enter City" aria-label="Search">
        <button class="btn btn-outline-success" type="submit" name="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
<!---Edma-->
<?php
if (isset($_SESSION['error']) && !empty($_SESSION['error'])){
?>
<div class="alert alert-danger">
  <?=$_SESSION['error']?>
</div>
<?php
unset($_SESSION['error']);
}
?>
<!--Shaun Amiel Andrei--->
<section class="vh-100" style="background-color: #f5f6f7;">
  <div class="row d-flex justify-content-center align-items-center h-100">
    <div class="col-md-10 col-lg-8 col-xl-6">
      <div class="card bg-dark text-white" >
        <div class="bg-image" style="border-radius: 50%">
          <img src="wth.jpg" class="card-img" alt="weather">
          <div class="mask" style="background-color: rgba(190, 216, 232, .5);"></div>
        </div>
        <div class="card-img-overlay text-dark p-5">
          <?php if (isset($temp)): ?>
            <h4 class="mb-0"></h4>
            <p class="display-2 my-3"><?= round($temp); ?>°C</p>
            <p class="mb-2">Location: <?= htmlspecialchars($_POST['city']); ?></p>
            <p class="mb-2">Weather: <?= $weather; ?></p>
            <p class="mb-2">Wind Pressure: <?= $pressure; ?> hPA</p>
            <p class="mb-2">Wind Speed: <?= $windspeed; ?> meter/sec</p>
            <p class="mb-2">Cloudiness: <?= $clouds; ?>%</p>
            <p class="mb-2">Timezone: <?= $time; ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>
