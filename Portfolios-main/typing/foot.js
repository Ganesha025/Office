document.addEventListener("DOMContentLoaded", () => {
  const footer = `
    <footer class="bg-gradient-to-b from-gray-900 to-black text-white mt-20">
      <div class="max-w-7xl mx-auto px-6 py-12">
        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
          
          <!-- Brand Section -->
          <div class="text-center md:text-left">
            <h3 class="text-2xl font-bold mb-3 bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">
              APM Explorers
            </h3>
            <p class="text-gray-400 text-sm leading-relaxed">
              Exploring the world, one adventure at a time.
            </p>
          </div>
          
          <!-- Quick Links -->
          <div class="text-center">
            <h4 class="text-lg font-semibold mb-4 text-gray-200">Quick Links</h4>
            <div class="space-y-2">
              <a href="#" class="block text-gray-400 hover:text-white transition-colors duration-200 text-sm">Home</a>
              <a href="pages/about" class="block text-gray-400 hover:text-white transition-colors duration-200 text-sm">About</a>
              <a href="pages/albums" class="block text-gray-400 hover:text-white transition-colors duration-200 text-sm">Albums</a>
            </div>
          </div>
          
          <!-- Social Media -->
          <div class="text-center md:text-right">
            <h4 class="text-lg font-semibold mb-4 text-gray-200">Follow Us</h4>
            <div class="flex justify-center md:justify-end gap-4">
              <a href="https://m.youtube.com/@APM_Explorers" 
                 target="_blank" 
                 rel="noopener noreferrer"
                 class="w-12 h-12 flex items-center justify-center bg-gray-800 hover:bg-red-600 rounded-lg transition-all duration-300 transform hover:scale-110 hover:shadow-lg hover:shadow-red-500/50"
                 aria-label="YouTube">
                <i class="fab fa-youtube text-xl"></i>
              </a>
              <a href="https://www.instagram.com/apm_explorers_?igsh=MWY4dTJvYWh4c3F6eg==" 
                 target="_blank" 
                 rel="noopener noreferrer"
                 class="w-12 h-12 flex items-center justify-center bg-gray-800 hover:bg-gradient-to-br hover:from-purple-600 hover:to-pink-500 rounded-lg transition-all duration-300 transform hover:scale-110 hover:shadow-lg hover:shadow-pink-500/50"
                 aria-label="Instagram">
                <i class="fab fa-instagram text-xl"></i>
              </a>
            </div>
          </div>
          
        </div>
        <div class="border-t border-gray-800 pt-6">
          <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-500 text-sm">
              &copy; ${new Date().getFullYear()} APM Explorers. All rights reserved.
            </p>
            <p class="text-gray-500 text-sm">
              Developed by 
            <a href="/pages/developer.html"
   class="text-blue-500 font-medium ml-1 relative after:block after:absolute after:w-0 after:h-[2px] after:bg-blue-500 after:transition-all after:duration-300 hover:after:w-full"
   aria-label="Developer page">
  Savage King
</a>
            </p>
          </div>
        </div>
        
      </div>
    </footer>
  `;
  
  document.body.insertAdjacentHTML("beforeend", footer);
});
