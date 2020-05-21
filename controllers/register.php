<?php
   
    // Database connection
    include('config/db.php');

    // Swiftmailer lib
    require_once './lib/vendor/autoload.php';
    
    // Error & success messages
    global $success_msg, $email_exist, $f_NameErr, $l_NameErr, $_emailErr, $_mobileErr, $_passwordErr;
    global $fNameEmptyErr, $lNameEmptyErr, $emailEmptyErr, $mobileEmptyErr, $passwordEmptyErr, $email_verify_err, $email_verify_success;
    
    // Set empty form vars for validation mapping
    $_first_name = $_last_name = $_email = $_mobile_number = $_password = "";

    if(isset($_POST["submit"])) {
        $firstname     = $_POST["firstname"];
        $lastname      = $_POST["lastname"];
        $email         = $_POST["email"];
        $mobilenumber  = $_POST["mobilenumber"];
        $password      = $_POST["password"];

        // check if email already exist
        $email_check_query = mysqli_query($connection, "SELECT * FROM users WHERE email = '{$email}' ");
        $rowCount = mysqli_num_rows($email_check_query);


        // PHP validation
        // Verify if form values are not empty
        if(!empty($firstname) && !empty($lastname) && !empty($email) && !empty($mobilenumber) && !empty($password)){
            
            // check if user email already exist
            if($rowCount > 0) {
                $email_exist = '
                    <div class="alert alert-danger" role="alert">
                        User with email already exist!
                    </div>
                ';
            } else {
                // clean the form data before sending to database
                $_first_name = mysqli_real_escape_string($connection, $firstname);
                $_last_name = mysqli_real_escape_string($connection, $lastname);
                $_email = mysqli_real_escape_string($connection, $email);
                $_mobile_number = mysqli_real_escape_string($connection, $mobilenumber);
                $_password = mysqli_real_escape_string($connection, $password);

                // perform validation
                if(!preg_match("/^[a-zA-Z ]*$/", $_first_name)) {
                    $f_NameErr = '<div class="alert alert-danger">
                            Only letters and white space allowed.
                        </div>';
                }
                if(!preg_match("/^[a-zA-Z ]*$/", $_last_name)) {
                    $l_NameErr = '<div class="alert alert-danger">
                            Only letters and white space allowed.
                        </div>';
                }
                if(!filter_var($_email, FILTER_VALIDATE_EMAIL)) {
                    $_emailErr = '<div class="alert alert-danger">
                            Email format is invalid.
                        </div>';
                }
                if(!preg_match("/^[0-9]{10}+$/", $_mobile_number)) {
                    $_mobileErr = '<div class="alert alert-danger">
                            Only 10-digit mobile numbers allowed.
                        </div>';
                }
                if(!preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{6,20}$/", $_password)) {
                    $_passwordErr = '<div class="alert alert-danger">
                             Password should be between 6 to 20 charcters long, contains atleast one special chacter, lowercase, uppercase and a digit.
                        </div>';
                }
                
                // Store the data in db, if all the preg_match condition met
                if((preg_match("/^[a-zA-Z ]*$/", $_first_name)) && (preg_match("/^[a-zA-Z ]*$/", $_last_name)) &&
                 (filter_var($_email, FILTER_VALIDATE_EMAIL)) && (preg_match("/^[0-9]{10}+$/", $_mobile_number)) && 
                 (preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/", $_password))){

                    // Generate random activation token
                    $token = md5(rand().time());

                    // Password hash
                    $password_hash = password_hash($password, PASSWORD_BCRYPT);

                    // Query
                    $sql = "INSERT INTO users (firstname, lastname, email, mobilenumber, password, token, is_active,
                    date_time) VALUES ('{$firstname}', '{$lastname}', '{$email}', '{$mobilenumber}', '{$password_hash}', 
                    '{$token}', '0', now())";
                    
                    // Create mysql query
                    $sqlQuery = mysqli_query($connection, $sql);
                    
                    if(!$sqlQuery){
                        die("MySQL query failed!" . mysqli_error($connection));
                    } 

                    // Send verification email
                    if($sqlQuery) {
                        $msg = 'Click on the activation link to verify your email. <br><br>
                          <a href="http://localhost:8888/php-user-authentication/user_verificaiton.php?token='.$token.'"> Click here to verify email</a>
                        ';

                        // Create the Transport
                        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
                        ->setUsername('your_email@gmail.com')
                        ->setPassword('your_email_password');

                        // Create the Mailer using your created Transport
                        $mailer = new Swift_Mailer($transport);

                        // Create a message
                        $message = (new Swift_Message('Please Verify Email Address!'))
                        ->setFrom([$email => $firstname . ' ' . $lastname])
                        ->setTo($email)
                        ->addPart($msg, "text/html")
                        ->setBody('Hello! User');

                        // Send the message
                        $result = $mailer->send($message);
                          
                        if(!$result){
                            $email_verify_err = '<div class="alert alert-danger">
                                    Verification email coud not be sent!
                            </div>';
                        } else {
                            $email_verify_success = '<div class="alert alert-success">
                                Verification email has been sent!
                            </div>';
                        }
                    }
                }
            }
        } else {
            if(empty($firstname)){
                $fNameEmptyErr = '<div class="alert alert-danger">
                    First name can not be blank.
                </div>';
            }
            if(empty($lastname)){
                $lNameEmptyErr = '<div class="alert alert-danger">
                    Last name can not be blank.
                </div>';
            }
            if(empty($email)){
                $emailEmptyErr = '<div class="alert alert-danger">
                    Email can not be blank.
                </div>';
            }
            if(empty($mobilenumber)){
                $mobileEmptyErr = '<div class="alert alert-danger">
                    Mobile number can not be blank.
                </div>';
            }
            if(empty($password)){
                $passwordEmptyErr = '<div class="alert alert-danger">
                    Password can not be blank.
                </div>';
            }            
        }
    }
?>