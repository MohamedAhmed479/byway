<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\CategoryModel;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Temporarily disable foreign key checks
        $db->query('SET FOREIGN_KEY_CHECKS = 0');
        
        // Main categories (parent_id = null for root categories)
        $mainCategories = [
            [
                'name' => 'Programming & Development',
                'description' => 'Learn programming languages, frameworks, and software development practices.',
                'parent_id' => 0
            ],
            [
                'name' => 'Design & Creativity',
                'description' => 'Master visual design, user experience, and creative skills.',
                'parent_id' => 0
            ],
            [
                'name' => 'Business & Marketing',
                'description' => 'Develop business acumen, marketing strategies, and entrepreneurial skills.',
                'parent_id' => 0
            ],
            [
                'name' => 'Data Science & Analytics',
                'description' => 'Learn data analysis, machine learning, and statistical methods.',
                'parent_id' => 0
            ],
            [
                'name' => 'Personal Development',
                'description' => 'Improve productivity, communication, and life skills.',
                'parent_id' => 0
            ],
            [
                'name' => 'Technology & IT',
                'description' => 'Master IT infrastructure, cybersecurity, and emerging technologies.',
                'parent_id' => 0
            ]
        ];

        // Insert main categories and store their IDs
        $categoryIds = [];
        foreach ($mainCategories as $category) {
            $category['created_at'] = date('Y-m-d H:i:s');
            $category['updated_at'] = date('Y-m-d H:i:s');
            $db->table('categories')->insert($category);
            $categoryIds[$category['name']] = $db->insertID();
        }

        // Subcategories
        $subcategories = [
            // Programming & Development subcategories
            [
                'name' => 'Web Development',
                'description' => 'Frontend and backend web development technologies.',
                'parent_id' => $categoryIds['Programming & Development']
            ],
            [
                'name' => 'Mobile Development',
                'description' => 'iOS, Android, and cross-platform mobile app development.',
                'parent_id' => $categoryIds['Programming & Development']
            ],
            [
                'name' => 'Game Development',
                'description' => 'Create games using various engines and programming languages.',
                'parent_id' => $categoryIds['Programming & Development']
            ],
            [
                'name' => 'Software Testing',
                'description' => 'Quality assurance, automated testing, and debugging techniques.',
                'parent_id' => $categoryIds['Programming & Development']
            ],
            [
                'name' => 'DevOps & Deployment',
                'description' => 'CI/CD, containerization, and cloud deployment strategies.',
                'parent_id' => $categoryIds['Programming & Development']
            ],

            // Design & Creativity subcategories
            [
                'name' => 'UI/UX Design',
                'description' => 'User interface and user experience design principles.',
                'parent_id' => $categoryIds['Design & Creativity']
            ],
            [
                'name' => 'Graphic Design',
                'description' => 'Visual communication, branding, and print design.',
                'parent_id' => $categoryIds['Design & Creativity']
            ],
            [
                'name' => 'Web Design',
                'description' => 'Website layouts, responsive design, and visual aesthetics.',
                'parent_id' => $categoryIds['Design & Creativity']
            ],
            [
                'name' => '3D & Animation',
                'description' => '3D modeling, animation, and motion graphics.',
                'parent_id' => $categoryIds['Design & Creativity']
            ],
            [
                'name' => 'Photography',
                'description' => 'Digital photography, editing, and visual storytelling.',
                'parent_id' => $categoryIds['Design & Creativity']
            ],

            // Business & Marketing subcategories
            [
                'name' => 'Digital Marketing',
                'description' => 'Online marketing strategies, SEO, and social media.',
                'parent_id' => $categoryIds['Business & Marketing']
            ],
            [
                'name' => 'Entrepreneurship',
                'description' => 'Starting and growing a business, startup strategies.',
                'parent_id' => $categoryIds['Business & Marketing']
            ],
            [
                'name' => 'Project Management',
                'description' => 'Agile, Scrum, and traditional project management methodologies.',
                'parent_id' => $categoryIds['Business & Marketing']
            ],
            [
                'name' => 'Sales & Communication',
                'description' => 'Sales techniques, negotiation, and effective communication.',
                'parent_id' => $categoryIds['Business & Marketing']
            ],
            [
                'name' => 'Finance & Accounting',
                'description' => 'Financial planning, accounting principles, and investment strategies.',
                'parent_id' => $categoryIds['Business & Marketing']
            ],

            // Data Science & Analytics subcategories
            [
                'name' => 'Machine Learning',
                'description' => 'ML algorithms, deep learning, and artificial intelligence.',
                'parent_id' => $categoryIds['Data Science & Analytics']
            ],
            [
                'name' => 'Data Analysis',
                'description' => 'Statistical analysis, data visualization, and reporting.',
                'parent_id' => $categoryIds['Data Science & Analytics']
            ],
            [
                'name' => 'Big Data',
                'description' => 'Hadoop, Spark, and large-scale data processing.',
                'parent_id' => $categoryIds['Data Science & Analytics']
            ],
            [
                'name' => 'Business Intelligence',
                'description' => 'BI tools, dashboards, and data-driven decision making.',
                'parent_id' => $categoryIds['Data Science & Analytics']
            ],

            // Personal Development subcategories
            [
                'name' => 'Productivity',
                'description' => 'Time management, organization, and efficiency techniques.',
                'parent_id' => $categoryIds['Personal Development']
            ],
            [
                'name' => 'Leadership',
                'description' => 'Leadership skills, team management, and motivation.',
                'parent_id' => $categoryIds['Personal Development']
            ],
            [
                'name' => 'Communication Skills',
                'description' => 'Public speaking, writing, and interpersonal communication.',
                'parent_id' => $categoryIds['Personal Development']
            ],
            [
                'name' => 'Career Development',
                'description' => 'Resume building, interview skills, and career planning.',
                'parent_id' => $categoryIds['Personal Development']
            ],

            // Technology & IT subcategories
            [
                'name' => 'Cybersecurity',
                'description' => 'Information security, ethical hacking, and risk management.',
                'parent_id' => $categoryIds['Technology & IT']
            ],
            [
                'name' => 'Cloud Computing',
                'description' => 'AWS, Azure, Google Cloud, and cloud architecture.',
                'parent_id' => $categoryIds['Technology & IT']
            ],
            [
                'name' => 'Network Administration',
                'description' => 'Network setup, maintenance, and troubleshooting.',
                'parent_id' => $categoryIds['Technology & IT']
            ],
            [
                'name' => 'Database Management',
                'description' => 'SQL, NoSQL, database design, and administration.',
                'parent_id' => $categoryIds['Technology & IT']
            ]
        ];

        // Insert subcategories
        foreach ($subcategories as &$subcategory) {
            $subcategory['created_at'] = date('Y-m-d H:i:s');
            $subcategory['updated_at'] = date('Y-m-d H:i:s');
        }
        $db->table('categories')->insertBatch($subcategories);
        
        // Re-enable foreign key checks
        $db->query('SET FOREIGN_KEY_CHECKS = 1');

        echo "CategorySeeder completed: Created " . count($mainCategories) . " main categories and " . count($subcategories) . " subcategories.\n";
    }
}
