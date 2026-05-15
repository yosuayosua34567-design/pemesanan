<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class WorksController extends Controller
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
            $works = Work::paginate(20);
            return view('work.index', compact('works'));
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
            return view('work.create');
        }        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Work::insert([
            'nama_pekerjaan' => $request->nama_pekerjaan,
            'lama_waktu' => $request->lama_waktu    
        ]);

        $experts_ = DB::table('works_detail_temp')
                        ->get();

        $id = Work::max('id');
        foreach ($experts_ as $expert)
        {
            DB::table('works_detail')->insert([
                'id_work' => $id,
                'id_keahlian' => $expert->id_keahlian,
                'nilai' => $expert->nilai
            ]);        
        }

        DB::table('works_detail_temp')
            ->delete();

        return redirect('/work');  
    }

    /**
     * Display the specified resource.
     */
    public function show(Work $work)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Work $work)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Work $work)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Work $work)
    {
        Work::destroy($work->id);
        DB::table('works_detail')
            ->where('id_work', $work->id)
            ->delete();
        return redirect('/work')->with('status','Data Pekerjaan Terhapus');
    }

    public function works_detail_temp(Request $request)
    {
        DB::table('works_detail_temp')->insert([
            'id_keahlian' => $request->id_keahlian,
            'nilai' => $request->nilai
        ]);

        return redirect('work/create');
    }

    public function work_booking()
    {
        if(!Session::get('login')){
            return redirect('/');
        }
        else
        {            
            $works = Booking::paginate(20);
            return view('work_booking.index', compact('works'));
        }    
    }

}
