<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer with Bootstrap Icons</title>
    <style>
        footer {
          
            padding: 40px 0;
        }

        footer h5 {
            font-weight: 600;
        }

        footer a {
            color: #6c757d;
            text-decoration: none;
        }

        footer a:hover {
            color: #007bff;
            text-decoration: underline;
        }

        .social-icons a {
            font-size: 1.5rem;
            color: #6c757d;
            margin: 0 15px;
        }

        .social-icons a:hover {
            color: #007bff;
        }
    </style>
</head>

<body>

<hr class="mt-5"/>

    <!-- Footer -->
    <footer class="text-center text-lg-start bg-body-tertiary text-muted">
        <!-- Section: Social media -->
        <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
            <!-- Left -->
            <div class="me-5 d-none d-lg-block">
                <span>Get connected with us on social networks:</span>
            </div>
            <!-- Left -->

            <!-- Right -->
            <div class="social-icons">
                <a href="#" class="me-4 text-reset">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="#" class="me-4 text-reset">
                    <i class="bi bi-twitter"></i>
                </a>
                <a href="#" class="me-4 text-reset">
                    <i class="bi bi-google"></i>
                </a>
                <a href="#" class="me-4 text-reset">
                    <i class="bi bi-instagram"></i>
                </a>
                <a href="#" class="me-4 text-reset">
                    <i class="bi bi-linkedin"></i>
                </a>
                <a href="#" class="me-4 text-reset">
                    <i class="bi bi-github"></i>
                </a>
            </div>
            <!-- Right -->
        </section>
        <!-- Section: Social media -->

        <!-- Section: Links -->
        <section class="">
            <div class="container text-center text-md-start mt-5">
                <!-- Grid row -->
                <div class="row mt-3">
                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                        <!-- Content -->
                        <h6 class="text-uppercase fw-bold mb-4">
                            <i class="bi bi-gem me-3"></i>Company name
                        </h6>
                        <p>
                            Here you can use rows and columns to organize your footer content. Lorem ipsum
                            dolor sit amet, consectetur adipisicing elit.
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">
                            Products
                        </h6>
                        <p>
                            <a href="#!" class="text-reset">Angular</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">React</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Vue</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Laravel</a>
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">
                            Useful links
                        </h6>
                        <p>
                            <a href="#!" class="text-reset">Pricing</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Settings</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Orders</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Help</a>
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
                        <p><i class="bi bi-house-door me-3"></i> New York, NY 10012, US</p>
                        <p>
                            <i class="bi bi-envelope me-3"></i>
                            info@example.com
                        </p>
                        <p><i class="bi bi-telephone me-3"></i> + 01 234 567 88</p>
                        <p><i class="bi bi-printer me-3"></i> + 01 234 567 89</p>
                    </div>
                    <!-- Grid column -->
                </div>
                <!-- Grid row -->
            </div>
        </section>
        <!-- Section: Links -->

        <!-- Copyright -->
        <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
            © 2021 Copyright:
            <a class="text-reset fw-bold" href="https://mdbootstrap.com/">MDBootstrap.com</a>
        </div>
        <!-- Copyright -->
    </footer>
    <!-- Footer -->
</body>

</html>