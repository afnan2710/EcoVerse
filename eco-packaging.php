<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ECO Friendly Packaging</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clifford: '#da373d',
                        'plant-primary': '#E76F51',
                        'plant-primary-bg': 'rgba(231, 111, 81, 0.10)',
                    }
                }
            }
        }
        </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f0fff4, #d0f0c0); /* Light gradient background */
        }

        /* Custom button styles */
        .btn-green {
            @apply bg-green-500 text-white px-6 py-2 rounded-full transition-all duration-300 ease-in-out transform hover:bg-green-600 hover:scale-105 shadow-lg;
        }

        /* Subtle fade-in animation for the content */
        .fade-in {
            opacity: 0;
            animation: fadeIn 1s ease-in forwards;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Footer icons hover effect */
        footer a {
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #9ae6b4;
        }

        /* Add a shadow to the video */
        .video-frame {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            border-radius: 1rem;
        }

        /* Adjust container padding */
        .container {
            padding: 2rem;
        }

        /* Styles for the information card */
        .info-card {
            transition: transform 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <nav>
        <?php include "navbar.php";?>
    </nav>

    <div class="container mx-auto my-10 fade-in">
        <h1 class="text-5xl font-extrabold text-center text-green-700 mb-8">ECO Friendly Packaging</h1>

        <!-- Centering the video section with rounded corners and shadow -->
        <div class="flex justify-center mb-10">
            <iframe width="1200" height="500" class="video-frame" src="https://www.youtube.com/embed/8VkiF8akzoM"
                frameborder="0" allowfullscreen></iframe>
        </div>

        <p class="text-center text-xl text-gray-700 mb-12 fade-in">
            Discover more about our sustainable packaging solutions by watching the video below!
        </p>

        <!-- Information Section -->
        <div class="flex flex-col lg:flex-row justify-center items-center fade-in">
            <div class="flex-1 p-8 bg-white rounded-lg shadow-2xl info-card text-center">
                <h2 class="text-4xl font-semibold text-green-600 mb-6">What is Eco-Friendly Packaging?</h2>
                <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                    Eco-friendly packaging refers to materials and practices that are environmentally sustainable and have a minimal impact on the planet.
                    These materials are often sourced from renewable resources or recycled materials and are designed to be biodegradable or compostable.
                </p>

                <!-- Added the new link with button styling -->
                <a href="https://www.cruzfoam.com/post/what-is-eco-friendly-packaging-a-guide-for-businesses/" class="btn-green" target="_blank">
                    Learn More About Eco-Friendly Packaging
                </a>

                <!-- Centered Images with more shadow and hover effects -->
                <div class="flex flex-wrap justify-center my-8 space-x-4">
                    <img src="https://i.pinimg.com/564x/b0/c6/58/b0c658b3a9fd8111e145e881769f588b.jpg" alt="Eco Packaging Image 1"
                        class="rounded-lg shadow-lg m-2 max-w-xs transition-transform transform hover:scale-110">
                    <img src="https://i.pinimg.com/564x/17/c6/33/17c633787f841c8ab19aad6406374915.jpg" alt="Eco Packaging Image 2"
                        class="rounded-lg shadow-lg m-2 max-w-xs transition-transform transform hover:scale-110">
                    <img src="https://i.pinimg.com/564x/3a/be/a5/3abea58fccba08d50a1d5e871383d0be.jpg" alt="Eco Packaging Image 3"
                        class="rounded-lg shadow-lg m-2 max-w-xs transition-transform transform hover:scale-110">
                </div>

                <h3 class="text-3xl font-medium text-green-700 mb-4">Benefits of Eco-Friendly Packaging:</h3>
                <ul class="list-disc list-inside text-left text-lg text-gray-700 mb-6">
                    <li>Reduces carbon footprint.</li>
                    <li>Minimizes waste and pollution.</li>
                    <li>Promotes recycling and sustainability.</li>
                </ul>

                <p class="text-lg text-gray-600">
                    By choosing eco-friendly packaging, you contribute to a healthier planet for future generations!
                </p>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?> 

</body>

</html>