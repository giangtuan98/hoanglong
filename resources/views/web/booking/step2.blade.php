<div class="step2" style="display: none;">
    <div class="row">
        <div class="col-sm-12">
            <form action="#" class="form" id="frBookingStep2" method="post"> 
                <a href="javascript:void(0)" class="btn buttonTransparent btnGoToStep" data-step="1">{{__('Back to step :step', ['step' => 1])}}</a>
                <div class="panel panel-default margin-top-10 pnlcustomerinfo packagesFilter">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{__('Customer information')}}</h3>
                    </div>
                    <div class="panel-body customerinfo">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 col-xs-12 control-label">
                                    {{ __('Fullname') }} (*)
                                </label>
                                <div class="col-sm-5 col-xs-12">
                                    <input type="text" class="form-control input-sm text" name="passengerName" id="passengerName" required="required" 
                                    @if(isset($cus_info)) 
                                        value="{{$cus_info->name}}" 
                                    @else 
                                        @if($user != null)
                                            value="{{$user->name}}"
                                        @endif
                                    @endif
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 col-xs-12 control-label">{{ __('Phone') }} (*)</label>
                                <div class="col-sm-5 col-xs-12">
                                    <input type="text" class="form-control input-sm text " name="passengerPhone" id="passengerPhone" required="required"  @if(isset($cus_info)) 
                                    value="{{$cus_info->phone}}" 
                                @endif>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 col-xs-12 control-label">Email (*)</label>
                                <div class="col-sm-5 col-xs-12">
                                    <input type="email" class="form-control input-sm text " name="passengerEmail" id="passengerEmail" required="required" 
                                    @if(isset($cus_info)) 
                                        value="{{$cus_info->email}}" 
                                    @else 
                                        @if($user != null)
                                            value="{{$user->email}}"
                                        @endif
                                    @endif
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 col-xs-12 control-label">{{ __('Address') }}</label>
                                <div class="col-sm-5 col-xs-12">
                                    <input type="text" class="form-control input-sm text " name="passengerAddress" id="passengerAddress" <?php if(isset($cus_info)) echo 'value = "' . $cus_info->address . '"'?>>
                                </div>
                            </div>
                        </div>                   
                    </div>
                </div>
                <div class="panel panel-default pnltransaction margin-top-10 packagesFilter">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{__('Payments method')}}</h3>
                    </div>
                    <div class="panel-body transaction">



                        <div class="radio">
                            <label>
                                <input type="radio" name="paymenttype" {{-- id="paymenttype" --}} value="1">{{__('Direct payment')}}
                            </label>
                        </div>
                        
                    </div>
                </div>
                <div class="panel panel-default pnlrules margin-top-10 packagesFilter">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ __('TRANSPORT RULES') }}</h3>
                    </div>
                    <div class="panel-body rules">
                        <ol class="padding-left-20">
                            <li style="padding-bottom: 5px;">{{ __('For the benefit of customers and the company, customers should bring their ID card when using the service') }}</li>
                        <li style="padding-bottom: 5px;">{{__('Customers should be at the pick-up point of Hoang Long by the guidance of ticket officer before the coach leaving at least 30 minutes for the procedures.')}}</li>
                        <li style="padding-bottom: 5px;">{{__('Customer should not smoke on the coach.')}}</li>
                        <li style="padding-bottom: 5px;">{{__('Customers should not carrying the dangerous goods, precious and rare animals, banned goods as weapons, explosives, inflammables, toxic smell on the coach.')}}</li>
                        <li style="padding-bottom: 5px;">{{__('Customers should not carrying the goods which banned from the specified list of the Socialist Republic of Vietnam.')}}</li>
                        <li style="padding-bottom: 5px;">{{__('The valid ticket only using by date, time and trip which printed on the ticket.')}}</li>
                        <li style="padding-bottom: 5px;">{{__('The customers have the rights to cancel, change the trip information BASED ON DEPARTURE TIME: before 12 hours the cancellation / no charge, from 03 to 12 hours are subject to cancellation / change and service charge 10%, within 03 hours are subject to cancellation / change and 30% service charge. Tickets will be refunded within 15-20 days of cancellation. Holiday - New Year has separate regulations.')}}</li>
                        <li style="padding-bottom: 5px;">{{__('Customer should not cancel or change information when lost the tickets, the customers can also get on the coach if still remember the ticket code and bring the ID card.')}}</li>
                        <li style="padding-bottom: 5px;">{{__('If for unforeseen conditions (natural disasters, traffic jams, etc.) the Company have the rights to canceled or changed the schedule and departure time.The company should have notice in advance without any responsibility.')}}</li>
                        <li style="padding-bottom: 5px;">{{__('Each passenger may carry 30kg of luggage. If overload of rule then pay 5000vnd/kg for addition fees. The bulky equipments, TV, computers, motorcycles ... in the specified list of the Company, not be counted as luggage.')}}</li>
                        <li style="padding-bottom: 5px;">{{__('In case, the customers think that staff of the company have lack of impolite behaviors or false of regulations, we are always receiving the customers opinion by phone, email, postal mail as the contact information below:')}}</li>
                        </ol>
                        <p></p><h3><u>{{ __('CONTACT INFORMATION')}}</u></h3><p></p>
                        <ol class="padding-left-20">
                            <li style="padding-bottom: 5px;">{{__('Hotline')}}  <strong>0398669765</strong> - 0398669765 - 0398669765</li>
                            <li style="padding-bottom: 5px;">Email:giangtuan6199@gmail.com</li>
                            <li style="padding-bottom: 5px;">{{__('Operation Center Department, Giang Tuan Transport Co.,Ltd, No 5, Pham Ngu Lao, Ngo Quyen Distric, Hai Phong, VietNam.')}}</li>
                        </ol>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="privacy" name="privacy" value="1" disabled="disabled">{{__('I agreed with the terms and policies for using service')}}<br>
                            </label>
                        </div>
                        <input type="submit" class="btn buttonCustomPrimary" disabled="disabled" id="confirm-button" data-culture="{{app()->getLocale()}}" value="Xác nhận đặt vé">
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
