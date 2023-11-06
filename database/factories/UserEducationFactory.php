<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserEducation>
 */
class UserEducationFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $academicDegrees = [
            'Associate of Arts (AA)',
            'Associate of Science (AS)',
            'Associate of Applied Science (AAS)',
            'Associate of Fine Arts (AFA)',
            'Associate of Occupational Studies (AOS)',
            'Bachelor of Arts (BA)',
            'Bachelor of Science (BS)',
            'Bachelor of Fine Arts (BFA)',
            'Bachelor of Business Administration (BBA)',
            'Bachelor of Engineering (BEng)',
            'Bachelor of Technology (BTech)',
            'Bachelor of Education (BEd)',
            'Bachelor of Music (BM)',
            'Bachelor of Nursing (BSN)',
            'Bachelor of Social Work (BSW)',
            'Master of Arts (MA)',
            'Master of Science (MS)',
            'Master of Business Administration (MBA)',
            'Master of Fine Arts (MFA)',
            'Master of Engineering (MEng)',
            'Master of Laws (LLM)',
            'Master of Public Health (MPH)',
            'Master of Social Work (MSW)',
            'Master of Education (MEd)',
            'Master of Music (MM)',
            'Master of Public Administration (MPA)',
            'Master of Computer Science (MCS)',
            'Doctor of Philosophy (Ph.D.)',
            'Doctor of Medicine (MD)',
            'Doctor of Education (Ed.D.)',
            'Doctor of Science (Sc.D.)',
            'Doctor of Business Administration (DBA)',
            'Doctor of Engineering (Eng.D.)',
            'Doctor of Laws (LLD or LLD)',
            'Doctor of Public Health (DrPH)',
            'Doctor of Psychology (Psy.D.)',
            'Doctor of Dental Medicine (DMD)',
            'Doctor of Juridical Science (SJD)',
            'Doctor of Veterinary Medicine (DVM)',
            'Juris Doctor (JD)',
            'Doctor of Osteopathic Medicine (DO)',
            'Doctor of Dental Surgery (DDS)',
            'Doctor of Pharmacy (PharmD)',
            'Doctor of Nursing Practice (DNP)',
            'Certificate of Completion',
            'Graduate Certificate',
            'Post-Baccalaureate Certificate',
            'Diploma in (specific field)'
        ];

        $majorFields = [
            'Accounting',
            'Agricultural Science',
            'Anthropology',
            'Architecture',
            'Art and Design',
            'Biology',
            'Business Administration',
            'Chemistry',
            'Civil Engineering',
            'Computer Science',
            'Criminal Justice',
            'Dentistry',
            'Economics',
            'Education',
            'Electrical Engineering',
            'Environmental Science',
            'Finance',
            'Graphic Design',
            'History',
            'Human Resources',
            'Information Technology',
            'Journalism',
            'Law',
            'Marketing',
            'Mathematics',
            'Mechanical Engineering',
            'Medicine',
            'Music',
            'Nursing',
            'Philosophy',
            'Physics',
            'Political Science',
            'Psychology',
            'Sociology',
            'Software Engineering',
            'Theater and Performing Arts',
            'Visual Arts',
            'Web Development',
            'Other (Specify)'
        ];

        $graduationStatusOptions = [
            'Expected Graduation',
            'Graduated',
            'On Leave',
            'Suspended',
        ];

        return [
            'institution_name' => $this->faker->company(),
            'degree' => $this->faker->randomElement($academicDegrees),
            'major' => $this->faker->randomElement($majorFields),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'graduation_status' => $this->faker->randomElement($graduationStatusOptions),
            'user_id' => User::all()->random()->id,
        ];
    }
}
