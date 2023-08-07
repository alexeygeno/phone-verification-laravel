<?php

namespace AlexGeno\PhoneVerificationLaravel\Http\Controllers;

use AlexGeno\PhoneVerification\Exception;
use AlexGeno\PhoneVerification\Manager\Completer;
use AlexGeno\PhoneVerification\Manager\Initiator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PhoneVerificationController extends Controller
{
    /**
     * Initiate phone verification process.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function initiate(Initiator $manager, Request $request)
    {
        $response = ['ok' => true, 'message' => trans('phone-verification::messages.initiation_success')];
        $status = 200;
        try {
            $manager->initiate($request->post('to'));
        } catch (Exception $e) {
            $response = ['ok' => false, 'message' => $e->getMessage()];
            $status = 406;
        }

        return response()->json($response, $status);
    }

    /**
     * Complete phone verification process.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete(Completer $manager, Request $request)
    {
        $response = ['ok' => true, 'message' => trans('phone-verification::messages.completion_success')];
        $status = 200;
        try {
            $manager->complete($request->post('to'), (int) $request->post('otp'));
        } catch (Exception $e) {
            $response = ['ok' => false, 'message' => $e->getMessage()];
            $status = 406;
        }

        return response()->json($response, $status);
    }
}
