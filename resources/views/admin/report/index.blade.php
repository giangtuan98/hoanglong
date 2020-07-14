@extends('admin.layout.app')
@section('content')
<div class="right_col" role="main">
	<div class="" style="margin-top: 50px;">
		@include('admin.layout.flash')
		<div class="page-title">
			<div class="title_left">
				<p>Home / Thống kê</p>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<form action="{{route('admin.report.search')}}" method="GET" id="frmSearchReport">
				<div class="col-md-12">
					<div class="col-md-6 col-xs-12">
						@php 
						$fromDate = request('from_date');
						@endphp
						<input type="text" name="from_date" 
						@if($fromDate) value="{{date('d-m-Y', strtotime($fromDate))}}" @endif
						id="dtpFromDate"
						class="form-control datepicker"
						autocomplete="off"
						readonly=true
						>
					</div>
					<div class="col-md-6 col-xs-12">
						@php
						$toDate = request('to_date');
						@endphp
						<input type="text" name="to_date" 
						@if($toDate) value="{{date('d-m-Y', strtotime($toDate))}}" @endif
						id="dtpToDate"
						class="form-control datepicker"
						autocomplete="off"
						readonly=true
						>
					</div>
					<div class="col-md-12" style="margin-top: 10px;">
						<button class="btn btn-primary float-right" id="btnSearch" href="{{route('admin.report.search')}}">Tìm kiếm <i class="fas fa-search"></i></button>
					</div>
				</div>
			</form>
		</div>
		<div class="">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2><i class="fa fa-bars"></i> Thống kê</h2>
						<button href="{{route('admin.report.excel')}}" target="_blank" class="btn btn-success float-right" id="btnExcel" href="{{route('admin.report.excel')}}"><i class="fas fa-file-excel"></i> Xuất file excel</button>
						
						<div class="clearfix"></div>
						<h3 class="" style="color: red;">Doanh thu: {{number_format($reports->sum('sum_total'))}} VND</h3>
					</div>
					<div class="x_content">
						<table class="table table-bordered" id="datatable">
							<thead>
								<tr>
									<th class="text-center">STT</th>
									<th class="text-center">Tuyến</th>
									<th class="text-center">Doanh thu</th>
									<th class="text-center">Đã thanh toán</th>
									<th class="text-center">Chưa thanh toán </th>
									<th class="text-center">Hoàn trả</th>
									<th class="text-center">Chưa hoàn trả</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($reports as $report)
								<tr>
									<td class="text-center">{{$loop->iteration}}</td>
									<td class="text-center" style="max-width: 160px;">{{$report->route_name}}</td>
									<th class="text-center">{{number_format($report->sum_total)}} VND</th>
									<th class="text-center">{{$report->count_paid}}</th>
									<th class="text-center">{{$report->count_unpaid}}</th>
									<th class="text-center">{{$report->count_refund}}</th>
									<th class="text-center">{{$report->count_not_refund_yet}}</th>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('after-scripts')
<script>
	$("#datatable").DataTable();
	$("#dtpFromDate").datepicker({
		format: 'dd-mm-yyyy'
	});
	$("#dtpToDate").datepicker({
		format: 'dd-mm-yyyy'
	});

	// $("#frmSearchReport"). submit(function () {

	// });

	$("#btnSearch").click(function (e) {
		e.preventDefault();

		if (!$("#dtpFromDate").val() || !$("#dtpToDate").val()) {
			Swal.fire(
				'',
				'Xin mời chọn từ ngày - đến ngày!',
				'warning'
			);
			return;
		}
		const url = $(this).attr('href');
		$("#frmSearchReport").attr("action", url);
		$("#frmSearchReport").submit();
	})

	$("#btnExcel").click(function (e) {
		e.preventDefault();

		const url = $(this).attr('href');
		$("#frmSearchReport").attr("action", url);
		$("#frmSearchReport").submit();
	})
</script>
@endsection
