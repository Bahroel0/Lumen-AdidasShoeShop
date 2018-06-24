<?php
	namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\User;
    use Illuminate\Support\Facades\DB;

class UserController extends Controller{

    public function register(Request $request){
        $hasher = app()->make('hash');
        $email = $request->input('email');
        $name = $request->input('name');
        $api_token = sha1(time());
        $user = User::where('email', $email)->first();

        if($user){
            $res['success'] = false;
            $res['message'] = 'Email already exist, use another email!';
            $res['user'] = null;
            return response($res);
        }else{
            $password = $hasher->make($request->input('password'));
            $register = User::create([
                'email'=> $email,
                'name' => $name,
                'password'=> $password,
                'api_token' => $api_token
            ]);

            if ($register) {
                // generate remember_token
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < 20; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }

                $user = User::where('email', $email)->first(); 
                $remember_token = User::where('id', $user->id)->update(['remember_token' => $randomString]);
                $userbaru = User::where('email', $email)->first(); 
                $res['success'] = true;
                $res['message'] = 'Pendaftaran akun berhasil';
                $res['user'] = $userbaru;
                return response($res);
            }else{
                $res['success'] = false;
                $res['message'] = 'Failed to register!';
                $res['user']    = null;
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
                for ($i = 0; $i < 20; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }

                
                $remember_token = User::where('id', $login->id)->update(['remember_token' => $randomString]);
                $userlogin = User::where('email', $email)->first();
                if ($remember_token) {

                    $res['success'] = true;
                    $res['message'] = 'Login berhasil';
                    $res['user'] = $userlogin;
                    return response($res);
                }
            }else{
                $res['success'] = false;
                $res['message'] = 'You email or password incorrect!';
                return response($res);
            }
        }
    }

    public function getUser(Request $request, $id){
        $user = User::where('id', $id)->first();
        if ($user) {
            $res['success'] = true;
            $res['message'] = 'Success register!';
            $res['user'] = $user;
            return response($res);
        }else{
            $res['success'] = false;
            $res['message'] = 'Cannot find user!';
            return response($res);
        }
    }

    public function changePassword(Request $request){
        $id = $request->input('id');
        $hasher = app()->make('hash');

        $password = $hasher->make($request->input('password'));
        $user = User::where('id', $id)->first();

        if($user){
            $user->password = $password;
            $user->save();
            $res['success'] = true;
            $res['message'] = "Success change password.";
            $res['user'] = $user;
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