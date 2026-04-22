<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@tukubuku.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Admin two',
            'email' => 'admin2@tukubuku.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $users = [
            ['name' => 'User Test', 'email' => 'user@test.com'],
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@test.com'],
            ['name' => 'Siti Aminah', 'email' => 'siti@test.com'],
            ['name' => 'Budi Santoso', 'email' => 'budi@test.com'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@test.com'],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'role' => 'user',
            ]);
        }

        $books = [
            ['title' => 'Laravel: From Apprentice To Artisan', 'author' => 'Taylor Otwell', 'description' => 'A comprehensive guide to mastering Laravel framework.', 'price' => 150000, 'stock' => 10],
            ['title' => 'Modern PHP', 'author' => 'Josh Lockhart', 'description' => 'New features and good practices in modern PHP.', 'price' => 120000, 'stock' => 15],
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'description' => 'A Handbook of Agile Software Craftsmanship.', 'price' => 175000, 'stock' => 8],
            ['title' => 'The Pragmatic Programmer', 'author' => 'David Thomas & Andrew Hunt', 'description' => 'Your journey to mastery in software development.', 'price' => 200000, 'stock' => 5],
            ['title' => 'Design Patterns', 'author' => 'Gang of Four', 'description' => 'Elements of Reusable Object-Oriented Software.', 'price' => 180000, 'stock' => 7],
            ['title' => 'Refactoring', 'author' => 'Martin Fowler', 'description' => 'Improving the Design of Existing Code.', 'price' => 165000, 'stock' => 12],
            ['title' => 'JavaScript: The Good Parts', 'author' => 'Douglas Crockford', 'description' => 'Most features of JavaScript are good parts.', 'price' => 95000, 'stock' => 20],
            ['title' => 'You Don\'t Know JS', 'author' => 'Kyle Simpson', 'description' => 'A deep dive into the core mechanisms of JavaScript.', 'price' => 140000, 'stock' => 18],
            ['title' => 'Python Crash Course', 'author' => 'Eric Matthes', 'description' => 'A fast-paced, thorough introduction to Python.', 'price' => 160000, 'stock' => 25],
            ['title' => 'Learn Python the Hard Way', 'author' => 'Zed A. Shaw', 'description' => 'A very simple introduction to programming with Python.', 'price' => 130000, 'stock' => 14],
            ['title' => 'Introduction to Algorithms', 'author' => 'Thomas H. Cormen', 'description' => 'Comprehensive introduction to the modern study of algorithms.', 'price' => 250000, 'stock' => 6],
            ['title' => 'Structure and Interpretation of Computer Programs', 'author' => 'Harold Abelson & Gerald Jay Sussman', 'description' => 'Classic computer science textbook.', 'price' => 190000, 'stock' => 4],
            ['title' => 'Code Complete', 'author' => 'Steve McConnell', 'description' => 'A Practical Handbook of Software Construction.', 'price' => 210000, 'stock' => 9],
            ['title' => 'The Clean Coder', 'author' => 'Robert C. Martin', 'description' => 'A Code of Conduct for Professional Programmers.', 'price' => 155000, 'stock' => 11],
            ['title' => 'Head First Design Patterns', 'author' => 'Eric Freeman & Elisabeth Robson', 'description' => 'A Brain-Friendly Guide to Design Patterns.', 'price' => 170000, 'stock' => 13],
            ['title' => 'Effective Java', 'author' => 'Joshua Bloch', 'description' => 'Best practices for the Java platform.', 'price' => 185000, 'stock' => 16],
            ['title' => 'The Mythical Man-Month', 'author' => 'Frederick P. Brooks Jr.', 'description' => 'Essays on Software Engineering.', 'price' => 145000, 'stock' => 3],
            ['title' => 'Working Effectively with Legacy Code', 'author' => 'Michael Feathers', 'description' => 'Strategies for working with existing codebase.', 'price' => 175000, 'stock' => 7],
            ['title' => 'Continuous Delivery', 'author' => 'Jez Humble & David Farley', 'description' => 'Reliable Software Releases through Build, Test, and Deployment.', 'price' => 195000, 'stock' => 5],
            ['title' => 'The DevOps Handbook', 'author' => 'Gene Kim, Jez Humble, Patrick Debois, John Willis', 'description' => 'How to Create World-Class Agility, Reliability, and Security.', 'price' => 220000, 'stock' => 8],
        ];

        $categories = ['Programming', 'Technology', 'Self-Help', 'Design', 'Business'];

        foreach ($books as $book) {
            $book['slug'] = Str::slug($book['title']);
            $book['category'] = $categories[array_rand($categories)];
            $book['rating'] = rand(35, 50) / 10;
            $book['reviews_count'] = rand(10, 500);
            Book::create($book);
        }
    }
}
