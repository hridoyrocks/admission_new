<?php

namespace App\Helpers;

class SmsTemplates
{
    /**
     * Application submitted SMS
     */
    public static function applicationSubmitted($student, $application)
    {
        $message = "প্রিয় {$student->name},\n";
        $message .= "আপনার IELTS কোর্স আবেদন গ্রহণ করা হয়েছে।\n";
        $message .= "Application ID: #{$application->id}\n";
        $message .= "Batch: {$application->batch->name}\n";
        $message .= "আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।\n";
        $message .= "ধন্যবাদ।";
        
        return $message;
    }

    /**
     * Application approved SMS
     */
    public static function applicationApproved($student, $application, $courseSetting)
    {
        $message = "অভিনন্দন {$student->name}!\n";
        $message .= "আপনার Banglay IELTS এডমিশন কনফার্ম হয়েছে।\n";
        $message .= "Class starts: {$application->batch->start_date->format('d M Y')}\n";
        $message .= "Time: " . ($student->classSession->time ?? 'N/A') . "\n";
        $message .= "Days: " . ($student->classSession->days ?? 'N/A') . "\n";
        if ($application->batch->start_date) {
                    $dayBeforeClass = $application->batch->start_date->subDay()->format('d M Y');
                    $message .= "\n আপনাকে ক্লাস শুরুর ১ দিন আগে ({$dayBeforeClass}) WhatsApp গ্রুপে এড করা হবে।\n";
                }
        
        return $message;
    }

    /**
     * Application rejected SMS
     */
    public static function applicationRejected($student, $application)
    {
        $message = "প্রিয় {$student->name},\n";
        $message .= "দুঃখিত, আপনার Banglay IELTS কোর্স আবেদন গ্রহণযোগ্য হয়নি।\n";
        
        if ($application->rejection_reason) {
            $message .= "কারণ: {$application->rejection_reason}\n";
        }
        
        $message .= "বিস্তারিত জানতে ইমেইল চেক করুন।\n";
        $message .= "পরবর্তী ব্যাচে আবার চেষ্টা করুন।";
        
        return $message;
    }

    /**
     * Payment reminder SMS
     */
    public static function paymentReminder($student, $courseFee)
    {
        $message = "প্রিয় {$student->name},\n";
        $message .= "আপনার IELTS কোর্স ফি ৳{$courseFee} এখনো বাকি আছে।\n";
        $message .= "অনুগ্রহ করে পেমেন্ট সম্পন্ন করুন।\n";
        $message .= "ধন্যবাদ।";
        
        return $message;
    }

    /**
     * Class reminder SMS
     */
    public static function classReminder($student, $classTime, $classDays)
    {
        $message = "Class Reminder!\n";
        $message .= "প্রিয় {$student->name},\n";
        $message .= "আগামীকাল আপনার IELTS ক্লাস আছে।\n";
        $message .= "Time: {$classTime}\n";
        $message .= "Please be on time.\n";
        $message .= "Thank you!";
        
        return $message;
    }

    /**
     * Welcome SMS after approval
     */
    public static function welcomeMessage($student)
    {
        $message = "Welcome to Banglay IELTS!\n";
        $message .= "প্রিয় {$student->name},\n";
        $message .= "আমাদের Banglay IELTS family তে আপনাকে স্বাগতম।\n";
        $message .= "আপনার সফলতাই আমাদের লক্ষ্য।\n";
        $message .= "Best wishes! 🌟";
        
        return $message;
    }

}