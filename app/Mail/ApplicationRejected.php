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

class ApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Student $student,
        public Application $application,
        public CourseSetting $courseSetting
    ) {}

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
                'batchName' => $this->application->batch?->name ?? 'N/A',
                'classTime' => $this->student->classSession?->time ?? 'N/A',
                'classDays' => $this->student->classSession?->days ?? 'N/A',
                'paymentMethod' => $this->application->payment_method ?? 'N/A',
                'paymentId' => $this->application->payment_id ?? 'N/A',
                'courseType' => strtoupper($this->student->course_type ?? 'academic'),
                'profession' => ucfirst(str_replace('_', ' ', $this->student->profession ?? 'student')),
                'score' => $this->student->score ?? 0,
                'contactNumber' => $this->courseSetting->contact_number ?? 'N/A',
                'courseFee' => $this->courseSetting->fee ?? 0,
            ],
        );
    }
}