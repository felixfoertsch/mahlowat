<?php
include 'includes/functions.php';
include 'includes/elements.php';
//include 'includes/theses.php';
$data_content = file_get_contents("config/data.json");
if(!$data_content){
  echo "ERROR READING CONFIG";
} else {
  $data = json_decode($data_content, true);
  if($data == NULL){
    echo "ERROR PARSING CONFIG";
  }
//$theses = get_theses_array();
  $theses = $data['theses'];
  $theses_count = sizeof($theses);
  $ans = Array();
  $emph = Array();
  $answerstring = '';
  $warning = false;
  $count = 'undefined';
  if(isset($_POST['count'])){
    $count = $_POST['count'];
  }
  if(isset($_POST['ans'])){
    $answerstring = $_POST['ans'];
    $retval = result_from_string($answerstring, $theses_count);
    $ans = $retval[0];
    $emph = $retval[1];
  } elseif(isset($_GET['ans'])){
    $answerstring = $_GET['ans'];
    $retval = result_from_string($answerstring, $theses_count);
    $ans = $retval[0];
    $emph = $retval[1];
  } else {
    $warning = true;
    for($i = 0; $i < $theses_count; $i++){
      $ans[$i] = 'skip';
      $emph[$i] = 1;
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>EUromat - Der Bundestagswahl-Helfer zum Thema Europa</title>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <meta content="Mahlowat">
  <link href="css/jef.min.css" rel="stylesheet" media="screen">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
  <script src="js/jquery-2.0.2.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/mahlowat.js"></script>

  <div id="savemodal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title" id="myModalLabel">Statistik</h4>
        </div>
        <div class="modal-body">
          Erlaubst du, dass dein Aufruf für die Statistik gezählt wird?<br>
          Falls du Nein auswählst, bist du lediglich als Logeintrag auf dem Server verewigt.<br>
          <small><a href="faq.php#log" target="_blank">Ich will aber gar keinen Logeintrag!</a></small>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" onclick="callResult(false)" style="width: 100px;"><i class="fa fa-times fa-lg"></i> Nein</button>
          <button type="button" class="btn btn-primary" onclick="callResult(true)" style="width: 100px;"><i class="fa fa-check fa-lg"></i> Ja</button>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <?php print_pagination($theses_count); ?>
        <?php print_thesesbox($theses); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 m-t-5">
          <button id="weight" type="button" class="btn btn-default btn-block" data-toggle="button">These doppelt gewichten</button>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 m-t-5">
          <button id='yes' type='submit' class='btn btn-success btn-block' name='yes' onclick="nextThesis('a')">
            <i class="fa fa-thumbs-up fa-lg"></i> Zustimmung
          </button>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 m-t-5">
          <button id='neutral' type='submit' class='btn btn-warning btn-block' name='neutral' onclick="nextThesis('b')">
            <i class="fa fa-asterisk fa-lg"></i> Neutral
          </button>
      </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 m-t-5">
          <button id='no' type='submit' class='btn btn-danger btn-block' name='no' onclick="nextThesis('c')">
            <i class="fa fa-thumbs-down fa-lg"></i> Ablehnung
          </button>
      </div>
    </div>
    
    <div class="row">
      <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 m-t-5">
          <button id='skip' type='submit' class='btn btn-default btn-block' name='skip' onclick="nextThesis('d')">
            Überspringen <i class="fa fa-step-forward fa-lg"></i>
           </button>
        </div>
      </div>

      <div class="row">
        <div class="col-md-10 col-md-offset-1">
          <div class="text-right">
            <hr>
            <small>Du kannst die Befragung <a href="index.php" title="Von vorn beginnen">neu starten</a> oder alle restlichen Thesen <a href="#" title="Auswertung anzeigen" onclick="gotoResultPage(resultArray)">überspringen</a>. </br> Der <a href="https://jef-sachsen.de/euromat">EUromat</a> ist ein Projekt der <a href="https://jef-sachsen.de/">JEF Sachsen</a>. Die häufig gestellten Fragen findest Du auf der <a href="faq.php?from=mahlowat.php" onclick="gotoFAQPage(event, resultArray)" title="FAQ">FAQ-Seite</a>.</small></p>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      var resultArray;
      var activeThesis = 0;
      var answerstring = '<?php echo $answerstring; ?>';
      $(function(){
        $('.tt').tooltip();
        $('.explic').hide();
        $('#weight').click(function(){
          $('#weight').toggleClass('btn-default');
          $('#weight').toggleClass('btn-info');
          if($('#weight').text() == 'These doppelt gewichten'){
            $('#weight').text('These wird doppelt gewichtet');
          } else {
            $('#weight').text('These doppelt gewichten');
          }
        });
        $('.explanationbutton').click(function(event){
          event.preventDefault();
          $('.explic').toggle(200);
        });
        thesesboxes = $('.singlethesis');
        pagination = $('#navigation li');
        resultArray = resultStringToArray(answerstring, thesesboxes.length);
        setPaginationColors(resultArray);
    // Remove the # from the hash, as different browsers may or may not include it
    var hash = location.hash.replace('#','');
    if(hash != '' && jQuery.isNumeric(hash)){
    // Show the hash if it's set
    loadThesis(hash);
  } else {
    loadThesis(activeThesis+1);
  }
});
      function gotoResultPage(result){
        count = '<?php echo $count; ?>';
        if(count != 'true' && count != 'false'){
          $('#savemodal').modal('show');
        } else if(count == 'true') {
          callResult(true);
        } else {
          callResult(false);
        }
      }
      function gotoFAQPage(evt, result){
        callPage(evt, 'faq.php?from=mahlowat.php'+window.location.hash, array2str(result), '<?php echo $count; ?>');
      }
      function callResult(count){
        ans = array2str(resultArray);
        if(count){
          url = "count.php?ans=" + ans;
          jQuery.get(url,function( data ) {
            callPage(null, 'result.php', ans, 'true');
          });
        } else {
          jQuery.get("count.php?false",function( data ) {
            callPage(null, 'result.php', ans, 'false');
          });
        }
      }
      function nextThesis(selection){
        multiply = false;
        if($('#weight').hasClass('active')){
          multiply = true;
        }
        resultArray[activeThesis] = result2letter(selection, multiply);
        pagination.eq(activeThesis).removeClass('pagination-yes pagination-neutral pagination-no');
        pagination.eq(activeThesis).addClass(letter2paginationclass(selection));
        if(activeThesis+1 < thesesboxes.length){
          loadThesis(activeThesis+2);
        } else {
    // call result page
    gotoResultPage(resultArray);
  }
}
function loadThesis(number){
  if(number > thesesboxes.length){
    number = 1;
  }
  activeThesis = number-1;
  thesesboxes.hide();
  pagination.removeClass('active');
  setClasses(resultArray[activeThesis]);
  thesesboxes.eq(number-1).show();
  pagination.eq(number-1).addClass('active');
  location.hash = number;
}
function letter2paginationclass(letter){
  switch(letter){
    case 'a':
    case 'e':
    return 'pagination-yes';
    break;
    case 'b':
    case 'f':
    return 'pagination-neutral';
    break;
    case 'c':
    case 'g':
    return 'pagination-no';
    break;
    case 'd':
    case 'h':
    return '';
    break;
  }
}
function setClasses(code){
  $('.explic').hide();
  if(code < 'e'){
    $('#weight').removeClass('btn-info');
    $('#weight').addClass('btn-default');
    $('#weight').removeClass('active');
    $('#weight').text('These doppelt gewichten');
  } else {
    $('#weight').addClass('btn-info');
    $('#weight').removeClass('btn-default');
    $('#weight').addClass('active');
    $('#weight').text('These wird doppelt gewichtet');
  }
  switch (code){
    case 'a':
    case 'e':
    $('#yes').addClass('btn-success');
    $('#neutral').removeClass('btn-warning');
    $('#no').removeClass('btn-danger');
    break;
    case 'b':
    case 'f':
    $('#yes').removeClass('btn-success');
    $('#neutral').addClass('btn-warning');
    $('#no').removeClass('btn-danger');
    break;
    case 'c':
    case 'g':
    $('#yes').removeClass('btn-success');
    $('#neutral').removeClass('btn-warning');
    $('#no').addClass('btn-danger');
    break;
    case 'd':
    case 'h':
    $('#yes').addClass('btn-success');
    $('#neutral').addClass('btn-warning');
    $('#no').addClass('btn-danger');
    break;
  }
}
function setPaginationColors(array){
  for(i = 0; i < array.length; i++){
    pagination.eq(i).addClass(letter2paginationclass(array[i]));
  }
}
</script>
</body>
</html>
