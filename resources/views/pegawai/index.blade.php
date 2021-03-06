@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading">Pegawai</div>
              <div class="panel-body">
                <a class="btn btn-small btn-success" href="{{ URL::to('pegawai/create') }}">Pegawai Baru</a>
				        <hr />
                @if (Session::has('message'))
                	<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <table id="table-pegawai" class="row-border stripe table-bordered" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                          <th class="number-td">No</th>
                          <th>Nama</th>
                          <th>Alamat</th>
                          <th>Telepon</th>
                          <th>Gaji</th>
                          <th>Username</th>
                          <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="number-td"></td>
                            <td>{{$user->nama}}</td>
                            <td>{{$user->alamat}}</td>
                            <td>{{$user->telepon}}</td>
                            <td>Rp {{number_format($user->gaji,2,",",".")}}</td>
                            <td>{{$user->username}}</td>
                            <td>
                                <a class="col-sm-12 col-lg-5 btn btn-small btn-info less-margin" href="{{ URL::to('pegawai/'.$user->id.'/edit') }}">Ubah</a>
                                <a class="col-sm-12 col-lg-5 btn btn-small btn-warning less-margin" href="{{ url('pegawai', [$user->id]) }}" data-method="delete" data-token="{{csrf_token()}}">Hapus</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    {{-- <tfoot>
                      <tr>
                          <th></th>
                          <th>Nama</th>
                          <th>Alamat</th>
                          <th>Telepon</th>
                          <th>Gaji</th>
                          <th>Username</th>
                          <th>&nbsp;</th>
                      </tr>
                    </tfoot> --}}
                </table>
              </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var t = $('#table-pegawai').DataTable( {
        "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]],
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Indonesian.json"
        }
        } );

        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });
</script>
@endsection
