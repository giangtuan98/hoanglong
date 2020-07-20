@if($ticketData)
<div class="step4" style="display: none;">
    <div class="panel panel-default pnlconfirminfo packagesFilter">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Confirm ticket information')}}</h3>
        </div>

        <div class="panel-body confirminfo row-fluid">
            <table class="table table-bordered">
                <tbody><tr>
                    <td>
                        {{ __('Ticket code') }}
                    </td>
                    <td>
                        <span class="bold">{{$ticketData['ticketCode']}}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('Fullname') }}
                    </td>
                    <td>
                        {{$ticketData['passengerName']}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('Phone') }}
                    </td>
                    <td>
                        {{$ticketData['passengerPhone']}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('Email')}}
                    </td>
                    <td>
                        {{$ticketData['passengerEmail']}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('Address')}}
                    </td>
                    <td>
                        {{$ticketData['passengerAddress']}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('Departure')}}
                    </td>
                    <td>
                        {{$ticketData['depProvinceName'] .' - ( '. $ticketData['departName'] . ' )'}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('Destination')}}
                    </td>
                    <td>
                        {{$ticketData['desProvinceName'] .' - ( '. $ticketData['desName'] . ' )'}}
                    </td>
                </tr>
                <tr>
                    <td>
                      {{ __('Brand')}}
                    </td>
                    <td>
                      <a href="{{route('brand', ['locale' => app()->getLocale(),'id' => $ticketData['brandId']])}}" target="_blank">{{$ticketData['brandName']}}</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('Route')}}
                    </td>
                    <td>
                        {{$ticketData['routeName']}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>{{ __('Expected customers will board the car in')}} <a href="{{$ticketData['pickupPlaceUrl']}}" target="_blank">{{$ticketData['pickupPlaceName']}}</a> {{__('at')}}</b>
                    </td>
                    <td>
                        <span class="bold">{{$ticketData['pickupTime'] . ' Ngày ' . $ticketData['date']}}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('Quantity') }}
                    </td>
                    <td>
                        <span class="bold">{{$ticketData['quantity']}}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('Seat booked') }}
                    </td>
                    <td>
                        <span class="bold">{{$ticketData['selectedSeats']}}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('Ticket price') }}
                    </td>
                    <td>
                        <span class="bold">{{number_format($ticketData['price'])}} VND</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('Total price') }}
                    </td>
                    <td>
                        <span class="bold">{{number_format($ticketData['quantity']*$ticketData['price'])}} VND</span>
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
            </tbody></table>
        </div>
    </div>
    <div class="panel panel-default pnlconfirmseat packagesFilter margin-top-10">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Booking confirmation')}}</h3>
        </div>
        <div class="panel-body confirminfo row-fluid">
            <div class="row-fluid" style="padding:10px;">
                <h3>{{ __('You have successfully booked the ticket') }}</h3>
                <p>
                    {{ __('Thank you for using the online booking service of Giang Tuan Transport Company.') }}
                </p>
                <span style="font-style:italic">
                    {{ __('We have sent you an email to your email address')}} {{$ticketData['passengerEmail'] }}<br>
                    {{ __('Please check your email for the ticket code, payment instructions and ticket receipt') }}
                </span>
            </div>
        </div>
    </div>
</div>
@else
<div>
    <div class="panel panel-default pnlconfirmseat packagesFilter margin-top-10">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('This ticket has been sold out')}}</h3>
        </div>
        <div class="panel-body confirminfo row-fluid">
            <div class="row-fluid" style="padding:10px;">
                <h3>{{__("This trip does not have enough number of tickets required, please choose another trip")}}!</h3>
            </div>
        </div>
    </div>
</div>
@endif