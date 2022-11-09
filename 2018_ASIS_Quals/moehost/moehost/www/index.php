<?php

error_reporting(0);
ini_set('display_errors','off');
require("vendor/autoload.php");
$kazuma = new Kazuma\Kazuma();
?>
<!-- probably wincest? https://www.youtube.com/watch?v=hgVfQWFbZjs -->
<!-- the goal is to trigger the Moe -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>moehost &raquo; moe moe hosting for LEETs!</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700,700i,900,900i" rel="stylesheet">
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/coming-soon.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="overlay"></div>
    <div class="masthead">
      <div class="masthead-bg"></div>
      <div class="container h-100">
        <div class="row h-100">
          <div class="col-12 my-auto">
            <div class="masthead-content text-white py-5 py-md-0">
              <h1 class="mb-3">Coming Soon!</h1>
              <p class="mb-5">We're a hosting company and we expertise in computer security and animes! We're under construction to develop the <b>kawaii-kaonojo-intelligence security</b>.<br>
                We are releasing the flag on <strong>December 2019</strong>! Can you pentest, pwn this weeby hosting, and get the flag in advance? :)</p> <!-- remember, it's 2018.. -->
              <div class="input-group input-group-newsletter">
                <input type="email" class="form-control" placeholder="Enter email..." aria-label="Enter email..." aria-describedby="basic-addon">
                <div class="input-group-append">
                  <button class="btn btn-secondary" type="button">Notify for flag!</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="social-icons">
      <ul class="list-unstyled text-center mb-0">
        <li class="list-unstyled-item">
          <a href="#">
            <i class="fa fa-twitter"></i>
          </a>
        </li>
        <li class="list-unstyled-item">
          <a href="#">
            <i class="fa fa-facebook"></i>
          </a>
        </li>
        <li class="list-unstyled-item">
          <a href="#">
            <i class="fa fa-instagram"></i>
          </a>
        </li>
      </ul>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/vide/jquery.vide.min.js"></script>
    <script src="js/coming-soon.min.js"></script>
    <script> $(document).ready(function(){$("button").on('click', function(){alert('n'+'o'+'o'+'b');});});</script>
  </body>
</html>
