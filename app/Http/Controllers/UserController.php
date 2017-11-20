<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Validator;
use Auth;
use Session;

class UserController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /*
     *  Change Password form
     */
    public function changePassword() {

        return view('auth.change-password');
    }
    
    /*
     *  Store Change password
     */
    public function storeChangePassword(Request $request) {

        $input = $request->all();
        $validator = Validator::make($input, [
                    'current_password' => 'required',
                    'password' => 'required',
                    'confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator)->withInput();
        }  
       
        
        $id = \Auth::id();
        $password = \Hash::make($input['password']);
        $user = User::find($id);
        if (\Hash::check($input['current_password'], $user->password)) {
            // The passwords match...
            $user->password = $password;
            $user->save();
            Session::flash('message', 'Password changed successfully.');
            Session::flash('alert-class', 'alert-success');
       
        } else {
                
            Session::flash('message', 'Current password did not match, Enter correct current password.');
            Session::flash('alert-class', 'alert-danger');
        }
        
        return \Redirect::back();
        
    }

}
