<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title><?php echo (isset($this->page_title) ? $this->page_title['display_name'] : "Football better");?></title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/Navigation-Clean.css">
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
<nav class="navbar navbar-light navbar-expand-md navigation-clean">
    <div class="container"><a class="navbar-brand" href="#"><?php echo isset($_SESSION['league']) ? $_SESSION['league']['name'] : "Football";?></a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse"
             id="navcol-1">
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item" role="presentation"><a class="nav-link" href="/football">Change League</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" href="/login/ajax/logout">Log out</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="row">