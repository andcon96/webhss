<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailRFQReject implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $note1, $note2, $note3, $note4, $arrayemail, $com_name, $com_email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($note1,$note2,$note3,$note4,$arrayemail,$com_name,$com_email)
    {
        //
        $this->note1 = $note1;
        $this->note2 = $note2;
        $this->note3 = $note3;
        $this->note4 = $note4;
        $this->arrayemail = $arrayemail;
        $this->com_name = $com_name;
        $this->com_email = $com_email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $arrayemail = $this->arrayemail;
        $com_name = $this->com_name;
        $com_email = $this->com_email;
        $note1 = $this->note1;
        $note2 = $this->note2;
        $note3 = $this->note3;
        $note4 = $this->note4;

        Mail::send('email.emailrfqreject', 
                    ['pesan' => 'An RFQ has been closed by Purchasing',
                     'note1' => $note1,
                     'note2' => $note2,
                     'note3' => $note3,
                     'note4' => $note4,],
                    function ($message) use ($arrayemail,$com_name,$com_email,$note1,$note2,$note3,$note4)
                {
                    $message->subject('PhD - Request for Quotation - '.$com_name);
                    $message->from($com_email); // Email Admin Fix
                    $message->to($arrayemail);
                });

    }
}
