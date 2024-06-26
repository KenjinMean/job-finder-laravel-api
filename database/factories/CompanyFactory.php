<?php

namespace Database\Factories;

use App\Models\CompanySizeCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {

        $companyDescriptionElements = [
            "We are a leading",
            "Innovative and forward-thinking",
            "Cutting-edge",
            "Dynamic and vibrant",
            "Global",
            "Passionate about",
            "Dedicated to",
            "Committed to excellence in",
            "Pioneering",
            "Revolutionizing",
            "Tech-driven",
            "Customer-focused",
            "Creative",
            "Collaborative",
            "Results-oriented",
            "At the forefront of",
            "With a strong emphasis on",
            "Striving for excellence in",
            "Embracing the latest trends in",
            "Committed to delivering top-notch",
            "Pushing the boundaries in",
            "Fostering innovation in",
            "With a mission to redefine",
            "Committed to sustainability in",
            "Driven by a passion for",
            "Unleashing the potential of",
            "Aiming to disrupt the",
            "Championing innovation in",
            "With a relentless pursuit of excellence in",
            "Transforming industries through",
            "Creating a mark in",
            "Revitalizing",
            "Shaping the future of",
            "We are a leading technology company, specializing in the development and delivery of cutting-edge solutions that redefine industry standards.",
            "With a global presence, we are dedicated to fostering innovation and creating a dynamic work environment that encourages collaboration and creativity.",
            "At the forefront of technological advancements, our team is committed to excellence, pushing the boundaries to deliver unparalleled products and services.",
            "Driven by a passion for innovation, we strive to revolutionize the way businesses operate, providing solutions that are both innovative and sustainable.",
            "As a customer-focused organization, we are committed to delivering top-notch services and products, ensuring the satisfaction of our clients worldwide.",
            "Embracing the latest trends in technology, we are a dynamic and vibrant company that values creativity, collaboration, and a results-oriented approach.",
            "With a mission to redefine industries and transform the landscape of business, we pride ourselves on being pioneers in our field.",
            "Committed to sustainability in every aspect of our operations, we aim to create a positive impact on the world through our tech-driven initiatives.",
            "Fostering innovation in every department, we believe in the power of collaboration and are dedicated to creating a workplace that inspires creativity and growth.",
            "With a relentless pursuit of excellence in everything we do, we are shaping the future of technology and leaving a lasting mark in our industry.",
            "Transforming industries through groundbreaking solutions, we are a creative and collaborative company that values diversity and inclusivity.",
            "Creating a mark in the ever-evolving tech landscape, we are on a mission to revitalize businesses and empower them with state-of-the-art technologies.",
            "Shaping the future of digital innovation, our team is committed to revitalizing the tech scene with a focus on sustainability, collaboration, and excellence.",
            "Revitalizing the tech sector, we are on a journey to redefine the standards of excellence and lead the way in creating innovative solutions for the modern world.",
            "With a focus on customer satisfaction and technological excellence, we are on the cutting edge of innovation, creating solutions that drive success and growth.",
            "Pioneering advancements in technology, we are dedicated to creating a positive impact on society by delivering innovative solutions that address real-world challenges.",
            "At the intersection of technology and creativity, we strive to unleash the full potential of businesses by providing them with innovative and transformative solutions.",
            "Revolutionizing the tech landscape, we are a dedicated team of professionals working together to bring about positive change through groundbreaking technological advancements.",
            "Championing innovation in the digital era, our company is committed to creating a future where technology seamlessly integrates with everyday life, transforming the way we live and work.",
        ];
        $randomNumber = fake()->numberBetween(6, 10);
        shuffle($companyDescriptionElements);
        $randomElements = array_slice($companyDescriptionElements, 0, $randomNumber);
        $combinedDescription = implode(" ", $randomElements);

        // Ensure the directory exists
        if (!Storage::disk('public')->exists('company_logos')) {
            Storage::disk('public')->makeDirectory('company_logos');
        }

        return [
            'name' => fake()->company,
            'company_logo' => "storage/company_logos/" . fake()->image(storage_path('app/public/company_logos'), 200, 200, null, false),
            'location' => fake()->city,
            'website' => fake()->url,
            'description' => $combinedDescription,
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'user_id' => function () {
                return User::inRandomOrder()->first()->id;
            },
            'company_size_id' => function () {
                return CompanySizeCategory::inRandomOrder()->first()->id;
            }
        ];
    }
}
