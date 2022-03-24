<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Service;
use App\Models\CarOwner;
use App\Models\Mechanic;
use App\Models\CarRepair;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    public function getOwner()
    {
        $owner = CarOwner::all();
        return response()->json([
            'data' => $owner,
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
    public function ownerById($id)
    {
        $owner = CarOwner::find($id);
        if (!$owner) {
            return response()->json([
                'errors' => 'Owner not found',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'data' => $owner,
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
    public function getMechanic(){
        $mechanic = Mechanic::all();
        return response()->json([
            'data' => $mechanic,
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
    public function getService(){
        $service = Service::all();
        return response()->json([
            'data' => $service,
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
    public function getStatus(){
        $status = Status::all();
        return response()->json([
            'data' => $status,
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
    public function getCarRepair(){
        $data = CarRepair::with('owner','mechanic','status','carService','carService.service')->get();
        return response()->json([
            'data' => $data,
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
            
    }
}
