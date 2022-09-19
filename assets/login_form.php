<?php

if(isset($_SESSION['error_msg'])){ 
  $msg = $_SESSION['error_msg'];
} 

?>

<div class="login-box move-left-50">

  <form class="login-form" id="login" method="POST">

    <fieldset>
    <legend>&nbsp; <b> ULOGUJ SE </b> &nbsp; </legend>

      <br><br>
      <div class="input-row">
        USERNAME:&nbsp <input type="text" name="username" id="username" class="input-right input-kupac" autofocus><br><br>
      </div>

      <div class="input-row">
        PASSWORD:&nbsp <input type="password" name="password" id="password" class="input-right input-kupac"><br><br>
      </div>

      <input type="submit" class="submit" name="submitBtnLogin" value="LOGIN"><br><br>

      <span class="loginMsg"><?php echo $msg;?></span>

      <br>

    </fieldset>

  </form>     

</div>

<script type="text/javascript">

/*
    if (window.matchMedia('(max-device-width: 768px)').matches) {
      alert('<768');
    } else {
      alert('>768');
    }
*/

  function mediaSize(){
    if (window.matchMedia('(max-device-width: 768px)').matches){
      $("body").css("background-image", "url('images/mont_background_mobile.jpg')");
      $(".header").css("border-bottom", "none");
    } 
    else{
      $("body").css("background-image", "url('images/mont_background.jpg')");
    }
  }

  mediaSize();
  window.addEventListener('resize', mediaSize, false);
  $("#headerTop").css("background-color", "rgba(10,10,10,0)");
  $("#pc-code-logo").css("display", "none");

</script>  