@extends('layouts.app')
@section('content')
    <div class="container">
        <!-- Button trigger modal -->
        {{ alertbs_form($errors) }}
        <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#modelIdPlus">
            <i class="fas fa-plus mr-1"></i> Produk
        </button>
        <div class="card card-rounded mt-2">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title pt-2"> <i class="fas fa-database me-1"></i> Data Produk</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4 ms-auto">
                        <form method="get" action="">
                            <div class="input-group mb-3">
                                <input type="text" value="{{ $request->get('search') }}" name="search" id="search"
                                    class="form-control" placeholder="Cari Produk" aria-describedby="helpId">
                                @if ($request->get('search'))
                                    <a href="{{ route('admin.produk') }}" class="input-group-text btn btn-success btn-md">
                                        <i class="fas fa-sync pr-2"></i>Refresh</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive mt-1">
                    <table class="table table-striped table-bordered" id="example1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama produk</th>
                                <th>Kategori</th>
                                <th>Subkategori</th>
                                <th>Harga jual</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no =1;@endphp
                            @forelse($produk as $r)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td><img src="{{ url_images('gambar', $r->gambar) }}" class="img-fluid"
                                            style="width:80px;"></td>
                                    <td>{{ $r->nama_produk }}</td>
                                    <td>{{ $r->nama_kategori }}</td>
                                    <td>{{ $r->nama_subkategori }}</td>
                                    <td>Rp{{ number_format($r->harga_jual) }},-</td>

                                    <td>
                                        <a href="{{ route('admin.edit_produk', $r) }}" data-id="{{ $r->id }}"
                                            class="btn btn-success btn-sm ubah" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="{{ url("admin/produk/delete/$r->id") }}" class="btn btn-danger btn-sm"
                                            onclick="javascript:return confirm(`Data ingin dihapus ?`);" title="Delete">
                                            <i class="fa fa-times"></i>
                                        </a>
                                        @if ($r->is_best_seller)
                                            <form action="{{ route('admin.remove_bestseller', $r) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-danger" style="margin-top: 5px">Remove
                                                    Best Seller</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.make_bestseller', $r) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success" style="margin-top: 5px">Make
                                                    Best Seller</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @php $no++;@endphp
                            @empty
                                <tr>
                                    <td colspan="7"> Tidak Ada Data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <br>
                {{ $produk->links() }}
            </div>
        </div>

        <!-- Modal (Form)-->
        <div class="modal fade" id="modelIdPlus" data-bs-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Produk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="{{ route('admin.create_produk') }}" enctype="multipart/form-data">

                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="id_kategori" class="form-label">{{ __('Kategori') }}</label>
                                    <select class="form-select" id="id_kategori" name="id_kategori" required>
                                        <option value="" selected>Pilih Kategori</option>
                                        @foreach ($kategori as $r)
                                            <option value="{{ $r->id }}">
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
                                            <option value="{{ $sub->id }}" data-kategori="{{ $sub->id_kategori }}">
                                                {{ $sub->nama_subkategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_sub_kategori')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <script>
                                    document.getElementById('id_kategori').addEventListener('change', function () {
                                        var selectedKategori = this.value;
                                        var subkategoriOptions = document.getElementById('id_sub_kategori').options;

                                        for (var i = 0; i < subkategoriOptions.length; i++) {
                                            var option = subkategoriOptions[i];

                                            if (option.getAttribute('data-kategori') === selectedKategori || selectedKategori === '') {
                                                option.style.display = '';
                                            } else {
                                                option.style.display = 'none';
                                            }
                                        }
                                    });
                                </script>
                            </div>
                        <div class="form-group mt-3">
                            <label for="">Nama Produk</label>
                            <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" required
                                value="{{ old('nama_produk') }}" name="nama_produk" id="nama_produk" placeholder="">
                            @error('nama_produk')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" rows="5" required name="deskripsi"
                                id="deskripsi" placeholder="">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Harga jual</label>
                            <input type="number" class="form-control @error('harga_jual') is-invalid @enderror" required
                                value="{{ old('harga_jual') }}" name="harga_jual" id="harga_jual" placeholder="">
                            @error('harga_jual')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Gambar</label>
                            <input type="file" class="form-control @error('gambar') is-invalid @enderror" required
                                value="{{ old('gambar') }}" name="gambar" id="gambar" placeholder="">
                            @error('gambar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <div class="modal fade" id="modelIdEdit" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="edit-content">

            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        // Call the dataTables jQuery plugin
        $('#example1 tbody').on('click', '.ubah', function() {
            var id = $(this).attr('data-id');
            $('#modelIdEdit').modal('show');
            $.ajax({
                url: '{{ route('admin.edit_produk') }}',
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                timeout: 60000,
                dataType: 'html',
                success: function(html) {
                    $("#edit-content").html(html);
                }
            });
        });
    </script>
@endsection
