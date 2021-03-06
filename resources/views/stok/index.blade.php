@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading">Kartu Stok</div>
              <div class="panel-body">
                <div class="overview">
                    <div class="col-md-6 overview-line no-padding">
                      <label class="col-xs-5 no-padding">Nama Obat</label>
                      <label class="col-xs-1 no-padding">:</label>
                      <label class="col-xs-6 no-padding">{{$obat->nama}}</label>
                    </div>
                    <div class="col-md-6 overview-line no-padding">
                      <label class="col-xs-5 no-padding">Dosis</label>
                      <label class="col-xs-1 no-padding">:</label>
                      <label class="col-xs-6 no-padding">{{$obat->dosis.' '.$obat->satuan_dosis}}</label>
                    </div>
                    <div class="col-md-6 overview-line no-padding">
                      <label class="col-xs-5 no-padding">Bentuk Sediaan</label>
                      <label class="col-xs-1 no-padding">:</label>
                      <label class="col-xs-6 no-padding">{{$obat->bentuk_sediaan}}</label>
                    </div>
                   <div class="col-md-6 overview-line no-padding">
                      <label class="col-xs-5 no-padding">Total Stok</label>
                      <label class="col-xs-1 no-padding">:</label>
                      <label class="col-xs-6 no-padding">{{$overview["total_stok"]}}</label>
                    </div>
                   <div class="col-md-6 overview-line no-padding">
                      <label class="col-xs-5 no-padding">Tanggal Expired Terdekat</label>
                      <label class="col-xs-1 no-padding">:</label>
                      <label class="col-xs-6 no-padding">{{date("d-m-Y",strtotime($overview["expired"]))}}</label>
                    </div>
                   <div class="col-md-6 overview-line no-padding">
                      <label class="col-xs-5 no-padding">Rata-rata Harga Beli</label>
                      <label class="col-xs-1 no-padding">:</label>
                      <label class="col-xs-6 no-padding">Rp {{number_format($overview["harga_beli"],2,",",".")}}</label>
                    </div>
                </div>
                <a class="btn btn-small btn-success" href="{{ URL::to('stok/'.$obat->id.'/create') }}">Stok Baru</a>
				        <hr />
                @if (Session::has('message'))
                	<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif

                <ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" href="#tab-table1">Alur Stok</a></li>
                  <li><a data-toggle="tab" href="#tab-table2">Stok Saat Ini</a></li>
                </ul>
                <div class="tab-content">
                  <div id="tab-table1" class="tab-pane fade in active">
                    <br>
                    <table id="table1" class="data-table row-border hover stripe table-bordered" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                              <th class="number-td"></th>
                              <th>Tanggal</th>
                              <th class="text-center">M</th>
                              <th class="text-center">K</th>
                              <th class="text-center">S</th>
                              {{-- <th>Harga</th>
                              <th>Tanggal Expired</th> --}}
                              <th>Keterangan</th>
                              <th></th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php $sisa = $overview["total_stok"];  $ctr=0; $temp = 0;?>
                        @foreach ($stokData as $stok)
                            <?php
                              if($ctr>0) $sisa = $sisa - $temp;
                            ?>
                            <tr>
                                <td class="number-td"></td>
                                <td>{{date("d-m-Y",strtotime($stok->tanggal))}}</td>
                                <td class="text-center">{{($stok->jenis == 'masuk')? abs($stok->jumlah) : 0}}</td>
                                <td class="text-center">{{($stok->jenis == 'keluar')? abs($stok->jumlah) : 0}}</td>
                                <td class="text-center">{{($sisa)}}</td>
                                {{-- <td>Rp {{number_format($stok->harga,2,",",".")}}</td>
                                <td>{{date("d-m-Y",strtotime($stok->expired_date))}}</td> --}}
                                <td>{{$stok->keterangan}}</td>
                                <td>
                                    {{-- <a class="col-sm-12 col-lg-5 btn btn-info btn-action less-margin" href="{{ URL::to('stok/'.$stok->id.'/edit') }}">Ubah</a> --}}
                                    @if ($stok->buatan == 1)
                                      <a class="col-sm-12 col-lg-12 btn btn-warning btn-action" href="{{ url('stok', [$stok->id]) }}" data-method="delete" data-token="{{csrf_token()}}">Hapus</a>
                                    @endif
                                </td>
                            </tr>
                            <?php $temp=$stok->jumlah; $ctr=$ctr+1; ?>
                        @endforeach
                        </tbody>
                    </table>
                  </div>
                  <div id="tab-table2" class="tab-pane fade">
                    <br>
                    <table id="table2" class="data-table row-border hover stripe table-bordered" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                              <th class="number-td"></th>
                              <th>Tanggal Beli</th>
                              <th>Tanggal Expired</th>
                              <th>Jumlah</th>
                              <th>Harga Beli</th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach ($descStok as $stok)
                            @if ($stok->total > 0)
                              <tr>
                                  <td class="number-td"></td>
                                  <td>{{date("d-m-Y",strtotime($stok->tanggal))}}</td>
                                  <td>{{date("d-m-Y",strtotime($stok->expired_date))}}</td>
                                  <td>{{$stok->total}}</td>
                                  <td>Rp {{number_format($stok->harga,2,",",".")}}</td>
                              </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
<script>
        $(document).ready(function(){
          $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
           $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
        });

        var t = $('#table1').DataTable( {
        "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        },{
            "width": "8%",
            "targets": [0,2,3,4]
        } ,{
            "width": "10%",
            "targets": [1]
        } ,{
            "width": "15%",
            "targets": [6]
        } ],
        "ordering" : false,
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

        var t2 = $('#table2').DataTable( {
        "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "ordering" : false,
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Indonesian.json"
        }
        } );

        t2.on( 'order.dt search.dt', function () {
            t2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });
</script>
@endsection
