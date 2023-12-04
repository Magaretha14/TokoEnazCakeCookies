{{-- Form Edit --}}
<div class="modal-header">
    <h5 class="modal-title">Edit Produk</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form method="post" action="{{ route('admin.update_produk') }}" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            {{-- <div class="mb-3">
                <label for="">Kategori</label>
                <select class="form-select" id="id_kategori" name="id_kategori" disabled required>
                    @foreach ($kategori as $r)
                        <option value="{{ $r->id }}">
                            {{ $r->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('id_kategori')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div> --}}
            <!-- Input tersembunyi untuk menyimpan nilai id_kategori -->
            <input type="hidden" name="id_kategori" value="{{ $kategori->first()->id }}">

            <div class="mb-3">
                <label for="id_kategori_select">Kategori</label>
                <select class="form-select" id="id_kategori_select" name="id_kategori_select" disabled required>
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
                <div class="mb-3">
                    <label for="">Sub Kategori</label>
                    <select class="form-select" id="id_sub_kategori" name="id_sub_kategori" required>
                        @foreach ($subkategori as $sub)
                            <option value="{{ $sub->id }}"
                                {{ $edit->id_sub_kategori == $sub->id ? 'selected' : '' }}>
                                {{ $sub->nama_subkategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_sub_kategori')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <script>
                    console.log("test");
                    document.getElementById('id_kategori').addEventListener('change', function() {
                        var selectedKategori = this.value;
                        var subkategoriSelect = document.getElementById('id_sub_kategori');
                        // Menggunakan AJAX untuk mendapatkan subkategori berdasarkan kategori
                        fetch(`/get-subkategori?kategori_id=${selectedKategori}`)
                            .then(response => response.json())
                            .then(data => {
                                // Mengosongkan dan menambahkan opsi subkategori baru
                                subkategoriSelect.innerHTML = '<option value="" selected>Pilih Subkategori</option>';
                                data.subkategori.forEach(sub => {
                                    var option = document.createElement('option');
                                    option.value = sub.id;
                                    option.textContent = sub.nama_subkategori;
                                    subkategoriSelect.appendChild(option);
                                });
                            })
                            .catch(error => console.error('Error:', error));
                    });
                </script>
            </div>

        </div>
        <div class="form-group mt-3">
            <label for="">Nama Produk</label>
            <input type="text" class="form-control @error('nama_produk') is-invalid @enderror"
                value="{{ $edit->nama_produk }}" name="nama_produk" id="nama_produk" placeholder="">
        </div>

        <div class="form-group mt-3">
            <label for="">Deskripsi</label>
            <textarea class="form-control @error('deskripsi') is-invalid @enderror" rows="8" name="deskripsi" id="deskripsi"
                placeholder="">{{ $edit->deskripsi }}</textarea>
        </div>
        <div class="form-group mt-3">
            <label for="">Harga jual</label>
            <input type="number" class="form-control @error('harga_jual') is-invalid @enderror"
                value="{{ $edit->harga_jual }}" name="harga_jual" id="harga_jual" placeholder="">
        </div>
        <div class="form-group mt-3">
            <label for="">Gambar <small class="text-danger ms-1">* Opsional</small></label>
            <input type="file" class="form-control @error('gambar') is-invalid @enderror"
                value="{{ old('gambar') }}" name="gambar" id="gambar" placeholder="">
            @error('gambar')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            <a href="{{ url_images('gambar', $edit->gambar) }}" target="_blank">
                <img src="{{ url_images('gambar', $edit->gambar) }}" class="img-fluid mt-3" style="width:80px;">
            </a>
        </div>
        <input type="hidden" value="{{ $edit->id }}" name="id">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>
