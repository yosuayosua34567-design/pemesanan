<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customer = Customer::where('user_id', $request->user_id)->count();

        $alert = "";
        if ($customer == 0)
        {
            Customer::create($request->all());
            return redirect('/login');       
        }
        else
        {
            $alert = "Data user ID telah ada";
            return redirect('/customer/create')->with('alert', $alert);
        }        
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }

    public function admin(Request $request)
    {
        $name = $request->user_name;
        $password = $request->user_pass;

        $data = DB::table('administrators')->where('user_id',$name)->first();

        if($data){ //apakah id user tersebut ada atau tidak
            if($password == $data->kata_sandi){
                Session::put('user_id',$data->user_id);
                Session::put('login',TRUE);
                return redirect('home_admin');
            }
            else{
                return redirect('/')->with('alert','Kata Sandi Salah !');
            }
        }
        else{
            return redirect('/')->with('alert','User ID Tidak Terdaftar !');
        }

        return redirect('home_admin');
    }

    public function home_admin()
    {
        return view('home_admin');
    }

    public function loginpost(Request $request)
    {
        $name = $request->email;
        $password = $request->kata_sandi;

        $data = Customer::where('user_id',$name)->first();
        $data_m = Mechanic::where('user_id',$name)->first();
        $data_adm = DB::table('administrators')
                        ->where('user_id',$name)->first();

        if($data_adm){ //apakah id user tersebut ada atau tidak
            if($password == $data_adm->kata_sandi){
                Session::put('user_id',$data_adm->user_id);
                Session::put('user_type', 'admin');
                Session::put('login',TRUE);
                return redirect('home_admin');
            }
            else{
                return redirect('/')->with('alert','Kata Sandi Salah !');
            }
        }
        elseif ($data_m)
        {
            if($password == $data_m->kata_sandi){
                Session::put('user_id',$data_m->user_id);
                Session::put('user_type', 'mekanik');
                Session::put('login',TRUE);
                return redirect('home_mekanik');
            }
            else{
                return redirect('/')->with('alert','Kata Sandi Salah !');
            }
        }
        elseif ($data)
        {
            if($password == $data->kata_sandi){
                Session::put('user_id',$data->user_id);
                Session::put('user_type', 'customer');
                Session::put('login',TRUE);
                return redirect('home');
            }
            else{
                return redirect('/')->with('alert','Kata Sandi Salah !');
            }
        }
        else{
            return redirect('/')->with('alert','User ID Tidak Terdaftar !');    
        }
    }

    public function home()
    {
        return view('home_user');
    }

    public function home_mekanik()
    {
        return view('home_mekanik');
    }

    public function logout(){
        Session::flush();
        return redirect('/');
    }

}
