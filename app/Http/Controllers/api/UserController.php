<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Validator;
use Auth;

class UserController extends Controller 
{   

    public function __construct() {
        
      //  $this->middleware('auth:api', ['except' => ['authenticate'] ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       die('maninder in api folder');
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


    public function authenticate(Request $request) {
    

        $input = $request->json()->all();
        
        $rules = array(
    
        'username'  => 'required',                     
        'password'   => 'required',    

         );
        $validator = Validator::make($input, $rules);
       
        if ($validator->fails()) {
             return response(
                 $validator->messages()
            ,422);
        }
        
        if (Auth::attempt(['username' => $input['username'], 'password' => $input['password'] ])) {
            $employee = Auth::user();
            
             
            return response([
                'status' => 'success',
                'api_token' => $employee->api_token,
                'data'=> $employee
            ], 200);
        }

        return response([
            'error' => ['Wrong username or password']
        ],400);
   

    }
    
}
