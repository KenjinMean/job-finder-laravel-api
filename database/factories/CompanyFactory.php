<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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

        $description = "
        Company Description:
        XYZ Tech Solutions is a leading technology company at the forefront of innovation and digital transformation. We specialize in providing cutting-edge IT solutions and services to businesses of all sizes, helping them achieve their strategic goals and stay competitive in the fast-paced digital landscape.
        
        Our Mission:
        At XYZ Tech Solutions, our mission is to empower organizations with the tools and expertise they need to thrive in the digital age. We are committed to delivering top-notch technology solutions that drive efficiency, productivity, and growth for our clients.
        
        Key Offerings:
        Custom Software Development: We design and develop tailor-made software applications that streamline processes and improve business operations.
        
        Cloud Services: Our cloud experts offer scalable and secure cloud solutions, enabling businesses to embrace the cloud with confidence.
        
        Cybersecurity: Protecting your data and assets is our priority. Our cybersecurity solutions ensure your organization remains resilient against cyber threats.
        
        IT Consulting: Our experienced consultants provide strategic guidance, helping you make informed technology decisions that align with your business objectives.
        
        Managed IT Services: We offer proactive monitoring and support to keep your IT infrastructure running smoothly, reducing downtime and minimizing disruptions.
        
        Data Analytics: Our data analytics solutions help you turn data into actionable insights, enabling data-driven decision-making.
        
        Why Choose XYZ Tech Solutions:
        
        Expertise: Our team consists of highly skilled and certified professionals who are passionate about technology and committed to delivering exceptional results.
        
        Innovation: We stay ahead of industry trends and emerging technologies to provide forward-thinking solutions.
        
        Client-Centric Approach: Your success is our success. We work closely with clients to understand their unique needs and tailor our solutions accordingly.
        
        Reliability: With a track record of successful projects and satisfied clients, you can trust us to deliver on our promises.
        
        Contact Us:
        
        If you're looking to leverage the power of technology to drive your business forward, XYZ Tech Solutions is your ideal partner. Contact us today to discuss how we can work together to achieve your IT goals.";

        return [
            'name' => fake()->company,
            'company_logo' => "storage/company_logos/" . fake()->image(storage_path('app/public/company_logos'), 200, 200, null, false),
            'location' => fake()->city,
            'website' => fake()->url,
            'description' => $description,
            'industry' => fake()->jobTitle,
            'user_id' => function () {
                return User::inRandomOrder()->first()->id;
            },
        ];
    }
}
