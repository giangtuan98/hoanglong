@extends('admin.layout.app')
@section('content')
<div class="right_col" role="main">
	<div class="" style="margin-top: 50px;">
		@include('admin.layout.flash')
		<div class="page-title">
			<div class="title_left">
				<p>Home / Lịch chạy</p>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<form action="{{route('admin.trip_date.search')}}" method="GET" id="frmSearchTripDD">
				<div class="col-md-12" style="margin-bottom: 10px;">
					<div class="col-md-4 col-xs-12">
						<select class="form-control" name="searchRouteId" id="searchRouteId" name="searchRouteId">
							<option value="" selected>Tuyến xe</option>
							@foreach($routes as $route)
							<option value="{{$route->id}}" @if($route->id == request('searchRouteId')) selected @endif>{{$route->name}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-4 col-xs-12">
						<select class="form-control" name="searchTripId" id="searchTripId" name="searchTripId">
							@if(isset($tripsByRoute))
								<option value="">Tất cả</option>
								@foreach ($tripsByRoute as $trip)
								<option value="{{$trip->id}}" @if($trip->id == request('searchTripId')) selected @endif>{{$trip->name}}</option>
								@endforeach
							@else
								<option value="">Vui lòng chọn tuyến xe</option>
							@endif
						</select>
					</div>
					<div class="col-md-4 col-xs-12">
						@php $searchIsActive = request('searchIsActive') ?? -1;@endphp
						<select class="form-control" name="searchIsActive" id="">
							<option value="-1" @if($searchIsActive == -1) selected @endif>Tất cả</option>
							<option value="1" @if($searchIsActive == 1) selected @endif>Hoạt động</option>
							<option value="0" @if($searchIsActive == 0) selected @endif>Ngưng hoạt động</option>
						</select>
					</div>
				</div>
				<div class="col-md-12">
					<div class="col-md-offset-2 col-md-4  col-xs-12">
						@php 
						$fromDate = request('searchFromDate', $fromDate);
						@endphp
						<input type="text" name="searchFromDate" 
						@if($fromDate) value="{{date('d-m-Y', strtotime($fromDate))}}" @endif
						id="searchFromDate"
						class="form-control datepicker"
						autocomplete="off"
						>
					</div>
					<div class="col-md-4  col-xs-12">
						@php
						$toDate = request('searchToDate', $toDate);
						@endphp
						<input type="text" name="searchToDate" 
						@if($toDate) value="{{date('d-m-Y', strtotime($toDate))}}" @endif
						id="searchToDate"
						class="form-control datepicker"
						autocomplete="off"
						>
					</div>
					
					<div class="col-md-12" style="margin-top: 10px;">
						<button class="btn btn-primary float-right">Tìm kiếm <i class="fas fa-search"></i></button>
					</div>
				</div>
			</form>
		</div>
		<div class="">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2><i class="fa fa-bars"></i> Danh sách lịch chạy</h2>
						<button data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-success float-right" id="btnCreateSingleSchedule"><i class="fa fa-pencil-alt"></i> Thêm/Hủy lịch theo chuyến</button>
						<button data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-warning float-right" id="btnCreateMultiSchedule"><i class="fa fa-pencil-alt"></i> Thêm/Hủy lịch theo tuyến</button>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<table class="table table-bordered" id="datatable">
							<thead>
								<tr>
									<th class="text-center">STT</th>
									<th class="text-center">Tên chuyến</th>
									<th class="text-center">Giờ đi</th>
									<th class="text-center">Giờ đến</th>
									<th class="text-center">Ngày đi</th>
									<th class="text-center">Số ghế trống</th>
									<th class="text-center">Trạng thái</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($tripDepartDates as $tdd)
								<tr>
									<td class="text-center">{{$loop->iteration}}</td>
									<td class="text-center" style="max-width: 160px;">{{$tdd->trip->name}}</td>
									<td class="text-center">{{date('H:i', strtotime($tdd->trip->depart_time))}}</td>
									<td class="text-center">{{date('H:i', strtotime($tdd->trip->arrive_time))}}</td>
									<td class="text-center">{{date('d-m-Y', strtotime($tdd->depart_date))}}</td>
									<td class="text-center">{{$tdd->available_seats}}</td>
									<td class="text-center">{{$tdd->is_active}}</td>
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
<div class="modal fade bs-example-modal-lg in" tabindex=false role="dialog" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Lịch chạy</h4>
			</div>
			<div class="modal-body">
				<form id="frmCreateSchedule" method="post" data-mode="single" data-action="create" style="z-index:9999">
					<div class="form-group col-md-12">
						<label for="">Tuyến</label>
						<select name="route_id" class="form-control select2" id="slRouteId" style="z-index:999999">
							<option value="" selected></option>
							@foreach($routes as $route)
							<option value="{{$route->id}}">{{$route->name}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-12" id="divSelecTripId">
						<label for="">Chuyến</label>
						<select name="trip_id" class="form-control select2" id="slTripId">
						</select>
					</div>
					<div class="form-group col-md-6">
						<label for="">Từ ngày</label>
						<input type="text" class="form-control datepicker" placeholder="" name="name" id="dtpFromDate" readonly="true" autocomplete="false" required>
					</div>
					<div class="form-group col-md-6">
						<label for="">Đến ngày</label>
						<input type="text" class="form-control datepicker" placeholder=""" name="name" id="dtpToDate" readonly="true" autocomplete="false" required>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary submit-frm-schedule" data-action="create" id="btnCreateSchedule">Tạo lịch</button>
				<button type="button" class="btn btn-success submit-frm-schedule" data-action="active" id="btnActiveSchedule">Kích hoạt</button>
				<button type="button" class="btn btn-danger submit-frm-schedule" data-action="inactive" id="btnInactiveSchedule">Ngưng kích hoạt</button>
			</div>
		</div>
	</div>
</div>
@endsection
@section('after-scripts')
<script>
	const today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
	$("#datatable").DataTable();
	$("#dtpFromDate").datepicker({
		format: 'dd-mm-yyyy',
		minDate: today,
		maxDate: function() {
			const date = new Date();
			date.setDate(date.getDate()+15);
			return new Date(date.getFullYear(), date.getMonth(), date.getDate());
		}
	});
	$("#dtpToDate").datepicker({
		format: 'dd-mm-yyyy',
		minDate: today,
		maxDate: function() {
			const date = new Date();
			date.setDate(date.getDate()+15);
			return new Date(date.getFullYear(), date.getMonth(), date.getDate());
		}
	});
	$("#dtpToDate1").datepicker({
		format: 'dd-mm-yyyy',
		minDate: today,
		maxDate: function() {
			const date = new Date();
			date.setDate(date.getDate()+15);
			return new Date(date.getFullYear(), date.getMonth(), date.getDate());
		}
	});
	$("#dtpFromDate1").datepicker({
		format: 'dd-mm-yyyy',
		minDate: today,
		maxDate: function() {
			const date = new Date();
			date.setDate(date.getDate()+15);
			return new Date(date.getFullYear(), date.getMonth(), date.getDate());
		}
	});
	$("#searchFromDate").datepicker({
		format: 'dd-mm-yyyy'
	});
	$("#searchToDate").datepicker({
		format: 'dd-mm-yyyy'
	});
	
	$("#searchRouteId").select2();
	$("#searchTripId").select2();
	$("#slRouteId").select2();
	$("#slTripId").select2();
	$("#slRouteId").change(function() {
		const routeId = $(this).val();
		
		$("#slTripId").empty();
		slTripId = $("#slTripId");
		fetchTripFromRoute(routeId, slTripId);
	});
	$("#searchRouteId").change(function() {
		const routeId = $(this).val();
		slTripId = $("#searchTripId");
		fetchTripFromRoute(routeId, slTripId);
	});
	
	function fetchTripFromRoute(routeId, slTripId) {
		slTripId.empty();
		const option1 = `<option value="">Xin mời chọn tuyến xe</option>`;
		if (!routeId) {
			slTripId.append(option1);
			return;
		}
		$.ajax({
			type: 'get',
			url: `/admin/routes/${routeId}/trips`,
			success: function(response) {
				if (response.status === 200) {
					$("#slTripId").empty();
					const option = 
					slTripId.append(`<option value="">Tất cả</option>`);
					for (const trip of response.data.trips) {
						const option = `<option value="${trip.id}">${trip.name}</option>`;
						slTripId.append(option);
					}
				}
			},
			error: function(err) {
				console.log(err);
			}
		});
	}
	
	$("#frmCreateSchedule").submit(function (e) {
		e.preventDefault();
		const tripId = $("#slTripId").val();
		const routeId = $("#slRouteId").val();
		const fromDate = $("#dtpFromDate").val();
		const toDate = $("#dtpToDate").val();
		const mode = $("#frmCreateSchedule").attr('data-mode');
		const action = $("#frmCreateSchedule").attr('data-action');
		const routeName = $("#slRouteId option:selected").text();
		const tripName = $("#slTripId option:selected").text();
		let isActive = 1;
		
		if (mode == 'multi' && !routeId) {
			Swal.fire(
			'',
			'Xin mời chọn tuyến xe!',
			'warning'
			);
			return;
		}

		if (mode == 'single' && !tripId) {
			Swal.fire(
			'',
			'Xin mời chọn chuyến xe!',
			'warning'
			);
			return;
		}
		if (!fromDate) {
			Swal.fire(
			'',
			'Xin mời chọn ngày bắt đầu!',
			'warning'
			);
			return;
		}
		if (!toDate) {
			Swal.fire(
			'',
			'Xin mời chọn ngày kết thúc!',
			'warning'
			);
			return;
		}
		if (isLater(fromDate, toDate)) {
			Swal.fire(
			'',
			'Ngày bắt đầu không thể lớn hơn ngày kết thúc!',
			'warning'
			);
			return;
		}

		let message = '';
		switch (action) {
			case 'create':
				message = `Xác nhận tạo lịch chạy cho 
				${mode === 'single' ? 'chuyến ' + tripName: 'tuyến ' + routeName} 
				từ ${fromDate} đến ${toDate}?`;
				break;
			case 'active':
				message = `Xác nhận kích hoạt lịch chạy cho 
				${mode === 'single' ? 'chuyến ' + tripName: 'tuyến'  + routeName} 
				từ ${fromDate} đến ${toDate}?`;
				break;
			case 'inactive':
				message = `Xác nhận ngưng kích hoạt lịch chạy cho 
				${mode === 'single' ? 'chuyến ' + tripName: 'tuyến ' + routeName} 
				từ ${fromDate} đến ${toDate}?`;
				break;
			default:
				break;
		}

		let url = '';
		switch (action) {
			case 'create':
				url = mode == 'single' ? '/admin/trip_dates/create_single_schedule' : '/admin/trip_dates/create_multi_schedule';
				break;
			case 'active':
				url = '/admin/trip_dates/change_status_schedule';
				break;
			case 'inactive':
				url = '/admin/trip_dates/change_status_schedule';
				isActive = 0;
				break;
		}

		Swal.fire({
			title: 'Xác nhận?',
			text: message,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'Xác nhận',
			cancelButtonText: 'Hủy'
		}).then((result) => {
			if (result.value) {
				$.ajax({
			url: url,
			type: 'post',
			data: {
				tripId,
				routeId,
				fromDate,
				toDate,
				isActive
			},
			success: function(response) {
				console.log(response);
				
				if (response.status === 201 || response.status === 200) {
					Swal.fire(
					'Thành công!',
					response.message,
					'success'
					).then((result) => {
						if (result.value) {
							window.location.reload();
						}
					});
				} else {
					Swal.fire(
					'Thất bại!',
					response.message,
					'error'
					);
				}
			}
		})
			}
		});

		
		
		
	});

	$("#frmSearchTripDD").submit(function (e) {
		let flag = true;
		const searchRouteId = $("#searchRouteId").val();
		const searchFromDate = $("#searchFromDate").val();
		const searchToDate = $("#searchToDate").val();
		const searchTripId = $("#searchTripId").val();
		let message = '';
		if (flag && !searchRouteId && !searchFromDate && !searchToDate && !searchTripId) {
			flag = false;
			message = 'Do lịch chạy xe rất lớn, bạn vui lòng chọn các điều kiện lọc';
		}
		
		if (flag && searchRouteId && !searchTripId && (!searchFromDate || !searchToDate)) {
			message = 'Vì số lượng chuyến xe theo tuyến rất lớn, bạn vui lòng chọn từ ngày - đến ngày hoặc chọn chuyến xe cụ thể';
			flag = false;
		}
		
		if (flag && !searchRouteId && !searchTripId && (!searchFromDate || !searchToDate)) {
			message = 'Vui lòng chọn đầy đủ từ ngày - đến ngày';
			
			flag = false;
		}

		if (flag && searchFromDate && searchToDate && isLater(searchFromDate, searchToDate)) {
			message = 'Ngày bắt đầu không thể lớn hơn ngày kết thúc';
			
			flag = false;
		}
		
		if (!flag) {
			Swal.fire(
				'',
				message,
				'warning'
				);
				e.preventDefault();
		}
	});
	$("#btnCreateSingleSchedule").click(function () {
		$("#divSelecTripId").show();
		$("#frmCreateSchedule").attr('data-mode', 'single');
		$("#frmCreateSchedule").attr('data-action', 'create');
	});
	$("#btnCreateMultiSchedule").click(function () {
		$("#divSelecTripId").hide();
		$("#frmCreateSchedule").attr('data-mode', 'multi');
		$("#frmCreateSchedule").attr('data-action', 'create');
	});

	$(".submit-frm-schedule").click(function () {
		const action = $(this).attr('data-action');
		$("#frmCreateSchedule").attr('data-action', action);
		$("#frmCreateSchedule").submit();
		
	});

	function isLater(firstDate, secondDate) {
		const arr1 = firstDate.split('-');
		const arr2 = secondDate.split('-');

		const number1 = arr1[2] * 10000 + arr1[1] * 100 + arr1[0];
		const number2 = arr2[2] * 10000 + arr2[1] * 100 + arr2[0];
		return number1 > number2;
	}
</script>
@endsection
