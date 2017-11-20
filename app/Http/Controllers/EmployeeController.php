<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use App\User;
use Validator;
use Session;

class EmployeeController extends Controller {

     
    public function __construct() {
        $this->middleware('auth');
    }
    
    
    /*
     *  Index page of employee searching
     */
    public function index(Request $request) {

        $employees = User::where('role_id', '=', 2)->orderBy('id', 'DESC');

        if ($request->has('q')) {
            $q = trim($request->q);
            $employees->where(function($employees) use($q) {
                $employees->orWhere('name', 'like', "%$q%")
                        ->orWhere('username', 'like', "%$q%")
                        ->orWhere('phone', 'like', "%$q%");
            });
        }


        $employees = $employees->paginate();

        return view('employee.index')->with('employees', $employees);
    }

     
    /*
     * create Employee
     */
    public function create() {
        return view('employee.create');
    }
    
    /*
     * Store Employee
     */ 
    public function store(Request $request) {

        $input = $request->all();
        $rules = User::$rules;

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return redirect()->back()->withInput($input)->withErrors($validator->errors());
        }

        $input['role_id'] = 2;
        $input['api_token'] = str_random(60);
        $input['password'] = bcrypt($request->password);

        $employee = new User;
        $employee::create($input);


        Session::flash('flash_message', 'Employee created successfully.');
        Session::flash('flash_type', 'alert-success');
        return redirect('employee');
    }

     /*
      * Edit Employee
      */
    public function edit($id) {
        $employee = User::find($id);

        if (is_null($employee)) {

            return redirect()->back();
        }

        return view('employee.edit')->with('employee', $employee);
    }

    /*
     * Update Employee
     */ 
    public function update(Request $request, $id) {
        $input = $request->all();
        $rules = User::$rules;
        $rules['username'].= ",username,$id";

        if (empty($input['password']) && empty($input['password_confirmation'])) {
            unset($rules['password'], $rules['password_confirmation'], $input['password'], $input['password_confirmation']);
        }

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return redirect()->back()->withInput($input)->withErrors($validator->errors());
        }

        if (isset($input['password'])) {
            $input['password'] = bcrypt($request->password);
        }

        $employee = User::find($id);
        $employee->update($input);

        Session::flash('flash_message', 'Employee updated successfully.');
        Session::flash('flash_type', 'alert-success');
        return redirect('employee');
    }

    /*
     * delete Employee
     */
    public function destroy(Request $request, $id) {

        $employee = User::find($id);
        if (is_null($employee)) {

            return redirect()->back();
        }

        $employee->delete();

        Session::flash('flash_message', 'Employee deleted successfully.');
        Session::flash('flash_type', 'alert-success');
        return redirect('employee');
    }

}
