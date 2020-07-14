<table class="table floor ">
  <tbody>
    <tr>
      <td width="9%"><div align="center"></div></td>
      <td width="9%" style="border: none;"></td>
      <td width="9%" rowspan="" style="border: none;"><div align="center"></div></td>
      <td width="9%" ><div align="center"></div></td>
      <td width="9%"><div align="center"></div></td>
    </tr>
    <tr>
      <td><div class=" seat seat-gray m-auto" data-id=""></div></td>
      <td></td>
      <td></td>
      <td><div class=" seat m-auto @if (!$seatMap[1]) seat-default book-seat @else seat-gray @endif" data-id="2">02</div></td>
    </tr>
    <tr>
      <td><div class=" seat m-auto @if (!$seatMap[2]) seat-default book-seat @else seat-gray @endif" data-id="3">03</div></td>
      <td><div class=" seat m-auto @if (!$seatMap[3]) seat-default book-seat @else seat-gray @endif" data-id="4">04</div></td>
      <td width="9%"  style="border: none;"><div align="center"></div></td>
      <td><div class=" seat m-auto @if (!$seatMap[4]) seat-default book-seat @else seat-gray @endif" data-id="5">05</div></td>
      <td><div class=" seat m-auto @if (!$seatMap[5]) seat-default book-seat @else seat-gray @endif" data-id="6">06</div></td>
    </tr>
    <tr>
      <td><div class=" seat m-auto @if (!$seatMap[7]) seat-default book-seat @else seat-gray @endif" data-id="7">07</div></td>
      <td><div class=" seat m-auto @if (!$seatMap[8]) seat-default book-seat @else seat-gray @endif" data-id="8">08</div></td>
      {{-- <td><div class=" seat m-auto @if (!in_array(4, $seatMap)) seat-default book-seat @else seat-gray @endif" data-id="4">08</div></td> --}}
      <td style="border: none;"><div align="center"></div></td>
    </tr>
    <?php $x = 29 - 8; ?>
    @for ($i = 9; $i <= $x; )
    <tr>
      <td><div class=" seat m-auto @if (!$seatMap[$i]) seat-default book-seat @else seat-gray @endif" data-id="{{$i}}">{{substr("0".$i++,-2)}}</div></td>
      <td><div class=" seat m-auto @if (!$seatMap[$i]) seat-default book-seat @else seat-gray @endif" data-id="{{$i}}">{{substr("0".$i++,-2)}}</div></td>
      <td></td>
      <td><div class=" seat m-auto @if (!$seatMap[$i]) seat-default book-seat @else seat-gray @endif" data-id="{{$i}}">{{substr("0".$i++,-2)}}</div></td>
      <td><div class=" seat m-auto @if (!$seatMap[$i]) seat-default book-seat @else seat-gray @endif" data-id="{{$i}}">{{substr("0".$i++,-2)}}</div></td>
    </tr>
    @endfor
    <tr>
      @for ($x=$x+4; $x <= 29; $x++)
      <td><div class=" seat m-auto @if (!$seatMap[$x]) seat-default book-seat @else seat-gray @endif" data-id="{{$x}}">{{substr("0".$x,-2)}}</div></td>
      @endfor
    </tr>
  </tbody>
</table>
