<!-- Improved compatibility of back to top link: See: https://github.com/othneildrew/Best-README-Template/pull/73 -->

<a name="readme-top"></a>

<!--
*** Thanks for checking out the Best-README-Template. If you have a suggestion
*** that would make this better, please fork the repo and create a pull request
*** or simply open an issue with the tag "enhancement".
*** Don't forget to give the project a star!
*** Thanks again! Now go create something AMAZING! :D
-->

<!-- PROJECT SHIELDS -->
<!--
*** I'm using markdown "reference style" links for readability.
*** Reference links are enclosed in brackets [ ] instead of parentheses ( ).
*** See the bottom of this document for the declaration of the reference variables
*** for contributors-url, forks-url, etc. This is an optional, concise syntax you may use.
*** https://www.markdownguide.org/basic-syntax/#reference-style-links
-->

[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]
[![LinkedIn][linkedin-shield]][linkedin-url]

<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/KenjinMean/job-finder-laravel-api">
    <img src="public/JobFinderLogo.png" alt="Logo" height="80">
  </a>

<h3 align="center">Job Finder Laravel API</h3>

  <p align="center">
    The Job Finder Backend API is the robust foundation powering the Job Finder web application. Built with Laravel, a leading PHP framework known for its elegance and efficiency, this API serves as the backbone for managing job listings, user authentication, and data interactions.
    <br />
    <a href="https://github.com/KenjinMean/job-finder-laravel-api"><strong>Explore the docs »</strong></a>
    <br />
    <br />
    <a href="https://github.com/KenjinMean/job-finder-laravel-api">View Demo</a>
    ·
    <a href="https://github.com/KenjinMean/job-finder-laravel-api/issues/new?labels=bug&template=bug-report---.md">Report Bug</a>
    ·
    <a href="https://github.com/KenjinMean/job-finder-laravel-api/issues/new?labels=enhancement&template=feature-request---.md">Request Feature</a>
  </p>
</div>

<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#acknowledgments">Acknowledgments</a></li>
  </ol>
</details>

<!-- ABOUT THE PROJECT -->

## About The Project

