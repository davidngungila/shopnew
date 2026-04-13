@php
    $showInstallButton = false;
@endphp

@if($showInstallButton)
    <button 
        id="pwa-install-button" 
        class="fixed bottom-6 right-6 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-200 z-50 flex items-center space-x-2"
        style="display: none;"
        title="Install ShopSmart App"
    >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
        <span class="font-medium">Install App</span>
    </button>
@endif

<script>
    // Show install button only on mobile devices and when PWA is not installed
    document.addEventListener('DOMContentLoaded', function() {
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || 
                            window.navigator.standalone === true;
        
        const installButton = document.getElementById('pwa-install-button');
        if (installButton && isMobile && !isStandalone) {
            // Check if the app can be installed
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                installButton.style.display = 'flex';
            });
        }
    });
</script>
