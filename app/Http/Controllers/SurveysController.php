<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class SurveysController extends Controller
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
            $surveys = Survey::paginate(20);
            return view('survey.index', compact('surveys'));
        }    
    }

    public function create_($id_booking)
    {
        if(!Session::get('login')){
            return redirect('/');
        }
        else
        {
            return view('survey.create')
                ->with('id_booking', $id_booking);
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
            return view('survey.create');
        }        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Survey::insert([
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'alamat' => $request->alamat,
            'keterangan' => $request->keterangan,
            'id_booking' => $request->id_booking
        ]);

        $experts_ = DB::table('surveys_detail_temp')
                        ->get();

        $id = Survey::max('id');
        foreach ($experts_ as $expert)
        {
            DB::table('surveys_detail')->insert([
                'id_booking' => $id,
                'id_pekerjaan' => $expert->id_pekerjaan,
                'lama_waktu' => $expert->lama_waktu
            ]);        
        }

        DB::table('surveys_detail_temp')
            ->delete();
            
        DB::table('bookings')
            ->where('id', $request->id_booking)
            ->update([
                'status' => 1
            ]);

        return redirect('/survey'); 
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Survey $survey)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Survey $survey)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Survey $survey)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Survey $survey)
    {
        Survey::destroy($survey->id);
        return redirect('/survey');
    }

    public function surveys_detail_temp(Request $request)
    {
        DB::table('surveys_detail_temp')->insert([
            'id_pekerjaan' => $request->id_pekerjaan,
            'lama_waktu' => $request->lama_waktu
        ]);

        return redirect('/survey/create/' . $request->id_booking);
    }

}
