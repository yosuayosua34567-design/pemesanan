@extends('layout.menu_admin')
@section('container')

    <!-- Contact Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h1 class="mb-5">Daftar Teknisi</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-12">   
                    <a href="/mechanic/create" class="btn btn-primary my-2">Tambah Data Teknisi</a>
                    <table class="table table-striped table-hover">
                      <thead>
                        <th>#</th>
                        <th>Nama</th>
                        <th>No HP</th>
                        <th>Alamat</th>
                        <th>Jenis Kelamin</th>
                        <th>Keahlian</th>
                        <th>Aksi</th>
                      </thead>
                        <tbody>
                            <?php
                                $loops = 0;
                            ?>
                            @foreach ($mechanics as $mechanic)
                            <tr>
                                <td><?php $loops += 1; print $loops; ?></td>						
                                <td>{{$mechanic->nama}}</td>						
                                <td>{{$mechanic->no_hp}}</td>						
                                <td>{{$mechanic->alamat}}</td>						
                                <td>{{$mechanic->jenis_kelamin}}</td>	
                                <td>
                                    <?php
                                        $mechanics_expert = DB::table('mechanics_detail as M')
                                                                ->join('experts as E', 'M.id_keahlian', 'E.id')
                                                                ->select('E.nama', 'M.nilai')
                                                                ->where('id_mechanic', $mechanic->id)
                                                                ->get();

                                        foreach ($mechanics_expert as $expert)
                                        {
                                            print $expert->nama . " = " . $expert->nilai . "<br>";
                                        }

                                    ?>
                                </td>					
                                <td>
                                    <form action="mechanic/{{ $mechanic->id }}" method="post" class="d-inline">  
                                        @method('delete')                              
                                        @csrf
                                        <?php
                                        echo "
                                        <button name='hapus' class='btn btn-danger' OnClick=\"return confirm('Yakin ingin menghapus data $mechanic->nama ?');\"><i class='fa fa-trash' aria-hidden='true'></i></button>";
                                        ?>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                              
                </div>

                <br>
                {{ $mechanics->links() }}
            </div>
        </div>
    </div>
    <!-- Contact End -->

@endsection