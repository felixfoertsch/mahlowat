<?php
$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
$baseurl = "http://" . $_SERVER['SERVER_NAME'] . $uri_parts[0];
?>
<!DOCTYPE html>
<html>
<head>
  <title>EUromat - Der Bundestagswahl-Helfer zum Thema Europa</title>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <meta content="EUromat">
  <meta name="image_src" content="img/euromat.png"/>
  <meta name="description" content="Der EUromat ist ein Angebot JEF Sachsen zur Bundestagswahl. Er ermöglicht es, zu europapolitischen Themen die eigenen Standpunkte mit denen der Parteien abzugleichen, die zur Wahl antreten."/>
  <meta property="og:title" content="EUromat"/>
  <meta property="og:type"  content="website"/>
  <meta property="og:image" content="img/euromat.png"/>
  <meta property="og:url"   content=""/>
  <meta property="og:site-name" content="eurom.at"/>
  <meta property="og:description" content="Der EUromat ist ein Angebot JEF Sachsen zur Bundestagswahl. Er ermöglicht es, zu europapolitischen Themen die eigenen Standpunkte mit denen der Parteien abzugleichen, die zur Wahl antreten."/>
  <link href="css/jef.min.css" rel="stylesheet" media="screen">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link href="shariff/shariff.min.css" rel="stylesheet">
  <script src="js/jquery-2.0.2.min.js"></script>
</head>
<body>
  <div class="container mt-25">

    <div class="jumbotron">
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <img class="img-responsive" src="img/euromat.png" title="Euromat Logo"/>
        </div>
        <div class="col-md-12 mt-50">
          <p>Der EUromat ist ein technisches Hilfsmittel, das es ermöglicht, zu europapolitischen Themen die eigenen Standpunkte mit denen der Parteien abzugleichen, die zur Bundestagswahl antreten.</p>
          <p>Er ist selbstverständlich nur als Automat ohne Hirn zu verstehen und spricht keine Wahlempfehlungen aus.</p>
          <p>Für ihre Stellungnahmen zu den Thesen sind die Parteien selbst verantwortlich.</p>
          <p class="text-center"><a class="btn btn-primary btn-lg" href="mahlowat.php" title="EUromat starten">Mit der Befragung beginnen!</a></p>
        </div>
        <div class="col-md-12 mt-25">
          <p class="text-center">
            <small>Die häufig gestellten Fragen findest Du hier: <a href="faq.php" title="Fragen und Antworten">FAQ</a>.</small>
          </p>
        </div>
      </div>
    </div>

    <div class="col-xs-12">
      <div class="shariff" data-url="<?php echo $baseurl; ?>" data-referrer-track=null></div>
    </div>

  </div>

  <script src="shariff/shariff.min.js"></script>
</body>
</html>