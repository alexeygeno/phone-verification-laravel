<?php

namespace AlexGeno\PhoneVerificationLaravel\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use AlexGeno\PhoneVerification\Exception;
use AlexGeno\PhoneVerification\Manager\Initiator;
use AlexGeno\PhoneVerification\Manager\Completer;

class PhoneVerificationController extends Controller
{
    public function initiate(Initiator $manager, Request $request):string{
        $response = ['ok' => true, 'message' => trans("phone-verification::messages.initiation_success")];
        try {
            $manager->initiate($request->post('to'));
        }catch (Exception $e){
            $response = ['ok' => false, 'message' => $e->getMessage()];
        }
        return response()->json($response);
    }

    public function complete(Completer $manager, Request $request):string{
        $response = ['ok' => true, 'message' => trans("phone-verification::messages.completion_success")];
        try {
            $manager->complete($request->post('to'), $request->post('otp'));
        }catch (Exception $e){
            $response = ['ok' => false, 'message' => $e->getMessage()];
        }
        return response()->json($response);
    }
}
