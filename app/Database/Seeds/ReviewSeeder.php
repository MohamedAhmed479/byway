<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\Review;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $reviewModel = new Review();
        $enrollmentModel = new Enrollment();
        $userModel = new User();
        $courseModel = new Course();
        
        // Get enrollments where progress > 50% (users who have made significant progress)
        $db = \Config\Database::connect();
        $enrollments = $db->table('enrollments')->where('progress >', 50)->get()->getResultArray();
        
        if (empty($enrollments)) {
            echo "ReviewSeeder skipped: No enrollments with sufficient progress found.\n";
            return;
        }
        
        // Review templates for different ratings
        $reviewTemplates = [
            5 => [
                "Absolutely fantastic course! The instructor explains everything clearly and the content is very comprehensive. Highly recommended!",
                "This course exceeded my expectations. Well-structured, engaging, and practical. I've learned so much!",
                "Outstanding quality! The projects are real-world relevant and the teaching style is excellent. Worth every penny!",
                "Perfect course for beginners and intermediates alike. Clear explanations, great examples, and excellent support.",
                "I'm impressed by the depth and quality of this course. The instructor is knowledgeable and passionate about the subject.",
                "This is exactly what I was looking for. Comprehensive, well-organized, and taught by an expert. Five stars!",
                "Excellent course! The step-by-step approach made complex topics easy to understand. Highly recommend!",
                "One of the best courses I've taken online. Great content, excellent delivery, and very practical.",
                "Amazing course! The instructor's expertise really shows and the course material is top-notch.",
                "Perfect blend of theory and practice. I feel confident applying what I've learned in real projects."
            ],
            4 => [
                "Very good course with solid content. Some sections could be more detailed, but overall quite satisfied.",
                "Great course! The instructor is knowledgeable and the content is relevant. Minor issues with pacing.",
                "Good quality course. Learned a lot and the projects were helpful. Could use more advanced examples.",
                "Solid course with good explanations. The content is comprehensive though some parts felt rushed.",
                "Really enjoyed this course. Well-structured and informative. Would have liked more interactive elements.",
                "Good course overall. The instructor knows the subject well and explains concepts clearly.",
                "Helpful course with practical examples. Some sections could be more in-depth but generally satisfied.",
                "Nice course with good coverage of the topic. The teaching style is engaging and easy to follow.",
                "Good value for money. The content is relevant and up-to-date. Could benefit from more exercises.",
                "Well-organized course with clear objectives. Some topics could be explained in more detail."
            ],
            3 => [
                "Decent course but nothing exceptional. The content is okay but could be more engaging.",
                "Average course. Some good points but also some areas that need improvement. Mixed experience.",
                "The course covers the basics well but lacks depth in some areas. Okay for beginners.",
                "Fair course. The instructor knows the material but the delivery could be more dynamic.",
                "It's an okay course. Covers the fundamentals but I expected more advanced topics.",
                "The course is adequate for getting started but doesn't go beyond the basics.",
                "Reasonable course with standard content. Nothing particularly stands out as exceptional.",
                "The course serves its purpose but could benefit from better organization and more examples.",
                "Basic coverage of the topic. Good for absolute beginners but experienced learners might find it lacking.",
                "The course is fine but there are probably better options available for the same price."
            ],
            2 => [
                "The course has some useful information but the presentation could be much better.",
                "Disappointing course. The content feels outdated and the explanations are often unclear.",
                "Not what I expected. The course lacks structure and some information seems inaccurate.",
                "The course covers the topic but in a very basic way. Expected more depth and practical examples.",
                "Poor organization and pacing. Some good content but hard to follow the instructor's approach.",
                "The course has potential but needs significant improvement in delivery and content quality.",
                "Below average course. The instructor seems knowledgeable but struggles to explain concepts clearly.",
                "Limited value for the price. The course feels rushed and lacks proper depth in key areas.",
                "The course touches on important topics but fails to provide adequate explanations or examples.",
                "Mediocre course with several issues. The content could be much better organized and presented."
            ],
            1 => [
                "Very disappointing course. Poor quality content and confusing explanations throughout.",
                "Waste of time and money. The course is poorly structured and the instructor is hard to understand.",
                "Terrible course. Outdated information, poor presentation, and no clear learning path.",
                "I regret purchasing this course. The quality is far below expectations and very unprofessional.",
                "Awful course with numerous errors and unclear explanations. Would not recommend to anyone.",
                "The worst online course I've taken. Completely disorganized and lacking in useful content.",
                "Poor quality throughout. The instructor seems unprepared and the material is confusing.",
                "Extremely disappointed. The course fails to deliver on its promises and is poorly executed.",
                "Terrible value for money. The course is riddled with issues and provides little actual learning.",
                "Completely unsatisfied with this course. Poor content, bad presentation, and waste of time."
            ]
        ];
        
        $reviews = [];
        $reviewCount = 0;
        
        // Create reviews for a percentage of enrollments (not everyone reviews)
        foreach ($enrollments as $enrollment) {
            // Only 40% of learners with progress > 50% leave reviews
            if (rand(1, 100) > 40) {
                continue;
            }
            
            // Determine rating based on realistic distribution
            // 40% give 5 stars, 30% give 4 stars, 20% give 3 stars, 8% give 2 stars, 2% give 1 star
            $ratingRand = rand(1, 100);
            if ($ratingRand <= 40) {
                $rating = 5;
            } elseif ($ratingRand <= 70) {
                $rating = 4;
            } elseif ($ratingRand <= 90) {
                $rating = 3;
            } elseif ($ratingRand <= 98) {
                $rating = 2;
            } else {
                $rating = 1;
            }
            
            // Select a random comment template for this rating
            $comment = $reviewTemplates[$rating][array_rand($reviewTemplates[$rating])];
            
            // Create review date (after enrollment date but before completion if completed)
            $enrolledDate = strtotime($enrollment['enrolled_at']);
            $maxReviewDate = $enrollment['completed_at'] 
                ? strtotime($enrollment['completed_at'])
                : time();
            
            $reviewDate = date('Y-m-d H:i:s', rand($enrolledDate + 86400, $maxReviewDate)); // At least 1 day after enrollment
            
            $review = [
                'course_id' => $enrollment['course_id'],
                'learner_id' => $enrollment['learner_id'],
                'rating' => $rating,
                'comment' => $comment,
                'created_at' => $reviewDate,
                'updated_at' => $reviewDate
            ];
            
            $reviews[] = $review;
            $reviewCount++;
        }
        
        // Insert reviews in batches using database builder
        if (!empty($reviews)) {
            $db = \Config\Database::connect();
            $batchSize = 50;
            $batches = array_chunk($reviews, $batchSize);
            
            foreach ($batches as $batch) {
                $db->table('reviews')->insertBatch($batch);
            }
        }
        
        echo "ReviewSeeder completed: Created {$reviewCount} reviews from " . count($enrollments) . " eligible enrollments.\n";
    }
}
