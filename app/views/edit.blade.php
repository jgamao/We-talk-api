<!doctype html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <title>User auth with Confide</title>
        {{-- Imports twitter bootstrap and set some styling --}}
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #EEE; } 
        h1{
            color: green;
        } 
    </style>
</head> 
<body> 
        <?php
            $message;
        ?>
        <?php 
            foreach($edit as $row)
            {
                $username = $row->username;
                $password = $row->password;
                $email = $row->email;
                $firstname = $row->firstname;
                $lastname = $row->lastname;
                $phone = $row->phone;
            }
         ?>

        <div class="container"> 
            <div>
                <h1>Edit Profile</h1>
                <a href="http://localhost:8000/upload">Upload Image</a> 
            </div>
            <form method="POST" action="{{{ URL::to('update') }}}" accept-charset="UTF-8">
                <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
                <fieldset>
                    <div class="form-group">
                        <label for="username">{{{ Lang::get('confide::confide.username') }}}</label>
                        <input class="form-control" placeholder="{{{ Lang::get('confide::confide.username') }}}" type="text" name="username" id="username" value="{{{ $username }}}">
                    </div>
                    <div class="form-group">
                        <label for="email">{{{ Lang::get('confide::confide.e_mail') }}} <small>{{ Lang::get('confide::confide.signup.confirmation_required') }}</small></label>
                        <input class="form-control" placeholder="{{{ Lang::get('confide::confide.e_mail') }}}" type="text" name="email" id="email" value="{{{ $email }}}">
                    </div>
                     <div class="form-group">
                        <label for="firstname">Firstname</label>
                        <input class="form-control" placeholder="Firstname" type="text" name="firstname" id="firstname" value="{{{ $firstname }}}">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Lastname</label>
                        <input class="form-control" placeholder="Lastname" type="text" name="lastname" id="lastname" value="{{{ $lastname }}}">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Cannot edit Phone!!</p>
                    </div>

                    @if (Session::get('error'))
                        <div class="alert alert-error alert-danger">
                            @if (is_array(Session::get('error')))
                                {{ head(Session::get('error')) }}
                            @endif
                        </div>
                    @endif

                    @if (Session::get('notice'))
                        <div class="alert">{{ Session::get('notice') }}</div>
                    @endif

                    <div class="form-actions form-group">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="edtPass">Edit Password</a>
                    </div>

                </fieldset>
            </form>
        </div>
    </body>
</html>