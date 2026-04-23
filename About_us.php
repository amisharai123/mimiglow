<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Mimi Glow</title>
    <link rel="stylesheet" href="About_us.css">
    <link rel="stylesheet" href="Header/header.css">
    <link rel="stylesheet" href="Footer/footer.css">
</head>
<body>
    
 <!-- JS for the Header -->
 <div id="header-placeholder"></div>
    <script>
        fetch('Header/header.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            })
            .catch(error => console.error('Error loading header:', error));
    </script>
    
    <section class="about-us-section">
        <div class="about-intro fade-in-center">
            <h1>Welcome to Mimi Glow</h1>
            <p>We bring you trusted skincare with a radiant twist, featuring the globally loved COSRX brand. Mimi Glow believes in pure, effective, and feel-good skincare for all skin types.</p>
        </div>

        <div class="content-row">
            <div class="text-left slide-in-left">
                <h2>Our Story</h2>
                <p>Started with love for self-care and a passion for real results, Mimi Glow was created to bring premium skincare to everyone. Partnering with COSRX, we deliver effective, honest beauty solutions.</p>
            </div>
            <div class="image-right slide-in-right">
                <img src="Web_image/about-us01.jpg" alt="Our Story Image">
            </div>
        </div>

        <div class="content-row">
            <div class="image-left slide-in-left">
                <img src="Web_image/about-us02.jpg" alt="Our Mission Image">
            </div>
            <div class="text-right slide-in-right">
                <h2>Our Mission</h2>
                <p>Our mission is to simplify skincare, helping you build routines that actually work. Mimi Glow is all about clean ingredients, clear results, and glowing confidence.</p>
            </div>
        </div>

        <div class="why-choose fade-in-center">
            <h2>Why Choose Mimi Glow?</h2>
            <ul>
                <li>100% authentic COSRX products</li>
                <li>Skincare solutions for every skin concern</li>
                <!-- <li>Trusted customer support & fast delivery</li> -->
                <li>Clean, safe, and effective ingredients</li>
            </ul>
        </div>
    </section>

    <!-- Intersection Observer Script -->
    <script>
        const faders = document.querySelectorAll('.fade-in-center, .slide-in-left, .slide-in-right');

        const appearOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        };

        const appearOnScroll = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add("appear");
                observer.unobserve(entry.target);
            });
        }, appearOptions);

        faders.forEach(fader => {
            appearOnScroll.observe(fader);
        });

        </script>

    <!-- JS for footer -->
  <div id="footer-container"></div>
    <script>
        fetch("Footer/footer.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById('footer-container').innerHTML = data;
            });
    </script>


</body>
</html>
