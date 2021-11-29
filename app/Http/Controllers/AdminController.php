<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function show_accounts($status)
    {
        if($status === "pending" || $status === "confirmed" || $status === "disabled" ){
            $account_status=[
                "pending"=>0,
                "confirmed"=>1,
                "disabled"=>2,
            ];
            
            $users = User::with('unit:id,unit')->with('user_type:id,user_type')->where([['status', $account_status[$status]], ['user_type_id', '!=', '1']])->get();

            if($account_status[$status] == 0){
                return view('contents.pending_accounts', ["users"=>$users]);
            }
            else if($account_status[$status] == 1){
                return view('contents.accounts', ["users"=>$users]);
            }
            else if($account_status[$status] == 2){
                return view('contents.disabled_accounts', ["users"=>$users]);
            }
        }
        else{
            return abort(404);
        }
        
    }
    public function show_rdo_registration()
    {
        return view('contents.rdo_registration');
    }
    public function add_rdo(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required'],
            'contact_number' => ['required', 'digits:11'],
            'address' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        else{
            $rdo = [
                "name"=>request('name'),
                "gender"=>request('gender'),
                "contact_number"=>request('contact_number'),
                "address"=>request('address'),
                "email"=>request('email'),
                "user_type_id"=>5,
                "status"=>1,
                'password' => Hash::make(request('password')),
            ];
            $user = User::create($rdo);
            User::where('id', $user->id)->update(['status'=>1]);
            return back()->with(['status'=>1]);
        }
    }
    public function delete_user()
    {
        $id = request('id');
        User::where('id', $id)->delete();
        return 'Deleted -'.$id;
    }
    public function accept_user()
    {
        $id = request('id');
        User::where('id', $id)->update(['status'=>1]);
        return 'Accepted -'.$id;
    }
    public function disable_user()
    {
        $id = request('id');
        User::where('id', $id)->update(['status'=>2]);
        return 'Disabled -'.$id;
    }
}
