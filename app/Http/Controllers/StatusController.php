<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Status::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return view('status.status',['title'=>'Status']);
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
        //validate the data
        $validator = Validator::make($request->all(),[
            'status' => 'required|string|max:100|unique:statuses',
        ]);
        //return error if data not valid
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors(),'status'=>Response::HTTP_NOT_ACCEPTABLE]);
        }
        // store if valid
        try {
            Status::create([
                'status' => $request->status,
            ]);
            // return success message
            return response()->json(['message'=>'Status created successfully','status'=>Response::HTTP_OK]);
        } catch (QueryException $e) {
            return response()->json(['errors'=>$e->getMessage(),'status'=>Response::HTTP_NOT_ACCEPTABLE]);
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
        //find status by id
        $status = Status::find($id);
        //return error if status not found
        if (!$status) {
            return response()->json(['errors'=>'Status not found','status'=>Response::HTTP_NOT_FOUND]);
        }
        //return status
        return response()->json(['data'=>$status,'status'=>Response::HTTP_OK]);
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
        //validate data
        $validator = Validator::make($request->all(),[
            'status' => 'required|string|max:100',
        ]);
        //return error if data not valid
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors(),'status'=>Response::HTTP_NOT_ACCEPTABLE]);
        }
        //find status by id
        $status = Status::find($id);
        //return error if status not found
        if (!$status) {
            return response()->json(['errors'=>'Status not found','status'=>Response::HTTP_NOT_FOUND]);
        }
        //update status
        try {
            $status->update([
                'status' => $request->status,
            ]);
            // return success message
            return response()->json(['message'=>'Status updated successfully','status'=>Response::HTTP_OK]);
        } catch (QueryException $e) {
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
        //find status by id
        $status = Status::find($id);
        //return error if status not found
        if (!$status) {
            return response()->json(['errors'=>'Status not found','status'=>Response::HTTP_NOT_FOUND]);
        }
        //delete status
        try {
            $status->delete();
            // return success message
            return response()->json(['message'=>'Status deleted successfully','status'=>Response::HTTP_OK]);
        } catch (QueryException $e) {
            return response()->json(['errors'=>$e->getMessage(),'status'=>Response::HTTP_NOT_IMPLEMENTED]);
        }
    }
}
