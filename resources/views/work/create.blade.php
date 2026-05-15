@extends('layout.menu_admin')
@section('container')

    <!-- The Modal -->
    <div class="modal fade" id="addnewproduct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            
            <form action="/works_detail_temp" method="post">
                @csrf
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Keahlian</h4>                
                </div>


                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            Keahlian:<br/>
                            <select name="id_keahlian" class="form-control" required>
                                <option value="">Pilih Keahlian</option>
                                <?php
                                $experts = DB::table('experts')->pluck('nama','id');
                                foreach ($experts as $id => $nama) {
                                    echo "
                                    <option value='$id'>$nama</option>
                                        ";
                                }            
                                ?>
                            </select>                            
                        </div>

                        <div class="col-md-12 mb-3">
                            Nilai:<br/>
                            <input type="number" name="nilai" placeholder="Masukkan Nilai Keahlian" > 
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
                <h1 class="mb-5">Input Data Pekerjaan</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="wow fadeInUp" data-wow-delay="0.2s">
                        <form method="post" action="/work">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <a href="#" class="btn btn-warning my-2" data-bs-toggle="modal" data-bs-target="#addnewproduct">Tambah Keahlian Pekerjaan</a>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="nama_pekerjaan" placeholder="Your Name">
                                        <label for="name">Nama Pekerjaan</label>
                                    </div>
                                </div>
                                <input type="hidden" name="lama_waktu" value="0" />
                                <!--
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="lama_waktu" placeholder="Your Name">
                                        <label for="name">Lama Waktu (jam)</label>
                                    </div>
                                </div>
                                -->
                  
                                <?php
                                    $experts = DB::table('works_detail_temp as M')
                                                    ->join('experts as E', 'E.id', 'M.id_keahlian')
                                                    ->select('M.*', 'E.nama')
                                                    ->get();                                                    
                                ?>

                                <div class="col-md-12">                                       
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <th>Nama Keahlian</th>
                                            <th>Nilai</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($experts as $expert)
                                            <tr>
                                                <td>{{$expert->nama}}</td>						
                                                <td>{{$expert->nilai}}</td>						
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