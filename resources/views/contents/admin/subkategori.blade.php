@extends('layouts.app')
@section('content')
    <div class="container">
        {{ alertbs_form($errors) }}
        <div class="row">
            <div class="col-sm-4">
                <div class="card card-rounded">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title pt-2">
                            @if (!empty($request->get('id')))
                                <i class="fas fa-edit me-1"></i>
                            @else
                                <i class="fas fa-plus me-1"></i>
                            @endif
                            Sub Kategori
                        </h5>
                    </div>
                    <div class="card-body">
                        @if (!empty($request->get('id')))
                            <form method="post" action="{{ route('admin.update_subkategori') }}">
                            @else
                                <form method="post" action="{{ route('admin.create_subkategori') }}">
                        @endif
                        @csrf
                        <div class="form-group">
                            <label for="nama_subkategori">Nama Sub Kategori</label>
                            @if (!empty($request->get('id')))
                                <input type="text"
                                    class="form-control mt-2 @error('nama_subkategori') is-invalid @enderror"
                                    value="{{ $edit->nama_subkategori }}" name="nama_subkategori" id="nama_subkategori"
                                    placeholder="">
                            @else
                                <input type="text"
                                    class="form-control mt-2 @error('nama_subkategori') is-invalid @enderror"
                                    value="{{ old('nama_subkategori') }}" name="nama_subkategori" id="nama_subkategori"
                                    placeholder="">
                            @endif

                            @error('nama_subkategori')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                            {{-- @if (!empty($request->get('id')))
                                <input type="hidden" value="{{ $request->get('id') }}" name="id">
                            @endif --}}
                        </div>

                        <br>

                        <div class="form-group">
                            <label for="">Kategori</label>
                            <select class="form-select" name="id_kategori" required>
                                @foreach ($kategori as $r)
                                    <option value="{{ $r->id }}">{{ $r->nama_kategori }}</option>
                                @endforeach
                            </select>
                            @error('id_kategori')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <button type="submit" class="btn btn-primary mt-3 btn-md">Simpan</button>
                        @if (!empty($request->get('id')))
                            <a href="{{ route('admin.subkategori') }}" class="btn btn-danger mt-3">Kembali</a>
                        @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card card-rounded">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title pt-2"> <i class="fas fa-database me-1"></i> Data Sub Kategori</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="example1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Sub Kategori</th>
                                        <th>Nama Kategori</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no =1;@endphp
                                    @forelse($subkategori as $r)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $r->nama_subkategori }}</td>
                                            <td>{{ $r->nama_kategori }}</td>
                                            <td>
                                                <a href="{{ url("admin/subkategori?id=$r->id") }}"
                                                    class="btn btn-success btn-sm" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="{{ url("admin/subkategori/delete/$r->id") }}"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="javascript:return confirm(`Data ingin dihapus ?`);"
                                                    title="Delete">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @php $no++;@endphp
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                Tidak Ada Data
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <br>
                        {{ $subkategori->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
