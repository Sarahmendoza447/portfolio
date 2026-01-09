<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Page-wide Background */
        body {
            background: url('Landing_Page_Images/landing_page.png') no-repeat center center/cover;
            color: white;
            font-family: Arial, sans-serif;
        }

        /* Semi-transparent panels for sections */
        .content-panel {
            background-color: rgba(0, 0, 0, 0.7); /* Dark transparent background */
            border-radius: 10px;
            padding: 20px;
            gap: 50px;
            max-width: 840px; /* Limit the width to prevent it from stretching too much on larger screens */
            text-align: center;
            margin: 0 auto; /* Center the panel horizontally */
        }

        /* Navbar Styling */
        .navbar {
            background-color: rgba(51, 68, 85, 0.8); /* Transparent version of the hover color */
            margin-bottom: -150px;
        }

        .navbar-brand, .nav-link {
            color: #ffffff !important; /* White text for readability */
        }

        .nav-link:hover {
            color: #334455 !important; /* Khaki yellow for hover effect */
        }

        /* Footer Styling */
        .footer {
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: 50px; /* Added margin-top to add space between the content and footer */
        }

        /* Team Card Styling */
        .team-card img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
        }

        /* Description Section Styling */
        .description {
            color: white;
            margin-top: -200px;
            text-align: center;
            font-size: 40px; /* Adjust text size as needed */
            text-shadow:
                    2px 2px 4px rgba(0, 0, 0, 0.5),
                    -2px -2px 4px rgba(0, 0, 0, 0.5),
                    2px -2px 4px rgba(0, 0, 0, 0.5),
                    -2px 2px 4px rgba(0, 0, 0, 0.5); /* Added stroke effect using text-shadow */
        }

        /* Logo and Description Section Styling */
        .logo-section {
            text-align: center;
            border-radius: 10px;
            margin-top: 90px;
        }

        .logo-section img {
            width: 100%; /* Make the logo responsive */
            max-width: 550px; /* Limit the maximum width */
            height: auto; /* Maintain aspect ratio */
        }

        .red-button {
            display: block;
            width: 180px; /* Set a width for the button */
            margin: 20px auto; /* Center the button horizontally */
            padding: 10px 20px;
            background-color: #B81701; /* Red background color */
            color: black; /* White text */
            font-size: 26px;
            font-weight: bold;
            border: none;
            border-radius: 50px; /* Fully curved corners (half of the button's height) */
            cursor: pointer;
            text-align: center;
            position: relative; /* Needed for absolute positioning of the image */
            margin-bottom: 130px;
        }

        .red-button img {
            position: absolute;
            top: -12px; /* Position the image at the upper left of the button */
            left: -12px; /* Slight overlap */
            width: 50px; /* Size of the image */
            height: 50px;
            border-radius: 50%; /* Optional: make the image circular */
        }

        .red-button:hover {
            background-color: darkred; /* Darker red on hover */
        }

        /* Sections Styling */
        #about, #team, #contact {
            margin-top: 50px; /* Adjust this value as needed */
        }

        /* Navbar Divider Styling */
        .navbar-divider {
            border-left: 2px solid #ffffff;
            height: 30px;
            margin: 0 20px;
        }

        /* Optional: Adjust the button size and appearance */
        .navbar .btn {
            font-size: 16px;
            padding: 5px 20px;
            border-radius: 50px;
        }

        /* Responsive Design Adjustments */
        @media (max-width: 768px) {
            /* Stack content vertically on smaller screens */
            .navbar-collapse {
                text-align: center;
            }

            .logo-section img {
                width: 80%; /* Make logo smaller on mobile */
            }

            .description {
                font-size: 30px; /* Reduce font size on mobile */
            }

            .content-panel {
                width: 100%; /* Use full width on small screens */
                padding: 15px; /* Reduce padding on mobile */
            }

            .red-button {
                width: 70%; /* Make the button width smaller on mobile */
            }

            .team-card img {
                width: 80px; /* Reduce size of team images on mobile */
                height: 80px;
            }

            /* Remove the navbar divider on small screens */
            .navbar-divider {
                display: none;
            }

            /* Hide the satellite image on mobile */
            .red-button img {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .description {
                font-size: 24px; /* Further reduce font size on very small screens */
            }

            .red-button {
                width: 200px; /* Make the button take up the full width */
            }

            .team-card img {
                width: 60px; /* Smaller images on very small screens */
                height: 60px;
            }
            .description {
                margin-top: -90px;  /* Remove the margin-top */
            }
            .logo-section{
                margin-top: 180px;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">SILONG</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#team">Team</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            </ul>

            <span class="navbar-divider"></span>

            <!-- Login and Sign Up Buttons -->
            <a href="log.php?show_modal=true" class="btn btn-outline-light mx-2">Login</a>
            <a href="sign_up.php" class="btn btn-outline-light mx-2">Sign Up</a>
        </div>
    </div>
</nav>

<!-- Logo Section -->
<section id="logo-section" class="logo-section">
    <img src="Landing_Page_Images/Logo_title.png" alt="Logo">
    <div class="description">
        <p>System for Integrated Local<br>Operations and Natural Disaster<br>Guidance</p>
    </div>
    <button class="red-button" >
        <img src="Landing_Page_Images/Sattelite.png" alt="Overlapping Image">
        View Info
    </button>
</section>

<!-- About Us Section -->
<section id="about">
    <div class="content-panel">
        <h2>About Us</h2>
        <p>We are a team of passionate developers dedicated to building user-friendly and innovative applications. Our mission is to bring ideas to life through technology and creativity.</p>
    </div>
</section>

<!-- Developer Team Section -->
<!-- Developer Team Section -->
<section id="team">
    <div class="content-panel">
        <h2>Meet the Team</h2>
        <div class="row justify-content-center mt-4">
            <!-- Team Member 1 -->
            <div class="col-md-4">
                <div class="team-card">
                    <img src="User/images/mariel.jpg" alt="Team Member">
                    <h5 class="mt-3">Mariel</h5>
                    <p>Analyst</p>
                </div>
            </div>
            <!-- Team Member 2 -->
            <div class="col-md-4">
                <div class="team-card">
                    <img src="User/images/sarah.jpg" alt="Team Member">
                    <h5 class="mt-3">Sarah</h5>
                    <p>Backend Developer</p>
                </div>
            </div>
            <!-- Team Member 3 -->
            <div class="col-md-4">
                <div class="team-card">
                    <img src="User/images/abet.jpg" alt="Team Member">
                    <h5 class="mt-3">Everton</h5>
                    <p>UI/UX Designer</p>
                </div>
            </div>
            <!-- Team Member 4 -->
            <div class="col-md-4">
                <div class="team-card">
                    <img src="User/images/tel.jpg" alt="Team Member">
                    <h5 class="mt-3">Christel</h5>
                    <p>Tester</p>
                </div>
            </div>
            <!-- Team Member 5 -->
            <div class="col-md-4">
                <div class="team-card">
                    <img src="User/images/jocelle.jpg" alt="Team Member">
                    <h5 class="mt-3">Jocelle</h5>
                    <p>Librarian</p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Contact Us Section -->
<section id="contact">
    <div class="content-panel">
        <h2>Contact Us</h2>
        <p>Email: info@landingpage.com</p>
        <p>Phone: +123-456-7890</p>
        <p>Follow us on our social channels for updates and more information!</p>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <p>&copy; 2024 LandingPage. All Rights Reserved.</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>