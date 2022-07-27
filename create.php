<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$jobtitle  = $skills = $experience = "";
$jobtitle_err = $skills_err = $experience_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_jobtitle = trim($_POST["jobtitle"]);
    if(empty($input_jobtitle)){
        $jobtitle_err = "Please enter a Job Profile.";
    } elseif(!filter_var($input_jobtitle, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $jobtitle_err = "Please enter a valid Job Profile.";
    } else{
        $jobtitle = $input_jobtitle;
    }
    
    // Validate address
    $input_skills = trim($_POST["skills"]);
    if(empty($input_skills)){
        $skills_err = "Please enter the skills required.";     
    } else{
        $skills = $input_skills;
    }
    
    // Validate salary
    $input_experience = trim($_POST["experience"]);
    if(empty($input_experience)){
        $experience_err = "Please enter the experince.";     
    } elseif(!ctype_digit($input_salary)){
        $experience_err = "Please enter a positive value only.";
    } else{
        $experience = $input_experience;
    }
    
    // Check input errors before inserting in database
    if(empty($jobtitle_err) && empty($skills_err) && empty($experience_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO admin_users ('jobtitle', 'skills', 'experience') VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_jobtitle, $param_skills, $param_experience);
            
            // Set parameters
            $param_name = $jobtitle;
            $param_skills = $skills;
            $param_salary = $experience;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($jobtitle_err)) ? 'has-error' : ''; ?>">
                            <label>Job Profile</label>
                            <input type="text" name="jobtitle" class="form-control" value="<?php echo $jobtitle; ?>">
                            <span class="help-block"><?php echo $jobtitle_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($skills_err)) ? 'has-error' : ''; ?>">
                            <label>Skills Required</label>
                            <textarea name="skills" class="form-control"><?php echo $skills; ?></textarea>
                            <span class="help-block"><?php echo $skills_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($salary_err)) ? 'has-error' : ''; ?>">
                            <label>Experince Required</label>
                            <input type="text" name="experience" class="form-control" value="<?php echo $experience; ?>">
                            <span class="help-block"><?php echo $experience_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>