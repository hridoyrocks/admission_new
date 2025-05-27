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

class ApplicationRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $application;
    public $courseSetting;

    /**
     * Create a new message instance.
     */
    public function __construct(Student $student, Application $application, CourseSetting $courseSetting = null)
    {
        $this->student = $student;
        $this->application = $application;
        $this->courseSetting = $courseSetting ?: CourseSetting::first();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'IELTS Course - Application Status Update',
            from: config('mail.from.address', 'noreply@ielts.com'),
            replyTo: [
                [
                    'email' => $this->courseSetting->contact_email ?? config('mail.from.address'),
                    'name' => 'Banglay IELTS - Admission Updates',
                ],
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.application-rejected',
            with: [
                // Student Information
                'studentName' => $this->student->name,
                'applicationId' => $this->application->id,
                
                // Rejection Details
                'rejectionReason' => $this->application->rejection_reason,
                'rejectedAt' => $this->application->updated_at->format('d M Y, h:i A'),
                
                // Application Details
                'batchName' => $this->application->batch->name,
                'paymentMethod' => $this->application->payment_method,
                'paymentId' => $this->application->payment_id,
                
                // Contact Information
                'contactNumber' => $this->courseSetting->contact_number,
                'contactEmail' => $this->courseSetting->contact_email ?? config('mail.from.address'),
                
                // Next Steps
                'hasRejectionReason' => !empty($this->application->rejection_reason),
                'canReapply' => true, // You can add logic here based on rejection reason
            ]
        );
    }

    /**
     * Get the message tags.
     */
    public function tags(): array
    {
        return ['rejected', 'ielts-course', 'batch-' . $this->application->batch->id];
    }

    /**
     * Get the message metadata.
     */
    public function metadata(): array
    {
        return [
            'application_id' => $this->application->id,
            'student_id' => $this->student->id,
            'batch_id' => $this->application->batch->id,
            'rejection_reason' => $this->application->rejection_reason,
        ];
    }
}