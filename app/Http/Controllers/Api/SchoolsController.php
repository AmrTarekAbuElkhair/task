<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllOrders(Request $request)
    {
        $lang = ($request->hasHeader('lang')) ? $request->header('lang') : 'en';
        $validator = Validator::make($request->all(),
            array(
                'school_id' => 'required',
            )
        );
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()]);
        }
        $result = School::where('id',$request->school_id)->first();
        $schoolorders = User::where('school_id',$result->id)->get();
        $allorders = array();
        $i = 0;
        foreach ($schoolorders as $order) {
            $allorders[$i] = array(
                'id'=>$order->id,
                'name' => $order->name,
                'email'=> $order->email,
                'order_number'=> $order->order_number,
                );
            $i++;
        }
        $school= $allorders;
        return response(res($lang, success(), 200, 'all_orders', $school));

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
