<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TicketStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Ticket;
use Carbon\Carbon;
use Excel;

class ReportController extends Controller
{
    public function index()
    {
        $this->authorize('report.viewAny');

        $data = [
            'from_date' => date('Y-m-01'),
            'to_date' => date('Y-m-t')
        ];

        return redirect()->route('admin.report.search', ['from_date' => $data['from_date'], 'to_date' => $data['to_date']]);
    }

    public function search(Request $request)
    {
        $data = [
            'from_date' => $request->from_date ? date('Y-m-d', strtotime($request->from_date)) : null,
            'to_date' => $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : null
        ];
        $reports = $this->searchHelper($data);
        
        return view('admin.report.index',[
            'reports' => $reports
        ]);
    }

    public function searchHelper($data)
    {
        $admin = auth('admin')->user();

        $where = [
            ['tickets.brand_id', $admin->brand_id]
        ];

        return Ticket::join('trip_depart_dates as ttd', 'trip_depart_date_id', 'ttd.id')
                    ->selectRaw("sum(if(tickets.status ='paid', tickets.total, 0)) as sum_total, r.id, r.name as route_name, sum(if(tickets.status = 'paid', 1, 0)) as count_paid, sum(if(tickets.status = 'unpaid', 1, 0)) as count_unpaid, sum(if(tickets.status = 'not refund yet', 1, 0)) as count_not_refund_yet, sum(if(tickets.status = 'refund', 1, 0)) as count_refund")
                    ->join('trips as t', 'ttd.trip_id', 't.id')
                    ->join('routes as r', 't.route_id', 'r.id')
                    ->where($where)
                    ->where(function ($q) use ($data) {
                        isset($data['from_date']) && $q->whereDate('tickets.created_at', '>=', $data['from_date']);
                        isset($data['to_date']) && $q->whereDate('tickets.created_at', '<=', $data['to_date']);
                    })
                    ->groupBy('r.id')
                    ->get();
    }

    public function excel(Request $request)
    {
        $data = [
            'from_date' => $request->from_date ? date('Y-m-d', strtotime($request->from_date)) : null,
            'to_date' => $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : null
        ];

        $report = $this->searchHelper($data);

        Excel::create("Thông kê doanh thu từ " . $data['from_date'] . " đến" . $data['to_date'], function ($excel) use ($report, $data) {
            $excel->sheet('Sheetname', function ($sheet) use ($report, $data) {
                $sheet->mergeCells('A2:G2');
                $sheet->setColumnFormat(array(
                    // 'C' => '#,##0',
                    // 'D' => '#,##0',
                    // 'E' => '#,##0',
                    // 'F' => '#,##0',
                    'G' => '#,##0',
                    // 'B' => \PHPExcel_Cell_DataType::TYPE_STRING,
                ));
                $sheet->setWidth(array(
                    'A'     =>  25,
                    'B'     =>  80,
                    'C'     =>  25,
                    'D'     =>  25,
                    'E'     =>  25,
                    'F'     =>  25,
                    'G'     =>  40,
                ));
                $sheet->cells('A2', function ($cells) use ($data) {
                    $cells->setValue("Thông kê doanh thu từ " . $data['from_date'] . " đến" . $data['to_date']);
                    $cells->setFont(array(
                        'size'       => '18',
                        'bold'       =>  true,
                    ));
                    $cells->setAlignment('center');
                });
                $sheet->cell('A3', 'STT');
                $sheet->cell('B3', 'Tên tuyến');
                $sheet->cell('C3', 'Đã thanh toán');
                $sheet->cell('D3', 'Chưa thanh toán');
                $sheet->cell('E3', 'Hoàn trả');
                $sheet->cell('F3', 'Chưa hoàn trả');
                $sheet->cell('G3', 'Doanh thu');
                $sheet->cells('A3:G3', function ($cells) {
                    $cells->setFont(array(
                        'bold'       =>  true,
                    ));
                    $cells->setAlignment('center');
                });
                $i = 4;
                foreach ($report as $d) {
                    $sheet->cell('A' . $i, $i-3);
                    $sheet->cell('B' . $i, $d->route_name);
                    $sheet->cell('C' . $i, $d->count_paid);
                    $sheet->cell('D' . $i, $d->count_unpaid);
                    $sheet->cell('E' . $i, $d->count_refund);
                    $sheet->cell('F' . $i, $d->count_not_refund_yet);
                    $sheet->cell('G' . $i, $d->sum_total);
                    $i++;
                }
                $sheet->cells('A4:G'. ($i - 1), function ($cells) {
                    $cells->setAlignment('center');
                });

                $total = $report->sum('sum_total');
                $sheet->mergeCells("A$i:F$i");
                $sheet->cells("A$i:F$i", function ($cells) {
                    $cells->setFont(array(
                        'bold'      =>  true,
                        'italic'    => true
                    ));
                    $cells->setAlignment('right');
                });
                $sheet->cells("G$i", function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setFont(array(
                        'bold'       =>  true,
                    ));
                });
                $sheet->cell("A$i", 'Tổng doanh thu');
                $sheet->cell("G$i", $total ?? 0);
            });
        })->download('xlsx');
    }
}
