<?php

namespace App\Http\Controllers;

use App\Models\CarOwner;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CarOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CarOwner::with('user')->latest()->get();            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('email', function($row){
                    return $row->user->email;
                })
                ->addColumn('role', function($row){
                    return $row->user->role;
                })
                ->make(true);           
        }

        $data['title'] = 'Car Owner';
        return view('owner.car_owner',$data);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //show specific car owner
        $data = CarOwner::with('user')->find($id);
        if ($data) {
            return response()->json(['data'=>$data,'status'=>Response::HTTP_OK]);
        }else{
            return response()->json(['message'=>'Car Owner not found','status'=>Response::HTTP_NOT_FOUND]);
        }
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
        $car_owner = CarOwner::find($id);
        //validate request
        $validate = Validator::make($request->all(),[
            'name' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:50|unique:users,email,'.$car_owner->user_id,
        ]);
        if ($validate->fails()) {
            return response()->json(['errors'=>$validate->errors(),'status'=>Response::HTTP_NOT_ACCEPTABLE]);
        }
        //update user
        if (!$car_owner) {
            return response()->json(['message'=>'Car Owner not found','status'=>Response::HTTP_NOT_FOUND]);
        }
        try {
            $car_owner->update([
                'name' => $request->name,
                'phone' => $request->phone,
            ]);
            //update user email
            $user = User::find($car_owner->user_id);
            $user->update([
                'email' => $request->email,
            ]);
            return response()->json(['message'=>'Car Owner updated successfully','status'=>Response::HTTP_OK]);
        } catch (QueryException $e) {
            return response()->json(['message'=>$e->getMessage(),'status'=>Response::HTTP_NOT_IMPLEMENTED]);
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
        //find car owner by id
        $car_owner = CarOwner::find($id);
        if (!$car_owner) {
            return response()->json(['message'=>'Car Owner not found','status'=>Response::HTTP_NOT_FOUND]);
        }
        //delete car owner
        try {
            $car_owner->delete();
            return response()->json(['message'=>'Car Owner deleted successfully','status'=>Response::HTTP_OK]);
        } catch (QueryException $e) {
            return response()->json(['message'=>$e->getMessage(),'status'=>Response::HTTP_NOT_IMPLEMENTED]);
        }
    }
}
