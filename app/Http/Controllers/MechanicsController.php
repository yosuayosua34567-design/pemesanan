<?php

namespace App\Http\Controllers;

use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class MechanicsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!Session::get('login')){
            return redirect('/');
        }
        else
        {            
            $mechanics = Mechanic::paginate(20);
            return view('mechanic.index', compact('mechanics'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(!Session::get('login')){
            return redirect('/');
        }
        else
        {
            return view('mechanic.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $name = $image->getClientOriginalName();
            $destinationPath = public_path('/img/mekanik');
            $image->move($destinationPath, $name);
        }

        $mechanic = Mechanic::where('user_id', $request->user_id)->count();
                    
        $alert = "";
        if ($mechanic == 0)
        {
            Mechanic::insert([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'jenis_kelamin' => $request->jenis_kelamin,
                'gambar' => $name,
                'user_id' => $request->user_id,
                'kata_sandi' => $request->kata_sandi
            ]);

            $experts_ = DB::table('mechanics_detail_temp')
                            ->get();

            $id = Mechanic::max('id');
            foreach ($experts_ as $expert)
            {
                DB::table('mechanics_detail')->insert([
                    'id_mechanic' => $id,
                    'id_keahlian' => $expert->id_keahlian,
                    'nilai' => $expert->nilai
                ]);        
            }

            DB::table('mechanics_detail_temp')
                ->delete();

            return redirect('/mechanic');  
        }
        else
        {
            $alert = "Data nama mekanik telah ada";
            return redirect('/mechanic/create')->with('alert', $alert);  
        }        
    }

    /**
     * Display the specified resource.
     */
    public function show(Mechanic $mechanic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mechanic $mechanic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mechanic $mechanic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mechanic $mechanic)
    {
        Mechanic::destroy($mechanic->id);
        DB::table('mechanics_detail')
            ->where('id_mechanic', $mechanic->id)
            ->delete();
        return redirect('/mechanic')->with('status','Data Mekanik Terhapus');
    }

    public function mechanics_detail_temp(Request $request)
    {
        DB::table('mechanics_detail_temp')->insert([
            'id_keahlian' => $request->id_keahlian,
            'nilai' => $request->nilai
        ]);

        return redirect('mechanic/create');
    }
}
