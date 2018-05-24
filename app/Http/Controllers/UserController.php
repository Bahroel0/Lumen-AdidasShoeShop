<?php
	namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\User;

class UserController extends Controller{
    /**
    * Register new user
    *
    * @param $request Request
    */
    public function register(Request $request){
        $hasher = app()->make('hash');
        $email = $request->input('email');

        $user = User::where('email', $email)->get();

        if($user){
            $res['success'] = false;
            $res['message'] = 'Email already exist, use another email!';
            return response($res);
        }else{
            $password = $hasher->make($request->input('password'));
            $register = User::create([
                'email'=> $email,
                'password'=> $password,
            ]);

            if ($register) {
                $res['success'] = true;
                $res['message'] = 'Success register!';
                return response($res);
            }else{
                $res['success'] = false;
                $res['message'] = 'Failed to register!';
                return response($res);
            }
        }

        
    }

    public function login(Request $request){
        $hasher = app()->make('hash');
        $email = $request->input('email');
        $password = $request->input('password');
        $login = User::where('email', $email)->first(); 

        if (!$login) {
            $res['success'] = false;
            $res['message'] = 'Your email or password incorrect!';
            return response($res);
        }else{
            if ($hasher->check($password, $login->password)) {

                // generate remember_token
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < 10; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }

                $api_token = sha1(time());
                $remember_token = User::where('id', $login->id)->update(['remember_token' => $randomString]);
                $create_token = User::where('id', $login->id)->update(['api_token' => $api_token]);
                if ($create_token) {
                    $res['success'] = true;
                    $res['remember_token'] = $randomString;
                    $res['api_token'] = $api_token;
                    $res['message'] = $login;
                    return response($res);
                }
            }else{
                $res['success'] = true;
                $res['message'] = 'You email or password incorrect!';
                return response($res);
            }
        }
    }

    /**
    * Get user by id
    *
    * URL /user/{id}
    */
    public function getUser(Request $request, $id){
        $user = User::where('id', $id)->get();
        if ($user) {
            $res['success'] = true;
            $res['message'] = $user;
            return response($res);
        }else{
            $res['success'] = false;
            $res['message'] = 'Cannot find user!';
            return response($res);
        }
    }

    public function changePassword(Request $request){
        $email = $request->input('email');
        $hasher = app()->make('hash');

        $password = $hasher->make($request->input('password'));
        $user = User::where('email', $email)->first();

        if($user){
            $user->password = $password;
            $user->save();
            $res['success'] = true;
            $res['message'] = "Success change password.";
            return response($res);
        }
    }

    public function logout(Request $request){
        $email = $request->input('email');
        $logout = User::where('email', $email)->first(); 
        if(!$logout){
            $res['success'] = false;
            $res['message'] = 'Your email incorrect!';
            return response($res);
        }else{
            $remember_token = User::where('id', $logout->id)->update(['remember_token' => null]);
            if($remember_token){
                $res['success'] = true;
                $res['message'] = 'Logout success';
                return response($res);
            }
        }
    }

}