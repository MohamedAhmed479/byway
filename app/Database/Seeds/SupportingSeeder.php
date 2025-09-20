<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\FavoriteModel;
use App\Models\CartItemModel;
use App\Models\NotificationModel;
use App\Models\LessonCompletionModel;
use App\Models\PaymentMethodModel;
use App\Models\WithdrawalsModel;
use App\Models\UserModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\LessonModel;

class SupportingSeeder extends Seeder
{
    public function run()
    {
        $this->createFavorites();
        $this->createCartItems();
        $this->createNotifications();
        $this->createLessonCompletions();
        $this->createPaymentMethods();
        $this->createWithdrawals();
    }
    
    private function createFavorites()
    {
        $favoriteModel = new FavoriteModel();
        $userModel = new UserModel();
        $courseModel = new CourseModel();
        
        $db = \Config\Database::connect();
        $learners = $db->table('users')->where('role', 'learner')->get()->getResultArray();
        $courses = $db->table('courses')->where('status', 'published')->get()->getResultArray();
        
        if (empty($learners) || empty($courses)) {
            echo "Favorites skipped: No learners or courses found.\n";
            return;
        }
        
        $favorites = [];
        $learnerIds = array_column($learners, 'id');
        $courseIds = array_column($courses, 'id');
        
        // Each learner favorites 0-3 courses randomly
        foreach ($learnerIds as $learnerId) {
            $numFavorites = rand(0, 3);
            if ($numFavorites > 0) {
                $favoriteCourses = array_rand(array_flip($courseIds), min($numFavorites, count($courseIds)));
                if (!is_array($favoriteCourses)) {
                    $favoriteCourses = [$favoriteCourses];
                }
                
                foreach ($favoriteCourses as $courseId) {
                    $favorites[] = [
                        'user_id' => $learnerId,
                        'course_id' => $courseId,
                        'added_at' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 90) . ' days'))
                    ];
                }
            }
        }
        
        if (!empty($favorites)) {
            $db = \Config\Database::connect();
            $db->table('favorites')->insertBatch($favorites);
        }
        
        echo "Created " . count($favorites) . " favorites.\n";
    }
    
    private function createCartItems()
    {
        $cartModel = new CartItemModel();
        $userModel = new UserModel();
        $courseModel = new CourseModel();
        $enrollmentModel = new EnrollmentModel();
        
        $db = \Config\Database::connect();
        $learners = $db->table('users')->where('role', 'learner')->get()->getResultArray();
        $courses = $db->table('courses')->where('status', 'published')->get()->getResultArray();
        
        if (empty($learners) || empty($courses)) {
            echo "Cart items skipped: No learners or courses found.\n";
            return;
        }
        
        // Get enrolled courses to avoid adding them to cart
        $enrollments = $db->table('enrollments')->get()->getResultArray();
        $enrolledCoursesMap = [];
        foreach ($enrollments as $enrollment) {
            $key = $enrollment['learner_id'] . '_' . $enrollment['course_id'];
            $enrolledCoursesMap[$key] = true;
        }
        
        $cartItems = [];
        $learnerIds = array_column($learners, 'id');
        $courseIds = array_column($courses, 'id');
        
        // Some learners have items in cart (about 20%)
        foreach ($learnerIds as $learnerId) {
            if (rand(1, 100) <= 20) { // 20% chance of having cart items
                $numCartItems = rand(1, 3);
                $availableCourses = [];
                
                // Only add courses they haven't enrolled in
                foreach ($courseIds as $courseId) {
                    $key = $learnerId . '_' . $courseId;
                    if (!isset($enrolledCoursesMap[$key])) {
                        $availableCourses[] = $courseId;
                    }
                }
                
                if (!empty($availableCourses)) {
                    $cartCourses = array_rand(array_flip($availableCourses), min($numCartItems, count($availableCourses)));
                    if (!is_array($cartCourses)) {
                        $cartCourses = [$cartCourses];
                    }
                    
                    foreach ($cartCourses as $courseId) {
                        $cartItems[] = [
                            'user_id' => $learnerId,
                            'course_id' => $courseId,
                            'added_at' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days'))
                        ];
                    }
                }
            }
        }
        
        if (!empty($cartItems)) {
            $db = \Config\Database::connect();
            $db->table('carts')->insertBatch($cartItems);
        }
        
        echo "Created " . count($cartItems) . " cart items.\n";
    }
    
    private function createNotifications()
    {
        $notificationModel = new NotificationModel();
        $userModel = new UserModel();
        $enrollmentModel = new EnrollmentModel();
        
        $db = \Config\Database::connect();
        $users = $db->table('users')->get()->getResultArray();
        $enrollments = $db->table('enrollments')->get()->getResultArray();
        
        if (empty($users)) {
            echo "Notifications skipped: No users found.\n";
            return;
        }
        
        $notifications = [];
        $notificationTypes = [
            'enrollment' => 'You have successfully enrolled in a new course!',
            'payment_success' => 'Your payment has been processed successfully.',
            'payment_failure' => 'Your payment could not be processed. Please try again.',
            'course_update' => 'A course you are enrolled in has been updated with new content.',
            'review_added' => 'Thank you for your review! It helps other learners.',
            'withdrawal_request' => 'Your withdrawal request has been received and is being processed.'
        ];
        
        // Create notifications for various events
        foreach ($users as $user) {
            $numNotifications = rand(2, 8);
            
            for ($i = 0; $i < $numNotifications; $i++) {
                $type = array_rand($notificationTypes);
                $message = $notificationTypes[$type];
                
                // Some notifications are read (70% chance)
                $readAt = (rand(1, 100) <= 70) ? date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days')) : null;
                
                $notifications[] = [
                    'user_id' => $user['id'],
                    'type' => $type,
                    'message' => $message,
                    'read_at' => $readAt,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 60) . ' days'))
                ];
            }
        }
        
        if (!empty($notifications)) {
            $db = \Config\Database::connect();
            $batchSize = 100;
            $batches = array_chunk($notifications, $batchSize);
            foreach ($batches as $batch) {
                $db->table('notifications')->insertBatch($batch);
            }
        }
        
        echo "Created " . count($notifications) . " notifications.\n";
    }
    
    private function createLessonCompletions()
    {
        $lessonCompletionModel = new LessonCompletionModel();
        $enrollmentModel = new EnrollmentModel();
        $lessonModel = new LessonModel();
        
        $db = \Config\Database::connect();
        $enrollments = $db->table('enrollments')->where('progress >', 0)->get()->getResultArray();
        
        if (empty($enrollments)) {
            echo "Lesson completions skipped: No enrollments with progress found.\n";
            return;
        }
        
        $lessonCompletions = [];
        
        foreach ($enrollments as $enrollment) {
            // Get lessons for this course
            $lessons = $db->table('lessons')->where('course_id', $enrollment['course_id'])
                                 ->orderBy('order', 'ASC')
                                 ->get()->getResultArray();
            
            if (empty($lessons)) {
                continue;
            }
            
            // Calculate how many lessons should be completed based on progress
            $totalLessons = count($lessons);
            $completedLessonsCount = floor(($enrollment['progress'] / 100) * $totalLessons);
            
            // Mark first N lessons as completed
            for ($i = 0; $i < $completedLessonsCount; $i++) {
                $lesson = $lessons[$i];
                
                // Completion date should be after enrollment and spread over time
                $enrollmentDate = strtotime($enrollment['enrolled_at']);
                $daysSinceEnrollment = max(1, floor($i / 2)); // Spread completions over time
                $completedAt = date('Y-m-d H:i:s', $enrollmentDate + ($daysSinceEnrollment * 86400) + rand(3600, 86400));
                
                $lessonCompletions[] = [
                    'enrollment_id' => $enrollment['id'],
                    'lesson_id' => $lesson['id'],
                    'completed_at' => $completedAt
                ];
            }
        }
        
        if (!empty($lessonCompletions)) {
            $db = \Config\Database::connect();
            $batchSize = 100;
            $batches = array_chunk($lessonCompletions, $batchSize);
            foreach ($batches as $batch) {
                $db->table('lesson_completions')->insertBatch($batch);
            }
        }
        
        echo "Created " . count($lessonCompletions) . " lesson completions.\n";
    }
    
    private function createPaymentMethods()
    {
        $paymentMethodModel = new PaymentMethodModel();
        $userModel = new UserModel();
        
        $db = \Config\Database::connect();
        $users = $db->table('users')->get()->getResultArray();
        
        if (empty($users)) {
            echo "Payment methods skipped: No users found.\n";
            return;
        }
        
        $paymentMethods = [];
        
        // Some users have saved payment methods (about 40%)
        foreach ($users as $user) {
            if (rand(1, 100) <= 40) {
                $numMethods = rand(1, 2);
                
                for ($i = 0; $i < $numMethods; $i++) {
                    $methodTypes = ['credit_card', 'paypal', 'bank_account'];
                    $type = $methodTypes[array_rand($methodTypes)];
                    
                    $details = [];
                    if ($type === 'credit_card') {
                        $details = [
                            'last_four' => str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                            'brand' => ['visa', 'mastercard', 'amex'][array_rand(['visa', 'mastercard', 'amex'])],
                            'exp_month' => rand(1, 12),
                            'exp_year' => rand(2024, 2029)
                        ];
                    } elseif ($type === 'paypal') {
                        $details = [
                            'email' => $user['email']
                        ];
                    } elseif ($type === 'bank_account') {
                        $details = [
                            'account_type' => 'checking',
                            'last_four' => str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT)
                        ];
                    }
                    
                    $paymentMethods[] = [
                        'id' => 'pm_' . uniqid() . '_' . $user['id'],
                        'user_id' => $user['id'],
                        'type' => $type,
                        'details' => json_encode($details),
                        'is_default' => ($i === 0) ? 1 : 0, // First method is default
                        'created_at' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 180) . ' days')),
                        'updated_at' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days'))
                    ];
                }
            }
        }
        
        if (!empty($paymentMethods)) {
            $db = \Config\Database::connect();
            $db->table('payment_methods')->insertBatch($paymentMethods);
        }
        
        echo "Created " . count($paymentMethods) . " payment methods.\n";
    }
    
    private function createWithdrawals()
    {
        $withdrawalModel = new WithdrawalsModel();
        $userModel = new UserModel();
        
        $db = \Config\Database::connect();
        $instructors = $db->table('users')->where('role', 'instructor')->get()->getResultArray();
        
        if (empty($instructors)) {
            echo "Withdrawals skipped: No instructors found.\n";
            return;
        }
        
        $withdrawals = [];
        
        // Some instructors have withdrawal requests
        foreach ($instructors as $instructor) {
            $numWithdrawals = rand(0, 3); // 0-3 withdrawals per instructor
            
            for ($i = 0; $i < $numWithdrawals; $i++) {
                $amount = rand(100, 2000) + (rand(0, 99) / 100); // Random amount between $100-$2000
                
                // Status distribution: 60% completed, 30% pending, 10% failed
                $statusRand = rand(1, 100);
                if ($statusRand <= 60) {
                    $status = 'completed';
                } elseif ($statusRand <= 90) {
                    $status = 'pending';
                } else {
                    $status = 'failed';
                }
                
                $requestedAt = date('Y-m-d H:i:s', strtotime('-' . rand(1, 90) . ' days'));
                $processedAt = null;
                
                if ($status !== 'pending') {
                    $processedAt = date('Y-m-d H:i:s', strtotime($requestedAt . ' +' . rand(1, 7) . ' days'));
                }
                
                $methods = ['bank_transfer', 'paypal', 'stripe'];
                $method = $methods[array_rand($methods)];
                
                $details = [];
                if ($method === 'bank_transfer') {
                    $details = [
                        'account_number' => '****' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                        'routing_number' => '****' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                        'bank_name' => ['Chase Bank', 'Bank of America', 'Wells Fargo', 'Citibank'][array_rand(['Chase Bank', 'Bank of America', 'Wells Fargo', 'Citibank'])]
                    ];
                } elseif ($method === 'paypal') {
                    $details = [
                        'email' => $instructor['email']
                    ];
                } else {
                    $details = [
                        'account_id' => 'acct_' . uniqid()
                    ];
                }
                
                $withdrawals[] = [
                    'id' => 'wd_' . uniqid() . '_' . time() . '_' . $i,
                    'instructor_id' => $instructor['id'],
                    'amount' => $amount,
                    'status' => $status,
                    'method' => $method,
                    'details' => json_encode($details),
                    'requested_at' => $requestedAt,
                    'processed_at' => $processedAt
                ];
            }
        }
        
        if (!empty($withdrawals)) {
            $db = \Config\Database::connect();
            $db->table('withdrawals')->insertBatch($withdrawals);
        }
        
        echo "Created " . count($withdrawals) . " withdrawals.\n";
    }
}
