<?php
if (isset($message)) {
  foreach ($message as $msg) {
    echo '
    <div class="message">
      <span>' . $msg . '</span>
      <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>
    ';
  }
}
?>

<div class="carousel w-full lg:h-[450px] bg-no-repeat bg-cover bg-left my-5" style="background-image: url(backdrop-green-leaves.jpg);">
  <!-- slide 01 -->
  <div id="slide1" class="carousel-item relative w-full" data-slide="1">
    <div class="flex flex-col lg:flex-row gap-80 p-4 lg:py-6 px-24">
      <div class="space-y-7 flex-1 pl-20">
        <h2 class="text-2xl lg:text-6xl font-bold text-yellow-200">
          Snake Plant
          <br>
          “Laurentii”
        </h2>
        <p class="text-white">Enjoy a cleaner home and a clearer mind with the Snake Plant.</p>
        <div class="flex gap-5">
          <button class="btn btn-primary rounded-full" onclick="window.open('https://en.wikipedia.org/wiki/Dracaena_trifasciata', '_blank')">Discover More</button>
          <a href="offers.php">
            <button class="btn btn-outline btn-warning rounded-full">Latest Offers</button>
          </a>
        </div>
      </div>
      <div class="flex-1">
        <img src="snake-plant-laurenti.png" class="lg:w-[400px] lg:h-[400px] rounded-lg shadow-2xl" />
      </div>
    </div>
    <div class="absolute flex justify-between gap-5 right-5 left-5 bottom-5 transform -translate-y-1/2">
      <a href="#slide4" class="btn btn-circle" onclick="prevSlide()">❮</a>
      <a href="#slide2" class="btn btn-circle" onclick="nextSlide()">❯</a>
    </div>
  </div>
  <!-- slide 02 -->
  <div id="slide2" class="carousel-item relative w-full" data-slide="2">
    <div class="flex flex-col lg:flex-row gap-80 p-4 lg:py-6 px-24">
      <div class="space-y-7 flex-1 pl-20">
        <h2 class="text-2xl lg:text-6xl font-bold text-yellow-200">
          Rubber Plant
          <br>
          “Elastica”
        </h2>
        <p class="text-white">Purify your space with the air-cleansing Rubber Plant.</p>
        <div class="flex gap-5">
          <button class="btn btn-primary rounded-full" onclick="window.open('https://en.wikipedia.org/wiki/Ficus_elastica', '_blank')">Discover More</button>
          <a href="offers.php">
            <button class="btn btn-outline btn-warning rounded-full">Latest Offers</button>
          </a>
        </div>
      </div>
      <div class="flex-1">
        <img src="rubber-plant-elastica.png" class="lg:w-[400px] lg:h-[400px] rounded-lg shadow-2xl" />
      </div>
    </div>
    <div class="absolute flex justify-between gap-5 right-5 left-5 bottom-5 transform -translate-y-1/2">
      <a href="#slide1" class="btn btn-circle" onclick="prevSlide()">❮</a>
      <a href="#slide3" class="btn btn-circle" onclick="nextSlide()">❯</a>
    </div>
  </div>
  <!-- slide 03 -->
  <div id="slide3" class="carousel-item relative w-full" data-slide="3">
    <div class="flex flex-col lg:flex-row gap-80 p-4 lg:py-6 px-24">
      <div class="space-y-7 flex-1 pl-20">
        <h2 class="text-2xl lg:text-6xl font-bold text-yellow-200">
          Peace Lily
        </h2>
        <h4 class="lg:text-4xl font-bold text-yellow-200">“Spathiphylum”</h4>
        <p class="text-white">Brighten your home with the elegant Peace Lily.</p>
        <div class="flex gap-5">
          <button class="btn btn-primary rounded-full" onclick="window.open('https://en.wikipedia.org/wiki/Spathiphyllum', '_blank')">Discover More</button>
          <a href="offers.php">
            <button class="btn btn-outline btn-warning rounded-full">Latest Offers</button>
          </a>
        </div>
      </div>
      <div class="flex-1">
        <img src="peace-lily.png" class="lg:w-[400px] lg:h-[400px] rounded-lg shadow-2xl" />
      </div>
    </div>
    <div class="absolute flex justify-between gap-5 right-5 left-5 bottom-5 transform -translate-y-1/2">
      <a href="#slide2" class="btn btn-circle" onclick="prevSlide()">❮</a>
      <a href="#slide4" class="btn btn-circle" onclick="nextSlide()">❯</a>
    </div>
  </div>
  <!-- slide 04 -->
  <div id="slide4" class="carousel-item relative w-full" data-slide="4">
    <div class="flex flex-col lg:flex-row gap-80 p-4 lg:py-6 px-24">
      <div class="space-y-7 flex-1 pl-20">
        <h2 class="text-2xl lg:text-6xl font-bold text-yellow-200">
          Spider Plant
        </h2>
        <h4 class="lg:text-4xl font-bold text-yellow-200">“Chlorophytum”</h4>
        <p class="text-white">Add a touch of nature with the versatile Spider Plant.</p>
        <div class="flex gap-5">
          <button class="btn btn-primary rounded-full" onclick="window.open('https://en.wikipedia.org/wiki/Chlorophytum_comosum', '_blank')">Discover More</button>
          <a href="offers.php">
            <button class="btn btn-outline btn-warning rounded-full">Latest Offers</button>
          </a>
        </div>
      </div>
      <div class="flex-1">
        <img src="spider-plant.png" class="lg:w-[400px] lg:h-[400px] rounded-lg shadow-2xl" />
      </div>
    </div>
    <div class="absolute flex justify-between gap-5 right-5 left-5 bottom-5 transform -translate-y-1/2">
      <a href="#slide3" class="btn btn-circle" onclick="prevSlide()">❮</a>
      <a href="#slide1" class="btn btn-circle" onclick="nextSlide()">❯</a>
    </div>
  </div>
</div>

<script>
let currentSlide = 1;
const totalSlides = document.querySelectorAll('.carousel-item').length;

function showSlide(slideIndex) {
  const slides = document.querySelectorAll('.carousel-item');
  slides.forEach(slide => {
    slide.style.display = 'none';
  });
  slides[slideIndex - 1].style.display = 'block';
}

function nextSlide() {
  currentSlide = (currentSlide % totalSlides) + 1;
  showSlide(currentSlide);
}

function prevSlide() {
  currentSlide = (currentSlide - 1 + totalSlides) % totalSlides || totalSlides;
  showSlide(currentSlide);
}

document.addEventListener('DOMContentLoaded', () => {
  showSlide(currentSlide);
  setInterval(nextSlide, 2500);
});
</script>
