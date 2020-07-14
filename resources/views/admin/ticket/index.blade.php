@extends('admin.layout.app')
@section('content')
<div class="right_col" role="main">
	<div class="" style="margin-top: 50px;">
		@include('admin.layout.flash')
		<div class="page-title">
			<div class="title_left">
				<p>Home / Vé xe</p>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<form action="{{route('admin.ticket.search')}}" method="GET" id="frmSearchTicket">
				<div class="col-md-12" style="margin-bottom: 10px;">
					<div class="col-md-4 col-xs-12">
						<input type="text" class="form-control" placeholder="Mã vé" name="code" value="{{request('code')}}">
					</div>
					<div class="col-md-4 col-xs-12">
						<select class="form-control" name="route_id" id="searchRouteId">
							<option value="" selected>Tuyến xe</option>
							@foreach($routes as $route)
							<option value="{{$route->id}}" @if($route->id == request('route_id')) selected @endif>{{$route->name}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-4 col-xs-12">
						<select class="form-control" name="trip_id" id="searchTripId">
							@if(isset($tripsByRoute))
								<option value="">Tất cả</option>
								@foreach ($tripsByRoute as $trip)
								<option value="{{$trip->id}}" @if($trip->id == request('trip_id')) selected @endif>{{$trip->name}}</option>
								@endforeach
							@else
								<option value="">Vui lòng chọn tuyến xe</option>
							@endif
						</select>
					</div>
				</div>
				<div class="col-md-12">
					<div class="col-md-4 col-xs-12">
						@php $ticketStatus = request('status') ?? -1;@endphp
						<select class="form-control" name="status" id="">
							<option value="">Tất cả</option>
							@foreach ($listTicketStatus as $status)
							<option value="{{$status}}" @if($status == $ticketStatus) selected @endif>{{__($status)}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-4  col-xs-12">
						@php 
						$fromDate = request('from_date');
						@endphp
						<input type="text" name="from_date" 
						@if($fromDate) value="{{date('d-m-Y', strtotime($fromDate))}}" @endif
						id="searchFromDate"
						class="form-control datepicker"
						autocomplete="off"
						>
					</div>
					<div class="col-md-4  col-xs-12">
						@php
						$toDate = request('to_date');
						@endphp
						<input type="text" name="to_date" 
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
						<h2><i class="fa fa-bars"></i> Danh sách vé xe</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<table class="table table-bordered" id="datatable">
							<thead>
								<tr>
									<th class="text-center">STT</th>
									<th class="text-center">Mã vé</th>
									<th class="text-center">Tên hành khách</th>
									<th class="text-center">Số điện thoại</th>
									<th class="text-center">Số lượng</th>
									<th class="text-center">Tuyến</th>
									<th class="text-center">Thời gian</th>
									<th class="text-center">Ngày đi</th>
									<th class="text-center">Trạng thái</th>
									<th class="text-center">Gửi mail hủy vé</th>
									<th class="text-center">Hành động</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($tickets as $ticket)
								<tr>
									<td class="text-center">{{$loop->iteration}}</td>
									<td class="text-center">{{$ticket->code}}</td>
									<td class="text-center">{{$ticket->passenger_info['name']}}</td>
									<td class="text-center">{{$ticket->passenger_info['phone']}}</td>
									<td class="text-center">{{$ticket->quantity}}</td>
									<td class="text-center">{{$ticket->trip_info['route_name']}}</td>
									<td class="text-center">{{date('H:i', strtotime($ticket->trip_info['depart_time'])) . ' - ' . date('H:i', strtotime($ticket->trip_info['des_time']))}}</td>
									<td class="text-center">{{date('d-m-Y', strtotime($ticket->tripDepartDate->depart_date))}}</td>
									<td class="text-center {{$ticket->getStatusColor()}}">{{$ticket->status}}</td>
									<td class="text-center">{{$ticket->sendmail_cancel_status}}</td>
									{{-- <td class="text-center">{{$ticket->slug}}</td> --}}
									<td class="text-center">
										<a href="{{route('admin.ticket.show', ['code' => $ticket->code])}}" class="btn btn-warning" title="Cập nhật"><i class="fas fa-eye"></i></a>
										{{-- <a href="{{route('admin.ticket.destroy', ['ticket' => $ticket->id])}}" class="btn btn-danger btn-delete" data-page="ticket" title="Xóa"><i class="fa fa-trash"></i></a></td> --}}
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
	$("#searchRouteId").select2();
	$("#datatable").DataTable();
	$("#searchFromDate").datepicker({
		format: 'dd-mm-yyyy',
	});
	$("#searchToDate").datepicker({
		format: 'dd-mm-yyyy',
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
</script>
@endsection
