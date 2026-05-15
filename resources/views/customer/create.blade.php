@extends('layout.base')
@section('container')

    @if(\Session::has('alert'))
        <?php
        $alert = Session::get('alert');
		echo "
		<script type='text/javascript'>
			alert('$alert');
		</script>
		";
        ?>
    @endif

    <!-- Contact Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h1 class="mb-5">Registrasi User Baru</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="wow fadeInUp" data-wow-delay="0.2s">
                        <form method="post" action="/customer">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" name="email" placeholder="Input Email User" required>
                                        <label for="email">Email User</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="nama" placeholder="Your Name" required>
                                        <label for="name">Nama Lengkap</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="user_id" placeholder="Your Name" required>
                                        <label for="name">User ID</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="password" class="form-control" name="kata_sandi" placeholder="Your Name" required>
                                        <label for="name">Kata Sandi</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="no_hp" placeholder="Your Name" required>
                                        <label for="name">Nomor HP</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a message here" name="alamat" style="height: 100px" required></textarea>
                                        <label for="message">Alamat</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div>
                                        <label for="name">Jenis Kelamin:</label><br>
                                        <input type="radio" name="jenis_kelamin" placeholder="Your Name" value="L"> Laki-Laki<br>
                                        <input type="radio" name="jenis_kelamin" placeholder="Your Name" value="P"> Perempuan
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