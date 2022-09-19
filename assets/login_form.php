<?php

if (isset($_SESSION['error_msg'])) {
	$msg = $_SESSION['error_msg'];
}

?>

<div class="login-box move-left-50">
  <div class="login-logo">
    <img src="images/logo-big.webp">
  </div>
  <br>

  <form class="login-form" id="login" method="POST">

    <div class="center-text"> Login: </div>

      <br>
      <div>
        <input type="text" name="username" id="username" class="center-text login-fields" placeholder="Username" autofocus><br>
      </div>

      <div>
        <input type="password" name="password" id="password" class="center-text login-fields" placeholder="Password"><br><br>
      </div>

      <div>
        <input type="submit" class="submit login-fields" name="submitBtnLogin" value="Login"><br><br>
      </div>


      <div class="loginMsg"><?php echo $msg; ?></div>

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
      $("body").css("background-image", "url('images/background_mobile.webp')");
      $(".header").css("border-bottom", "none");
    }
    else{
      $("body").css("background-image", "url('images/background.webp')");
    }
  }

  mediaSize();
  window.addEventListener('resize', mediaSize, false);
  $("#headerTop").css("background-color", "rgba(10,10,10,0)");
  $("#pc-code-logo").css("display", "none");

</script>