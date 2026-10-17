import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

const reverbKey = import.meta.env.VITE_REVERB_APP_KEY;

if (reverbKey) {
    const scheme = import.meta.env.VITE_REVERB_SCHEME ?? 'http';
    const port = Number(import.meta.env.VITE_REVERB_PORT ?? 8080);
    const configuredHost = import.meta.env.VITE_REVERB_HOST;
    const loopbackHosts = ['localhost', '127.0.0.1', '::1'];
    const configuredHostIsLoopback = loopbackHosts.includes(
        String(configuredHost ?? '').replace(/^\[|\]$/g, '').toLowerCase()
    );
    const pageHostIsLoopback = loopbackHosts.includes(
        window.location.hostname.replace(/^\[|\]$/g, '').toLowerCase()
    );
    const wsHost = configuredHostIsLoopback && !pageHostIsLoopback ?
        window.location.hostname :
        (configuredHost ?? window.location.hostname);

    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: reverbKey,
        wsHost,
        wsPort: port,
        wssPort: port,
        forceTLS: scheme === 'https',
        enabledTransports: scheme === 'https' ? ['wss'] : ['ws'],
    });
    window.dispatchEvent(new CustomEvent('restaurant:echo-ready'));
} else {
    console.error('Reverb is not configured. Set VITE_REVERB_APP_KEY in the environment.');
}
