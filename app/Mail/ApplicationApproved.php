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

class ApplicationApproved extends Mailable
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
            subject: '✅ IELTS Course - Admission Confirmed!',
            from: config('mail.from.address', 'noreply@ielts.com'),
            replyTo: [
                [
                    'email' => $this->courseSetting->contact_email ?? config('mail.from.address'),
                    'name' => 'IELTS Course Support',
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
            view: 'emails.application-approved',
            with: [
                // Student Information
                'studentName' => $this->student->name,
                'studentEmail' => $this->student->email,
                'studentPhone' => $this->student->phone,
                'applicationId' => $this->application->id,
                
                // Course Information
                'courseType' => strtoupper($this->student->course_type),
                'courseDuration' => $this->courseSetting->duration,
                'courseFee' => number_format($this->courseSetting->fee),
                'courseClasses' => $this->courseSetting->classes,
                'courseMaterials' => $this->courseSetting->materials,
                'mockTests' => $this->courseSetting->mock_tests,
                'additionalInfo' => $this->courseSetting->additional_info,
                
                // Batch Information
                'batchName' => $this->application->batch->name,
                'batchStartDate' => $this->application->batch->start_date->format('d M Y'),
                'batchStartDay' => $this->application->batch->start_date->format('l'),
                
                // Class Information
                'classTime' => $this->student->classSession->time ?? 'N/A',
                'classDays' => $this->student->classSession->days ?? 'N/A',
                'sessionName' => $this->student->classSession->session_name ?? 'N/A',
                
                // Contact Information
                'contactNumber' => $this->courseSetting->contact_number,
                'youtubeLink' => $this->courseSetting->youtube_link,
                
                // Payment Information
                'paymentMethod' => $this->application->payment_method,
                'paymentId' => $this->application->payment_id,
                
                // Additional Data
                'profession' => ucfirst(str_replace('_', ' ', $this->student->profession)),
                'score' => $this->student->score,
                'approvedAt' => now()->format('d M Y, h:i A'),
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
        $attachments = [];
        
        // You can attach welcome PDF or course materials here
        // Example:
        // if (file_exists(public_path('pdfs/welcome-guide.pdf'))) {
        //     $attachments[] = Attachment::fromPath(public_path('pdfs/welcome-guide.pdf'))
        //         ->as('IELTS-Course-Welcome-Guide.pdf')
        //         ->withMime('application/pdf');
        // }
        
        return $attachments;
    }

    /**
     * Get the message tags.
     */
    public function tags(): array
    {
        return ['approved', 'ielts-course', 'batch-' . $this->application->batch->id];
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
        ];
    }
}