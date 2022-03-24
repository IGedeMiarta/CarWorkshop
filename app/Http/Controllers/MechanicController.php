<?php

namespace App\Http\Controllers;

use App\Mail\MechanicDeleteMail;
use App\Mail\MechanicMail;
use App\Models\User;
use App\Models\Mechanic;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class MechanicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Mechanic::with('user')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('email', function ($row) {
                    return $row->user->email;
                })
                ->make(true);
        }
        return view('mechanics.mechanic',['title'=>'Mechanic']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //valide the data
        $valide = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|min:11',
        ]);
        //if the data is not valide
        if ($valide->fails()) {
            return response()->json(['errors'=>$valide->errors(),'status'=>Response::HTTP_UNPROCESSABLE_ENTITY]);
        }
        //if the data is valide create random password to send it to the user email
        $password = Str::random(8);
        try {
            //create the user
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($password),
                'role' => 'mechanic',
            ]);
            //create the mechanic
            $mechanic = Mechanic::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'user_id' => $user->id,
            ]);
            //send the email
            $maildata=[
                'userInvited' => auth()->user()->email,
                'name' => $request->name,
                'email' => $request->email,
                'password' => $password,
            ];
            Mail::to($request->email)->send(new MechanicMail($maildata));
            // return the response
            return response()->json(['message'=>'Mechanic created successfully','status'=>Response::HTTP_CREATED]);
        } catch (Exception $e) {
            return response()->json(['errors'=>$e->getMessage(),'status'=>Response::HTTP_NOT_IMPLEMENTED]);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //check if the user is the mechanicn by id
        $data = Mechanic::with('user')->find($id);
        if(!$data){
            return response()->json(['errors'=>'Mechanic not found','status'=>Response::HTTP_NOT_FOUND]);
        }
        return response()->json(['data'=>$data,'status'=>Response::HTTP_OK]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validate the data
        $validate = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:11',
        ]);
        //if the data is not valide
        if ($validate->fails()) {
            return response()->json(['errors'=>$validate->errors(),'status'=>Response::HTTP_UNPROCESSABLE_ENTITY]);
        }
        //if the data is valide update the user and the mechanic
        try {
            $mechanic = Mechanic::find($id);
            $mechanic->update([
                'name' => $request->name,
                'phone' => $request->phone,
            ]);
            $user = User::where('id',$mechanic->user_id)->update([
                'email' => $request->email,
            ]);
            // return the response
            return response()->json(['message'=>'Mechanic updated successfully','status'=>Response::HTTP_OK]);
        } catch (Exception $e) {
            return response()->json(['errors'=>$e->getMessage(),'status'=>Response::HTTP_NOT_IMPLEMENTED]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //find the mechanic by id
        $mechanic = Mechanic::find($id);
        // if the mechanic not found
        if(!$mechanic){
            return response()->json(['errors'=>'Mechanic not found','status'=>Response::HTTP_NOT_FOUND]);
        }
        //delete the user and the mechanic
        try {
            $data['name'] = $mechanic->name;
            $mechanic->delete();
            $user = User::find($mechanic->user_id);
            $user->delete();
            // send email to mechanic
            Mail::to($user->email)->send(new MechanicDeleteMail($data));
            // return the response
            return response()->json(['message'=>'Mechanic deleted successfully','status'=>Response::HTTP_OK]);
        } catch (Exception $e) {
            return response()->json(['errors'=>$e->getMessage(),'status'=>Response::HTTP_NOT_IMPLEMENTED]);
        }
        

    }
}
