@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="post" action="{{ route('admin.update_produk') }}" enctype="multipart/form-data">
            @csrf
            {{-- <div class="form-group">
                <input type="hidden" name="id_kategori" value="{{ $kategori->first()->id }}">
                <label for="id_kategori_select">Kategori</label>
                <select class="form-select" id="id_kategori_select" name="id_kategori_select">
                    @foreach ($kategori as $r)
                        <option value="{{ $r->id }}"
                            {{ old('id_kategori_select', $kategori->first()->id) == $r->id ? 'selected' : '' }}>
                            {{ $r->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('id_kategori')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="id_sub_kategori">Sub Kategori</label>
                <select class="form-select" id="id_sub_kategori" name="id_sub_kategori" required>
                    @foreach ($subkategori as $sub)
                        <option value="{{ $sub->id }}" {{ $edit->id_sub_kategori == $sub->id ? 'selected' : '' }}>
                            {{ $sub->nama_subkategori }}
                        </option>
                    @endforeach
                </select>
                @error('id_sub_kategori')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div> --}}

            <div class="mb-3">
                <label for="id_kategori" class="form-label">{{ __('Kategori') }}</label>
                <select class="form-select" id="id_kategori" name="id_kategori" required>
                    <option value="" selected>Pilih Kategori</option>
                    @foreach ($kategori as $r)
                        <option value="{{ $r->id }}" {{ $edit->id_kategori == $r->id ? 'selected' : '' }}>
                            {{ $r->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('id_kategori')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="id_sub_kategori" class="form-label">{{ __('Subkategori') }}</label>
                <select class="form-select" id="id_sub_kategori" name="id_sub_kategori" required>
                    <option value="" selected>Pilih Subkategori</option>
                    @foreach ($subkategori as $sub)
                        <option value="{{ $sub->id }}" data-kategori="{{ $sub->id_kategori }}"
                            {{ $edit->id_sub_kategori == $sub->id ? 'selected' : '' }}>
                            {{ $sub->nama_subkategori }}
                        </option>
                    @endforeach
                </select>
                @error('id_sub_kategori')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <script>
                // Inisialisasi nilai terpilih untuk kategori
                var selectedKategori = document.getElementById('id_kategori').value;

                // Pemfilteran subkategori berdasarkan kategori yang dipilih
                function filterSubkategori() {
                    var subkategoriOptions = document.getElementById('id_sub_kategori').options;

                    for (var i = 0; i < subkategoriOptions.length; i++) {
                        var option = subkategoriOptions[i];

                        if (option.getAttribute('data-kategori') === selectedKategori || selectedKategori === '') {
                            option.style.display = '';
                        } else {
                            option.style.display = 'none';
                        }
                    }
                }

                // Memanggil fungsi pemfilteran saat halaman dimuat
                filterSubkategori();

                // Menambahkan event listener untuk perubahan pada kategori
                document.getElementById('id_kategori').addEventListener('change', function() {
                    selectedKategori = this.value;
                    filterSubkategori();
                });
            </script>

            <div class="form-group mt-3">
                <label for="nama_produk">Nama Produk</label>
                <input type="text" class="form-control @error('nama_produk') is-invalid @enderror"
                    value="{{ $edit->nama_produk }}" name="nama_produk" id="nama_produk" placeholder="">
            </div>

            <div class="form-group mt-3">
                <label for="deskripsi">Deskripsi</label>
                <textarea class="form-control @error('deskripsi') is-invalid @enderror" rows="8" name="deskripsi"
                    id="deskripsi" placeholder="">{{ $edit->deskripsi }}</textarea>
            </div>

            <div class="form-group mt-3">
                <label for="harga_jual">Harga jual</label>
                <input type="number" class="form-control @error('harga_jual') is-invalid @enderror"
                    value="{{ $edit->harga_jual }}" name="harga_jual" id="harga_jual" placeholder="">
            </div>

            <div class="form-group mt-3">
                <label for="gambar">Gambar <small class="text-danger ms-1">* Opsional</small></label>
                <input type="file" class="form-control @error('gambar') is-invalid @enderror" value="{{ old('gambar') }}"
                    name="gambar" id="gambar" placeholder="">
                @error('gambar')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <a href="{{ url_images('gambar', $edit->gambar) }}" target="_blank">
                    <img src="{{ url_images('gambar', $edit->gambar) }}" class="img-fluid mt-3" style="width:80px;">
                </a>
            </div>

            <input type="hidden" value="{{ $edit->id }}" name="id">

            <div class="form-group mt-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection
