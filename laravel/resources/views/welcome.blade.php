<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ✅ Page Title -->
    <title>Bussaty | School Bus Management & Tracking App</title>

    <!-- ✅ Meta Description -->
    <meta name="description"
          content="Track your school buses live, send instant alerts to parents, and manage routes and drivers easily with Bussaty’s smart apps for schools, parents, and drivers.">

    <!-- Favicon for browsers -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/icons/favicon-16x16.png') }}">

    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/icons/apple-touch-icon.png') }}">

    <!-- Manifest for PWA -->
    <link rel="manifest" href="/site.webmanifest">

    <!-- ✅ Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- ✅ Open Graph Meta Tags -->
    <meta property="og:title" content="Bussaty | School Bus Management & Tracking App">
    <meta property="og:description"
          content="Track your school buses live, send instant alerts to parents, and manage routes and drivers easily with Bussaty’s smart apps for schools, parents, and drivers.">
    <meta property="og:image" content="{{ asset('assets/hero.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- ✅ Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Bussaty | School Bus Management & Tracking App">
    <meta name="twitter:description"
          content="Track your school buses live, send instant alerts to parents, and manage routes and drivers easily with Bussaty’s smart apps for schools, parents, and drivers.">
    <meta name="twitter:image" content="{{ asset('assets/hero.png') }}">

    <!-- ✅ Favicon -->
    <link rel="icon" href="{{ asset('assets/icons/bus-logo.png') }}" type="image/png">

    <!-- ✅ Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Cairo:wght@400;700&display=swap"
          rel="stylesheet">

    <!-- ✅ Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Additional SEO -->
    <meta name="robots" content="index, follow">

    <!-- ✅ AOS CSS (escape @) -->
    <link href="https://unpkg.com/aos@@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Cairo', 'Nunito', sans-serif; }
        .hero { background: linear-gradient(135deg, #0d6efd 0%, #00b3ff 100%); color: #fff; padding: 120px 0; text-align: center; }
        .hero h1 { font-size: 48px; font-weight: 800; }
        .hero p { font-size: 20px; max-width: 700px; margin: 0 auto 30px; }
        .features .card { border: none; transition: all 0.3s ease; }
        .features .card:hover { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }
        footer { background: #0d6efd; color: #fff; padding: 20px 0; text-align: center; }
        .navbar-brand img { border-radius: 8px; }
        .navbar .nav-link { color: #333; font-weight: 600; transition: color 0.2s ease-in-out; }
        .navbar .nav-link:hover { color: #0d6efd; }
        .navbar .btn-primary { border-radius: 50px; }
        .hero-title { font-size: 48px; font-weight: 800; }
        .hero-subtitle { font-size: 20px; max-width: 600px; }
        .hero a.btn { border-radius: 50px; font-weight: 600; transition: all 0.3s ease-in-out; }
        .hero a.btn:hover { background: #fff; color: #0d6efd; }
        .hero-mockup { max-width: 100%; border-radius: 1rem; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2); }
        .section-title { font-size: 36px; font-weight: 800; }
        .section-subtitle { font-size: 18px; color: #666; max-width: 600px; margin: 0 auto; }
        .feature-card, .app-card, .testimonial-card, .pricing-card {
            border: none; border-radius: 16px; background: #fff; transition: all 0.3s ease; box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .feature-card:hover, .app-card:hover, .testimonial-card:hover, .pricing-card:hover {
            transform: translateY(-5px); box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }
        .feature-card img, .app-card img { margin-bottom: 15px; }
        .feature-card h5, .app-card h5 { font-weight: 700; }
        .testimonial-card p { font-style: italic; color: #555; }
        .testimonial-author img { border: 2px solid #0d6efd; }
        .pricing-card.featured { border: 2px solid #0d6efd; }
        .contact-cta { background: linear-gradient(135deg, #0d6efd 0%, #00b3ff 100%); }
        .contact-cta h2 { font-weight: 800; }
        .contact-cta p { font-size: 18px; }
        .contact-cta .btn, .contact-form .btn { border-radius: 50px; font-weight: 600; }
        .contact-form { background: #f9f9f9; border-top: 1px solid #eee; }
        .contact-form .form-control { border-radius: 10px; }
    </style>
</head>

<body>

    <!-- ✅ Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
                <img src="{{ asset('assets/icons/bus-logo.png') }}" alt="Bussaty Logo" width="40" class="me-2">
                {{ $settings?->name ?? 'Bussaty' }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#pricing">Pricing</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <li class="nav-item ms-lg-3">
                        <a href="/dashboard/login" class="btn btn-primary px-4 py-2">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ✅ Hero -->
    <section class="hero py-5" id="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                    <h1 class="hero-title mb-3">Smart School Bus Tracking & Management System</h1>
                    <p class="hero-subtitle mb-4">
                        Bussaty is your all-in-one smart school bus tracking and management platform.
                        Track every school bus in real-time, keep students safe every day, and provide
                        parents with instant updates and complete peace of mind — anytime, anywhere.
                    </p>
                    <a href="/dashboard/login" class="btn btn-light btn-lg px-5 py-2">Get Started Today</a>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="{{ asset('assets/hero.png') }}" alt="Bussaty School Bus Tracking App"
                         class="img-fluid hero-mockup" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <section class="py-4">
        <div class="container">
            <p style="font-size: 18px; color: #555; max-width: 900px; margin: 0 auto; text-align: center;">
                With Bussaty, schools gain powerful tools to monitor and manage bus routes efficiently,
                track buses live on a detailed map, and communicate seamlessly with drivers and parents.
                Parents receive instant alerts for pick-ups and drop-offs, while students travel safely
                on optimized routes every day. Bussaty brings schools, drivers, and families together in
                one simple, smart system designed to make school transportation safer and more reliable.
            </p>
        </div>
    </section>

    <!-- ✅ Features -->
    <section class="features py-5 bg-light" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Key Features</h2>
                <p class="section-subtitle">
                    Bussaty gives you all the smart tools you need to manage, track, and secure your school bus fleet
                    every day.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="card feature-card text-center p-4 h-100">
                        <img src="{{ asset('assets/LiveTracking.png') }}" alt="Live Tracking feature Bussaty school bus on map" width="80" loading="lazy">
                        <h5>Live Tracking</h5>
                        <p>Bussaty’s real-time bus tracking shows the exact location of every bus on an interactive map. Parents and school admins can follow the bus movement, check estimated arrival times, and ensure students stay safe on the road.</p>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card feature-card text-center p-4 h-100">
                        <img src="{{ asset('assets/Instant Alerts.png') }}" alt="Instant Alerts for parents" width="80" loading="lazy">
                        <h5>Instant Alerts</h5>
                        <p>Parents receive instant notifications for pick-ups, drop-offs, route changes, or any unexpected delays. Stay informed at every moment and never worry about missing your child’s bus again.</p>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card feature-card text-center p-4 h-100">
                        <img src="{{ asset('assets/Smart Dashboard.png') }}" alt="Smart Dashboard for school admins" width="80" loading="lazy">
                        <h5>Smart Dashboard</h5>
                        <p>The Bussaty admin dashboard puts you in full control: manage routes, assign drivers, track performance, and access detailed reports. Make data-driven decisions to improve safety and efficiency.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ✅ 3 Apps -->
    <section class="apps py-5 bg-light" id="apps">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">3 Applications – One Complete Solution</h2>
                <p class="section-subtitle">
                    Bussaty’s smart ecosystem includes three dedicated apps to help schools manage transportation, keep parents updated,
                    and support drivers and supervisors every day.
                </p>
            </div>

            <div class="row g-4 text-center">
                <div class="col-md-4" data-aos="fade-up">
                    <div class="card app-card p-4 h-100">
                        <img src="{{ asset('assets/School Admin.png') }}" alt="School Admin App dashboard" width="60" loading="lazy">
                        <h5 class="mt-3">School Admin</h5>
                        <p>Our School Admin app gives transport managers full control of the entire school bus fleet. Add or edit student data, assign drivers and supervisors, and manage bus routes in real-time. Access live reports and dashboards to monitor performance, handle emergencies, and ensure that every child arrives safely.</p>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card app-card p-4 h-100">
                        <img src="{{ asset('assets/Parent.png') }}" alt="Parent App live bus tracking" width="60" loading="lazy">
                        <h5 class="mt-3">Parent</h5>
                        <p>The Parent app offers peace of mind for families. Parents can track their child’s bus in real-time, view expected arrival times, and receive instant alerts for pick-ups, drop-offs, or route changes. Direct messaging keeps communication open between parents, drivers, and supervisors at all times.</p>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card app-card p-4 h-100">
                        <img src="{{ asset('assets/SupervisorDriver.png') }}" alt="Supervisor and Driver App safety tools" width="60" loading="lazy">
                        <h5 class="mt-3">Supervisor & Driver</h5>
                        <p>The Supervisor & Driver app simplifies daily operations. Drivers get optimized routes and real-time updates for safe and on-time trips. They can mark student attendance with one tap and send quick updates to parents. Supervisors can manage multiple buses, ensure compliance, and communicate directly with families for maximum trust.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ✅ Testimonials -->
    <section class="testimonials py-5" id="testimonials">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">What Our Users Say</h2>
                <p class="section-subtitle">Trusted by schools, parents, and drivers across the region.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card testimonial-card p-4 h-100">
                        <p>"Bussaty has made tracking our buses so easy and parents love it!"</p>
                        <div class="testimonial-author d-flex align-items-center mt-3">
                            <img src="{{ asset('assets/icons/user1.svg') }}" alt="User 1" width="50" class="rounded-circle me-3">
                            <div><strong>Ahmed Khaled</strong><br><small>School Admin</small></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card testimonial-card p-4 h-100">
                        <p>"Now I always know when my kids are picked up or dropped off. Peace of mind!"</p>
                        <div class="testimonial-author d-flex align-items-center mt-3">
                            <img src="{{ asset('assets/icons/user2.svg') }}" alt="User 2" width="50" class="rounded-circle me-3">
                            <div><strong>Sarah M.</strong><br><small>Parent</small></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card testimonial-card p-4 h-100">
                        <p>"The supervisor app is so easy to use. Marking attendance is a breeze."</p>
                        <div class="testimonial-author d-flex align-items-center mt-3">
                            <img src="{{ asset('assets/icons/user3.svg') }}" alt="User 3" width="50" class="rounded-circle me-3">
                            <div><strong>Mohamed S.</strong><br><small>Driver</small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ✅ Pricing -->
    <section class="pricing py-5" id="pricing">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Flexible Pricing</h2>
                <p class="section-subtitle">Start for free, upgrade when you’re ready.</p>
            </div>

            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="card pricing-card p-4 h-100">
                        <h5>Free Plan</h5>
                        <h2 class="my-3">$0<span class="fs-6">/month</span></h2>
                        <p>Basic features for schools and parents.</p>
                        <ul class="list-unstyled my-3">
                            <li>✔️ Live Bus Tracking</li>
                            <li>✔️ Notifications</li>
                            <li>✔️ Supervisor App always free</li>
                        </ul>
                        <a href="/dashboard/login" class="btn btn-outline-primary">Get Started Free</a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card pricing-card featured p-4 h-100">
                        <h5>Pro Plan</h5>
                        <h2 class="my-3">$XX<span class="fs-6">/month</span></h2>
                        <p>Advanced features for growing schools.</p>
                        <ul class="list-unstyled my-3">
                            <li>✔️ Everything in Free</li>
                            <li>✔️ Advanced Reporting</li>
                            <li>✔️ Custom Branding</li>
                            <li>✔️ Premium Support</li>
                        </ul>
                        <a href="/contact" class="btn btn-primary">Contact Sales</a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card pricing-card p-4 h-100">
                        <h5>Supervisor App</h5>
                        <h2 class="my-3">Always Free</h2>
                        <p>Dedicated app for supervisors & drivers with no cost forever.</p>
                        <ul class="list-unstyled my-3">
                            <li>✔️ Mark Attendance</li>
                            <li>✔️ Route Guidance</li>
                            <li>✔️ Parent Communication</li>
                        </ul>
                        <a href="/dashboard/login" class="btn btn-outline-primary">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ✅ Contact -->
    <section class="contact-form py-5" id="contact">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Contact Us</h2>
                <p class="section-subtitle">
                    Have a question, need support, or want to see how Bussaty can improve your school bus management?
                    Fill out the form below and our team will get back to you as soon as possible.
                </p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form id="contactForm">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg" placeholder="Your Full Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Your Email" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Your Message</label>
                            <textarea name="message" id="message" rows="5" class="form-control form-control-lg" placeholder="Tell us how we can help you..." required></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-2">Send Message</button>
                        </div>
                    </form>
                    <div id="contactMessage" class="alert d-none mt-4"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-4">
        <div class="container">
            <p style="font-size: 18px; color: #555; max-width: 800px; margin: 0 auto; text-align: center;">
                Ready to experience a hassle-free, smart school bus tracking and management solution?
                Join hundreds of schools and thousands of parents who trust Bussaty every day
                to keep their students safe, improve transport efficiency, and build better communication
                between schools, drivers, and families. Start with Bussaty today and feel the difference in safety and
                peace of mind.
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        &copy; {{ now()->year }} {{ $settings?->name ?? 'Bussaty' }} – All rights reserved.
    </footer>

    <!-- ✅ Bootstrap + AOS JS (AOS escaped) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
    </script>

    <!-- ✅ Contact Form JS -->
    <script>
        const contactForm = document.querySelector('#contactForm');
        const contactMessage = document.querySelector('#contactMessage');

        contactForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            const response = await fetch('{{ route('api.contact.send') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const result = await response.json();

            if (result.status) {
                contactMessage.classList.remove('d-none', 'alert-danger');
                contactMessage.classList.add('alert-success');
                contactMessage.innerText = result.message;
                this.reset();
            } else {
                contactMessage.classList.remove('d-none', 'alert-success');
                contactMessage.classList.add('alert-danger');
                contactMessage.innerText = 'Something went wrong. Please try again.';
            }
        });
    </script>

    <!-- ✅ JSON-LD blocks wrapped with @verbatim to avoid Blade parsing @context/@type -->
    @verbatim
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Review",
      "reviewBody": "Bussaty has made tracking our buses so easy...",
      "author": {
        "@type": "Person",
        "name": "Ahmed Khaled"
      },
      "itemReviewed": {
        "@type": "Product",
        "name": "Bussaty"
      },
      "reviewRating": {
        "@type": "Rating",
        "ratingValue": "5",
        "bestRating": "5"
      }
    }
    </script>
    @endverbatim

    @verbatim
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Product",
      "name": "Bussaty Pro Plan",
      "offers": {
        "@type": "Offer",
        "priceCurrency": "USD",
        "price": "XX",
        "availability": "https://schema.org/InStock"
      }
    }
    </script>
    @endverbatim

    @verbatim
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "ContactPage",
      "name": "Contact Us - Bussaty",
      "url": "https://new.busatyapp.com/#contact"
    }
    </script>
    @endverbatim

</body>
</html>
