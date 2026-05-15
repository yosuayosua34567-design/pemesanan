@extends('layout.menu_admin')
@section('container')

    <!-- Contact Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h1 class="mb-5">Daftar Pekerjaan</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-12">   
                    <a href="/work/create" class="btn btn-primary my-2">Tambah Data Pekerjaan</a>
                    <table class="table table-striped table-hover">
                      <thead>
                        <th>#</th>
                        <th>Nama Pekerjaan</th>
                        <th>Keahlian</th>
                        <th>Aksi</th>
                      </thead>
                        <tbody>
                            @foreach ($works as $work)
                            <tr>
                                <td>{{$work->id}}</td>						
                                <td>{{$work->nama_pekerjaan}}</td>						
                                <td>
                                    <?php
                                        $works_expert = DB::table('works_detail as M')
                                                                ->join('experts as E', 'M.id_keahlian', 'E.id')
                                                                ->select('E.nama', 'M.nilai')
                                                                ->where('id_work', $work->id)
                                                                ->get();

                                        foreach ($works_expert as $expert)
                                        {
                                            print $expert->nama . " = " . $expert->nilai . "<br>";
                                        }

                                    ?>
                                </td>					
                                <td>
                                    <form action="work/{{ $work->id }}" method="post" class="d-inline">  
                                        @method('delete')                              
                                        @csrf
                                        <?php
                                        echo "
                                        <button name='hapus' class='btn btn-danger' OnClick=\"return confirm('Yakin ingin menghapus data $work->nama ?');\"><i class='fa fa-trash' aria-hidden='true'></i></button>";
                                        ?>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                              
                </div>

                <br>
                {{ $works->links() }}
            </div>
        </div>
    </div>
    <!-- Contact End -->

@endsection