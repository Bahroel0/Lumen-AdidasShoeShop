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

}