<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Test\Fabricator;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Create admin user
        $adminData = [
            'name' => 'System Administrator',
            'email' => 'admin@byway.com',
            'password' => password_hash('admin123456', PASSWORD_DEFAULT),
            'role' => 'admin',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'profile_picture' => 'https://via.placeholder.com/150x150/007bff/ffffff?text=Admin',
            'bio' => 'System administrator with full access to manage the learning platform.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $db->table('users')->insert($adminData);

        // Create instructors
        $instructors = [
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@byway.com',
                'password' => 'instructor123',
                'role' => 'instructor',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'profile_picture' => 'https://via.placeholder.com/150x150/28a745/ffffff?text=SJ',
                'bio' => 'Computer Science professor with 10+ years of experience in web development and programming.',
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@byway.com',
                'password' => 'instructor123',
                'role' => 'instructor',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'profile_picture' => 'https://via.placeholder.com/150x150/dc3545/ffffff?text=MC',
                'bio' => 'Full-stack developer and tech entrepreneur. Specializes in modern JavaScript frameworks and cloud technologies.',
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily.rodriguez@byway.com',
                'password' => 'instructor123',
                'role' => 'instructor',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'profile_picture' => 'https://via.placeholder.com/150x150/6f42c1/ffffff?text=ER',
                'bio' => 'UI/UX Designer and Frontend Developer with a passion for creating beautiful and functional user experiences.',
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david.wilson@byway.com',
                'password' => 'instructor123',
                'role' => 'instructor',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'profile_picture' => 'https://via.placeholder.com/150x150/fd7e14/ffffff?text=DW',
                'bio' => 'Data Scientist and Machine Learning expert with extensive experience in Python and AI technologies.',
            ],
            [
                'name' => 'Lisa Thompson',
                'email' => 'lisa.thompson@byway.com',
                'password' => 'instructor123',
                'role' => 'instructor',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'profile_picture' => 'https://via.placeholder.com/150x150/e83e8c/ffffff?text=LT',
                'bio' => 'Digital Marketing strategist and content creator with expertise in social media and online business growth.',
            ]
        ];

        foreach ($instructors as &$instructor) {
            $instructor['password'] = password_hash($instructor['password'], PASSWORD_DEFAULT);
            $instructor['created_at'] = date('Y-m-d H:i:s');
            $instructor['updated_at'] = date('Y-m-d H:i:s');
        }
        $db->table('users')->insertBatch($instructors);

        // Create learners manually to avoid casting issues
        $learners = [];
        $firstNames = ['John', 'Jane', 'Mike', 'Sarah', 'David', 'Emily', 'Chris', 'Lisa', 'Mark', 'Anna', 'Tom', 'Maria', 'James', 'Linda', 'Robert'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson'];
        
        $bios = [
            'Passionate learner interested in technology and personal development.',
            'Student looking to expand skills in programming and digital literacy.',
            'Professional seeking to upskill in modern technologies.',
            'Entrepreneur interested in learning new business strategies.',
            'Creative individual exploring design and development.',
            'Recent graduate eager to learn practical skills.',
            'Career changer transitioning into tech industry.',
            'Lifelong learner with diverse interests.',
            'Freelancer looking to expand service offerings.',
            'Team lead wanting to stay current with trends.'
        ];
        
        for ($i = 0; $i < 50; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $name = $firstName . ' ' . $lastName;
            $email = strtolower($firstName . '.' . $lastName . rand(1, 999) . '@example.com');
            
            $learners[] = [
                'name' => $name,
                'email' => $email,
                'password' => password_hash('learner123', PASSWORD_DEFAULT),
                'role' => 'learner',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'profile_picture' => "https://via.placeholder.com/150x150/" . sprintf("%06x", mt_rand(0, 0xFFFFFF)) . "/ffffff?text=" . strtoupper(substr($name, 0, 2)),
                'bio' => $bios[$i % count($bios)],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        $db->table('users')->insertBatch($learners);

        echo "UserSeeder completed: Created 1 admin, 5 instructors, and 50 learners.\n";
    }
}
