@extends('layout.menu_admin')
@section('container')

    <!-- Contact Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h1 class="mb-5">Daftar Keahlian</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-12">   
                    <a href="/expert/create" class="btn btn-primary my-2">Tambah Data Keahlian</a>
                    <table class="table table-striped table-hover">
                      <thead>
                        <th>#</th>
                        <th>Nama Keahlian</th>
                        <th>Bobot</th>
                        <th>Aksi</th>
                      </thead>
                        <tbody>
                            @foreach ($experts as $expert)
                            <tr>
                                <td>{{$expert->id}}</td>						
                                <td>{{$expert->nama}}</td>						
                                <td>{{$expert->bobot}}</td>						
                                <td>
                                    <form action="expert/{{ $expert->id }}" method="post" class="d-inline">  
                                        @method('delete')                              
                                        @csrf
                                        <?php
                                        echo "
                                        <button name='hapus' class='btn btn-danger' OnClick=\"return confirm('Yakin ingin menghapus data $expert->nama ?');\"><i class='fa fa-trash' aria-hidden='true'></i></button>";
                                        ?>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                              
                </div>

                <br>
                {{ $experts->links() }}
            </div>
        </div>
    </div>
    <!-- Contact End -->

@endsection