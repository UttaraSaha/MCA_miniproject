<?php
  session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jQuery/jquery-2.1.1.js" type="text/javascript"></script>
    <link rel="stylesheet" href="./login.css">
    <!-- Favicon -->
    <link href="img/TU_logo.jpeg" rel="icon">

  </head>
  <body class="Home_body">
    

    <?php require 'db_connection.php' ?>
    <?php
  
    $success = "";
    $error_message = "";
    
    if(isset($_POST['submit_email'])) {
        $email=$_POST["email"];
        $enroll=$_POST["enroll"];
        
        $_SESSION["email"] = $email;

        if (ValidEmail($email)==1) {
          
          $newQuery="SELECT Email FROM $db.Account WHERE Email='$email'";
          $res=mysqli_query($conn,$newQuery);
          echo mysqli_num_rows($res);
          
          if(mysqli_num_rows($res)==0){
            echo "New Email registered";
            $pw=SendEmail();
            $InsertQuery="INSERT INTO $db.Account(enroll,password,Email) VALUES ('$enroll', '$pw', '$email')";
            $queryExecute=mysqli_query($conn,$InsertQuery);
            $success=1;
          }
          else
          {
            echo "Already registered";
            $pw=SendEmail();
            $InsertQuery="UPDATE $db.Account SET password='$pw' WHERE Email='$email'";
            $queryExecute=mysqli_query($conn,$InsertQuery);
            $success=1;
          }
        }
        
    }
    if(isset($_POST["submit_otp"])) {
        $input=$_POST["otp"];
        $email=$_SESSION["email"];
        echo "email is $email";
        $result = mysqli_query($conn,"SELECT * FROM $db.Account WHERE password='$input'");
        echo mysqli_num_rows($result)." Values found";
        if(mysqli_num_rows($result)>0) {
            $success = 2;	
        } else {
            $success =1;
            echo "Invalid OTP!";
        }
    }
    if (isset($_POST["reset_password"])){
      $p1=$_POST["password1"];
      $p2=$_POST["password2"];

      $email=$_SESSION["email"];
      echo "email is $email";
      
      if ($p1==$p2) {
        $InsertQuery="UPDATE $db.Account SET password='$p1' WHERE Email='$email'";
        $queryExecute=mysqli_query($conn,$InsertQuery);
        echo "resetted password";
      }
      else {
        echo "password should be same";
      }
    }
    if (isset($_POST["Login"])){
      $email = $_POST['login_email'];

      $password = $_POST['login_pw'];
      
      
      echo "$email";
      $query = "SELECT * FROM $db.Account WHERE Email = '$email' AND password = '$password'";
      
      $result = mysqli_query($conn,$query);
      
      $num = mysqli_num_rows($result);
      if($num == 1){
      
      echo "Welcome!";
      
      }
      
      else{
      
      echo "Incorrect email or password";
      
      }
    }
    function email_validation($str) {
        return (!preg_match(
    "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $str))
            ? FALSE : TRUE;
    }
    function SendEmail() {
            $n = 6;
            
            $otp=generateNumericOTP($n);
            
            $to = "kaushikbaruah24x7.kb@gmail.com";
            $subject = "This is subject";
        
            $message = "<b>This is HTML message.</b>";
            $message .= "<h1>Your OTP is $otp.</h1>";
        
            $header = "From: minorprojectmca3@gmail.com" . "\r\n" ;
            $retval = mail($to,$subject,$message,$header);
            if(isset($retval))//change
            {
                echo "Message sent successfully...";
                echo "otp is $otp";
            }
            else
            {
                echo "Message could not be sent...";
            }
            
            return $otp;
    }
    function generateNumericOTP($n) {
          
        // Take a generator string which consist of
        // all numeric digits
        $generator = "1357902468ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      
        $result = "";
      
        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand()%(strlen($generator))), 1);
        }
      
        // Return result
        return $result;
    }
    function ValidEmail($n){
      $email = $n;

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo("$email is a valid email address");
            return 1;
        } else {
            echo("$email is not a valid email address");
            return 0;
        }
    }
    
    ?>
    
    <!--navbar-->
     <nav class="navbar navbar-expand-lg bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Dropdown
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled">Disabled</a>
            </li>
          </ul>
         
            <div class="nav-item">
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#signupModal">
                 Sign-up
              </button>
            </div>
            </div>
            <div class="nav-item">
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                Log-in
              </button>
            </div>
        </div>
      </div>
    </nav>
    <!--Top_section-->
  <div class="container text-center">
      <div class="row">
        <div class="col1">
          <div class="logo">
          <img src="img/TU_logo.jpeg" class="d-block w-100" alt="..." >
          </div>
        </div>
        <div class="col">
          <div class ="heading">
            <h1>Training and Placement Cell</h1><br>
            <h5>School of engineering, Tezpur University</h5>
          </div>
        </div>
      </div>
    </div>
   
