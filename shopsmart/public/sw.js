// This will be replaced by the actual manifest during build
self.__WB_MANIFEST = [];

const CACHE_NAME = 'shopsmart-v1';
const STATIC_CACHE = 'shopsmart-static-v1';
const DYNAMIC_CACHE = 'shopsmart-dynamic-v1';

const STATIC_ASSETS = [
    '/',
    '/dashboard',
    '/manifest.json',
    '/app.css',
    '/app.js',
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

// Enhanced Push Notifications
self.addEventListener('push', (event) => {
    let notificationData = {};
    
    try {
        if (event.data) {
            notificationData = event.data.json();
        }
    } catch (e) {
        // Fallback to text if JSON parsing fails
        notificationData = {
            title: 'ShopSmart',
            body: event.data ? event.data.text() : 'New notification',
            icon: '/icons/icon-192x192.png'
        };
    }
    
    const defaultOptions = {
        title: notificationData.title || 'ShopSmart',
        body: notificationData.body || 'New notification available',
        icon: notificationData.icon || '/icons/icon-192x192.png',
        badge: '/icons/icon-72x72.png',
        image: notificationData.image || null,
        vibrate: notificationData.vibrate || [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: notificationData.id || Math.random().toString(36).substr(2, 9),
            url: notificationData.url || '/',
            type: notificationData.type || 'general',
            ...notificationData.data
        },
        actions: notificationData.actions || [
            {
                action: 'open',
                title: 'Open App',
                icon: '/icons/icon-96x96.png'
            },
            {
                action: 'dismiss',
                title: 'Dismiss',
                icon: '/icons/icon-96x96.png'
            }
        ],
        requireInteraction: notificationData.requireInteraction || false,
        silent: notificationData.silent || false,
        tag: notificationData.tag || 'shopsmart-default',
        renotify: notificationData.renotify || true,
        timestamp: Date.now()
    };
    
    event.waitUntil(
        self.registration.showNotification(defaultOptions.title, defaultOptions)
    );
});

// Enhanced Notification Click Handler
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    
    const notificationData = event.notification.data || {};
    const action = event.action;
    
    if (action === 'dismiss') {
        return; // Just close the notification
    }
    
    // Handle different actions
    let urlToOpen = '/';
    
    if (notificationData.url) {
        urlToOpen = notificationData.url;
    } else if (notificationData.type) {
        // Route based on notification type
        switch (notificationData.type) {
            case 'inventory':
                urlToOpen = '/inventory';
                break;
            case 'sales':
                urlToOpen = '/sales';
                break;
            case 'purchases':
                urlToOpen = '/purchases';
                break;
            case 'reports':
                urlToOpen = '/reports';
                break;
            case 'settings':
                urlToOpen = '/settings';
                break;
            default:
                urlToOpen = '/dashboard';
        }
    }
    
    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        }).then((clientList) => {
            // Focus existing window if available
            for (const client of clientList) {
                if (client.url === urlToOpen && 'focus' in client) {
                    return client.focus();
                }
            }
            
            // Open new window if no existing window found
            if (clients.openWindow) {
                return clients.openWindow(urlToOpen);
            }
        })
    );
});

// Notification Close Handler
self.addEventListener('notificationclose', (event) => {
    const notificationData = event.notification.data || {};
    
    // Log notification dismissal for analytics
    console.log('Notification closed:', {
        id: notificationData.primaryKey,
        type: notificationData.type,
        dismissedAt: Date.now()
    });
    
    // You could send this data to your server for analytics
    event.waitUntil(
        fetch('/api/notifications/dismissed', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                notificationId: notificationData.primaryKey,
                type: notificationData.type,
                dismissedAt: Date.now()
            })
        }).catch(() => {
            // Silently fail if analytics endpoint is not available
        })
    );
});
