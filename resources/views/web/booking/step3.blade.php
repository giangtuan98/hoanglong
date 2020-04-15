<div class="step3" style="display: none;">
    <a href="javascript:;" class="btn buttonTransparent btnGoToStep" data-step="2">Trở lại bước 2</a>
    <div class="panel panel-default pnlconfirminfo packagesFilter margin-top-10">
        <div class="panel-heading">
            <h3 class="panel-title">Xác nhận thông tin đặt vé</h3>
        </div>
        <div class="panel-body confirminfo ">
            <input type="hidden" id="hddep" value="10">
            <input type="hidden" id="hdshift" name="shift" value="134">
            <table class="table">
                <tbody>
                    <tr>
                        <td>
                            {{ __('Fullname')}}
                        </td>
                        <td id="passengerNameTbl"></td>
                    </tr>
                    <tr>
                        <td>
                            {{ __('Phone')}}
                        </td>
                        <td id="passengerPhoneTbl"></td>
                    </tr>
                    <tr>
                        <td>
                            {{ __('Email')}}
                        </td>
                        <td id="passengerEmailTbl"></td>
                    </tr>
                    <tr>
                        <td>
                            {{ __('Address')}}
                        </td>
                        <td id="passengerAddressTbl"></td>
                    </tr>
                    <tr>
                        <td>
                            {{ __('Departure')}} - {{ __('Destination')}}
                        </td>
                        <td id="departToDesTbl"></td>
                    </tr>
                    <tr>
                        <td>
                            {{ __('Rotue')}}
                        </td>
                        <td id="routeNameTbl">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{__('Expected customers will board the car in :place at :time')}}
                        </td>
                        <td id="dateTbl">
                            <span class="bold"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{__('Quantity')}}
                        </td>
                        <td>
                            <span class="bold" id="quantityTbl"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{__('Ticket prices')}}
                        </td>
                        <td >
                            <span class="bold" id="priceTbl"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{__('Total price')}}
                        </td>
                        <td >
                            <span class="bold" id="totalTbl"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{('Payment method')}}
                        </td>
                        <td id="paymenttypeTbl"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-default pnlconfirmseat packagesFilter margin-top-10">
        <div class="panel-heading">
            <h3 class="panel-title"> {{__('Booking confirmation')}} </h3>
            <div class="row">
                
                <div class="col-sm-2 col-xs-12">  {{ __('Verification code')}} </div>
                <div class="col-sm-4 col-xs-12 form-group">
                    <input type="hidden" name="CapImageText" id="CapImageText" value="383802">
                    <div id="captcha">
                    </div>
                    <input type="text" class="form-control" autocomplete="off"  placeholder="" id="CaptchaCodeText" name="CaptchaCodeText" required="required">
                    <span class="notecaptcha" id="notecaptcha" style="display: none;">({{ __('Incorrect code')}})</span>
                    <span class="note">({{ __('Please enter the verification code')}})</span>
                </div>
            </div>
        </div>
        <div class="panel-body confirminfo">
            <form action="{{route('booking-route', ['locale' => app()->getLocale()])}}" method="POST" class="form" id="form_step3">
                {{-- <input type="hidden" name="_token" value="{{csrf_token()}}"> --}}
                <input type="hidden" name="passengerName" id="passengerNameHd">
                <input type="hidden" name="passengerPhone" id="passengerPhoneHd" >
                <input type="hidden" name="passengerEmail" id="passengerEmailHd" >
                <input type="hidden" name="passengerAddress" id="passengerAddressHd" >
                <input type="hidden" name="price" id="priceHd">
                <input type="hidden" name="quantity" id="quantityHd">
                <input type="hidden" name="date" id="dateHd">
                <input type="hidden" name="paymenttype" id="paymenttypeHd">
                <input type="hidden" name="tddId" id="tddIdHd">
                <input type="hidden" name="brandId" id="brandIdHd">
                <input type="hidden" name="departName" id="departNameHd">
                <input type="hidden" name="departTime" id="departTimeHd">
                <input type="hidden" name="desName" id="desNameHd">
                <input type="hidden" name="desTime" id="desTimeHd">
                <input type="hidden" name="routeName" id="routeNameHd">
                <div class="listseat ">
                    <div class="row">
                        <div class="col-sm-2 col-xs-12"></div>
                        <div class="col-sm-9 col-xs-12 form-group">
                            <input type="button" value="{{__('Book ticket')}}" class="btn buttonCustomPrimary btnConfirmTicket" data-culture="vi">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr>
</div>