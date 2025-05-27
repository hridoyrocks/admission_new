<?php

namespace App\Mail;

use App\Models\Student;
use App\Models\Application;
use App\Models\CourseSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class ApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $application;
    public $courseSetting;

    /**
     * Create a new message instance.
     */
    public function __construct(Student $student, Application $application, CourseSetting $courseSetting)
    {
        $this->student = $student;
        $this->application = $application;
        $this->courseSetting = $courseSetting;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Banglay IELTS - Application Received',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.application-submitted',
            with: [
                'studentName' => $this->student->name,
                'applicationId' => $this->application->id,
                'batchName' => $this->application->batch->name,
                'classTime' => $this->student->classSession->time ?? 'N/A',
                'classDays' => $this->student->classSession->days ?? 'N/A',
                'paymentMethod' => $this->application->payment_method,
                'paymentId' => $this->application->payment_id,
                'courseType' => strtoupper($this->student->course_type),
                'profession' => ucfirst(str_replace('_', ' ', $this->student->profession)),
                'score' => $this->student->score,
                'contactNumber' => $this->courseSetting->contact_number,
                'courseFee' => $this->courseSetting->fee,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}