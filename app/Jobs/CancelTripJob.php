<?php

namespace App\Jobs;

use App\Mail\CancelTicketMail;
use App\Mail\CancelTripMail;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
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
            Mail::to($this->data['to'])->send(new CancelTripMail($this->data));
            Ticket::where('code', $this->data['code'])->update(['sendmail_cancel_status' => 'Thành công']);
        } catch (Throwable $th) {
            Ticket::where('code', $this->data['code'])->update(['sendmail_cancel_status' => 'Thất bại']);
        }
    }
}
