// Service Worker for ShopSmart PWA
const CACHE_NAME = 'shopsmart-v1';
const STATIC_CACHE = 'shopsmart-static-v1';
const DYNAMIC_CACHE = 'shopsmart-dynamic-v1';

const STATIC_ASSETS = [
    '/',
    '/dashboard',
    '/manifest.json',
    '/build/assets/app-CtKWRRj6.css',
    '/build/assets/app-CWxCsbqF.js',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png'
];

// Install Service Worker
self.addEventListener('install', (event) => {
    console.log('Service Worker: Installing...');
    
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then((cache) => {
                console.log('Service Worker: Caching static assets');
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => {
                console.log('Service Worker: Static assets cached');
                return self.skipWaiting();
            })
    );
});

// Activate Service Worker
self.addEventListener('activate', (event) => {
    console.log('Service Worker: Activating...');
    
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== STATIC_CACHE && cacheName !== DYNAMIC_CACHE) {
                        console.log('Service Worker: Clearing old cache', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    
    return self.clients.claim();
});

// Fetch Strategy: Network First with Fallback to Cache
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);
    
    // Skip non-HTTP requests
    if (!request.url.startsWith('http')) {
        return;
    }
    
    // API requests - Network only with timeout
    if (url.pathname.startsWith('/api/')) {
        event.respondWith(
            Promise.race([
                fetch(request),
                new Promise((_, reject) => 
                    setTimeout(() => reject(new Error('Network timeout')), 5000)
                )
            ]).catch(() => {
                return new Response(
                    JSON.stringify({ 
                        error: 'Network unavailable', 
                        message: 'Please check your internet connection' 
                    }), 
                    { 
                        status: 503, 
                        headers: { 'Content-Type': 'application/json' } 
                    }
                );
            })
        );
        return;
    }
    
    // Static assets - Cache First with Network Fallback
    if (STATIC_ASSETS.some(asset => request.url.includes(asset)) || 
        request.destination === 'script' || 
        request.destination === 'style' || 
        request.destination === 'image') {
        
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                if (cachedResponse) {
                    // Serve from cache, but update in background
                    fetch(request).then((networkResponse) => {
                        if (networkResponse.ok) {
                            caches.open(STATIC_CACHE).then((cache) => {
                                cache.put(request, networkResponse.clone());
                            });
                        }
                    }).catch(() => {
                        // Network error, but we have cache
                    });
                    return cachedResponse;
                }
                
                // Not in cache, fetch from network
                return fetch(request).then((networkResponse) => {
                    if (networkResponse.ok) {
                        // Cache the response for future use
                        const responseClone = networkResponse.clone();
                        caches.open(STATIC_CACHE).then((cache) => {
                            cache.put(request, responseClone);
                        });
                    }
                    return networkResponse;
                });
            })
        );
        return;
    }
    
    // Dynamic pages - Network First with Cache Fallback
    event.respondWith(
        fetch(request)
            .then((networkResponse) => {
                if (networkResponse.ok) {
                    // Cache successful responses
                    const responseClone = networkResponse.clone();
                    caches.open(DYNAMIC_CACHE).then((cache) => {
                        cache.put(request, responseClone);
                    });
                    return networkResponse;
                }
                
                // If network response is not ok, try cache
                return caches.match(request).then((cachedResponse) => {
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    
                    // Return offline page if available
                    return caches.match('/offline').then((offlineResponse) => {
                        return offlineResponse || new Response(
                            'Offline - Please check your internet connection',
                            { status: 503, statusText: 'Service Unavailable' }
                        );
                    });
                });
            })
            .catch(() => {
                // Network failed, try cache
                return caches.match(request).then((cachedResponse) => {
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    
                    // Return offline page if available
                    return caches.match('/offline').then((offlineResponse) => {
                        return offlineResponse || new Response(
                            'Offline - Please check your internet connection',
                            { status: 503, statusText: 'Service Unavailable' }
                        );
                    });
                });
            })
    );
});

// Background Sync for offline actions
self.addEventListener('sync', (event) => {
    if (event.tag === 'background-sync') {
        event.waitUntil(
            // Handle any offline actions that need to be synced
            console.log('Service Worker: Background sync triggered')
        );
    }
});

// Push Notifications
self.addEventListener('push', (event) => {
    const options = {
        body: event.data ? event.data.text() : 'ShopSmart: New notification',
        icon: '/icons/icon-192x192.png',
        badge: '/icons/icon-72x72.png',
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1
        },
        actions: [
            {
                action: 'explore',
                title: 'Open App',
                icon: '/icons/icon-96x96.png'
            },
            {
                action: 'close',
                title: 'Close',
                icon: '/icons/icon-96x96.png'
            }
        ]
    };
    
    event.waitUntil(
        self.registration.showNotification('ShopSmart', options)
    );
});

// Notification Click Handler
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    
    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});
