<?php

namespace App\Jobs;

use App\Mail\OrderEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $Order;

    public function __construct($Order)
    {
        $this->Order = $Order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->Order->email)->send(new OrderEmail($this->Order));
    }
}
