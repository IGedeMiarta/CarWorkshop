<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Service::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }   
        $data['title'] = 'Service';
        return view('services.service',$data);
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
        //validete data
        $validate = Validator::make($request->all(),[
            'name' => 'required|string|max:100',
            'price' => 'required|numeric',
        ]);
        //return error if data not valid
        if ($validate->fails()) {
            return response()->json(['errors'=>$validate->errors(),'status'=>Response::HTTP_NOT_ACCEPTABLE]);
        }
        //create new service
        try {
            Service::create([
                'name' => $request->name,
                'price' => $request->price,
            ]);
            return response()->json(['message'=>'Service created successfully','status'=>Response::HTTP_OK]);
        } catch (QueryException $e) {
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
        //show specific service by id
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['message'=>'Service not found','status'=>Response::HTTP_NOT_FOUND]);
        }
        return response()->json(['data'=>$service,'status'=>Response::HTTP_OK]);
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
        //valide data
        $valide = Validator::make($request->all(),[
            'name' => 'required|string|max:100',
            'price' => 'required|numeric',
        ]);
        //return error if data not valid
        if ($valide->fails()) {
            return response()->json(['errors'=>$valide->errors(),'status'=>Response::HTTP_NOT_ACCEPTABLE]);
        }
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['message'=>'Service not found','status'=>Response::HTTP_NOT_FOUND]);
        }
        //update service 
        try {
            $service->update([
                'name' => $request->name,
                'price' => $request->price,
            ]);
            return response()->json(['message'=>'Service updated successfully','status'=>Response::HTTP_OK]);
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
        //find service by id
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['message'=>'Service not found','status'=>Response::HTTP_NOT_FOUND]);
        }
        //delete service
        try {
            $service->delete();
            return response()->json(['message'=>'Service deleted successfully','status'=>Response::HTTP_OK]);
        } catch (QueryException $e) {
            return response()->json(['errors'=>$e->getMessage(),'status'=>Response::HTTP_NOT_IMPLEMENTED]);
        }
    }
}
