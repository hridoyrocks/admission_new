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
            background: linear-gradient(135deg, #3498DB 0%, #5DADE2 100%); 
            color: white; 
            padding: 40px 20px; 
            text-align: center; 
        }
        .content { 
            padding: 40px 30px; 
        }
        .info-box { 
            background-color: #f8f9fa; 
            padding: 25px; 
            margin: 20px 0; 
            border-radius: 8px; 
            border: 1px solid #e9ecef;
        }
        .status-pending {
            background-color: #FFF3CD;
            border-color: #FFEAA7;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .footer { 
            background-color: #2C3E50;
            color: white;
            text-align: center; 
            padding: 30px 20px;
            font-size: 14px;
        }
        .timeline {
            margin: 30px 0;
        }
        .timeline-item {
            display: flex;
            align-items: center;
            margin: 15px 0;
        }
        .timeline-icon {
            width: 40px;
            height: 40px;
            background-color: #3498DB;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            font-weight: bold;
        }
        .timeline-content {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        
        
        <div class="content">
            <h2>Dear {{ $studentName }},</h2>
            
            <p>We have successfully received your application for our Private Batch. Your application is currently being reviewed by our admission team.</p>
            
            <div class="status-pending">
                <h3 style="margin: 0; color: #856404;">Application Status: PENDING</h3>
                <p style="margin: 10px 0 0 0;">Your application will be reviewed within 24 hours</p>
            </div>
            
            <div class="info-box">
                <h3 style="margin-top: 0;">Application Summary</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0;"><strong>Application ID:</strong></td>
                        <td>#{{ $applicationId }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0;"><strong>Name:</strong></td>
                        <td>{{ $studentName }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0;"><strong>Course Type:</strong></td>
                        <td>{{ $courseType }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0;"><strong>Profession:</strong></td>
                        <td>{{ $profession }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0;"><strong>Score:</strong></td>
                        <td>{{ $score }}/40</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0;"><strong>Batch:</strong></td>
                        <td>{{ $batchName }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0;"><strong>Tentative Time:</strong></td>
                        <td>{{ $classTime }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0;"><strong>Payment Method:</strong></td>
                        <td>{{ $paymentMethod }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0;"><strong>Transaction ID:</strong></td>
                        <td>{{ $paymentId }}</td>
                    </tr>
                </table>
            </div>
            
            
              
            
            <div style="background-color: #E3F2FD; padding: 20px; border-radius: 8px; margin-top: 30px;">
                <h4 style="margin: 0 0 10px 0;">💡 Important Notes:</h4>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Keep your Application ID for future reference</li>
                    <li>Check your email regularly for updates</li>
                    <li>Ensure your phone is reachable for SMS notifications</li>
                    <li>Contact us if you don't receive confirmation within 24 hours</li>
                </ul>
            </div>
            
            <p style="margin-top: 30px;">If you have any questions, please contact us at <strong>{{ $contactNumber }}</strong></p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply directly to this email.</p>
            <p>© {{ date('Y') }} Banglay IELTS . Made with ❤️ Rocks</p>
        </div>
    </div>
</body>
</html>