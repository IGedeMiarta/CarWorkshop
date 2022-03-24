<div id="top"></div>
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
  <a href="https://github.com/github_username/repo_name">
    <img src="images/logo.png" alt="Logo" width="80" height="80">
  </a>

<h3 align="center">CarWorkshop</h3>

  <p align="center">
    this is interview project to Taksu Teknologi
    <br />
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

Here's a blank template to get started: To avoid retyping too much info. Do a search and replace with your text editor for the following: `github_username`, `repo_name`, `twitter_handle`, `linkedin_username`, `email_client`, `email`, `project_title`, `project_description`

<p align="right">(<a href="#top">back to top</a>)</p>



### Built With


* [Laravel](https://laravel.com)
* [Bootstrap](https://getbootstrap.com)
* [MailTrap](https://mailtrap.io)
* [JQuery](https://jquery.com)
* [SBAdmin](https://startbootstrap.com/theme/sb-admin-2)
* [DataTables](https://datatables.net)
* [SweetAlert2](https://sweetalert2.github.io)
* [Select2](https://select2.org)

<p align="right">(<a href="#top">back to top</a>)</p>



<!-- GETTING STARTED -->
## Getting Started

Car Workshop System

The Client is an owner of car workshops. This is how his traditional business is like:

Usually, a car owner will come to the workshop with his car. Then, the workshop will diagnose the car and suggest the services that a car owner should take, for example:
1. Change tyres
2. Fix dents
3. Change battery
4. Etc. 

After the car owner agrees to the proposal, the car owner will leave the car in the workshop for a few days while the workshop is fixing the car. The workshop will assign a few mechanics to fulfill the jobs. Each mechanic will perform a service. When all services are done, the workshop will call the car owner to collect the car. 

When the car owner arrives, he will inspect the jobs. If he is not satisfied with the job, the workshop needs to note the complaint and perform one or more services to address the dissatisfaction. Again, mechanics will be assigned to perform the services. 

When the car owner is satisfied with the job, he will take back the car and the workshop will send an invoice to the car owner. 

### Prerequisites

You Must Make Account in Mailtrap to get to send notification send via SMTP email, where it uses  Mailtrap SMTP Provider.




### Installation

1. Clone the repo
   ```sh
   git clone https://github.com/IGedeMiarta/CarWorkshop.git
   ```
2. Update Composer
   ```sh
   composer update
   ```
3. Generate JWT Secret
   ```sh
   php artisan jwt:secret
   ```
4. Configuration `.env` file
   ```sh
    APP_URL=http://localhost:8000
    ASSET_URL=http://localhost:8000

    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=
    MAIL_PASSWORD=
    MAIL_ENCRYPTION=
    MAIL_FROM_ADDRESS=support@carWorkshop.com
   ```
4. migrate database 
   ```sh
    php artisan migrate --seed
   ```
5. now you have user account role `admin`
    ```sh
    email : admin@mail.com
    pass  : password
    ```
<p align="right">(<a href="#top">back to top</a>)</p>


<!-- ROADMAP -->
## Roadmap

- [ ] User Login
- [ ] User Register
- [ ] Admin
    - [ ] Car owner
    - [ ] Car mechanic
    - [ ] Service
    - [ ] Status
    - [ ] Car Repair
- [ ] Mechanic
    - [ ] Work Order / Car Repair
- [ ] Owner
    - [ ] Car Repair Status




<p align="right">(<a href="#top">back to top</a>)</p>




<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[contributors-shield]: https://img.shields.io/github/contributors/github_username/repo_name.svg?style=for-the-badge
[contributors-url]: https://github.com/github_username/repo_name/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/github_username/repo_name.svg?style=for-the-badge
[forks-url]: https://github.com/github_username/repo_name/network/members
[stars-shield]: https://img.shields.io/github/stars/github_username/repo_name.svg?style=for-the-badge
[stars-url]: https://github.com/github_username/repo_name/stargazers
[issues-shield]: https://img.shields.io/github/issues/github_username/repo_name.svg?style=for-the-badge
[issues-url]: https://github.com/github_username/repo_name/issues
[license-shield]: https://img.shields.io/github/license/github_username/repo_name.svg?style=for-the-badge
[license-url]: https://github.com/github_username/repo_name/blob/master/LICENSE.txt
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=for-the-badge&logo=linkedin&colorB=555
[linkedin-url]: https://linkedin.com/in/linkedin_username
[product-screenshot]: images/screenshot.png