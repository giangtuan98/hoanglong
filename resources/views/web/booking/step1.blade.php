<?php 
$departureId = Request::get('departure_id') ?? -1;
$quantity = Request::get('quantity') ?? 0;
$destinationId = Request::get('destination_id') ?? -1;
$date = Request::get('depart_date') ?? $nextdate;
$user = auth('web')->user();
$depProvince = $provinces->where('id', $departureId)->first();
$desProvince = $provinces->where('id', $destinationId)->first();
$depProvinceName = $depProvince != null ? $depProvince->name : '';
$desProvinceName = $desProvince != null ? $desProvince->name : '';
?>
<div class="step1 bookinghcm ">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="darkSection citiesPage">
                <div class="row gridResize">
                    <div class="col-sm-3 col-xs-12">
                        <div class="sectionTitleDouble">
                            @if ($locale == 'vi')
                            <p style="margin-bottom: 8px;">Chọn</p>
                            <h2 style="margin-bottom: 8px;">điểm đi</h2>
                            <h2><span>Điểm đến</span></h2>
                            @else
                            <p style="margin-bottom: 8px;">Choose</p>
                            <h2 style="margin-bottom: 8px;">departure</h2>
                            <h2><span>destination</span></h2>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <div class="row">
                            <form action="{{route('booking', ['locale' => $locale])}}" method="get" id="form">
                                <div class="col-sm-9 col-xs-12">
                                    <div class="row">
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="searchTour">
                                                <select class="select2bootstrap" id="departure" name="departure_id"><option value="" selected>{{__('Departure')}}</option>
                                                    @foreach($provinces as $province)
                                                    <option value="{{$province->id}}" @if($departureId == $province->id) selected @endif>{{$province->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="searchTour">
                                                <select class="select2bootstrap" id="destination" name="destination_id"><option value="">{{__('Destination')}}</option>
                                                    @foreach($provinces as $province)
                                                    <option value="{{$province->id}}" @if($destinationId == $province->id) selected @endif>{{$province->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="input-group date ed-datepicker">
                                                <input type="text" class="form-control jqueryuidatepicker" data-mindate="+0D" data-maxdate="+100D" data-format="dd-mm-yy" id="datebook" name="depart_date" readonly="readonly" value="{{$date}}">
                                                <div class="input-group-addon">
                                                    <span class="fa fa-calendar"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="searchTour">
                                                <select name="quantity" class="select2bootstrap" id="quantity">
                                                    @for ($i = 1; $i < 6; $i++)
                                                    <option value="{{$i}}" @if($quantity == $i) selected @endif>{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="searchTour">
                                                <select name="brand_id" class="select2bootstrap" id="brand">
                                                    <option value="">{{__('Brand')}}</option>
                                                    @foreach($brands as $brand)
                                                    <option value="{{$brand->id}}" @if($brand->id == request('brand_id')) selected @endif>{{$brand->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-xs-12">
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            <input type="hidden" id="hddate" />
                                            <input type="hidden" id="hdculture" value="{{app()->getLocale()}}" />
                                            <input type="hidden" name="step" value="step1">
                                            <input type="button" value="{{__('Search route')}}" class="btn buttonCustomPrimary btnSearchShift" style="width: 100%" data-blank="1" />
                                        </div>
                                        
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if( $step == config('constants.step.STEP1'))
    <div class="row">
        <div class="col-sm-12  loadshifthcm">
            <div class="panel panel-default packagesFilter">
                <div class="panel-heading">
                    <b>{{__('SEARCH RESULTS')}}</b>
                </div>
                <div class="note" style="padding:10px" id="note">
                    ({{ __('Departure') }} <a target="_blank" href="javascript::void(0);">{{ $departProvince->name }}</a> {{__('to')}} <a target="_blank" href="">{{ $desProvince->name }}</a> {{__('Date')}} {{date('d-m-Y', strtotime($date)) }})
                </div>
                <div class="panel-body resultbooking">
                    <div class="listshift">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="hidden-xs hidden-sm">{{__('Brand')}}</th>
                                    <th class="hidden-xs hidden-sm">{{__('Code trips')}}</th>
                                    <th class="hidden-xs hidden-sm">{{__('Departure')}}</th>
                                    <th>{{__('Start time')}}</th>
                                    <th class="hidden-xs hidden-sm">{{__('Destination')}}</th>
                                    <th class="hidden-xs hidden-sm">{{__('Arrival')}}</th>
                                    <th class="hidden-xs hidden-sm">{{__('Time')}}</th>
                                    <th>{{__('Seat')}}</th>
                                    <th class="hidden-xs hidden-sm">{{__('Seat type')}}</th>
                                    <th>{{__('Ticket price')}}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($routes as $route)
                                <tr>
                                    <td class="hidden-xs hidden-sm"><a href="{{route('brand', ['locale' => app()->getLocale(), 'id' => $route->brandId])}}" target="_blank">{{$route->brandName}}</a></td>
                                    <td class="hidden-xs hidden-sm">{{$route->tripName}}</td>
                                    <td class="hidden-xs hidden-sm"><b>{{$route->departName}}</b></td>
                                    <td>{{$route->departTime}}</td>
                                    <td class="hidden-xs hidden-sm"><b>{{$route->desName}}</b></td>
                                    <td class="hidden-xs hidden-sm">{{$route->arriveTime}}</td>
                                    
                                    <td class="hidden-xs hidden-sm">{{decodeDurationJson($route->duration)}}</td>
                                    <td><b>{{$route->availableSeats}}</b></td>
                                    <td class="hidden-xs hidden-sm">{{$route->carType}}</td>
                                    <td>{{number_format($route->price)}}</td>
                                    <td>
                                        <a class="btn buttonTransparent btnSetVehicleId" data-brand-id="{{$route->brandId}}" data-brand-name="{{$route->brandName}}" data-tdd-id="{{$route->tripDepartDateId}}" data-depart-id="{{$route->departId}}" data-depart-name="{{$route->departName}}" data-des-id="{{$route->desId}}" data-des-name="{{$route->desName}}" data-depart-time="{{$route->departTime}}" data-date="{{$date}}" data-price="{{$route->price}}" data-datecheck="{{$date}}" data-quantity="{{$quantity}}"  data-route-name="{{$route->routeName}}" data-des-time="{{$route->arriveTime}}" data-dep-province-name="{{$depProvinceName}}" data-des-province-name="{{$desProvinceName}}" data-trip-id="{{$route->tripId}}" data-trip-name="{{$route->tripName}}">{{ __('book') }}</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- @if($locale == config('app.support_language.vietnam')) --}}
                    <div class="bottombooking">
                        <div>
                            <u>{{ __('Note') }}:</u><br>
                            + <b>{{ __('Departure') }}:</b> {{ __('is your departure point') }}.<br>
                            + <b>{{__('Start time')}}:</b> {{ __('is the scheduled departure time of the day') }}.<br>
                            + <b>{{ __('Destination') }}:</b> {{ __('is your destination point') }}.<br>
                            + <b>{{__('Arrival')}}:</b> {{ __('is the scheduled time you will arrive at the destination') }}.<br>
                            + <b>{{__('Time')}}:</b> {{ __('is the total estimated travel time of the vehicle') }}.<br>
                            + <b>{{__('Seat')}}:</b> {{ __('is the number of available seats on the bus') }}.<br>
                            + <b>{{__('Ticket price')}}:</b> {{ __('the amount you pay to ride on the chosen itinerary') }}.<br>
                        </div>
                        <br>
                    </div>
                    {{-- @endif --}}
                </div>
            </div><!--end panel-default-->
        </div>
    </div>
    @endif
</div>
