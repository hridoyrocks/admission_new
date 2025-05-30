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

class ApplicationApproved extends Mailable
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
            subject: 'Admission Confirmed!',
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
                'courseDuration' => $this->courseSetting->duration ?? '2 মাস',
                'courseFee' => number_format($this->courseSetting->fee ?? 0),
                'courseClasses' => $this->courseSetting->classes ?? 'সপ্তাহে 3 দিন',
                'courseMaterials' => $this->courseSetting->materials ?? 'Free PDF + Videos',
                'mockTests' => $this->courseSetting->mock_tests ?? '5টি',
                'additionalInfo' => $this->courseSetting->additional_info ?? [],
                
                // Batch Information - Safe navigation
                'batchName' => $this->application->batch?->name ?? 'N/A',
                'batchStartDate' => $this->application->batch?->start_date?->format('d M Y') ?? 'N/A',
                'batchStartDay' => $this->application->batch?->start_date?->format('l') ?? 'N/A',
                
                // Class Information - Safe navigation
                'classTime' => $this->student->classSession?->time ?? 'N/A',
                'classDays' => $this->student->classSession?->days ?? 'N/A',
                'sessionName' => $this->student->classSession?->session_name ?? 'N/A',
                
                // Contact Information
                'contactNumber' => $this->courseSetting->contact_number ?? 'N/A',
                'youtubeLink' => $this->courseSetting->youtube_link ?? '',
                
                // Payment Information
                'paymentMethod' => $this->application->payment_method ?? 'N/A',
                'paymentId' => $this->application->payment_id ?? 'N/A',
                
                // Additional Data
                'profession' => ucfirst(str_replace('_', ' ', $this->student->profession ?? 'student')),
                'score' => $this->student->score ?? 0,
                'approvedAt' => now()->format('d M Y, h:i A'),
            ],
        );
    }
}