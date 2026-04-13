<!-- PWA Install Button - Top Right for Desktop, Bottom Right for Mobile -->
<button 
    id="pwa-install-button" 
    class="fixed top-4 right-4 lg:top-6 lg:right-6 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 lg:px-4 lg:py-3 rounded-lg lg:rounded-full shadow-lg hover:shadow-xl transition-all duration-200 z-50 flex items-center space-x-2 text-sm lg:text-base"
    style="display: none;"
    title="Install ShopSmart App on your device"
>
    <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
    </svg>
    <span class="font-medium hidden lg:inline">Install App</span>
    <span class="font-medium lg:hidden">Install</span>
</button>

<script>
    // Show install button on both desktop and mobile when PWA is not installed
    document.addEventListener('DOMContentLoaded', function() {
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || 
                            window.navigator.standalone === true;
        
        const installButton = document.getElementById('pwa-install-button');
        if (installButton && !isStandalone) {
            // Check if the app can be installed
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                installButton.style.display = 'flex';
                
                // Add animation to draw attention
                installButton.classList.add('animate-pulse');
                setTimeout(() => {
                    installButton.classList.remove('animate-pulse');
                }, 3000);
            });
            
            // Also check if PWA is already installed and hide button
            window.addEventListener('appinstalled', () => {
                installButton.style.display = 'none';
            });
            
            // Handle manual install prompt for desktop browsers
            setTimeout(() => {
                if (installButton.style.display === 'none') {
                    // Check if browser supports PWA installation
                    if ('serviceWorker' in navigator && 'PushManager' in window) {
                        // Show manual install instructions for desktop
                        const userAgent = navigator.userAgent.toLowerCase();
                        if (userAgent.includes('chrome') && !userAgent.includes('edg')) {
                            // Chrome desktop - show install button
                            installButton.style.display = 'flex';
                        } else if (userAgent.includes('firefox')) {
                            // Firefox desktop - show install button
                            installButton.style.display = 'flex';
                        } else if (userAgent.includes('edg')) {
                            // Edge desktop - show install button
                            installButton.style.display = 'flex';
                        }
                    }
                }
            }, 2000);
        }
    });
    
    // Handle install button click
    document.addEventListener('click', function(e) {
        if (e.target.closest('#pwa-install-button')) {
            const installButton = document.getElementById('pwa-install-button');
            if (installButton) {
                // Show installation instructions based on browser
                const userAgent = navigator.userAgent.toLowerCase();
                let instructions = '';
                
                if (userAgent.includes('chrome') && !userAgent.includes('edg')) {
                    instructions = 'Chrome: Click the download icon (±) in the address bar or three dots menu > "Install ShopSmart"';
                } else if (userAgent.includes('firefox')) {
                    instructions = 'Firefox: Open menu (three lines) > "Install ShopSmart"';
                } else if (userAgent.includes('edg')) {
                    instructions = 'Edge: Click the app icon in the address bar or three dots menu > "Install ShopSmart"';
                } else if (userAgent.includes('safari')) {
                    instructions = 'Safari: Click Share button > "Add to Home Screen"';
                } else {
                    instructions = 'Look for the install option in your browser menu or address bar';
                }
                
                // Show instructions in a user-friendly way
                if (confirm('How to install ShopSmart:\n\n' + instructions + '\n\nClick OK to continue')) {
                    // User can proceed with manual installation
                    console.log('PWA Install Instructions:', instructions);
                }
            }
        }
    });
</script>
