<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $employee;

    /**
     * Create a new message instance.
     *
     * @param array $task
     * @param array $employee
     */
    public function __construct(array $task, array $employee)
    {
        $this->task = (object)$task;       // Convert to object for easy access
        $this->employee = (object)$employee; // Convert to object for easy access
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('You have been assigned to a task')
                    ->view('emails.task_assigned');
    }
}
