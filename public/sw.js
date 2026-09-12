// Service Worker placeholder to eliminate 404/redirect latency
self.addEventListener('install', () => self.skipWaiting());
self.addEventListener('activate', () => self.clients.claim());
