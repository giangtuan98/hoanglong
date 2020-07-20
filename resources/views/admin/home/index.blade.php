@extends('admin.layout.app')
@section('content')
<div class="right_col" role="main">
	<div class="" style="margin-top: 50px;">
    @include('admin.layout.flash')
		{{-- <div class="page-title">
			<div class="title_left">
				<h3>Users <small>Some examples to get you started</small></h3>
			</div>
			
			<div class="title_right">
				<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Search for...">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button">Go!</button>
						</span>
					</div>
				</div>
			</div>
		</div>
		
		<div class="clearfix"></div>
		
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Default Example <small>Users</small></h2>
						<ul class="nav navbar-right panel_toolbox">
							<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="#">Settings 1</a>
									</li>
									<li><a href="#">Settings 2</a>
									</li>
								</ul>
							</li>
							<li><a class="close-link"><i class="fa fa-close"></i></a>
							</li>
						</ul>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<p class="text-muted font-13 m-b-30">
							DataTables has most features enabled by default, so all you need to do to use it with your own tables is to call the construction function: <code>$().DataTable();</code>
						</p>
						<table id="datatable" class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Name</th>
									<th>Position</th>
									<th>Office</th>
									<th>Age</th>
									<th>Start date</th>
									<th>Salary</th>
								</tr>
							</thead>
							
							
							<tbody>
								<tr>
									<td>Tiger Nixon</td>
									<td>System Architect</td>
									<td>Edinburgh</td>
									<td>61</td>
									<td>2011/04/25</td>
									<td>$320,800</td>
								</tr>
								<tr>
									<td>Garrett Winters</td>
									<td>Accountant</td>
									<td>Tokyo</td>
									<td>63</td>
									<td>2011/07/25</td>
									<td>$170,750</td>
								</tr>
								<tr>
									<td>Ashton Cox</td>
									<td>Junior Technical Author</td>
									<td>San Francisco</td>
									<td>66</td>
									<td>2009/01/12</td>
									<td>$86,000</td>
								</tr>
								<tr>
									<td>Cedric Kelly</td>
									<td>Senior Javascript Developer</td>
									<td>Edinburgh</td>
									<td>22</td>
									<td>2012/03/29</td>
									<td>$433,060</td>
								</tr>
								<tr>
									<td>Airi Satou</td>
									<td>Accountant</td>
									<td>Tokyo</td>
									<td>33</td>
									<td>2008/11/28</td>
									<td>$162,700</td>
								</tr>
								<tr>
									<td>Brielle Williamson</td>
									<td>Integration Specialist</td>
									<td>New York</td>
									<td>61</td>
									<td>2012/12/02</td>
									<td>$372,000</td>
								</tr>
								<tr>
									<td>Donna Snider</td>
									<td>Customer Support</td>
									<td>New York</td>
									<td>27</td>
									<td>2011/01/25</td>
									<td>$112,000</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			
			
		</div> --}}
		<div class="row top_tiles">
			<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="tile-stats">
					<a href="{{route('admin.ticket.search', ['from_date' => date('Y-m-d'), 'to_date' => date('Y-m-d'), 'status' => 'booked'])}}">
					<div class="icon"><i class="fas fa-ticket-alt"></i></div>
					<div class="count">{{count($todayTicket)}}</div>
					<h3>Vé xe</h3>
					<p>Số lượng vé đặt trong ngày hôm nay.</p>
				</a>
				</div>
			</div>
			<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a href="{{route('admin.report.search', ['from_date' => date('Y-m-d'), 'to_date' => date('Y-m-d')])}}">
				<div class="tile-stats">
					<div class="icon"><i class="fas fa-coins"></i></div>
					<div class="count">{{number_format($todayTicket->where('status', __('paid'))->sum('total'))}}</div>
					<h3>Doanh thu</h3>
					<p>Doanh thu theo ngày.</p>
				</div>
				</a>
			</div>
			<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a href="{{route('admin.ticket.search', ['from_date' => date('Y-m-01'), 'to_date' => date('Y-m-t'), 'status' => 'booked'])}}">
				<div class="tile-stats">
					<div class="icon"><i class="fa fa-sort-amount-desc"></i></div>
					<div class="count">{{count($monthTicket)}}</div>
					<h3>Vé xe</h3>
					<p>Số lượng vé đặt trong tháng.</p>
				</div>
			</a>
			</div>
			<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a href="{{route('admin.report.search', ['from_date' => date('Y-m-01'), 'to_date' => date('Y-m-t'), 'status' => 'booked'])}}">
				<div class="tile-stats">
					<div class="icon"><i class="fas fa-coins"></i></div>
					<div class="count">{{number_format($monthTicket->where('status', __('paid'))->sum('total'))}}</div>
					<h3>Doanh thu</h3>
					<p>Doanh thu theo tháng.</p>
				</div>
				</a>
			</div>
		</div>
	</div>
</div>
@endsection
@section('after-scripts')
<script>
	$("#datatable").DataTable();
</script>
@endsection
