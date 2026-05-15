<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class BookingsController extends Controller
{
    public function index_admin()
    {
        if(!Session::get('login')){
            return redirect('/');
        }
        else
        {        
            $user = Session::get('user_id');
            $bookings = Booking::join('works', 'works.id', 'bookings.jenis_pekerjaan')
                            ->join('users', 'users.user_id', 'bookings.email_user')
                            ->select('bookings.*', 'works.nama_pekerjaan', 'users.alamat', 'users.no_hp')
                            ->paginate(20);
            return view('booking.index_admin', compact('bookings'));
        }    
    }

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
            $user = Session::get('user_id');
            $bookings = Booking::join('works', 'works.id', 'bookings.jenis_pekerjaan')
                        ->select('bookings.*', 'works.nama_pekerjaan')
                        ->where('email_user', $user)
                        ->paginate(20);
            return view('booking.index', compact('bookings'));
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
            return view('booking.create');
        }        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Booking::create($request->all());
        $tanggal = date('Y-m-d');
        Booking::insert([
            'email_user' => $request->email_user,
            'tanggal' => $tanggal,
            'keterangan' => $request->keterangan,
            'jenis_pekerjaan' => $request->jenis_pekerjaan,
            'status' => 0
        ]);

        return redirect('/booking');            
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        Booking::destroy($booking->id);
        return redirect('/booking');
    }

    public function booking_batal($id_booking)
    {
        DB::table('bookings')
            ->where('id', $id_booking)
            ->update([
                'status' => 2
            ]);

        $bookings = Booking::paginate(20);
        return view('booking.index_admin', compact('bookings'));        
    }

    public function booking_reset($id_booking)
    {
        DB::table('bookings')
            ->where('id', $id_booking)
            ->update([
                'status' => 0
            ]);

        $bookings = Booking::paginate(20);
        return view('booking.index_admin', compact('bookings'));
    }
}
