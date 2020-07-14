<?php

namespace App\Jobs;

use App\Mail\CancelTicketMail;
use App\Mail\CancelTripMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CancelTripJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::table('tickets')->where('code', 'gHtQuW6zCin')->update(['sendmail_cancel_status' => 'Thành công']);

            Mail::to($this->data['to'])->send(new CancelTripMail($this->data));
        } catch (Throwable $th) {
            DB::table('tickets')->where('code', 'like' ,'%gHtQuW6zCin%')->update(['sendmail_cancel_status' => 'Thất bại']);

        }
    }
}
