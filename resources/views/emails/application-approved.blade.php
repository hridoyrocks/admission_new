<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { 
            font-family: 'Arial', sans-serif; 
            line-height: 1.6; 
            color: #333; 
            margin: 0; 
            padding: 0; 
            background-color: #f4f4f4; 
        }
        .container { 
            max-width: 600px; 
            margin: 0 auto; 
            background-color: #ffffff; 
        }
        .header { 
            background: linear-gradient(135deg, #27AE60 0%, #2ECC71 100%); 
            color: white; 
            padding: 40px 20px; 
            text-align: center; 
        }
        .header h1 { 
            margin: 0; 
            font-size: 28px; 
            font-weight: bold; 
        }
        .header p { 
            margin: 10px 0 0 0; 
            font-size: 16px; 
            opacity: 0.9; 
        }
        .content { 
            padding: 40px 30px; 
        }
        .success-box { 
            background-color: #E8F5E9; 
            padding: 25px; 
            margin: 20px 0; 
            border-radius: 10px; 
            border-left: 5px solidrgb(174, 39, 39); 
            text-align: center;
        }
        .success-box h2 {
            color: #27AE60;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .info-box { 
            background-color: #f9f9f9; 
            padding: 25px; 
            margin: 20px 0; 
            border-radius: 8px; 
            border: 1px solid #e0e0e0;
        }
        .info-box h3 {
            color: #2C3E50;
            margin: 0 0 15px 0;
            font-size: 20px;
            border-bottom: 2px solid #27AE60;
            padding-bottom: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 15px;
        }
        .info-item {
            padding: 10px;
            background-color: white;
            border-radius: 5px;
        }
        .info-item strong {
            color: #34495E;
            display: block;
            margin-bottom: 5px;
        }
        .highlight {
            background-color: #FFF3CD;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #FFEAA7;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 15px 30px;
            background-color: #27AE60;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer { 
            background-color: #2C3E50;
            color: white;
            text-align: center; 
            padding: 30px 20px;
            font-size: 14px;
        }
        .footer a {
            color: #3498DB;
            text-decoration: none;
        }
        ul {
            padding-left: 20px;
        }
        ul li {
            margin: 10px 0;
        }
        .icon {
            display: inline-block;
            width: 20px;
            margin-right: 10px;
        }
        @media only screen and (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Congratulations!</h1>
            <p>Banglay IELTS Admission is Confirmed</p>
        </div>
        
        <div class="content">
            <div class="success-box">
                <h2>Welcome to Banglay IELTS</h2>
                <p>Dear <strong>{{ $studentName }}</strong>, we're thrilled to have you join us.</p>
                <p>Application ID: <strong>#{{ $applicationId }}</strong></p>
            </div>
            
            <div class="info-box">
                <h3>Your Class Schedule</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Batch Name:</strong>
                        {{ $batchName }}
                    </div>
                    <div class="info-item">
                        <strong>Class Time:</strong>
                        {{ $classTime }}
                    </div>
                    <div class="info-item">
                        <strong>Class Days:</strong>
                        {{ $classDays }}
                    </div>
                    <div class="info-item">
                        <strong>Starting Date:</strong>
                        {{ $batchStartDate }} ({{ $batchStartDay }})
                    </div>
                </div>
            </div>
            
            <div class="info-box">
                <h3>Course Details</h3>
                <ul>
                    <li><strong>Course Type:</strong> {{ $courseType }}</li>
                    <li><strong>Duration:</strong> {{ $courseDuration }}</li>
                    <li><strong>Classes:</strong> {{ $courseClasses }}</li>
                    <li><strong>Course Fee:</strong> ৳{{ $courseFee }} (Paid via {{ $paymentMethod }})</li>
                    <li><strong>Materials:</strong> {{ $courseMaterials }}</li>
                    <li><strong>Mock Tests:</strong> {{ $mockTests }}</li>
                </ul>
                
                @if($additionalInfo && count($additionalInfo) > 0)
                <p><strong>Additional Benefits:</strong></p>
                <ul>
                    @foreach($additionalInfo as $info)
                        <li>{{ $info }}</li>
                    @endforeach
                </ul>
                @endif
            </div>
            
          
            
            <div style="background-color: #E3F2FD; padding: 20px; border-radius: 8px; margin-top: 30px;">
                <p style="margin: 0;"><strong>Need Help?</strong></p>
                <p style="margin: 5px 0;">If you have any questions or concerns, please don't hesitate to contact us at {{ $contactNumber }}.</p>
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated confirmation email for your IELTS course admission.</p>
            <p>© {{ date('Y') }} Banglay IELTS . Made with ❤️ Rocks</p>
            <p>
                <a href="#">Terms & Conditions</a> | 
                <a href="#">Privacy Policy</a> | 
                <a href="#">Contact Us</a>
            </p>
        </div>
    </div>
</body>
</html>