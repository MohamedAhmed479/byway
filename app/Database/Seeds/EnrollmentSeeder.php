<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\EnrollmentModel;
use App\Models\UserModel;
use App\Models\CourseModel;

class EnrollmentSeeder extends Seeder
{
    public function run()
    {
        $enrollmentModel = new EnrollmentModel();
        $userModel = new UserModel();
        $courseModel = new CourseModel();
        
        // Get learners and published courses using database builder
        $db = \Config\Database::connect();
        $learners = $db->table('users')->where('role', 'learner')->get()->getResultArray();
        $courses = $db->table('courses')->where('status', 'published')->get()->getResultArray();
        
        if (empty($learners) || empty($courses)) {
            echo "EnrollmentSeeder skipped: No learners or courses found.\n";
            return;
        }
        
        $learnerIds = array_column($learners, 'id');
        $courseIds = array_column($courses, 'id');
        
        $enrollments = [];
        $enrollmentCount = 0;
        
        // Create realistic enrollment patterns
        foreach ($learnerIds as $learnerId) {
            // Each learner enrolls in 1-5 courses randomly
            $numEnrollments = rand(1, 5);
            $enrolledCourses = array_rand(array_flip($courseIds), min($numEnrollments, count($courseIds)));
            
            // Ensure $enrolledCourses is always an array
            if (!is_array($enrolledCourses)) {
                $enrolledCourses = [$enrolledCourses];
            }
            
            foreach ($enrolledCourses as $courseId) {
                // Generate enrollment date (last 6 months)
                $enrolledAt = date('Y-m-d H:i:s', strtotime('-' . rand(1, 180) . ' days'));
                
                // Determine progress (0-100%)
                // 30% complete courses, 40% in progress, 30% just started
                $progressType = rand(1, 100);
                if ($progressType <= 30) {
                    // Completed course
                    $progress = 100.00;
                    $completedAt = date('Y-m-d H:i:s', strtotime($enrolledAt . ' +' . rand(7, 60) . ' days'));
                } elseif ($progressType <= 70) {
                    // In progress
                    $progress = rand(10, 90);
                    $completedAt = null;
                } else {
                    // Just started
                    $progress = rand(0, 15);
                    $completedAt = null;
                }
                
                $enrollment = [
                    'learner_id' => $learnerId,
                    'course_id' => $courseId,
                    'enrolled_at' => $enrolledAt,
                    'progress' => $progress,
                    'completed_at' => $completedAt
                ];
                
                $enrollments[] = $enrollment;
                $enrollmentCount++;
            }
        }
        
        // Insert enrollments in batches using database builder
        $db = \Config\Database::connect();
        $batchSize = 100;
        $batches = array_chunk($enrollments, $batchSize);
        
        foreach ($batches as $batch) {
            $db->table('enrollments')->insertBatch($batch);
        }
        
        echo "EnrollmentSeeder completed: Created {$enrollmentCount} enrollments for " . count($learners) . " learners.\n";
    }
}
