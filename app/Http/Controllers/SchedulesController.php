<?php

namespace App\Http\Controllers;

use App\Models\Mechanic;
use App\Models\Expert;
use App\Models\Schedule;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class SchedulesController extends Controller
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
            $tanggal = date("Y-m-d");
            $user_type = Session::get('user_type');

            if ($user_type == "customer")            
            {
                $schedules = Schedule::where('hari', $tanggal)
                                    ->get();

                return view('schedule.index')
                        ->with(compact('schedules'))
                        ->with('tanggal', $tanggal);                
            }
            else
            {
                $id_mechanic = Session::get('user_id');
                $schedules = Schedule::where('hari', $tanggal)
                                    ->where('kode_mekanik', $id_mechanic)
                                    ->get();
                
                return view('schedule.index_mekanik')
                        ->with(compact('schedules'))
                        ->with('tanggal', $tanggal);
            }

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!Session::get('login')){
            return redirect('/');
        }
        else
        {
            $user_type = Session::get('user_type');

            if ($user_type == "customer")            
            {
                $user_id = Session::get('user_id');
                $schedules = Schedule::join('mechanics', 'schedules.kode_mekanik', 'mechanics.id')
                                    ->join('works', 'works.id', 'schedules.id_pekerjaan')
                                    ->select('schedules.*', 'works.nama_pekerjaan', 'mechanics.nama as nama_mekanik')
                                    ->where('hari', $request->tanggal)
                                    ->get();

                return view('schedule.index')
                        ->with(compact('schedules'))
                        ->with('tanggal', $request->tanggal);
            }
            else
            {
                $user_id = Session::get('user_id');
                
                $kode_mekanik = DB::table('mechanics')
                                    ->where('user_id', $user_id)
                                    ->first();

                $schedules = Schedule::join('mechanics', 'schedules.kode_mekanik', 'mechanics.id')
                                    ->join('works', 'works.id', 'schedules.id_pekerjaan')
                                    ->select('schedules.*', 'works.nama_pekerjaan', 'mechanics.nama as nama_mekanik')
                                    ->where('hari', $request->tanggal)
                                    ->where('kode_mekanik', $kode_mekanik->id)
                                    ->orderBy('jam_mulai')
                                    ->get();

                return view('schedule.index_mekanik')
                        ->with(compact('schedules'))
                        ->with('tanggal', $request->tanggal);
            }

        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        //
    }

    public function schedule()
    {
        if(!Session::get('login')){
            return redirect('/');
        }
        else
        {
            $tanggal = date("Y-m-d");
            $user_id = Session::get('user_id');
            $schedules = Schedule::where('hari', $tanggal)
                                ->orderBy('jam_mulai')
                                ->get();

            return view('schedule.index_admin')
                    ->with(compact('schedules'))
                    ->with('hari', $tanggal);
        }
    }

    public function schedule_mekanik()
    {
        if(!Session::get('login')){
            return redirect('/');
        }
        else
        {
            $tanggal = date("Y-m-d");
            $user_id = Session::get('user_id');
            $schedules = Schedule::where('hari', $tanggal)
                                ->where('kode_mekanik', $user_id)
                                ->orderBy('jam_mulai')
                                ->get();


            return view('schedule.index_mekanik')
                    ->with(compact('schedules'))
                    ->with('hari', $tanggal);
        }
    }

    public function schedule_admin(Request $request)
    {
        $tanggal = $request->tanggal;

        //Baca data teknisi
        $mechanics = Mechanic::get();
        $arr_mekanik = array(); $idx = 0;
        foreach ($mechanics as $mechanic)
        {
            $arr_mekanik[$idx] = $mechanic->id;
            $idx += 1;
        }

        //Baca data keahlian
        $experts = Expert::get();
        $arr_expert = array(); $idx = 0;
        foreach ($experts as $expert)
        {
            $arr_expert[$idx] = $expert->id;
            $idx += 1;
        }

        $arr_K = array(); $max = array();
        for ($j = 0; $j < sizeof($arr_expert); $j++)
        {
            $max[$j] = 0;
        }

        //Baca data keahlian teknisi
        for ($i = 0; $i < sizeof($arr_mekanik); $i++)
            for ($j = 0; $j < sizeof($arr_expert); $j++)
            {
                $mekanik_expert = DB::table('mechanics_detail')
                                    ->where('id_keahlian', $arr_expert[$j])
                                    ->where('id_mechanic', $arr_mekanik[$i])
                                    ->first();

                if ($mekanik_expert)
                    $arr_K[$i][$j] = $mekanik_expert->nilai;
                else
                    $arr_K[$i][$j] = 0;

                //Ambil nilai max untuk setiap kategori
                $max[$j] = max($arr_K[$i][$j], $max[$j]);
            }

        //Normalisasi
        for ($i = 0; $i < sizeof($arr_mekanik); $i++)
            for ($j = 0; $j < sizeof($arr_expert); $j++)
            {
                if ($max[$j] > 0)
                    $arr_K[$i][$j] = round($arr_K[$i][$j] / $max[$j], 3);
            }

        //Baca data booking untuk tanggal yang bersangkutan
        $bookings = DB::table('surveys as H')
                    ->join('surveys_detail as D', 'H.id', 'D.id_booking')
                    ->select('H.*', 'D.id_pekerjaan', 'D.lama_waktu')
                    ->where('tanggal', $tanggal)
                    ->orderBy('D.id_booking')
                    ->get();
            
        $idx = 0; $arr_booking = array(); $arr_B = array();
        $email_user = array(); $jam = array(); $lama_waktu = array();
        foreach ($bookings as $booking)
        {
            $arr_booking[$idx] = $booking->id . "," . $booking->id_pekerjaan;
            $email_user[$idx] = $booking->alamat;
            $jam[$idx] = $booking->jam;
            $lama_waktu[$idx] = $booking->lama_waktu;

            $max = array();
            for ($j = 0; $j < sizeof($arr_expert); $j++)
            {
                $max[$j] = 0;
            }
    
            //Baca data keahlian yang diperlukan untuk menyelesaikan pekerjaan booking
            for ($j = 0; $j < sizeof($arr_expert); $j++)
            {
                $work_expert = DB::table('works_detail')
                                    ->where('id_keahlian', $arr_expert[$j])
                                    ->where('id_work', $booking->id_pekerjaan)
                                    ->first();

                if ($work_expert)
                    $arr_B[$idx][$j] = $work_expert->nilai;
                else
                    $arr_B[$idx][$j] = 0;

                //Ambil nilai max untuk setiap kategori
                $max[$j] = max($arr_B[$idx][$j], $max[$j]);
            }

            $idx += 1;
        }

        //Normalisasi
        for ($i = 0; $i < sizeof($arr_booking); $i++)
            for ($j = 0; $j < sizeof($arr_expert); $j++)
            {
                if ($max[$j] > 0)
                    $arr_B[$i][$j] = round($arr_B[$i][$j] / $max[$j], 3);
            }


        //Hapus hasil penjadwalan sebelumnya
        DB::table('schedules')
            ->where('hari', $tanggal)
            ->delete();           
            
        $sesi = 0;
        for ($i = 0; $i < sizeof($arr_booking); $i+=sizeof($arr_mekanik))
        {
            //Sekali proses sebanyak jumlah teknisi yang tersedia
            $sesi += 1;

            //=============
            //  Genetika
            //=============
            //Inisialisasi populasi -> jumlah populasi sebanyak 

			//Tentukan jlh_populasi
            echo "Inisialisasi Populasi<br>
                  ----------------------<br>";
			$jlh_populasi = sizeof($arr_mekanik);
            $populasi = array();

            //set maks pekerjaan per satu sesi
            $maks = (sizeof($arr_booking) - $i > sizeof($arr_mekanik) ? sizeof($arr_mekanik) : (sizeof($arr_booking) - $i));

            //Populasi pertama
            $pos = 0;
            echo "Populasi " . ($pos + 1) . " = ";
            for ($j = 0; $j < $maks; $j++)
            {
                $populasi[$pos][$j] = $j;
                echo $j . "&nbsp";
            }
            echo "<br>";

			//Untuk populasi lainnya --> acak posisinya saja
			for ($x = 1; $x < $jlh_populasi; $x++)
			{
                echo "Populasi " . ($x + 1) . " = ";

				//Passing data dari populasi pertama
                for ($j = 0; $j < $maks; $j++)
                {
                    $populasi[$x][$j] = $j;
                }
                    
				//Acak posisi
				for ($j = 0; $j < $maks; $j++)
				{
                    //Ambil dua buah nilai acak
                    $pos1 = rand(0, $maks - 1);
                    $pos2 = rand(0, $maks - 1);
                    
                    //Tukar posisi					
                    $temp = $populasi[$x][$pos1];
                    $populasi[$x][$pos1] = $populasi[$x][$pos2];
                    $populasi[$x][$pos2] = $temp;					
				}

                for ($j = 0; $j < $maks; $j++)
                {
                    echo $populasi[$x][$j] . "&nbsp";
                }

                echo "<br>";
            }
			
			//Evaluasi Populasi
            echo "<br>";
            echo "Evaluasi Populasi<br>
                  ------------------<br>";

			$fitness = array(); $total_fitness = 0;
			$selesai = false;
			for ($x = 0; $x < $jlh_populasi; $x++)
			{
				//Hitung nilai fitness
				$fitness[$x] = 0;
				
				//Cek per pekerjaan
				for ($j = 0; $j < $maks; $j++)
				{
					//Hitung selisih keahlian dari pekerjaan dan teknisi
					for ($k = 0; $k < sizeof($arr_expert); $k++)
					{
                        $idx_teknisi = $populasi[$x][$j];
						$fitness[$x] += ($arr_K[$idx_teknisi][$k] - $arr_B[$j][$k]);			
					}			
				}

                $fitness[$x] = $fitness[$x] / $maks;
                $total_fitness += $fitness[$x];

                echo "Populasi " . ($x + 1) . " = " . $fitness[$x] . "<br>";
                
				if ($fitness[$x] == 0)
					$selesai = true;
			}
			
			$loop = 0;
			while ((!$selesai) && ($loop < 50))
			{
				$loop += 1;
				
                echo "<br>";
                echo "Seleksi populasi yang akan dikenai operator genetika<br>
                      -----------------------------------------------------<br>";
    
				//Seleksi populasi yang akan dikenai operator genetika
				$saldo = array();
				$persentase = array();				
				for ($x = 0; $x < $jlh_populasi; $x++)
				{
					$persentase[$x] = round($fitness[$x] / $total_fitness, 5);
					
					if ($x > 0)
						$saldo[$x] = $saldo[$x - 1];
					else
						$saldo[$x] = 0;
						
					$saldo[$x] += $persentase[$x];
				}
				
				//Ambil sebuah nilai acak dan bandingkan dengan saldo
				$hasil_seleksi = array();
				for ($x = 0; $x < $jlh_populasi; $x++)
				{
					$acak = rand(0, 10000) / 10000;
					
					//Tentukan hasil seleksi
					$xi = 0;
					while ($acak > $saldo[$xi])
					{
						$xi += 1;
					}
					
					$hasil_seleksi[$x] = $xi;					
				}

				//Set populasi hasil seleksi
				$populasi_baru = array();
				for ($x = 0; $x < $jlh_populasi; $x++)
                {
                    echo "Populasi " . ($x + 1) . " = ";
					for ($j = 0; $j < $maks; $j++)
                    {
                        $populasi_baru[$x][$j] = $populasi[$hasil_seleksi[$x]][$j];
                        echo $populasi_baru[$x][$j] . "&nbsp";
                    }
                    echo "<br>";
                }
				
                echo "<br>";
                echo "Proses penyilangan pasangan entitas tertentu<br>
                      ---------------------------------------------<br>";

				//Proses penyilangan
				$idx = 0;
				$induk_penyilangan = array();
				for ($x = 0; $x < $jlh_populasi; $x++)
				{
					$r = rand(0, 4 * $persentase[$x]);
					
					if ($r < $persentase[$x])
					{
						//Terpilih sebagai induk penyilangan
                        echo "Populasi " . ($x + 1) . " terpilih sebagai entitas induk<br>";
						$induk_penyilangan[$idx] = $x;
						$idx += 1;
					}
				}
				
				if (sizeof($induk_penyilangan) > 1)
				{
                    echo "<br>";
                    echo "Hasil proses penyilangan<br>";

					//Lakukan proses swapping data
					for ($x = 0; $x < sizeof($induk_penyilangan) - 1; $x++)
						for ($y = $x + 1; $y < sizeof($induk_penyilangan); $y++)
						{
							//Swap nilai populasi baru
								for ($j = 0; $j < $maks; $j++)
								{
									$temp = $populasi_baru[$induk_penyilangan[$x]][$j];
									$populasi_baru[$induk_penyilangan[$x]][$j] = $populasi[$induk_penyilangan[$y]][$j];
									$populasi_baru[$induk_penyilangan[$y]][$j] = $temp;
								}
						}
                    
                    for ($x = 0; $x < $jlh_populasi; $x++)
                    {
                        echo "Populasi " . ($x + 1) . " = ";
                        for ($j = 0; $j < $maks; $j++)
                        {
                            echo $populasi_baru[$x][$j] . "&nbsp";
                        }
                        echo "<br>";
                    }
				}
				
                echo "<br>";
                echo "Proses mutasi entitas tertentu<br>
                      -------------------------------<br>";

				//Proses mutasi
				//Tentukan entitas yang akan dikenai mutasi secara acak
				for ($x = 0; $x < $jlh_populasi; $x++)
					for ($j = 0; $j < $maks; $j++)
					{
                        $pos1 = rand(0, $maks - 1);
                        $pos2 = rand(0, $maks - 1);
                        
                        $temp = $populasi_baru[$x][$pos1];
                        $populasi_baru[$x][$pos1] = $populasi_baru[$x][$pos2];;
                        $populasi_baru[$x][$pos2] = $temp;
					}

                    
                for ($x = 0; $x < $jlh_populasi; $x++)
                {
                    echo "Populasi " . ($x + 1) . " = ";
                    for ($j = 0; $j < $maks; $j++)
                    {
                        echo $populasi_baru[$x][$j] . "&nbsp";
                    }
                    echo "<br>";
                }

                echo "<br>";
                echo "Evaluasi populasi baru<br>
                      -----------------------<br>";

				//Evaluasi populasi baru
				//Hitung nilai fitness
				$fitness = array();
				$total_fitness = 0;
				$selesai = false;
				for ($x = 0; $x < $jlh_populasi; $x++)
				{
                    //Hitung nilai fitness
                    $fitness[$x] = 0;
                    
                    //Cek per pekerjaan
                    for ($j = 0; $j < $maks; $j++)
                    {
                        //Hitung selisih keahlian dari pekerjaan dan teknisi
                        for ($k = 0; $k < sizeof($arr_expert); $k++)
                        {
                            $idx_teknisi = $populasi[$x][$j];
                            $fitness[$x] += ($arr_K[$idx_teknisi][$k] - $arr_B[$j][$k]);			
                        }			
                    }

                    $fitness[$x] = $fitness[$x] / $maks;
                    $total_fitness += $fitness[$x];

                    echo "Populasi " . ($x + 1) . " = " . $fitness[$x] . "<br>";
                    
                    if ($fitness[$x] <= 0.25) //kondisi berhenti
                        $selesai = true;                    
				
				}				
				
				//Passing populasi baru ke populasi
				$jadwal = array();
				for ($x = 0; $x < $jlh_populasi; $x++)
					for ($j = 0; $j < $maks; $j++)
							$populasi[$x][$j] = $populasi_baru[$x][$j];
			}
			
			//Ambil solusi dengan fitness terendah sebagai solusi
			$min = $fitness[0];
			$idx_solusi = 0;
			for ($x = 1; $x < $jlh_populasi; $x++)
			{
				if ($min > $fitness[$x])
				{
					$min = $fitness[$x];
					$idx_solusi = $x;
				}
			}
			//--------------------

            for ($k = $i; $k < $i + $maks; $k++)
            {
                $temp = explode(",", $arr_booking[$k]);

                if ($sesi > 1)
                {
                    $schedules = DB::table('schedules')
                                    ->where('kode_mekanik', $arr_mekanik[$populasi[$idx_solusi][$k - $i]])
                                    ->where('hari', $tanggal)
                                    ->orderBy('jam_mulai', 'DESC')
                                    ->first();

                    $jam_mulai = strtotime($schedules->jam_mulai) + $schedules->lama_waktu * 60 * 60;
                    $jam_mulai = date('H:i', $jam_mulai);

                }
                else
                    $jam_mulai = $jam[$k];

                    
                //Simpan hasil penjadwalan
                Schedule::insert([
                    'id_booking' => $temp[0],
                    'id_pekerjaan' => $temp[1],
                    'kode_mekanik' => $arr_mekanik[$populasi[$idx_solusi][$k - $i]],
                    'hari' => $tanggal,
                    'jam_mulai' => $jam_mulai,
                    'jam_booking' => $jam[$k],
                    'lama_waktu' => $lama_waktu[$k],
                    'alamat_customer' => $email_user[$k]
                ]);
            }

        }

        echo "<br>";
        echo "<a href='/schedule_admin_result/$tanggal' class='btn btn-primary my-2'>Tampilkan Hasil Penjadwalan</a>";
        
        return redirect('/schedule_admin_result/' . $tanggal);
    }

    public function schedule_admin_result($tanggal)
    {
        $schedules = Schedule::join('mechanics', 'schedules.kode_mekanik', 'mechanics.id')
                            ->join('works', 'works.id', 'schedules.id_pekerjaan')
                            ->select('schedules.*', 'works.nama_pekerjaan', 'mechanics.nama as nama_mekanik')
                            ->where('hari', $tanggal)
                            ->orderBy('jam_mulai')
                            ->get();
        
        return view('schedule.index_admin')
                ->with(compact('schedules'))
                ->with('tanggal', $tanggal);
    }
}
