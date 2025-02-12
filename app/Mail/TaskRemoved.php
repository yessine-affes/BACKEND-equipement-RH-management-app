<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskRemoved extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $employee;

    /**
     * Create a new message instance.
     *
     * @param object $task
     * @param object $employee
     */
    public function __construct($task, $employee)
    {
        $this->task = $task;
        $this->employee = $employee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('You have been removed from a task')
                    ->view('emails.task_removed');
    }
}

