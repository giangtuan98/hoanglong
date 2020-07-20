<?php

namespace App\Jobs;

use App\Mail\CancelInvalideTicketMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class CancelInvalidTicketJob implements ShouldQueue
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
        $this->data['view'] = 'mail.cancel_invalid_ticket';
        $this->data['subject'] = __('Cancel invalid ticket!');
        Mail::to($this->data['to'])->send(new CancelInvalideTicketMail($this->data));
    }
}
