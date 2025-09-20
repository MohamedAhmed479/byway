<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\Lesson;
use App\Models\Course;

class LessonSeeder extends Seeder
{
    public function run()
    {
        $lessonModel = new Lesson();
        $courseModel = new Course();
        
        // Get all published courses using database builder
        $db = \Config\Database::connect();
        $courses = $db->table('courses')->where('status', 'published')->get()->getResultArray();
        
        // Lesson templates for different course types
        $lessonTemplates = [
            'web_development' => [
                ['title' => 'Introduction and Setup', 'duration' => 0.5],
                ['title' => 'HTML Fundamentals', 'duration' => 1.2],
                ['title' => 'CSS Styling Basics', 'duration' => 1.5],
                ['title' => 'JavaScript Essentials', 'duration' => 2.0],
                ['title' => 'DOM Manipulation', 'duration' => 1.8],
                ['title' => 'Responsive Design', 'duration' => 1.3],
                ['title' => 'Modern CSS Grid and Flexbox', 'duration' => 1.7],
                ['title' => 'JavaScript ES6+ Features', 'duration' => 2.2],
                ['title' => 'Asynchronous JavaScript', 'duration' => 2.5],
                ['title' => 'Final Project and Deployment', 'duration' => 3.0]
            ],
            'react' => [
                ['title' => 'React Introduction and Setup', 'duration' => 0.8],
                ['title' => 'Components and JSX', 'duration' => 1.5],
                ['title' => 'Props and State', 'duration' => 2.0],
                ['title' => 'Event Handling', 'duration' => 1.3],
                ['title' => 'React Hooks - useState and useEffect', 'duration' => 2.5],
                ['title' => 'Component Lifecycle', 'duration' => 1.8],
                ['title' => 'Forms and Controlled Components', 'duration' => 2.2],
                ['title' => 'React Router', 'duration' => 2.0],
                ['title' => 'State Management with Context API', 'duration' => 2.8],
                ['title' => 'Building and Deploying React Apps', 'duration' => 2.5]
            ],
            'nodejs' => [
                ['title' => 'Node.js Architecture and Setup', 'duration' => 1.0],
                ['title' => 'Express.js Framework', 'duration' => 2.0],
                ['title' => 'Middleware and Routing', 'duration' => 1.8],
                ['title' => 'Database Integration', 'duration' => 2.5],
                ['title' => 'Authentication and Authorization', 'duration' => 3.0],
                ['title' => 'RESTful API Design', 'duration' => 2.2],
                ['title' => 'Error Handling and Validation', 'duration' => 1.7],
                ['title' => 'File Upload and Processing', 'duration' => 2.0],
                ['title' => 'Testing and Debugging', 'duration' => 2.3],
                ['title' => 'Deployment and Production', 'duration' => 2.8]
            ],
            'mobile' => [
                ['title' => 'Mobile Development Introduction', 'duration' => 0.7],
                ['title' => 'Development Environment Setup', 'duration' => 1.0],
                ['title' => 'Basic UI Components', 'duration' => 1.8],
                ['title' => 'Navigation and Routing', 'duration' => 2.0],
                ['title' => 'State Management', 'duration' => 2.5],
                ['title' => 'API Integration', 'duration' => 2.2],
                ['title' => 'Device Features Access', 'duration' => 2.8],
                ['title' => 'Performance Optimization', 'duration' => 2.0],
                ['title' => 'Testing Mobile Apps', 'duration' => 1.5],
                ['title' => 'App Store Deployment', 'duration' => 2.5]
            ],
            'design' => [
                ['title' => 'Design Principles and Theory', 'duration' => 1.2],
                ['title' => 'Color Theory and Typography', 'duration' => 1.8],
                ['title' => 'User Research Methods', 'duration' => 2.0],
                ['title' => 'Wireframing and Sketching', 'duration' => 1.5],
                ['title' => 'Prototyping Techniques', 'duration' => 2.5],
                ['title' => 'Design Tools Mastery', 'duration' => 2.8],
                ['title' => 'Responsive Design Principles', 'duration' => 2.2],
                ['title' => 'Usability Testing', 'duration' => 2.0],
                ['title' => 'Design Systems', 'duration' => 2.3],
                ['title' => 'Portfolio Development', 'duration' => 1.7]
            ],
            'marketing' => [
                ['title' => 'Digital Marketing Overview', 'duration' => 1.0],
                ['title' => 'Market Research and Analysis', 'duration' => 1.8],
                ['title' => 'Content Marketing Strategy', 'duration' => 2.2],
                ['title' => 'Social Media Marketing', 'duration' => 2.5],
                ['title' => 'Search Engine Optimization', 'duration' => 2.8],
                ['title' => 'Pay-Per-Click Advertising', 'duration' => 2.0],
                ['title' => 'Email Marketing Campaigns', 'duration' => 1.7],
                ['title' => 'Analytics and Tracking', 'duration' => 2.3],
                ['title' => 'Conversion Optimization', 'duration' => 2.0],
                ['title' => 'Marketing Automation', 'duration' => 2.5]
            ],
            'data_science' => [
                ['title' => 'Data Science Introduction', 'duration' => 1.0],
                ['title' => 'Python Programming Basics', 'duration' => 2.5],
                ['title' => 'Data Manipulation with Pandas', 'duration' => 3.0],
                ['title' => 'Data Visualization', 'duration' => 2.2],
                ['title' => 'Statistical Analysis', 'duration' => 2.8],
                ['title' => 'Machine Learning Fundamentals', 'duration' => 3.5],
                ['title' => 'Supervised Learning Algorithms', 'duration' => 3.2],
                ['title' => 'Unsupervised Learning', 'duration' => 2.7],
                ['title' => 'Model Evaluation and Validation', 'duration' => 2.5],
                ['title' => 'Real-world Projects', 'duration' => 4.0]
            ],
            'business' => [
                ['title' => 'Business Fundamentals', 'duration' => 1.2],
                ['title' => 'Market Analysis and Research', 'duration' => 2.0],
                ['title' => 'Business Planning', 'duration' => 2.5],
                ['title' => 'Financial Management', 'duration' => 2.3],
                ['title' => 'Operations and Processes', 'duration' => 2.0],
                ['title' => 'Marketing and Sales', 'duration' => 2.2],
                ['title' => 'Team Building and Leadership', 'duration' => 1.8],
                ['title' => 'Legal and Compliance', 'duration' => 1.5],
                ['title' => 'Growth Strategies', 'duration' => 2.7],
                ['title' => 'Case Studies and Implementation', 'duration' => 2.8]
            ],
            'personal_development' => [
                ['title' => 'Self-Assessment and Goal Setting', 'duration' => 1.0],
                ['title' => 'Time Management Techniques', 'duration' => 1.5],
                ['title' => 'Productivity Systems', 'duration' => 1.8],
                ['title' => 'Communication Skills', 'duration' => 2.0],
                ['title' => 'Emotional Intelligence', 'duration' => 1.7],
                ['title' => 'Stress Management', 'duration' => 1.3],
                ['title' => 'Building Habits', 'duration' => 1.5],
                ['title' => 'Networking and Relationships', 'duration' => 1.8],
                ['title' => 'Continuous Learning', 'duration' => 1.2],
                ['title' => 'Action Planning', 'duration' => 1.0]
            ],
            'technology' => [
                ['title' => 'Technology Overview', 'duration' => 1.0],
                ['title' => 'Infrastructure Basics', 'duration' => 2.0],
                ['title' => 'Security Fundamentals', 'duration' => 2.5],
                ['title' => 'Network Configuration', 'duration' => 2.2],
                ['title' => 'System Administration', 'duration' => 2.8],
                ['title' => 'Monitoring and Maintenance', 'duration' => 2.0],
                ['title' => 'Troubleshooting Techniques', 'duration' => 2.3],
                ['title' => 'Best Practices', 'duration' => 1.8],
                ['title' => 'Advanced Topics', 'duration' => 3.0],
                ['title' => 'Hands-on Projects', 'duration' => 3.5]
            ]
        ];

        // Map course titles to lesson templates
        $courseTemplateMapping = [
            'Complete Web Development Bootcamp' => 'web_development',
            'React.js for Beginners' => 'react',
            'Advanced Node.js Development' => 'nodejs',
            'iOS Development with Swift' => 'mobile',
            'Flutter Cross-Platform Development' => 'mobile',
            'Complete UI/UX Design Course' => 'design',
            'Figma for Designers' => 'design',
            'Digital Marketing Masterclass' => 'marketing',
            'SEO Optimization Complete Guide' => 'marketing',
            'Python for Data Science' => 'data_science',
            'Machine Learning A-Z' => 'data_science',
            'Entrepreneurship Fundamentals' => 'business',
            'Project Management with Agile' => 'business',
            'Productivity Mastery' => 'personal_development',
            'Leadership Skills Development' => 'personal_development',
            'Cybersecurity Fundamentals' => 'technology',
            'AWS Cloud Computing' => 'technology'
        ];

        $totalLessons = 0;
        $allLessons = [];
        
        foreach ($courses as $course) {
            // Determine which template to use
            $templateKey = $courseTemplateMapping[$course['title']] ?? 'web_development';
            $lessonTemplate = $lessonTemplates[$templateKey];
            
            // Create lessons for this course
            foreach ($lessonTemplate as $index => $lessonData) {
                $lesson = [
                    'course_id' => $course['id'],
                    'title' => $lessonData['title'],
                    'video_url' => 'https://example.com/videos/' . strtolower(str_replace([' ', '-', '&', '+'], ['_', '_', 'and', 'plus'], $lessonData['title'])) . '.mp4',
                    'duration' => $lessonData['duration'],
                    'order' => $index + 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $allLessons[] = $lesson;
                $totalLessons++;
            }
        }
        
        // Insert all lessons using database builder
        if (!empty($allLessons)) {
            $db = \Config\Database::connect();
            $batchSize = 100;
            $batches = array_chunk($allLessons, $batchSize);
            
            foreach ($batches as $batch) {
                $db->table('lessons')->insertBatch($batch);
            }
        }

        echo "LessonSeeder completed: Created {$totalLessons} lessons for " . count($courses) . " courses.\n";
    }
}
