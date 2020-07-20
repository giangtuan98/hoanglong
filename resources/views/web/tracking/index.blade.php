@extends('web.layout.master')
@section('class-header')
lightHeader
@endsection
@section('content')
<section class="pageTitle" style="background-image:url('../web_template/Content/themes/startravel/img/pages/page-title-bang-gia.jpg');">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <div class="titleTable">
          <div class="titleTableInner">
            <div class="pageTitleInfo">
              <h1> {{ __('Tracking') }}</h1>
              <div class="under-border"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div style="position: fixed; top: 0; width:100%; z-index: 1111; height: 100%; background-color: rgba(0,0,0,0.5); display:none;" id="pageLoading">
  <div style="position: absolute; top: 45%; left: 40%;" class="text-center">
      <i class="fa fa-spinner fa-spin" style="font-size:70px; color: wheat;"></i>
      <p style="font-size:20px; color: wheat;"> {{ __('Data is in processing') }} ...</p>
  </div>
</div>
<section class="mainContentSection packagesSection boxbooking">
  <div class="container">
    <div class="row tabsPart">
      <div class="col-sm-12">
        <div class="positiontop"></div>
        <div class="row">
          <div class="col-sm-12 col-xs-12">
            <div class="darkSection citiesPage">
              <div class="row gridResize">
                <div class="col-sm-3 col-xs-12">
                  <div class="sectionTitleDouble">
                    @if (app()->getLocale())
                    <p>Mã</p>
                    <h2><span>Vé</span></h2>
                    @else
                    <p>Ticket</p>
                    <h2><span>Code</span></h2>
                    @endif
                  </div>
                </div>
                <div class="col-sm-9 col-xs-12">
                  <div class="row">
                    <form action="{{route('tracking', ['locale' => app()->getLocale()])}}" method="get" id="form">
                      <div class="col-sm-9 col-xs-12">
                        <div class="row">
                          <div class="col-md-12">
                            <input type="text" style="
                            width: 100%;
                            height: 45px;
                            font-size: 18px;
                            padding: 5px 20px;
                            margin-top: 3px;
                            background: whitesmoke;
                            border: none;
                            border-radius: 4px;"
                            name="ticket_code"
                            value="{{request('ticket_code')}}"
                            >
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-3 col-xs-12">
                        <div class="row">
                          <div class="col-sm-12 col-xs-12">
                            <button type="submit" class="btn buttonCustomPrimary" style="width: 100%" type="submit" >{{__('Tracking')}}</button>
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
        
        @if (isset($ticket))
        <div class="panel panel-default pnlconfirminfo packagesFilter col-md-12">
          <div class="panel-heading" style="background: ">
            <h3 class="panel-title">{{__('Ticket information')}}</h3>
          </div>
          
          <div class="panel-body confirminfo row-fluid">
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <td>
                    <span class="bold">{{ __('Ticket code') }}</span>
                  </td>
                  <td>
                    <span class="bold" id="ticketCode">{{$ticket->code}}</span>
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ __('Fullname') }}
                  </td>
                  <td>
                    {{$ticket->passenger_info['name']}}
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ __('Phone') }}
                  </td>
                  <td>
                    {{$ticket->passenger_info['phone']}}
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ __('Email')}}
                  </td>
                  <td>
                    {{$ticket->passenger_info['email']}}
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ __('Address')}}
                  </td>
                  <td>
                    {{$ticket->passenger_info['address']}}
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ __('Brand')}}
                  </td>
                  <td>
                    <a href="{{route('brand', ['locale' => app()->getLocale(),'id' => $ticket->brand->id])}}" target="_blank">{{$ticket->brand->name}}</a>
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ __('Departure')}}
                  </td>
                  <td>
                    {{$ticket->trip_info['dep_province_name'] .' - ( '. $ticket->trip_info['depart_name'] . ' )'}}
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ __('Destination')}}
                  </td>
                  <td>
                    {{$ticket->trip_info['des_province_name'] .' - ( '. $ticket->trip_info['des_name'] . ' )'}}
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ __('Route')}}
                  </td>
                  <td>
                    {{$ticket->trip_info['trip_name']}}
                  </td>
                </tr>
                <tr>
                  <td>
                    <b>{{ __('Expected customers will board the car in')}} <a href="{{$ticket->trip_info['pickup_url']}}" target="_blank">{{$ticket->trip_info['pickup_place']}}</a> {{__('at')}}</b>
                  </td>
                  <td>
                    <span class="bold">{{$ticket->trip_info['pickup_time'] . ' - ' . date('d/m/Y', strtotime($ticket->tripDepartDate->depart_date))}}</span>
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ __('Quantity') }}
                  </td>
                  <td>
                    <span class="bold">{{$ticket['quantity']}}</span>
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ __('Seat booked') }}
                  </td>
                  <td>
                    <span class="bold">{{$ticket->getListSeatString()}}</span>
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ __('Ticket price') }}
                  </td>
                  <td>
                    <span class="bold">{{number_format($ticket->trip_info['unit_price'])}} VND</span>
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ __('Total price') }}
                  </td>
                  <td>
                    <span class="bold">{{number_format($ticket['quantity']*$ticket->trip_info['unit_price'])}} VND</span>
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ __('Payment method')}}
                  </td>
                  <td>
                    {{ __('Direct payment')}}
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ __('Status')}}
                  </td>
                  <td class="bold text-{{$ticket->getStatusColor()}}">
                    {{$ticket->status}}
                  </td>
                </tr>
              </tbody>
            </table>
            @if (($ticket->getOriginal('status') == 'unpaid' || $ticket->getOriginal('status') == 'paid') && $checkCanCancel)
            <div class="row" id="divCancelBooking">
              <div class="col-md-offset-2 col-md-4 col-sm-12">
                <button class="btn buttonCustomPrimary" id="btnCancelTicket" data-href="{{route('send-cancel-ticket-mail', ['locale' => app()->getLocale()])}}" data-locale="{{app()->getLocale()}}">{{__('Cancel booking')}}</button>
              </div>
            </div>
            <div class="row" style="display:none;" id="divCodeInput">
                <div class="col-md-offset-2 col-md-4 form-group">
                  <span><i>{{__('Check your email to get verification code')}}</i></span>
                  <input type="text" class="form-control" autocomplete="off" placeholder="" id="cancelCode" name="cancelCode" required="required" style="margin-bottom: 10px;">
                  <p id="msgStatusCode" style="color: #ee4735 !important" data-locale="{{app()->getLocale()}}">{{__('Your code will expire in :seconds second', ['seconds' => 90])}}</p>
                  <button class="btn buttonCustomPrimary" id="submit" data-href="{{route('cancel-ticket', ['locale' => app()->getLocale()])}}">{{__('Submit')}}</button>
                  <button class="btn buttonCustomPrimary" id="resend" style="display: none;">{{__('Resend')}}</button>
                  <button class="btn " style="background-color: #21A1DB!important; color: white; padding: 0 16px; line-height: 45px;" id="cancel">{{__('Cancel')}}</button>
                </div>
            </div>
            @endif
          </div>
        </div>
        @else
        @if (request('ticket_code'))
        <p class="text-center" style="text-align: center">{{__('There is no result for the key')}} "{{request("ticket_code")}}"</p>
        @endif
        @endif
      </div>
      
    </div>
  </div>
