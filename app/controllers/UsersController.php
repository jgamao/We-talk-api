<?php 

/**
 * UsersController Class
 *
 * Implements actions regarding user management
 */
class UsersController extends Controller
{

    /**
     * Displays the form for account creation
     *
     * @return  Illuminate\Http\Response
     */
    public function create()
    {
        return View::make(Config::get('confide::signup_form'));
    }

    /**
     * Stores new account
     *
     * @return  Illuminate\Http\Response
     */
    public function store()
    {                                                                                                       
        $repo = App::make('UserRepository');
        $users = $repo->signup(Input::all());
        $user=$users["user"];

        if ($user->id) {
            if (Config::get('confide::signup_email')) {
                Mail::queueOn(
                    Config::get('confide::email_queue'),
                    Config::get('confide::email_account_confirmation'),
                    compact('user'),
                    function ($message) use ($user) {
                        $message
                            ->to($user->email, $user->username)
                            ->subject(Lang::get('confide::confide.email.account_confirmation.subject'));
                    }
                );
            }

            $data[] = array(
                $users["username"] =>'username',
                $users["password"]=>'password'
            );

            $this->request($data);
 
            return Redirect::action('UsersController@login')
                ->with('notice', Lang::get('confide::confide.alerts.account_created'));
        } else {
            $error = $user->errors()->all(':message');

            return Redirect::action('UsersController@create')
                ->withInput(Input::except('password'))
                ->with('error', $error);
        }
    }

    /**
     *make a http request to make a new account in the xmpp server using xml
     *
     */
    private function request($data = []){
        $url = 'http://69.38.168.229:9090/plugins/userService/users';
        $request = cURL::newRawRequest('post', $url , $this->arrayToXML($data));
        $request->setHeader('Authorization', 'zoog101');
        $request->setHeader('Content-Type', 'application/xml');
    
        return $request->send();
    }

    /*
     *Converts array to xml
     *
     */
    public function arrayToXML($data) {
        
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.'<user/>');
        array_walk_recursive($data, array ($xml, 'addChild'));

