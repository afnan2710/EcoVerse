<footer class="footer p-10 bg-green-900 text-white">
  <aside>
    <img class="h-[130px] w-[120px]" src="logowhite.png" alt="image">
    <p class="text-white text-xl font-bold">EcoVerse</p>
    <p class="text-green-200 text-l">A Comprehensive Platform for Tree Enthusiasts</p>
  </aside>

  <nav>
    <h6 class="footer-title">Services</h6> 
    <a href="branding.html" class="link link-hover text-green-100">Branding</a>
    <a href="design.html" class="link link-hover text-green-100">Design</a>
    <a href="marketing.html" class="link link-hover text-green-100">Marketing</a>
    <a href="advertisement.php" class="link link-hover text-green-100">Advertisement</a>
  </nav>

  <nav>
    <h6 class="footer-title">Company</h6> 
    <a href="about_us.html" class="link link-hover text-green-100">About us</a>
    <a href="contact.html" class="link link-hover text-green-100">Contact</a>
    <a href="jobs.html" class="link link-hover text-green-100">Jobs</a>
    <a href="blogs.html" class="link link-hover text-green-100">Blog</a>
  </nav>

  <nav>
    <h6 class="footer-title">Legal</h6> 
    <a class="link link-hover text-green-100">Terms of Use</a>
    <a class="link link-hover text-green-100">Privacy Policy</a>
    <a class="link link-hover text-green-100">Cookie Policy</a>
    <a href="faq.html" class="link link-hover text-green-100">FAQ</a>
  </nav>

  <!-- Newsletter Subscription Form -->
  <aside class="newsletter">
    <h6 class="footer-title">Subscribe to our Newsletter</h6>
    <form id="subscribeForm">
      <input type="email" name="email" id="email" placeholder="Enter your email" class="input input-bordered w-full max-w-xs" required>
      <button type="submit" class="btn bg-green-500 text-white mt-2">Subscribe</button>
    </form>
    
    <!-- Success/Error Message Display -->
    <p id="responseMessage" class="mt-2"></p>
  </aside>
</footer>

  <script>
    document.getElementById('subscribeForm').addEventListener('submit', function(e) {
      e.preventDefault(); // Prevent form from reloading the page

      const email = document.getElementById('email').value;
      const responseMessage = document.getElementById('responseMessage');

      // AJAX request
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'subscribe.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          const response = JSON.parse(xhr.responseText); // Get JSON response

          // Display success or error message
          if (response.success) {
            responseMessage.textContent = response.success;
            responseMessage.className = 'text-green-300'; // Success message style
          } else if (response.error) {
            responseMessage.textContent = response.error;
            responseMessage.className = 'text-red-400'; // Error message style
          }
        }
      };

      // Send request with the email parameter
      xhr.send('email=' + encodeURIComponent(email));
    });
  </script>

