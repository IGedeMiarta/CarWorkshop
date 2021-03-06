<?php

namespace App\Http\Controllers;

use App\Mail\MechanicOrder;
use App\Mail\Repairstatus;
use Exception;
use App\Models\Status;
use App\Models\Service;
use App\Models\CarOwner;
use App\Models\Mechanic;
use App\Models\CarRepair;
use App\Models\CarService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CarRepairController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CarRepair::with('owner','mechanic','status','carService','carService.service')->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('owner', function($row){
                        return $row->owner->name;
                    })
                    ->addColumn('mechanic', function($row){
                        return $row->mechanic->name;
                    })
                    ->addColumn('status', function($row){
                        return $row->status->status;
                    })
                    ->addColumn('car_entry', function($row){
                        return $row->car_entry;
                    })
                    ->addColumn('service', function($row){
                        $log='';
                        foreach ($row->carservice as $carservice) {
                            $log .=  $carservice->service->name.', ';
                        }
                        return $log;
                    })
                    ->make(true);
        }
        $data['title']='Car Repair';
        $data['owner']=CarOwner::all();
        $data['status']=Status::all();
        $data['mechanic']=Mechanic::all();
        $data['service']=Service::all();
        return view('repair.index',$data);
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
   
        //validate data
        $validate = Validator::make($request->all(), [
            'owner' => 'required',
            'mechanic' => 'required',
            'service' => 'required|array|min:1',
            'note' => 'max:255',
            'status' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors(),'status'=>Response::HTTP_UNPROCESSABLE_ENTITY]);
        }
        //create data
        try {
            $carRepair = CarRepair::create([
                'car_entry' => date('Y-m-d' . ' ' . 'H:i:s'),
                'owner_id' => $request->owner,
                'mechanic_id' => $request->mechanic,
                'note' => $request->note,
                'status_id' => $request->status,

            ]);
            //create car service
            $carservice = $request->service;
            foreach ($carservice as $key => $value) {
                CarService::create([
                    'repair_id' => $carRepair->id_repairs,
                    'service_id' => $carservice[$key],
                ]);
            }
            // send email to mechanic
            $mechanic = Mechanic::find($request->mechanic);
            $user = User::find($mechanic->user_id);
       
            Mail::to($user->email)->send(new MechanicOrder($mechanic));
            return response()->json(['message' => 'Data is successfully added','status'=>Response::HTTP_OK]);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage(),'status'=>Response::HTTP_NOT_IMPLEMENTED]);
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
        //
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
        // validate data
        $validate = Validator::make($request->all(), [
            'note' => 'max:255',
            'status' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors(),'status'=>Response::HTTP_UNPROCESSABLE_ENTITY]);
        }
        // update data
        try {
            $carRepair = CarRepair::find($id);
            $carRepair->update([
                'note' => $request->note,
                'status_id' => $request->status,
            ]);
            $owner = CarOwner::find($carRepair->owner_id);
            $user = User::find($owner->user_id);

            $status = Status::find($request->status);
            $data =[
                'name' => $owner->name,
                'status' => $status->status,
                'note' => $request->note,
            ];
        
            //send email to owner
            Mail::to($user->email)->send(new Repairstatus($data));
            return response()->json(['message' => 'Data is successfully updated','status'=>Response::HTTP_OK]);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage(),'status'=>Response::HTTP_NOT_IMPLEMENTED]);
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
        //
    }
}
