<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/utils/control_panel/login_utils.php";

if (isset($_POST['error'])) {
} else if (isset($_POST['submit'])) {

  if (
    empty($_POST['email']) ||
    empty($_POST['pwd'])
  ) {
    header("location: ./login.php?error=empty");
    exit();
  }

  if (!LoginUtils::isValidEmail($_POST['email'])) {
    header("location: /admin/?error=invalid_email");
    exit();
  }

  
  $result  = $_CONN->query("SELECT * FROM users WHERE user_email = \"".$_POST['email']."\"");
  if ($record = $result->fetch_assoc()) {
    $correct_pwd = password_verify( $_POST['pwd'], $record['user_pwd'] );

    if( $correct_pwd ) {

        session_unset();
        session_destroy();

        session_start();
        $_SESSION['user_id'] = $record['user_id'];
        $_SESSION['user_email'] = $record['user_email'];
        
        header("location: /admin/?error=invalid_email");
        exit();

    } else {
        header("location: /admin/?error=nouser");
        exit();
    }
  } else {
    header("location: /admin/?error=nouser");
    exit();
  }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/styles.css">
  <title>Document</title>
</head>

<body>

  <div class="container-fluid">
    <div class="row no-gutter">
      <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
      <div class="col-md-8 col-lg-6">
        <div class="login d-flex align-items-center py-5">
          <div class="container">
            <div class="row">
              <div class="col-md-9 col-lg-8 mx-auto">
                <h3 class="login-heading mb-4">Welcome back!</h3>
                <form method="POST">
                  <div class="form-label-group">
                    <input type="email" id="inputEmail" class="form-control" placeholder="Email address" name="email" required autofocus>
                    <label for="inputEmail">Email address</label>
                  </div>

                  <div class="form-label-group">
                    <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="pwd" required>
                    <label for="inputPassword">Password</label>
                  </div>

                  <!--div class="custom-control custom-checkbox mb-3">
                  <input type="checkbox" class="custom-control-input" id="customCheck1">
                  <label class="custom-control-label" for="customCheck1">Remember password</label>
                </div-->
                  <button class="btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2" name="submit" type="submit">Sign in</button>
                  <!--div class="text-center">
                  <a class="small" href="#">Forgot password?</a></div-->
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
  <style>
    :root {
      --input-padding-x: 1.5rem;
      --input-padding-y: 0.75rem;
    }

    .login,
    .image {
      min-height: 100vh;
    }

    .bg-image {
      background-image: url('https://source.unsplash.com/WEQbe2jBg40/600x1200');
      background-size: cover;
      background-position: center;
    }

    .login-heading {
      font-weight: 300;
    }

    .btn-login {
      font-size: 0.9rem;
      letter-spacing: 0.05rem;
      padding: 0.75rem 1rem;
      border-radius: 2rem;
    }

    .form-label-group {
      position: relative;
      margin-bottom: 1rem;
    }

    .form-label-group>input,
    .form-label-group>label {
      padding: var(--input-padding-y) var(--input-padding-x);
      height: auto;
      border-radius: 2rem;
    }

    .form-label-group>label {
      position: absolute;
      top: 0;
      left: 0;
      display: block;
      width: 100%;
      margin-bottom: 0;
      /* Override default `<label>` margin */
      line-height: 1.5;
      color: #495057;
      cursor: text;
      /* Match the input under the label */
      border: 1px solid transparent;
      border-radius: .25rem;
      transition: all .1s ease-in-out;
    }

    .form-label-group input::-webkit-input-placeholder {
      color: transparent;
    }

    .form-label-group input:-ms-input-placeholder {
      color: transparent;
    }

    .form-label-group input::-ms-input-placeholder {
      color: transparent;
    }

    .form-label-group input::-moz-placeholder {
      color: transparent;
    }

    .form-label-group input::placeholder {
      color: transparent;
    }

    .form-label-group input:not(:placeholder-shown) {
      padding-top: calc(var(--input-padding-y) + var(--input-padding-y) * (2 / 3));
      padding-bottom: calc(var(--input-padding-y) / 3);
    }

    .form-label-group input:not(:placeholder-shown)~label {
      padding-top: calc(var(--input-padding-y) / 3);
      padding-bottom: calc(var(--input-padding-y) / 3);
      font-size: 12px;
      color: #777;
    }

    /* Fallback for Edge
-------------------------------------------------- */

    @supports (-ms-ime-align: auto) {
      .form-label-group>label {
        display: none;
      }

      .form-label-group input::-ms-input-placeholder {
        color: #777;
      }
    }

    /* Fallback for IE
-------------------------------------------------- */

    @media all and (-ms-high-contrast: none),
    (-ms-high-contrast: active) {
      .form-label-group>label {
        display: none;
      }

      .form-label-group input:-ms-input-placeholder {
        color: #777;
      }
    }
  </style>
</body>

</html>