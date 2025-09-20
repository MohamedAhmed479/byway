<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\Payment;
use App\Models\PaymentItem;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        $paymentModel = new Payment();
        $paymentItemModel = new PaymentItem();
        $enrollmentModel = new Enrollment();
        $courseModel = new Course();
        $userModel = new User();
        
        // Get all enrollments (these represent purchases)
        $db = \Config\Database::connect();
        $enrollments = $db->table('enrollments')->get()->getResultArray();
        $courses = $db->table('courses')->get()->getResultArray();
        $courseMap = [];
        foreach ($courses as $course) {
            $courseMap[$course['id']] = $course;
        }
        
        if (empty($enrollments)) {
            echo "PaymentSeeder skipped: No enrollments found.\n";
            return;
        }
        
        $payments = [];
        $paymentItems = [];
        $paymentMethods = ['stripe', 'paypal', 'credit_card', 'bank_transfer'];
        $paymentCount = 0;
        
        // Group enrollments by learner and enrollment date to simulate bulk purchases
        $enrollmentsByLearner = [];
        foreach ($enrollments as $enrollment) {
            $key = $enrollment['learner_id'] . '_' . date('Y-m-d', strtotime($enrollment['enrolled_at']));
            if (!isset($enrollmentsByLearner[$key])) {
                $enrollmentsByLearner[$key] = [];
            }
            $enrollmentsByLearner[$key][] = $enrollment;
        }
        
        foreach ($enrollmentsByLearner as $groupKey => $enrollmentGroup) {
            // Create a payment for this group of enrollments
            $firstEnrollment = $enrollmentGroup[0];
            $learnerId = $firstEnrollment['learner_id'];
            $enrollmentDate = $firstEnrollment['enrolled_at'];
            
            // Generate unique payment ID
            $paymentId = 'pay_' . uniqid() . '_' . time();
            
            // Calculate total amount from courses
            $totalAmount = 0;
            $courseItems = [];
            
            foreach ($enrollmentGroup as $enrollment) {
                $course = $courseMap[$enrollment['course_id']];
                $coursePrice = $course['price'] ?? 0;
                $totalAmount += $coursePrice;
                
                $courseItems[] = [
                    'payment_id' => $paymentId,
                    'course_id' => $enrollment['course_id'],
                    'price_at_purchase' => $coursePrice
                ];
            }
            
            // Skip if total amount is 0 (free courses)
            if ($totalAmount == 0) {
                continue;
            }
            
            // Determine payment status (95% successful, 4% failed, 1% refunded)
            $statusRand = rand(1, 100);
            if ($statusRand <= 95) {
                $status = 'completed';
            } elseif ($statusRand <= 99) {
                $status = 'failed';
            } else {
                $status = 'refunded';
            }
            
            // Generate transaction ID for successful payments
            $transactionId = null;
            if ($status === 'completed') {
                $transactionId = 'txn_' . uniqid() . '_' . substr(md5($paymentId), 0, 8);
            }
            
            // Payment date is same as enrollment date for successful payments
            $paidAt = ($status === 'completed') ? $enrollmentDate : null;
            
            $payment = [
                'id' => $paymentId,
                'learner_id' => $learnerId,
                'amount' => $totalAmount,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'status' => $status,
                'transaction_id' => $transactionId,
                'paid_at' => $paidAt,
                'created_at' => $enrollmentDate
            ];
            
            $payments[] = $payment;
            $paymentItems = array_merge($paymentItems, $courseItems);
            $paymentCount++;
        }
        
        // Insert payments using database builder
        $db = \Config\Database::connect();
        if (!empty($payments)) {
            $batchSize = 50;
            $paymentBatches = array_chunk($payments, $batchSize);
            
            foreach ($paymentBatches as $batch) {
                $db->table('payments')->insertBatch($batch);
            }
        }
        
        // Insert payment items using database builder
        if (!empty($paymentItems)) {
            $batchSize = 100;
            $itemBatches = array_chunk($paymentItems, $batchSize);
            
            foreach ($itemBatches as $batch) {
                $db->table('payment_items')->insertBatch($batch);
            }
        }
        
        echo "PaymentSeeder completed: Created {$paymentCount} payments with " . count($paymentItems) . " payment items.\n";
    }
}
