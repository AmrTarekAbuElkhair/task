<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function signUp(Request $request)
    {
        $lang = ($request->hasHeader('lang')) ? $request->header('lang') : 'en';
        $validator = Validator::make($request->all(),
            array(
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'school_id' => 'required|exists:schools,id',
            )
        );
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()]);
        }
        $emialex = User::whereEmail($request->email)->get();
        if (isset($emialex) && count($emialex) > 0) {
            return response()->json(res_msg($lang, failed(), 401, 'email_exist'));
        }
        $digits = 4;
        $orderNumber = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['order_number']=$orderNumber;
        $user = User::create($data);
        $userToVerify=User::whereEmail($request->email)->first();
        if (isset($userToVerify)) {
            $userToVerify->verified_status = 0;
            $userToVerify->save();
            $code = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            $verification_Ob=new Verification();
            $verification_Ob->verifications_code = $code;
            $verification_Ob->user_id = $user->id;
            $verification_Ob->save();
            return response(res_msg($lang, success(), 405, 'code_sent'));
//            $data = array('name'=>$usertoverify->name,'code'=>$code);
//            Mail::send('mail', $data, function($message)use($usertoverify) {
//                $message->to($usertoverify->email)->subject
//                ('Verification Code');
//                $message->from('info@troylab.net','task Application');
//            });
        }else{
            return response(res_msg($lang, success(), 405, 'user_not_found'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $lang = ($request->hasHeader('lang')) ? $request->header('lang') : 'en';

        $validator = Validator::make($request->all(),
            [
                'email' => 'required',
                'password' => 'required',

            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()]);
        }



        $user = User::whereEmail($request->email)->first();
        if (!$user) {
            return response()->json(res_msg($lang, failed(), 404, 'user_not_found'));
        }

        $check = Hash::check($request->password, $user->password);

        if ($check) {

            if ($user->verified_status == 0) {
                $digits = 4;
                $code = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
                $verification_Ob=new Verification();
                $verification_Ob->verifications_code = $code;
                $verification_Ob->user_id = $user->id;
                $verification_Ob->save();
                return response(res_msg($lang, success(), 405, 'code_sent'));
//            $data = array('name'=>$usertoverify->name,'code'=>$code);
//            Mail::send('mail', $data, function($message)use($usertoverify) {
//                $message->to($usertoverify->email)->subject
//                ('Verification Code');
//                $message->from('info@troylab.net','task Application');
//            });
                return response()->json(res_msg($lang, failed(), 405, 'user_not_verified'));
            }
            $user['school_name']=School::where('id',$user->school_id)->select('name')->first()->name;
            return response()->json(res($lang, success(), 200, 'logged_in', $user));
        } else {
            return response()->json(res_msg($lang, failed(), 401, 'invalid_password'));
        }


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