        return $xml->asXML();
    }

    /**
     *Uploads image as profile pic
     *
     */
    public function upload()
    {
        $image = DB::table('uploads')->where('user_id', Confide::user()->id)->get();

        if($image == null){
            $file = array('image' => Input::file('image'));
            // setting up rules
            $rules = array('image' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
            // doing the validation, passing post data, rules and the messages
            $validator = Validator::make($file, $rules);
            if ($validator->fails()) {
                // send back to the page with the input data and errors
                return Redirect::to('upload')->withInput()->withErrors($validator);
            }
            else {
            // checking file is valid.
            if (Input::file('image')->isValid()) {
                $destinationPath = 'public/uploads'; // upload path
                $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
                $fileName = Input::file('image')->getClientOriginalName();// renaming image  
                    Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
                    // sending back with message
            
                    $repo = App::make('UserRepository');
                    $users = $repo->insertFile($destinationPath, $fileName);

                    $image = DB::table('uploads')->where('user_id', Confide::user()->id)->get();

                    Session::flash('success', 'Upload successfully'); 
                    return Redirect::to('upload');
            }
            else {
              // sending back with error message.
              Session::flash('error', 'uploaded file is not valid');
              return Redirect::to('upload');
            }
          }
        }else{
            $this->image();
            Session::flash('success', 'uploaded successfully');
            return Redirect::to('upload');
        }
    }

    /*
     *Retrieves the uploaded image
     *
     */
    public function retrieveImage()
    {
        $image = DB::table('uploads')->where('user_id', Confide::user()->id)->get();
            return View::make('retrieve')->with('image', $image);
    }

    /**
     * view for the update profile of the user
     *
     */ 
    public function edit()
    {
        $edit = DB::table('users')->where('id', Confide::user()->id)->get();
           
            return View::make('edit')->with('edit', $edit);
    }

    /**
     *Function that edits the profile of the user
     *
     */

    public function update()
    {
        $edit= DB::table('users')->where('id', Confide::user()->id)
            ->update(array(
                'username' => Input::get('username'),
                'email'    => Input::get('email'),
                'firstname'=> Input::get('firstname'),
                'lastname' => Input::get('lastname'),
        ));

        return Redirect::to('/');
    }


    /** 
     * Function that edits the password of the user
     *
     */
    public function editPass()
    {
        if(Input::get('password')==null){
            Session::flash('error', 'Field is required');
              return Redirect::to('edtPass');
        }
        if(Input::get('password') != Input::get('password_confirmation')){
            Session::flash('error', 'Password not match');
              return Redirect::to('edtPass');
        }else{
            $edit= DB::table('users')->where('id', Confide::user()->id)
                ->update(array(
                    'password' => Hash::make(Input::get('password')),
            ));

            return Redirect::to('/');
        }
    }   

    /**
     *function that edits the profile picture of the user
     *
     */
    public function image()
    {
        $file = array('image' => Input::file('image'));
        // setting up rules
        $rules = array('image' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($file, $rules);
        if ($validator->fails()) {
            // send back to the page with the input data and errors
            return Redirect::to('updateImg')->withInput()->withErrors($validator);
        }
        else {
        // checking file is valid.
            if (Input::file('image')->isValid()) {
                $destinationPath = 'public/uploads'; // upload path
                $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
                $fileName = Input::file('image')->getClientOriginalName();// renaming image
                $link = URL::to('uploads').'/'.$fileName;    
                    Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
                    // sending back with message

                     $editImg= DB::table('uploads')->where('user_id', Confide::user()->id)
                        ->update(array(
                            'filename' => $fileName,
                            'link'     => $link
                    ));

                    Session::flash('success', 'Upload successfully'); 
                    return Redirect::to('retrieveImage');
            }
            else {
              // sending back with error message.
              Session::flash('error', 'uploaded file is not valid');
              return Redirect::to('updateImg');
            }
        }
    }

    /**
     * Displays the login form
     *
     * @return  Illuminate\Http\Response
     */
    public function login()
    {
        if (Confide::user()) {
            return Redirect::to('/');
        } else {
            return View::make(Config::get('confide::login_form'));
        }
    }

    /**
     * Attempt to do login
     *
     * @return  Illuminate\Http\Response
     */
    public function doLogin()
    {
        $repo = App::make('UserRepository');
        $input = Input::all();

        if ($repo->login($input)) {
            return Redirect::intended('/');
            // return Confide::user();
        } else {
            if ($repo->isThrottled($input)) {
                $err_msg = Lang::get('confide::confide.alerts.too_many_attempts');
            } elseif ($repo->existsButNotConfirmed($input)) {
                $err_msg = Lang::get('confide::confide.alerts.not_confirmed');
            } else {
                $err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
            }

            return Redirect::action('UsersController@login')
                ->withInput(Input::except('password'))
                ->with('error', $err_msg);
        }
    }

    /**
     * Attempt to confirm account with code
     *
     * @param  string $code
     *
     * @return  Illuminate\Http\Response
     */
    public function confirm($code)
    {
        if (Confide::confirm($code)) {
            $notice_msg = Lang::get('confide::confide.alerts.confirmation');
            return Redirect::action('UsersController@login')
                ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_confirmation');
            return Redirect::action('UsersController@login')
                ->with('error', $error_msg);
        }
    }

    /**
     * Displays the forgot password form
     *
     * @return  Illuminate\Http\Response
     */
    public function forgotPassword()
    {
        return View::make(Config::get('confide::forgot_password_form'));
    }

    /**
     * Attempt to send change password link to the given email
     *
     * @return  Illuminate\Http\Response
     */
    public function doForgotPassword()
    {
        if (Confide::forgotPassword(Input::get('email'))) {
            $notice_msg = Lang::get('confide::confide.alerts.password_forgot');
            return Redirect::action('UsersController@login')
                ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_forgot');
            return Redirect::action('UsersController@doForgotPassword')
                ->withInput()
                ->with('error', $error_msg);
        }
    }

    /**
     * Shows the change password form with the given token
     *
     * @param  string $token
     *
     * @return  Illuminate\Http\Response
     */
    public function resetPassword($token)
    {
        return View::make(Config::get('confide::reset_password_form'))
                ->with('token', $token);
    }

    /**
     * Attempt change password of the user
     *
     * @return  Illuminate\Http\Response
     */
    public function doResetPassword()
    {
        $repo = App::make('UserRepository');
        $input = array(
            'token'                 =>Input::get('token'),
            'password'              =>Input::get('password'),
            'password_confirmation' =>Input::get('password_confirmation'),
        );

        // By passing an array with the token, password and confirmation
        if ($repo->resetPassword($input)) {
            $notice_msg = Lang::get('confide::confide.alerts.password_reset');
            return Redirect::action('UsersController@login')
                ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_reset');
            return Redirect::action('UsersController@resetPassword', array('token'=>$input['token']))
                ->withInput()
                ->with('error', $error_msg);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @return  Illuminate\Http\Response
     */
    public function logout()
    {
        Confide::logout();

        // return Redirect::to('/');

        return Redirect::action('UsersController@login');
    }
}