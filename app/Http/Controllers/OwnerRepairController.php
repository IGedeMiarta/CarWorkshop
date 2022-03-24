<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Service;
use App\Models\CarOwner;
use App\Models\Mechanic;
use App\Models\CarRepair;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OwnerRepairController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CarRepair::with('owner','mechanic','status','carService','carService.service')
            ->where('owner_id',session()->get('user')->id_car_owners)
            ->get();
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
        return view('repair.owner',$data);

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
        //
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
