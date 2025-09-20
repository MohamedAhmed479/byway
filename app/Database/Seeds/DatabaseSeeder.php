<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        echo "=== Starting Database Seeding ===\n\n";
        
        // Step 1: Create users (admin, instructors, learners)
        echo "Step 1: Creating users...\n";
        $this->call('UserSeeder');
        echo "\n";
        
        // Step 2: Create categories (main categories and subcategories)
        echo "Step 2: Creating categories...\n";
        $this->call('CategorySeeder');
        echo "\n";
        
        // Step 3: Create courses (assigned to categories and instructors)
        echo "Step 3: Creating courses...\n";
        $this->call('CourseSeeder');
        echo "\n";
        
        // Step 4: Create lessons (for each course)
        echo "Step 4: Creating lessons...\n";
        $this->call('LessonSeeder');
        echo "\n";
        
        // Step 5: Create enrollments (learners enrolled in courses)
        echo "Step 5: Creating enrollments...\n";
        $this->call('EnrollmentSeeder');
        echo "\n";
        
        // Step 6: Create payments and payment items (based on enrollments)
        echo "Step 6: Creating payments...\n";
        $this->call('PaymentSeeder');
        echo "\n";
        
        // Step 7: Create reviews (based on enrollments with progress)
        echo "Step 7: Creating reviews...\n";
        $this->call('ReviewSeeder');
        echo "\n";
        
        // Step 8: Create supporting data (favorites, cart items, notifications, etc.)
        echo "Step 8: Creating supporting data...\n";
        $this->call('SupportingSeeder');
        echo "\n";
        
        echo "=== Database Seeding Completed Successfully! ===\n";
        echo "\n";
        echo "Summary of created data:\n";
        echo "- Users: 1 admin + 5 instructors + 50 learners = 56 total\n";
        echo "- Categories: 6 main categories + 26 subcategories = 32 total\n";
        echo "- Courses: ~18 courses across different categories\n";
        echo "- Lessons: ~10 lessons per course (varies by course type)\n";
        echo "- Enrollments: Random enrollments with realistic progress\n";
        echo "- Reviews: ~40% of progressed enrollments have reviews\n";
        echo "- Payments: Payments for all paid enrollments\n";
        echo "- Supporting data: Favorites, cart items, notifications, lesson completions, payment methods, withdrawals\n";
        echo "\n";
        echo "Default login credentials:\n";
        echo "Admin: admin@byway.com / admin123456\n";
        echo "Instructors: [instructor-email] / instructor123\n";
        echo "Learners: [learner-email] / learner123\n";
        echo "\n";
        echo "You can now start using your application with realistic test data!\n";
    }
}