</div>
</div>
</div>
</section>
@endsection
@section('after-scripts')
<script>
  let locale = 'vi';
  let cancelTicketCode = '';
  $('#btnCancelTicket').click(async function () {
    $("#pageLoading").show();
    const url = $(this).attr('data-href');
    const ticketCode = $("#ticketCode").text();
    locale = $(this).attr('data-locale');
    await swal({
      title: "Warning",
      text: "Bạn có chắc chắn muốn hủy vé này không",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: '#ee4735',
      confirmButtonText: 'Xác nhận!',
    },
    function(isConfirm){
      if (isConfirm){
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: 'POST',
          url: url,
          data: {ticketCode},
          success(response) {
            
            if (response.status == 200) {
              cancelTicketCode = response.data.cancelTicketCode;
              console.log(cancelTicketCode);
              setTimeout(() => {
                $("#pageLoading").hide();
                $("#divCodeInput").slideDown();
                $("#divCancelBooking").slideUp();
                countDown(90);
              }, 1000);  
              
            }
          },
          error(err) {
            console.log(err);
          }
        });
      } else {
        $("#pageLoading").hide();
      }
    });
    
  });

  $("#cancel").click(function () {
    $("#divCodeInput").slideUp();
    $("#divCancelBooking").slideDown();
    let msg = locale == 'en' ? "Your code will expire in 90 second" : "Mã xác nhận sẽ hết hạn sau 90 giây";
    $("#msgStatusCode").text(msg);
    clearInterval(count);
  });

  let count;
  function countDown(seconds) {
    timer = seconds;
    clearInterval(count);
    count = setInterval(() => {
      if (timer-- > 0) {
        let msg = locale == 'en' ? "Your code will expire in " + (parseInt(timer)) + " second" : "Mã xác nhận sẽ hết hạn sau " + (parseInt(timer)) + " giây";
        $("#msgStatusCode").text(msg);
      } else {
        let msg = locale == 'en' ? "Your code has expired" : "Mã xác nhận đã hết hạn";
        $("#msgStatusCode").text(msg);
        $("#resend").show();
        $("#submit").hide();
      }
    }, 1000);
  }

  $("#resend").click(function () {
    const url = $("#btnCancelTicket").attr('data-href');
    const ticketCode = $("#ticketCode").text();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: 'POST',
      url: url,
      data: {ticketCode},
      success(response) {        
        if (response.status == 200) {
          countDown(90);
          $("#resend").hide();
          $("#submit").show();
        }
      },
      error(err) {
        console.log(err);
      }
    });
  });

  $("#submit").click(function () {
    const url = $(this).attr('data-href');
    const ticketCode = $("#ticketCode").text();
    const cancelCode = $("#cancelCode").val();

    if (count) {
      clearInterval(count);
      $("#msgStatusCode").text('');
      count = null;
    }

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    swal({
      title: "Warning",
      text: "Bạn có chắc chắn muốn hủy vé này không",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: '#ee4735',
      confirmButtonText: 'Xác nhận!',
    },
    function(isConfirm){
      if (isConfirm) {
        $.ajax({
          type: 'POST',
          url: url,
          data: {ticketCode, cancelCode: cancelCode},
          success(response) {
            console.log(response);
            if (response.status == 200) {
              swal({
                title: "Success",
                text: response.message,
                type: "success",
              },
              function(isConfirm){
                if (isConfirm){
                  window.location.reload();
                }
              });
            } else {
              Alert.Error(response.message);
            }
          },
          error(err) {
            console.log(err);
            
          }
        });
      } else {
        
      }
    });

  });
</script>
@endsection