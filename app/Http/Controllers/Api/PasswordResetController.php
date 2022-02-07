<?php

namespace App\Http\Controllers\Api;

use App\Mail\EmailManager;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Mail;

class PasswordResetController extends Controller
{
    public function create(Request $request)
    {
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {

            $user = User::where('email', $request->email)->first();
            if ($user != null) {
                $user->verification_code = rand(100000,999999);
                $user->save();

                $array['view'] = 'emails.verification';
                $array['from'] = env('MAIL_FROM_ADDRESS');
                $array['subject'] = translate('Password Reset');
                $array['content'] = translate('Verification Code is ').$user->verification_code;

                Mail::to($user->email)->queue(new EmailManager($array));

                return response()->json([
                    'success' => true,
                    'message' => translate('A verification code has been sent to your email.')
                ], 200);
            }
            else {
                return response()->json([
                    'success' => false,
                    'message' => translate('No user found with this email address.')
                ], 200);
            }
        }
        else{
            return response()->json([
                'success' => false,
                'message' => translate('Invalid email address.')
            ], 200);
        }
    }

    public function reset(Request $request){
        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => translate('No user found with this email address.')
            ], 200);
        }
        if($user->verification_code != $request->code){
            return response()->json([
                'success' => false,
                'message' => translate('Code does not match.')
            ], 200);
        }else{

            if($request->password){
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
            $user->save();

            return response()->json([
                'success' => true,
                'message' => translate('Your password has been updated.')
            ], 200);
        }
    }
}
