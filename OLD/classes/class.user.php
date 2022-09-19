<?php
class USER 
{
    private $db;
 
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }
 

    public function register($username,$password,$email,$id_korisnika,$status){

      /*Porvera da li vec postoji isti suername ili password u bazi*/
      try {
        $query = "SELECT * FROM admin WHERE id_korisnika=:id_korisnika AND username=:username OR email=:email";
        $stmt = $this->db->prepare($query);
        $stmt->bindparam('id_korisnika', $password, PDO::PARAM_STR);
        $stmt->bindparam('username', $username, PDO::PARAM_STR);
        $stmt->bindparam('email', $email, PDO::PARAM_STR);

        $stmt->execute();
        $count = $stmt->rowCount();
        $row   = $stmt->fetch(PDO::FETCH_ASSOC);

        if($count == 0) {

          /*Ukoliko su username i email jedinstveni vrsi se registracija usera sa statusom 0*/
          try{
            $stmt = $this->db->prepare("INSERT INTO admin(username,password,email,id_korisnika,status) VALUES(:username, :password, :email, :id_korisnika, :status)");
              
            $stmt->bindparam(":username", $username);
            $stmt->bindparam(":password", $password);
            $stmt->bindparam(":email", $email);   
            $stmt->bindparam(":id_korisnika", $id_korisnika); 
            $stmt->bindparam(":status", $status);  

            if($stmt->execute()){
              $body_message = "<h1>Hvala vam što ste se registrovali na aplikaciji Moje Fakture Online</h1>. <br><br>Da biste završili registraciju potrebno je da izvršite verifikaciju klikom na <a href='".ROOT_PATH."/register.php?email=".$email."&pw=".$password."'>link</a><br><br> Ukoliko vi niste započeli ovu registraciju ignorišite ovaj mejl.<br><br><br><a href='".ROOT_PATH."' style='text-decoration:none; color:green;'>Moje Poslovanje Online</a>";
              $subject = "User Registration";
              $this->sned_mail_now($email,$username,$subject,$body_message);
            } 

            if($this->is_loggedin()!="" && $statusUser > 2){
              $this->login($username,$password);
            }
            else{
              header('location:index.php');
            }
          }
          catch(PDOException $e){
             echo $e->getMessage();
          }    
         
        } 
        else {
          if($row['username'] != ""){
            $_SESSION['error_msg'] = "Username '" . $row['username'] . "' je zauzeto";
          }
          if($row['email'] != ""){
            $_SESSION['error_msg'] = "Email '" . $row['email'] . "' je zauzet";
          }
        }
      }
      catch(PDOException $e){
        echo $e->getMessage();
      }   
    }
 
    public function login($username,$password){

      if($username != "" && $password != "") {
        try {
          $query = "SELECT * FROM admin WHERE username=:username AND password=:password";
          $stmt = $this->db->prepare($query);
          $stmt->bindParam('username', $username, PDO::PARAM_STR);
          $stmt->bindValue('password', $password, PDO::PARAM_STR);
          $stmt->execute();
          $count = $stmt->rowCount();
          $row   = $stmt->fetch(PDO::FETCH_ASSOC);

          if($count = 1 && !empty($row)) {

            $_SESSION['sess_user_id']   = $row['id_admin'];
            $_SESSION['sess_user_name'] = $row['username'];
            $_SESSION['sess_id_korisnika'] = $row['id_korisnika'];
            $_SESSION['sess_user_status'] = $row['status'];

            /*preuzimanje informacija korisnika*/
            $query = "SELECT * FROM korisnici where id_korisnika=:id_korisnika and status=:status";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam('id_korisnika', $_SESSION['sess_id_korisnika'], PDO::PARAM_STR);
            $stmt->bindValue('status', 1, PDO::PARAM_STR);
            $stmt->execute();
            
            $korisnik_row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['sess_korisnik_name'] = $korisnik_row['korisnik'];
            $_SESSION['sess_korisnik_status'] = $korisnik_row['status'];
          } else {
            $_SESSION['error_msg'] = "Pogresan username ili password!";
          }
        } 
        catch (PDOException $e) {
          echo "Error : ".$e->getMessage();
        }
          header('location:index.php');
      }
    }
 
   public function is_loggedin()
   {
      if(isset($_SESSION['sess_user_id']))
      {
         return true;
      }
   }
 
   public function redirect($url)
   {
       header("Location: $url");
   }
 
   public function logout()
   {
        session_destroy();
        unset($_SESSION['sess_user_id']);
        header("location: index.php");
        return true;
   }

   public function sned_mail_now($send_to,$recipient,$subject,$body_message){

      //Include required phpmailer files
      require 'classes/PHPMailer.php';
      require 'classes/SMTP.php';
      require 'classes/Exception.php';  

      // Define name spaces
/*      use PHPMailer\PHPMailer\PHPMailer;
      use PHPMailer\PHPMailer\SMTP;
      use PHPMailer\PHPMailer\Exception;
*/
      //sender information
      $sender = "info@petarcvetic.com";
      $email_password = "Pep@9917";


      //Create instance of phpmailer
      //$mail = new PHPMailer();
      $mail = new PHPMailer\PHPMailer\PHPMailer();

      $mail->SMTPDebug = 0;              // Enable verbose debug output

      $mail->isSMTP();                   // Set mailer to use SMTP
      $mail->Host = HOST;    // Specify main and backup SMTP servers
      $mail->SMTPAuth = AUTH;          // Enable SMTP authentication
      $mail->SMTPSecure = SSL;         // Enable TLS encryption, `ssl` 
      $mail->Port = PORT;               // TCP port to connect to
      $mail->Username = EMAIL;         // SMTP username
      $mail->Password = PASS; // SMTP password

    //  $mail->Subject = $subject;
      $mail->Subject = $subject;

      $mail->setFrom(EMAIL, 'MontingTim - Kartica Dobavljaca');
      $mail->Body = $body_message;             //Email body
      $mail->addAddress($send_to, $recipient); // Add a recipient
      $mail->addAddress('');                   // Name is optional
      $mail->addReplyTo(EMAIL);
      //      $mail->addCC('cc@example.com');
      //      $mail->addBCC('bcc@example.com');

      //      $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //      $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
      $mail->isHTML(true);                                  // Set email format to HTML


      if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
      } else {
        echo 'Message has been sent';
      }
        
      $mail->smtpClose();    //Closing SMTP connection
   }
}
?>
 
