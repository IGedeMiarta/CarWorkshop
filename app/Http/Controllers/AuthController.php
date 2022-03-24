<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CarOwner;
use App\Models\Mechanic;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        // validate the credentials and create a token for the user
        $validator = Validator::make($credentials, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        // send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),'status'=>Response::HTTP_UNPROCESSABLE_ENTITY ],401);
        }

        // request validated, now authenticate user
        try {
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json(['errors'=>'Wrong Email Password','status'=>Response::HTTP_NOT_ACCEPTABLE]);
            }
        } catch (JWTException $e) {
            return response()->json(['errors' => 'Could not create credentials', 'status'=>Response::HTTP_INTERNAL_SERVER_ERROR],500);
        }
        //set sessoion
        $user = User::where('email',$request->email)->first();
        $data = Mechanic::where('user_id',$user->id)->first();
        if ($user->role == 'owner') {
            $data = CarOwner::where('user_id',$user->id)->first();
        }elseif($user->role == 'mechanic'){
            $data = [];
        }
        Session::put('user',$data);
        //set coocie
        Cookie::queue('token', $token, 60);

        // if user is authenticated, return a token
        return response()->json(['token' => $token, 'status'=>Response::HTTP_OK],200);

    }

    public function regist(Request $request)
    {
       $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
       ]);
       if($validator->fails()){
           return response()->json([
               'errors'=>$validator->errors(),
               'status'=>Response::HTTP_UNPROCESSABLE_ENTITY
            ]);
       }
       try {
            $user =  User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'owner',
            ]);
            CarOwner::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'user_id' => $user->id,
            ]);
            $data['name'] = $request->name;
            Mail::to($request->email)->send(new \App\Mail\OwnerRegister($data));
            return response()->json([
                'message'=>'Successfully created user!',
                'status'=>Response::HTTP_CREATED
            ]);
       } catch (QueryException $e) {
            return response()->json([
                'errors'=>$e->getMessage(),
                'status'=>Response::HTTP_NOT_IMPLEMENTED
            ]);
       }
      
    }
    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(),'status'=>Response::HTTP_UNPROCESSABLE_ENTITY], 201);
        }

		//Request is validated, do logout        
        try {
            JWTAuth::invalidate($request->token);
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out',
                'status'=>Response::HTTP_OK
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'response' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
