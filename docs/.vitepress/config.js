export default {
    title: 'Fluxor PHP',
    description: 'Lightweight PHP MVC framework with file-based routing',
    themeConfig: {
        logo: '/logo.svg',
        nav: [
            { text: 'Guide', link: '/guide/' },
            { text: 'API', link: '/api/' },
            { text: 'GitHub', link: 'https://github.com/lizzyman04/fluxor-php' }
        ],
        sidebar: {
            '/guide/': [
                {
                    text: 'Getting Started',
                    items: [
                        { text: 'Introduction', link: '/guide/' },
                        { text: 'Installation', link: '/guide/installation' },
                        { text: 'Configuration', link: '/guide/configuration' }
                    ]
                },
                {
                    text: 'Core Concepts',
                    items: [
                        { text: 'Routing', link: '/guide/routing' },
                        { text: 'Flow Syntax', link: '/guide/flow-syntax' },
                        { text: 'Views', link: '/guide/views' },
                        { text: 'Controllers', link: '/guide/controllers' },
                        { text: 'Middleware', link: '/guide/middleware' }
                    ]
                }
            ],
            '/api/': [
                {
                    text: 'API Reference',
                    items: [
                        { text: 'App', link: '/api/app' },
                        { text: 'Request', link: '/api/request' },
                        { text: 'Response', link: '/api/response' },
                        { text: 'Flow', link: '/api/flow' }
                    ]
                }
            ]
        },
        socialLinks: [
            { icon: 'github', link: 'https://github.com/lizzyman04/fluxor-php' }
        ],
        footer: {
            message: 'Released under the MIT License.',
            copyright: 'Copyright © 2026 lizzyman04'
        }
    }
}