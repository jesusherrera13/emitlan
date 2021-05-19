<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.80.0">
    <title>emitlan</title>


    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">



    <!-- Favicons -->
<meta name="theme-color" content="#563d7c">
  <style>

    body {
      background: #f3f3f3;
    }

    .scrolling-wrapper {
      overflow-x: auto;
    }

    .scores-scroll {
      overflow-x: auto;
      overflow-y: hidden;
      white-space: nowrap;
      margin-bottom: 10px;
    }
    .scores-scroll .row{
      display:block;
    }

    .scores-scroll .scores-scroll-game {
      display: inline-block;
      float: none;
      border: 1px solid #ccc;
      width: 200px;
      background: #fff;
      margin-left: 15px;
    }

    .card-block_1 {
      height: 75px;
      width: 150px;
    }

    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }

    .scores {
      font-weight: bold;
    }

    .scores .title {
      text-align: left;
      font-size: 12px;
      font-weight: bold;
    }

    .scores .logo {
      width: 25px;
      margin-right: 3px;
    }

    .scores .team-name, .scores .game-status {
      font-size: 12px;
    }
    .scores .score {
      font-size: 16px;
    }

    .scores .team-info {
      /* width: 30px; */
    }

    .scores .record {
      font-size: 10px;
    }

    .scores .game-info {
      font-size: 9px;
    }

    .score-block {
      background: #fff;
      margin-bottom: 5px;
      padding: 10px;
    }

    .round-block {
      background: #fff;
      border-top-left-radius: 5px;
      border-top-right-radius: 5px;
      border: 1px solid #f8f8f8;
    }

    .scores-dates {
      background: #fff;
    }

    .scores-dates .dayname {
      font-size: 10px;
      font-weight: bold;
    }

    .scores-dates .date {
      font-size: 14px;
      font-weight: bold;
    }

    .score-info {
      font-size:9px;
    }

    .team-record {
      font-size:12px;
    }

    .articulo .titulo {
      font-weight: bold;
      font-size: 26px;
    }

    .articulo .contenido, .info .contenido {
      font-size: 14px;
    }

    .articulo-contenido {
      
    }

    .nota .nota-titulo {
      font-size: 12px;
      font-weight: bold;
    }

    .nota .nota-contenido {
      font-size: 14px;
    }

    .contenedor {
      background: #ffffff;
    }
   
    .contenedor-noticia {
      padding-top: 10px;
      margin-bottom: 10px;
    }

    

    .info-container {
      border-left: 1px solid #ccc;
    }

    .info .contenido {
      margin-top: 15px;
    }

    .info .titulo {
      border-bottom: 1px solid #ccc;
      font-size: 14px;
      font-weight: bold;
      padding-bottom: 5px;
    }

    .lider-estadistica {
      font-size: 12px;
    }

    .lider-nombre {
      font-size:16px;
      /* font-weight: bold; */
    }

    .lider-equipo {
      color: #666;
      font-size:11px;
    }

    .lider-info {
      font-size: 12px;
      font-weight: bold;
    }

    .lider-estadistica-valor {
      font-size: 24px;
    }

    .oakland-green {
      background:#003831;
    }
    .oakland-green .navbar-brand {
      color:#EFB21E;
    }

    .oakland-green .navbar-brand:hover {
      color:#fff;
    }

    .oakland-green .nav-link {
      color:#EFB21E;
    }

    .oakland-green .nav-link:hover {
      color:#fff;
    }

    .oakland-green .nav-link.disabled {
      color:#666;
    }
    
  </style>

    
    <!-- Custom styles for this template -->
    <link href="css/starter-template.css" rel="stylesheet">
  </head>
  <body>
    
<nav class="navbar navbar-expand-md fixed-top oakland-green">
  <a class="navbar-brand" href="#">emitlán</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
        <div class="dropdown-menu" aria-labelledby="dropdown01">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
      <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>

<main role="main" class="container-fluid pl-5 pr-5">

    @include('scores.scores_h')
    @yield('contenido')

</main><!-- /.container -->


    <script src="js/jquery-3.5.1.slim.min.js" ></script>
      <script>window.jQuery || document.write('<script src="js/jquery.slim.min.js"><\/script>')</script><script src="js/bootstrap.bundle.min.js"></script>

      
  </body>
</html>
