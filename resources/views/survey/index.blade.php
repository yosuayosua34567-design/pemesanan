@extends('layout.menu_admin')
@section('container')

    <!-- Contact Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h1 class="mb-5">Daftar Hasil Survei Pekerjaan</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-12">   
                    <!--
                    <a href="/survey/create" class="btn btn-primary my-2">Tambah Data Hasil Survei</a>
                    -->

                    <table class="table table-striped table-hover">
                      <thead>
                        <th>ID Booking</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Alamat</th>
                        <th>Keterangan</th>
                        <th>Detail Pekerjaan</th>
                        <th>Aksi</th>
                      </thead>
                        <tbody>
                            @foreach ($surveys as $survey)
                            <tr>
                                <td>{{$survey->id}}</td>						
                                <td>{{$survey->tanggal}}</td>						
                                <td>{{$survey->jam}}</td>						
                                <td>{{$survey->alamat}}</td>						
                                <td>{{$survey->keterangan}}</td>						
                                <td>
                                    <?php
                                        $surveys_detail = DB::table('surveys_detail as M')
                                                                ->join('works as E', 'M.id_pekerjaan', 'E.id')
                                                                ->select('E.nama_pekerjaan', 'M.lama_waktu')
                                                                ->where('id_booking', $survey->id)
                                                                ->get();

                                        foreach ($surveys_detail as $surveys_)
                                        {
                                            print $surveys_->nama_pekerjaan . " (" . $surveys_->lama_waktu . " jam)<br>";
                                        }

                                    ?>
                                </td>					
                                <td>
                                    <form action="survey/{{ $survey->id }}" method="post" class="d-inline">  
                                        @method('delete')                              
                                        @csrf
                                        <?php
                                        echo "
                                        <button name='hapus' class='btn btn-danger' OnClick=\"return confirm('Yakin ingin menghapus data $survey->nama ?');\"><i class='fa fa-trash' aria-hidden='true'></i></button>";
                                        ?>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                              
                </div>

                <br>
                {{ $surveys->links() }}
            </div>
        </div>
    </div>
    <!-- Contact End -->

@endsection