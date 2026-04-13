@php
    $notificationPermission = 'default';
    $isSupported = false;
@endphp

<div x-data="notificationManager()" x-init="init()" class="fixed bottom-20 right-6 z-40">
    <!-- Notification Permission Banner -->
    <div x-show="showPermissionBanner" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-4"
         class="bg-blue-600 text-white p-4 rounded-lg shadow-lg max-w-sm mb-4">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-medium">Enable Notifications</h3>
                <p class="text-xs mt-1 opacity-90">Stay updated with inventory alerts, sales notifications, and important updates.</p>
                <div class="mt-3 flex space-x-2">
                    <button @click="requestPermission()" 
                            class="bg-white text-blue-600 px-3 py-1 rounded text-xs font-medium hover:bg-blue-50 transition-colors">
                        Enable
                    </button>
                    <button @click="dismissBanner()" 
                            class="text-white opacity-75 hover:opacity-100 px-3 py-1 rounded text-xs font-medium transition-opacity">
                        Not now
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Settings Button -->
    <button @click="showSettings = !showSettings" 
            class="bg-gray-800 hover:bg-gray-700 text-white p-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-200"
            title="Notification Settings">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
        </svg>
        <span x-show="unreadCount > 0" 
              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
              x-text="unreadCount"></span>
    </button>

    <!-- Notification Settings Panel -->
    <div x-show="showSettings" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         @click.away="showSettings = false"
         class="absolute bottom-full right-0 mb-2 bg-white rounded-lg shadow-xl border border-gray-200 w-80">
        <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Notification Settings</h3>
            
            <!-- Permission Status -->
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Permission Status</span>
                    <span class="text-xs px-2 py-1 rounded-full"
                          :class="{
                              'bg-green-100 text-green-800': permissionStatus === 'granted',
                              'bg-yellow-100 text-yellow-800': permissionStatus === 'default',
                              'bg-red-100 text-red-800': permissionStatus === 'denied'
                          }"
                          x-text="permissionStatusText"></span>
                </div>
                <button x-show="permissionStatus !== 'granted'" 
                        @click="requestPermission()"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors">
                    Request Permission
                </button>
            </div>

            <!-- Notification Types -->
            <div class="space-y-3">
                <h4 class="text-sm font-medium text-gray-700">Notification Types</h4>
                
                <label class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Inventory Alerts</span>
                    <input type="checkbox" x-model="settings.inventory" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                </label>

                <label class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Sales Updates</span>
                    <input type="checkbox" x-model="settings.sales" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                </label>

                <label class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Purchase Orders</span>
                    <input type="checkbox" x-model="settings.purchases" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                </label>

                <label class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">System Updates</span>
                    <input type="checkbox" x-model="settings.system" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                </label>
            </div>

            <!-- Test Notification -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                <button @click="sendTestNotification()" 
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded text-sm font-medium transition-colors">
                    Send Test Notification
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function notificationManager() {
    return {
        showPermissionBanner: false,
        showSettings: false,
        permissionStatus: 'default',
        unreadCount: 0,
        settings: {
            inventory: true,
            sales: true,
            purchases: true,
            system: false
        },
        
        init() {
            this.checkPermission();
            this.loadSettings();
            
            // Check if we should show the permission banner
            if (this.permissionStatus === 'default' && !localStorage.getItem('notificationBannerDismissed')) {
                setTimeout(() => {
                    this.showPermissionBanner = true;
                }, 3000);
            }
        },
        
        get permissionStatusText() {
            switch(this.permissionStatus) {
                case 'granted': return 'Enabled';
                case 'denied': return 'Blocked';
                default: return 'Not requested';
            }
        },
        
        async checkPermission() {
            if (!('Notification' in window)) {
                console.log('This browser does not support notifications');
                return;
            }
            
            this.permissionStatus = Notification.permission;
        },
        
        async requestPermission() {
            if (!('Notification' in window)) {
                alert('Your browser does not support notifications');
                return;
            }
            
            try {
                const permission = await Notification.requestPermission();
                this.permissionStatus = permission;
                
                if (permission === 'granted') {
                    this.showPermissionBanner = false;
                    this.subscribeToPush();
                    
                    // Show success notification
                    this.showLocalNotification('Notifications Enabled', 'You will now receive important updates from ShopSmart');
                } else if (permission === 'denied') {
                    this.showPermissionBanner = false;
                }
            } catch (error) {
                console.error('Error requesting notification permission:', error);
            }
        },
        
        async subscribeToPush() {
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                return;
            }
            
            try {
                const registration = await navigator.serviceWorker.ready;
                const subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: this.urlBase64ToUint8Array('YOUR_VAPID_PUBLIC_KEY') // Replace with your VAPID key
                });
                
                // Send subscription to server
                await fetch('/api/notifications/subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(subscription)
                });
                
                console.log('Push subscription successful');
            } catch (error) {
                console.error('Push subscription failed:', error);
            }
        },
        
        dismissBanner() {
            this.showPermissionBanner = false;
            localStorage.setItem('notificationBannerDismissed', 'true');
        },
        
        loadSettings() {
            const saved = localStorage.getItem('notificationSettings');
            if (saved) {
                this.settings = JSON.parse(saved);
            }
        },
        
        saveSettings() {
            localStorage.setItem('notificationSettings', JSON.stringify(this.settings));
        },
        
        async sendTestNotification() {
            if (this.permissionStatus !== 'granted') {
                await this.requestPermission();
                return;
            }
            
            try {
                await fetch('/api/notifications/test', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
            } catch (error) {
                // Fallback to local notification
                this.showLocalNotification('Test Notification', 'This is a test notification from ShopSmart');
            }
        },
        
        showLocalNotification(title, body, options = {}) {
            if (this.permissionStatus === 'granted') {
                const notification = new Notification(title, {
                    body: body,
                    icon: '/icons/icon-192x192.png',
                    badge: '/icons/icon-72x72.png',
                    ...options
                });
                
                setTimeout(() => {
                    notification.close();
                }, 5000);
            }
        },
        
        urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            
            return outputArray;
        }
    }
}
</script>
