<div id=":1kz" class="a3s aXjCH ">{{__('Hello')}} {{$data['passengerName']}}<br>
    @if (app()->getLocale() == 'vi')
    <br>
    Cong ty TNHH Van tai Giang Tuan cam on ban da su dung dich vu dat ve truc tuyen qua mang Internet tu website <a href="http://hoanglongasia.com" target="_blank">http://hoanglongasia.com</a><br>
    <br>
    Day la thong tin ve dat truoc cua ban:<br>
    @else
    <br>
    Thank you for use our booking ticket online service<br>
    <br>
    This is your ticket information:<br>
    @endif
    {{__('Fullname')}}: {{$data['passengerName']}}<br>
    {{__('Phone')}}: {{$data['passengerPhone']}}<br>
    {{__('Email')}}: <a href="mailto:{{$data['passengerEmail']}}" target="_blank">{{$data['passengerEmail']}}</a><br>
    {{__('Address')}}: {{$data['passengerAddress']}}<br>
    
    {{__('Ticket code')}}: <strong>{{$data['ticketCode']}}</strong><br>
    {{__('Expected customers will board the car at')}}: {{$data['departTime']}} {{$data['date']}} <br>
    {{__('Departure')}}: {{$data['depProvinceName']}} (So 1 Ngoc Hoi - BX Nuoc Ngam - HN)<br>
    {{__('Destination')}}: {{$data['desProvinceName']}}<br>
    {{__('Route')}}: {{$data['routeName']}} - khoi hanh ngay: {{$data['date']}} <br>
    {{__('Quantity')}} : {{$data['quantity']}} <br>
    {{__('Ticket price')}}: {{$data['price']}}<br>
    {{__('Total price')}} : <strong>{{number_format($data['quantity'] * $data['price'])}}</strong><br>
    <br>
    @if (app()->getLocale() == 'vi')
    <strong style="color:red">TINH TRANG VE: CHUA THANH TOAN</strong><br>
    @else
    <strong style="color:red">TICKET STATUS: UNPAID</strong><br>
    @endif
    {{-- <b>(Quy khach vui long lien lac voi chung toi qua so 0225 3920920, đe xac nhan lai thong tin dat ve sau khi thanh toan chuyen khoan thanh cong.)</b><br>
    LUU Y QUAN TRONG: Truoc 24h xe chay doi voi ngay binh thuong va ngay sau khi dat ve doi voi nhung ngay LE, ban phai mang theo MA DAT VE toi van phong dai dien bat ky cua chung toi de thanh toan va nhan ve chinh thuc. Neu khong ve dat nay se tu dong bi huy boi he thong ve khong co gia tri su dung. Sau khi thanh toan,  Quy khach duoc quyen Huy ve/thay doi thung tin chuyen dua vao THOI GIAN DU KIEN XUAT BEN : truoc 12 tieng duoc huy/doi mien phi, tu 03 den 12 tieng duoc huy/doi va mat phi dich vu 10%, trong vong 03 tieng duoc huy/doi va mat phi dich vu 30%. Gia tri ve sau khi tru phi se duoc hoan lai trong vong 15-20 ngay ke tu khi huy ve. ngay Le - Tet co Quy dinh rieng. Cac thong tin khac duoc ghi ro tren ve, chung toi khuyen ban nen doc ky truoc khi su dung dich vu cua chung toi.<br>
    <br>
    Thông tin chi tiết về nhà xe:<br>
    <br> --}}
    <a href="{{url('/nha-xe')}}" target="_blank" data-saferedirecturl="https://www.google.com/url?q=http://localhost/vi/nha-xe&amp;source=gmail&amp;ust=1586680991905000&amp;usg=AFQjCNGlE0Ghy2barcl8-A_WXJEeKhLqxA">http://localhost/vi/nha-xe<wbr>contact</a><div class="yj6qo"></div><div class="adL">
        
        <br>
    </div></div>