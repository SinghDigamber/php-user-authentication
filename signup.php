<?php include('./controllers/register.php'); ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>PHP User Registration System Example</title>
    <!-- jQuery + Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<!-- required before boostrap for Boostrap tooltips -->
	<script src="js/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>

<body>
   
   <?php include('./header.php'); ?>

    <div class="App">
        <div class="vertical-center">
            <div class="inner-block">
                <form action="" method="post">
                    <h3>Register</h3>

                    <?php echo $success_msg; ?>
                    <?php echo $email_exist; ?>

                    <?php echo $email_verify_err; ?>
                    <?php echo $email_verify_success; ?>

                    <div class="form-group">
                        <label>First name</label>
                        <input type="text" class="form-control" name="firstname" id="firstName" />

                        <?php echo $fNameEmptyErr; ?>
                        <?php echo $f_NameErr; ?>
                    </div>

                    <div class="form-group">
                        <label>Last name</label>
                        <input type="text" class="form-control" name="lastname" id="lastName" />

                        <?php echo $l_NameErr; ?>
                        <?php echo $lNameEmptyErr; ?>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" id="email" />

                        <?php echo $_emailErr; ?>
                        <?php echo $emailEmptyErr; ?>
                    </div>

                    <div class="form-group">
                        <label>Mobile</label>
                        <input type="text" class="form-control" name="mobilenumber" id="mobilenumber" />

                        <?php echo $_mobileErr; ?>
                        <?php echo $mobileEmptyErr; ?>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" id="password" />

                        <?php echo $_passwordErr; ?>
                        <?php echo $passwordEmptyErr; ?>
                    </div>

                    <button type="submit" name="submit" id="submit" class="btn btn-outline-primary btn-lg btn-block">Sign up
                    </button>
                </form>
				
            </div>
        </div>
    </div>
	
	<script>
		
		const element = document.querySelector("form");
		const inputNodes = element.getElementsByTagName("input");
				
		for (let i = 0; i < inputNodes.length; i++) {
			  inputId = inputNodes[i].id;
			  addTooltip(inputId);
			}
			
		function addTooltip(inputId){
			input = document.getElementById(inputId);
			input.setAttribute("data-toggle", "tool-tip");
			input.setAttribute("data-placement", "top");
						
			switch(inputId){
				case 'firstName':
				case 'lastName':
					input.setAttribute("title", "Only letters and white space allowed");
					break;
				case 'email':
					input.setAttribute("title", "Enter valid email address");
					break;		
				case 'mobilenumber':
					input.setAttribute("title", "10 digit phone number");
					break;		
				case 'password':
					input.setAttribute("title", "Password should be between 6 to 20 characters long, contains at least one special chacter, lowercase, uppercase and a digit");
					break;							
				default:
					input.setAttribute("title", "Put something here");
				
			}

		}
		
		$(function () {
			$(inputNodes).tooltip({ boundary: 'window' })
		})
	</script>

</body>

</html>