<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$jobtitle = $skills = $experience = "";
$jobtitle_err = $skills_err = $experience_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_jobtitle = trim($_POST["jobtitle"]);
    if(empty($input_jobtitle)){
        $jobtitle_err = "Please enter a Job Profile.";
    } elseif(!filter_var($input_jobtitle, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $jobtitle_err = "Please enter a valid Job Profile.";
    } else{
        $jobtitle = $input_jobtitle;
    }
    
    // Validate address address
    $input_skills = trim($_POST["skills"]);
    if(empty($input_skills)){
        $skills_err = "Please enter the skills required.";     
    } else{
        $skills = $input_skills;
    }
    
    // Validate salary
    $input_experience = trim($_POST["experience"]);
    if(empty($input_salary)){
        $salary_err = "Please enter the experience.";     
    } elseif(!ctype_digit($input_salary)){
        $experience_err = "Please enter a positive value.";
    } else{
        $experience = $input_experience;
    }
    
    // Check input errors before inserting in database
    if(empty($jobtitle_err) && empty($skills_err) && empty($experience_err)){
        // Prepare an update statement
        $sql = "UPDATE admin_users SET jobtitle=?, skills=?, experience=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_jobtitle, $param_skills, $param_experience, $param_id);
            
            // Set parameters
            $param_jobtitle = $jobtitle;
            $param_address = $skills;
            $param_experience = $experience;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM admin_users WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $jobtitle = $row["jobtitle"];
                    $address = $row["skills"];
                    $salary = $row["experience"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Update Record </title>
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
                        <h2> Update Record </h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group <?php echo (!empty($jobtitle_err)) ? 'has-error' : ''; ?>">
                            <label>Job Profile</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $jobtitle_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($skills_err)) ? 'has-error' : ''; ?>">
                            <label>Skills Required</label>
                            <textarea name="skills" class="form-control"><?php echo $skills; ?></textarea>
                            <span class="help-block"><?php echo $skills_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($experience_err)) ? 'has-error' : ''; ?>">
                            <label>Experience Required</label>
                            <input type="text" name="experience" class="form-control" value="<?php echo $experience; ?>">
                            <span class="help-block"><?php echo $experience_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </form>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>