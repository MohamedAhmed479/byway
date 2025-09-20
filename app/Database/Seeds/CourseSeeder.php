<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\CourseModel;
use App\Models\UserModel;
use App\Models\CategoryModel;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $courseModel = new CourseModel();
        $userModel = new UserModel();
        $categoryModel = new CategoryModel();
        
        // Get instructors using database builder
        $db = \Config\Database::connect();
        $instructors = $db->table('users')->where('role', 'instructor')->get()->getResultArray();
        $instructorIds = array_column($instructors, 'id');
        
        // Get categories (we'll use subcategories for more specific course placement)
        $categories = $db->table('categories')->where('parent_id !=', 0)->get()->getResultArray();
        $categoryMap = [];
        foreach ($categories as $category) {
            $categoryMap[$category['name']] = $category['id'];
        }
        
        // Course data with realistic content
        $courses = [
            // Web Development Courses
            [
                'title' => 'Complete Web Development Bootcamp',
                'description' => 'Learn HTML, CSS, JavaScript, Node.js, and MongoDB to become a full-stack web developer. This comprehensive course covers everything from basic web fundamentals to advanced backend development.',
                'image_url' => 'https://via.placeholder.com/400x225/007bff/ffffff?text=Web+Dev+Bootcamp',
                'video_url' => 'https://example.com/videos/web-dev-intro.mp4',
                'price' => 89.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['Web Development'],
                'status' => 'published',
                'thumbnail' => 'https://via.placeholder.com/200x150/007bff/ffffff?text=Web+Dev',
                'duration' => 40.5
            ],
            [
                'title' => 'React.js for Beginners',
                'description' => 'Master the fundamentals of React.js including components, props, state, hooks, and routing. Build real-world projects and learn modern React development practices.',
                'image_url' => 'https://via.placeholder.com/400x225/61dafb/000000?text=React.js',
                'video_url' => 'https://example.com/videos/react-intro.mp4',
                'price' => 49.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['Web Development'],
                'status' => 'published',
                'thumbnail' => 'https://via.placeholder.com/200x150/61dafb/000000?text=React',
                'duration' => 25.0
            ],
            [
                'title' => 'Advanced Node.js Development',
                'description' => 'Deep dive into Node.js with Express.js, authentication, database integration, API development, and deployment strategies. Perfect for experienced developers.',
                'image_url' => 'https://via.placeholder.com/400x225/339933/ffffff?text=Node.js+Advanced',
                'video_url' => 'https://example.com/videos/nodejs-advanced.mp4',
                'price' => 79.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['Web Development'],
                'status' => 'published',
                'thumbnail' => 'https://via.placeholder.com/200x150/339933/ffffff?text=Node.js',
                'duration' => 35.5
            ],

            // Mobile Development Courses
            [
                'title' => 'iOS Development with Swift',
                'description' => 'Learn iOS app development from scratch using Swift and Xcode. Build native iOS applications with modern UI design patterns and best practices.',
                'image_url' => 'https://via.placeholder.com/400x225/000000/ffffff?text=iOS+Swift',
                'video_url' => 'https://example.com/videos/ios-swift.mp4',
                'price' => 69.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['Mobile Development'],
                'status' => 'published',
                'thumbnail' => 'https://via.placeholder.com/200x150/000000/ffffff?text=iOS',
                'duration' => 30.0
            ],
            [
                'title' => 'Flutter Cross-Platform Development',
                'description' => 'Build beautiful mobile apps for both iOS and Android using Flutter and Dart. Learn widgets, state management, and app deployment.',
                'image_url' => 'https://via.placeholder.com/400x225/02569b/ffffff?text=Flutter',
                'video_url' => 'https://example.com/videos/flutter-intro.mp4',
                'price' => 59.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['Mobile Development'],
                'status' => 'published',
                'thumbnail' => 'https://via.placeholder.com/200x150/02569b/ffffff?text=Flutter',
                'duration' => 28.5
            ],

            // UI/UX Design Courses
            [
                'title' => 'Complete UI/UX Design Course',
                'description' => 'Master user interface and user experience design principles. Learn Figma, design systems, prototyping, and user research methodologies.',
                'image_url' => 'https://via.placeholder.com/400x225/ff6b6b/ffffff?text=UI+UX+Design',
                'video_url' => 'https://example.com/videos/uiux-complete.mp4',
                'price' => 64.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['UI/UX Design'],
                'status' => 'published',
                'thumbnail' => 'https://via.placeholder.com/200x150/ff6b6b/ffffff?text=UI%2FUX',
                'duration' => 32.0
            ],
            [
                'title' => 'Figma for Designers',
                'description' => 'Learn Figma from basics to advanced features. Create professional designs, prototypes, and collaborate effectively with development teams.',
                'image_url' => 'https://via.placeholder.com/400x225/f24e1e/ffffff?text=Figma+Course',
                'video_url' => 'https://example.com/videos/figma-mastery.mp4',
                'price' => 39.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['UI/UX Design'],
                'status' => 'published',
                'thumbnail' => 'https://via.placeholder.com/200x150/f24e1e/ffffff?text=Figma',
                'duration' => 18.5
            ],

            // Digital Marketing Courses
            [
                'title' => 'Digital Marketing Masterclass',
                'description' => 'Comprehensive digital marketing course covering SEO, social media marketing, content marketing, email marketing, and paid advertising strategies.',
                'image_url' => 'https://via.placeholder.com/400x225/4285f4/ffffff?text=Digital+Marketing',
                'video_url' => 'https://example.com/videos/digital-marketing.mp4',
                'price' => 54.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['Digital Marketing'],
                'status' => 'published',
                'thumbnail' => 'https://via.placeholder.com/200x150/4285f4/ffffff?text=Marketing',
                'duration' => 26.0
            ],
            [
                'title' => 'SEO Optimization Complete Guide',
                'description' => 'Master search engine optimization with on-page SEO, off-page SEO, technical SEO, keyword research, and analytics. Boost your website rankings.',
                'image_url' => 'https://via.placeholder.com/400x225/34a853/ffffff?text=SEO+Guide',
                'video_url' => 'https://example.com/videos/seo-guide.mp4',
                'price' => 44.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['Digital Marketing'],
                'status' => 'published',
                'thumbnail' => 'https://via.placeholder.com/200x150/34a853/ffffff?text=SEO',
                'duration' => 20.5
            ],

            // Data Science Courses
            [
                'title' => 'Python for Data Science',
                'description' => 'Learn Python programming for data science with pandas, numpy, matplotlib, and scikit-learn. Analyze data and build machine learning models.',
                'image_url' => 'https://via.placeholder.com/400x225/3776ab/ffffff?text=Python+Data+Science',
                'video_url' => 'https://example.com/videos/python-datascience.mp4',
                'price' => 74.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['Data Analysis'],
                'status' => 'published',
                'thumbnail' => 'https://via.placeholder.com/200x150/3776ab/ffffff?text=Python+DS',
                'duration' => 42.0
            ],
            [
                'title' => 'Machine Learning A-Z',
                'description' => 'Complete machine learning course covering supervised learning, unsupervised learning, deep learning, and real-world ML projects.',
                'image_url' => 'https://via.placeholder.com/400x225/ff9800/ffffff?text=Machine+Learning',
                'video_url' => 'https://example.com/videos/ml-complete.mp4',
                'price' => 84.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['Machine Learning'],
                'status' => 'published',
                'thumbnail' => 'https://via.placeholder.com/200x150/ff9800/ffffff?text=ML',
                'duration' => 38.5
            ],

            // Business Courses
            [
                'title' => 'Entrepreneurship Fundamentals',
                'description' => 'Learn how to start and grow a successful business. Cover business planning, funding, marketing, operations, and scaling strategies.',
                'image_url' => 'https://via.placeholder.com/400x225/9c27b0/ffffff?text=Entrepreneurship',
                'video_url' => 'https://example.com/videos/entrepreneurship.mp4',
                'price' => 49.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['Entrepreneurship'],
                'status' => 'published',
                'thumbnail' => 'https://via.placeholder.com/200x150/9c27b0/ffffff?text=Business',
                'duration' => 24.0
            ],
            [
                'title' => 'Project Management with Agile',
                'description' => 'Master project management using Agile and Scrum methodologies. Learn sprint planning, user stories, and team collaboration techniques.',
                'image_url' => 'https://via.placeholder.com/400x225/795548/ffffff?text=Agile+PM',
                'video_url' => 'https://example.com/videos/agile-pm.mp4',
                'price' => 59.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['Project Management'],
                'status' => 'published',
                'thumbnail' => 'https://via.placeholder.com/200x150/795548/ffffff?text=Agile',
                'duration' => 22.5
            ],

            // Personal Development
            [
                'title' => 'Productivity Mastery',
                'description' => 'Boost your productivity with time management techniques, goal setting, habit formation, and workflow optimization strategies.',
                'image_url' => 'https://via.placeholder.com/400x225/607d8b/ffffff?text=Productivity',
                'video_url' => 'https://example.com/videos/productivity.mp4',
                'price' => 34.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['Productivity'],
                'status' => 'published',
                'thumbnail' => 'https://via.placeholder.com/200x150/607d8b/ffffff?text=Productivity',
                'duration' => 16.0
            ],
            [
                'title' => 'Leadership Skills Development',
                'description' => 'Develop essential leadership skills including team management, communication, decision-making, and conflict resolution.',
                'image_url' => 'https://via.placeholder.com/400x225/e91e63/ffffff?text=Leadership',
                'video_url' => 'https://example.com/videos/leadership.mp4',
                'price' => 44.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['Leadership'],
                'status' => 'published',
                'thumbnail' => 'https://via.placeholder.com/200x150/e91e63/ffffff?text=Leadership',
                'duration' => 19.5
            ],

            // Technology & IT
            [
                'title' => 'Cybersecurity Fundamentals',
                'description' => 'Learn cybersecurity basics including network security, threat analysis, risk management, and security best practices.',
                'image_url' => 'https://via.placeholder.com/400x225/f44336/ffffff?text=Cybersecurity',
                'video_url' => 'https://example.com/videos/cybersecurity.mp4',
                'price' => 69.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['Cybersecurity'],
                'status' => 'published',
                'thumbnail' => 'https://via.placeholder.com/200x150/f44336/ffffff?text=Security',
                'duration' => 31.0
            ],
            [
                'title' => 'AWS Cloud Computing',
                'description' => 'Master Amazon Web Services with EC2, S3, RDS, Lambda, and other core services. Learn cloud architecture and deployment strategies.',
                'image_url' => 'https://via.placeholder.com/400x225/232f3e/ffffff?text=AWS+Cloud',
                'video_url' => 'https://example.com/videos/aws-cloud.mp4',
                'price' => 79.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['Cloud Computing'],
                'status' => 'published',
                'thumbnail' => 'https://via.placeholder.com/200x150/232f3e/ffffff?text=AWS',
                'duration' => 36.0
            ],

            // Some draft courses
            [
                'title' => 'Advanced React Patterns',
                'description' => 'Learn advanced React patterns including render props, higher-order components, custom hooks, and performance optimization techniques.',
                'image_url' => 'https://via.placeholder.com/400x225/61dafb/000000?text=Advanced+React',
                'video_url' => 'https://example.com/videos/advanced-react.mp4',
                'price' => 89.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['Web Development'],
                'status' => 'draft',
                'thumbnail' => 'https://via.placeholder.com/200x150/61dafb/000000?text=React+Adv',
                'duration' => 28.0
            ],
            [
                'title' => 'Blockchain Development',
                'description' => 'Learn blockchain technology, smart contracts, and decentralized application development using Ethereum and Solidity.',
                'image_url' => 'https://via.placeholder.com/400x225/627eea/ffffff?text=Blockchain',
                'video_url' => 'https://example.com/videos/blockchain.mp4',
                'price' => 99.99,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'category_id' => $categoryMap['Web Development'],
                'status' => 'pending_approval',
                'thumbnail' => 'https://via.placeholder.com/200x150/627eea/ffffff?text=Blockchain',
                'duration' => 45.0
            ]
        ];

        // Insert courses using database builder
        $db = \Config\Database::connect();
        foreach ($courses as &$course) {
            $course['created_at'] = date('Y-m-d H:i:s');
            $course['updated_at'] = date('Y-m-d H:i:s');
        }
        $db->table('courses')->insertBatch($courses);

        echo "CourseSeeder completed: Created " . count($courses) . " courses across different categories.\n";
    }
}