[![Product Name Screen Shot][product-screenshot]](https://example.com)

<!-- Here's a blank template to get started: To avoid retyping too much info. Do a search and replace with your text editor for the following: `KenjinMean`, `job-finder-laravel-api`, `twitter_handle`, `genesis-saquibal-9b99a5245`, `gmail.com`, `genesisjinsaquibal`, `Job Finder Laravel API`, `The Job Finder Backend API is the robust foundation powering the Job Finder web application. Built with Laravel, a leading PHP framework known for its elegance and efficiency, this API serves as the backbone for managing job listings, user authentication, and data interactions.` -->

<p align="right">(<a href="#readme-top">back to top</a>)</p>

### Built With

-   [![Laravel][Laravel.com]][Laravel-url]
-   [![MySQL][MySQL.com]][MySQL-url]
-   [![JWT][JWT.com]][JWT-url]

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- GETTING STARTED -->

## Getting Started

This README is still under construction, please 🐻 with me 🥺😊.

This is an example of how you may give instructions on setting up your project locally.
To get a local copy up and running follow these simple example steps.

### Prerequisites

### Installation

Before you start, please check the official Laravel installation guide for server requirements: [Official Documentation](https://laravel.com/docs/5.4/installation#installation).

1. **Clone the repository**:

    ```sh
    git clone https://github.com/KenjinMean/job-finder-laravel-api.git
    ```

2. **Install all dependencies using Composer**:

    ```sh
    composer install
    ```

3. **Copy the example environment file and make required configuration changes in the `.env` file**:

    ```sh
    cp .env.example .env
    ```

4. **Generate a new application key**:

    ```sh
    php artisan key:generate
    ```

5. **Generate a new JWT authentication secret key**:

    ```sh
    php artisan jwt:secret
    ```

6. **Create a symbolic link for the storage**:

    ```sh
    php artisan storage:link
    ```

7. **Run the database migrations** (**Set the database connection in `.env` before migrating**):

    ```sh
    php artisan migrate
    ```

8. **Start the local development server**:
    ```sh
    php artisan serve
    ```

You can now access the server api at [http://localhost:8000](http://localhost:8000/api).

## Database Seeding

Populate the database with seed data, including users, articles, comments, tags, favorites, and follows. This can help you quickly start testing the API or couple a frontend and start using it with ready content.

1. **Open `DatabaseSeeder.php` and set the property values as per your requirements**:

    ```php
    // Edit this file: database/seeders/DatabaseSeeder.php
    ```

2. **Run the database seeder**:
    ```sh
    php artisan db:seed
    ```

**Note**: It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running:
`sh
    php artisan migrate:refresh
    `

By following these steps, you'll set up your Laravel application and populate your database with sample data for testing and development.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

## Dependencies

-   [jwt-auth](https://github.com/PHP-Open-Source-Saver/jwt-auth.git) - For authentication using JSON Web Tokens

<!-- ROADMAP -->

## Roadmap

-   [ ] Implement Secure JWT token using cookies when already have access to https
-   [ ] Feature 2
-   [ ] Feature 3
    -   [ ] Nested Feature

See the [open issues](https://github.com/KenjinMean/job-finder-laravel-api/issues) for a full list of proposed features (and known issues).

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- CONTRIBUTING -->

## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- LICENSE -->

## License

Distributed under the MIT License. See `LICENSE.txt` for more information.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- CONTACT -->

## Contact

<!-- Your Name - [@twitter_handle](https://twitter.com/twitter_handle) - genesisjinsaquibal@gmail.com.com -->

Genesis Saquibal - genesisjinsaquibal@gmail.com.com

Project Link: [https://github.com/KenjinMean/job-finder-laravel-api](https://github.com/KenjinMean/job-finder-laravel-api)

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- ACKNOWLEDGMENTS -->

## Acknowledgments

Use this space to list resources you find helpful and would like to give credit to. I've included a few of my favorites to kick things off!

-   [Best README Template](https://github.com/othneildrew/Best-README-Template/tree/master)
-   [Choose an Open Source License](https://choosealicense.com)
-   [GitHub Emoji Cheat Sheet](https://www.webpagefx.com/tools/emoji-cheat-sheet)
-   [Malven's Flexbox Cheatsheet](https://flexbox.malven.co/)
-   [Malven's Grid Cheatsheet](https://grid.malven.co/)
-   [Img Shields](https://shields.io)
-   [GitHub Pages](https://pages.github.com)
-   [Font Awesome](https://fontawesome.com)
-   [React Icons](https://react-icons.github.io/react-icons/search)

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->

[contributors-shield]: https://img.shields.io/github/contributors/KenjinMean/job-finder-laravel-api.svg?style=for-the-badge
[contributors-url]: https://github.com/KenjinMean/job-finder-laravel-api/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/KenjinMean/job-finder-laravel-api.svg?style=for-the-badge
[forks-url]: https://github.com/KenjinMean/job-finder-laravel-api/network/members
[stars-shield]: https://img.shields.io/github/stars/KenjinMean/job-finder-laravel-api.svg?style=for-the-badge
[stars-url]: https://github.com/KenjinMean/job-finder-laravel-api/stargazers
[issues-shield]: https://img.shields.io/github/issues/KenjinMean/job-finder-laravel-api.svg?style=for-the-badge
[issues-url]: https://github.com/KenjinMean/job-finder-laravel-api/issues
[license-shield]: https://img.shields.io/github/license/KenjinMean/job-finder-laravel-api.svg?style=for-the-badge
[license-url]: https://github.com/KenjinMean/job-finder-laravel-api/blob/master/LICENSE.txt
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=for-the-badge&logo=linkedin&colorB=555
[linkedin-url]: https://linkedin.com/in/genesis-saquibal-9b99a5245
[product-screenshot]: public/jobFinderApiDocs.PNG
[Laravel.com]: https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white
[Laravel-url]: https://laravel.com
[MySQL.com]: https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white
[MySQL-url]: https://www.mysql.com/
[JWT.com]: https://img.shields.io/badge/JWT-black?style=for-the-badge&logo=JSON%20web%20tokens
[JWT-url]: https://github.com/PHP-Open-Source-Saver/jwt-auth
