<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User auth with Confide</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #EEE; }
        h1{
            color:green;
        }
        .link,.edit{
            float:right;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome!!</h1>
        <a class="link" href="http://localhost:8000/users/logout">Logout</a>
        <a class="edit" href="edit">Edit Profile|</a> 
    	<div class="col-md-12">
        	<p class="lead">
    		    Hi <br />
    		    <?php 
                    echo Confide::user();
                ?>
    		</p>
        </div>
     </div>
</body> 
</html>


