@extends('layout.menu_admin')
@section('container')

    <!-- Contact Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h1 class="mb-5">Input Data Keahlian</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-12">   
                    <div class="wow fadeInUp" data-wow-delay="0.2s">
                        <form method="post" action="/expert">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="nama" placeholder="Your Name" required>
                                        <label for="name">Nama Keahlian</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" name="bobot" placeholder="Your Name" required>
                                        <label for="name">Bobot</label>
                                    </div>
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