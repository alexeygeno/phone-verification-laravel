<?php

namespace AlexGeno\PhoneVerificationLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use AlexGeno\PhoneVerification\Exception;
use AlexGeno\PhoneVerification\Manager\Initiator;
use AlexGeno\PhoneVerification\Manager\Completer;

class PhoneVerificationController extends Controller
{
    public function initiate(Initiator $manager, Request $request):\Illuminate\Http\JsonResponse{
        $response = ['ok' => true, 'message' => trans("phone-verification::messages.initiation_success")];
        $status = 200;
        try {
            $manager->initiate($request->post('to'));
        }catch (Exception $e){
            $response = ['ok' => false, 'message' => $e->getMessage()];
            $status = 406;
        }
        return response()->json($response, $status);
    }

    public function complete(Completer $manager, Request $request):\Illuminate\Http\JsonResponse{
        $response = ['ok' => true, 'message' => trans("phone-verification::messages.completion_success")];
        $status = 200;
        try {
            $manager->complete($request->post('to'), $request->post('otp'));
        }catch (Exception $e){
            $response = ['ok' => false, 'message' => $e->getMessage()];
            $status = 406;
        }
        return response()->json($response, $status);
    }
}