<!--LOGIN MODAL-->
<div id="loginModal" class="modal fade">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
       <button type="button" class="btn-close btn-close-white"
       data-bs-dismiss="modal"></button>
       <div class="myform bg-dark">
        <h1 class="text-center">Login Form</h1>
        <form name="frmUser" method="post" action="">
          <div>
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" name="login_email" name="email" class="form-control" id="" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
          </div>
          <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" name="login_pw" class="form-control" id="">
          </div>
          <!-- <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
          </div> -->
          <button type="submit" name="Login" class="btn btn-primary" >
                     Log In
          </button>
        </form>
          </div>
        </form>
       </div>
      </div>
    </div>
  </div>
</div>
<!--LOGIN MODAL END-->

<!--Signup MODAL-->
<div id="signupModal" class="modal fade">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
       <button type="button" class="btn-close btn-close-white"
       data-bs-dismiss="modal"></button>
       <div class="myform bg-dark">
        <h1 class="text-center">Sign-up Form</h1>
        <form name="frmUser" method="post" action="">
            <?php
                if ($success==2){
            ?>
            <script type="text/javascript">
                $(document).ready(function(){
                $("#signupModal").modal('show');
            });
            </script>
              <!-- <form name="frmUser" method="post" action=""> -->
              <div>
                  <label for="exampleInputEmail1" class="form-label">Enter Password</label>
                  <input type="password" name="password1" class="form-control" id="" aria-describedby="emailHelp">
                  <div id="emailHelp" class="form-text"></div>
              </div>
              <div>
                  <label for="exampleInputEmail1" class="form-label">Confirm Password</label>
                  <input type="password" name="password2" class="form-control" id="" aria-describedby="emailHelp">
                  <div id="emailHelp" class="form-text"></div>
              </div>
              <button type="submit" name="reset_password" class="btn btn-primary" >
                      Reset Password
              </button>
              <!-- </form> -->
            <?php
                }
                else{
            ?>
            
            
            <?php
              if ($success==1) 
              {
            ?>
                  <script type="text/javascript">
                      $(document).ready(function(){
                      $("#signupModal").modal('show');
                  });
                  </script>
                  <!-- <form name="frmUser" method="post" action=""> -->
                  <div>
                      <label for="exampleInputEmail1" class="form-label">Enter OTP</label>
                      <div >
                        <input type="text" name="otp" placeholder="One Time Password" class="form-control" >
                      </div>
                      <div id="emailHelp" class="form-text">This will be your default password.</div>
                  </div>
                  <button type="submit" name="submit_otp" class="btn btn-primary" >
                      Submit OTP
                  </button>
                  <!-- </form> -->
            <?php
              
                }
                else 
                {  
            ?>
            <div>
                <label for="exampleInputEmail1" class="form-label">Enrollment no.</label>
                <input type="text" name="enroll" placeholder="eg: CSM21024" class="form-control" >
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div>
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="text" name="email" placeholder="example@gmail.com" class="form-control" >
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <button type="submit" name="submit_email" class="btn btn-primary" >
                Submit
            </button>
                  
            <?php
                 }
              }
              ?>
            </form>
          </div>
       </div>
      </div>
    </div>
  </div>
</div>
<!--SIGup MODAL END-->
<!-- OTP Model -->
<div id="OTPModal" class="modal fade">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
       <button type="button" class="btn-close btn-close-white"
       data-bs-dismiss="modal"></button>
       <div class="myform bg-dark">
        <h1 class="text-center">Sign-up Form</h1>
            <div>
                <label for="exampleInputEmail1" class="form-label">Enter OTP</label>
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">This will be your default password.</div>
            </div>
          </div>
       </div>
      </div>
    </div>
  </div>
</div>
<!-- END -->
</body>
</html>