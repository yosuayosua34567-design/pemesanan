@extends('layout.menu_admin')
@section('container')

    <!-- The Modal -->
    <div class="modal fade" id="addnewproduct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            
            <form action="/surveys_detail_temp" method="post">
                @csrf
                <input type="hidden" name="id_booking" value="{{$id_booking}}" />

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Pekerjaan</h4>                
                </div>


                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            Pekerjaan:<br/>
                            <select name="id_pekerjaan" class="form-control" required>
                                <option value="">Pilih Jenis Pekerjaan</option>
                                <?php
                                $works = DB::table('works')->pluck('nama_pekerjaan','id');
                                foreach ($works as $id => $nama_pekerjaan) {
                                    echo "
                                    <option value='$id'>$nama_pekerjaan</option>
                                        ";
                                } 
                                ?>
                            </select>                            
                        </div>

                        <div class="col-md-12 mb-3">
                            Lama Waktu (jam):<br/>
                            <input type="number" name="lama_waktu" placeholder="Masukkan Lama Waktu" > 
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">     
                    <input value="Simpan" type="submit" class="btn btn-primary" name="tombol">               
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                </div>
            
            </form>

            </div>
        </div>
    </div>


    <!-- Contact Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h1 class="mb-5">Input Data Hasil Survei Pekerjaan</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="wow fadeInUp" data-wow-delay="0.2s">
                        <form method="post" action="/survey">
                            @csrf
                            <input type="hidden" name="id_booking" value="{{$id_booking}}" />

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="name"><b>Informasi Booking:</b></label>
                                    <p>
                                        <?php
                                            $bookings = DB::table('bookings')
                                                            ->where('id', $id_booking)
                                                            ->first();
                                            
                                            print "Customer: " . $bookings->email_user . "<br>" .
                                                  "Tanggal: " . $bookings->tanggal . "<br>" .
                                                  "Keterangan: " . $bookings->keterangan;
                                        ?>
                                    </p>
                                    <br>
                                </div>
                            </div>


                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" name="tanggal" placeholder="Your Name">
                                        <label for="name">Tanggal</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="time" class="form-control" name="jam" placeholder="Your Name">
                                        <label for="name">Jam</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a message here" name="alamat" style="height: 100px" required></textarea>
                                        <label for="message">Alamat Customer</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a message here" name="keterangan" style="height: 100px" required></textarea>
                                        <label for="message">Keterangan</label>
                                    </div>
                                </div>
                                
                                <?php
                                    $surveys = DB::table('surveys_detail_temp as M')
                                                    ->join('works as E', 'E.id', 'M.id_pekerjaan')
                                                    ->select('M.*', 'E.nama_pekerjaan')
                                                    ->get();                                                    
                                ?>

                                <div class="col-md-12">   
                                    <a href="#" class="btn btn-warning my-2" data-bs-toggle="modal" data-bs-target="#addnewproduct">Tambah Pekerjaan</a>
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <th>Nama Pekerjaan</th>
                                            <th>Lama Waktu</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($surveys as $survey)
                                            <tr>
                                                <td>{{$survey->nama_pekerjaan}}</td>						
                                                <td>{{$survey->lama_waktu}}</td>						
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>                              
                                </div>                                

                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3" type="submit">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->


@endsection