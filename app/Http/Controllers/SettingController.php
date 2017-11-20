<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use Validator;
use Session;

class SettingController extends Controller {

     
    public function __construct() {
        $this->middleware('auth');
    }

    public function edit($id) {
        $setting = Setting::find($id);
        if (is_null($setting)) {
            return redirect()->back();
        }

        return view('settings.edit')->with('setting', $setting);
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id) {

        $input = $request->all();

        $rules = Setting::$rules;


        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return redirect()->back()->withInput($input)->withErrors($validator->errors());
        }

        $logo = $request->file('logo');

        if (!empty($logo)) {

            $input['logo'] = time() . '.' . $logo->getClientOriginalExtension();
            $destinationPath = 'images/logo';
            $logo->move($destinationPath, $input['logo']);
            $logo = $input['logo'];
        }


        $setting = Setting::find($id);
        $setting->update($input);

        Session::flash('flash_message', 'Settings updated successfully.');
        Session::flash('flash_type', 'alert-success');
        return redirect()->back();
    }

}
