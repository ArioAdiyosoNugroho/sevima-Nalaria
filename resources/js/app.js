import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';

const safeCreateIcons = (options) => {
    try {
        return createIcons(options && options.icons ? options : { icons });
    } catch (e) {
        console.warn('Lucide createIcons warning:', e);
    }
};

window.Alpine = Alpine;
window.lucide = {
    createIcons: safeCreateIcons,
    icons,
};

const renderLucide = () => {
    safeCreateIcons();
};

window.renderLucide = renderLucide;

document.addEventListener('DOMContentLoaded', () => {
    renderLucide();

    if (document.body) {
        let isRendering = false;
        const observer = new MutationObserver((mutations) => {
            if (isRendering) return;
            let shouldRender = false;
            for (const mutation of mutations) {
                if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                    for (const node of mutation.addedNodes) {
                        if (node.nodeType === 1) {
                            if (node.hasAttribute && node.hasAttribute('data-lucide')) {
                                shouldRender = true;
                                break;
                            }
                            if (node.querySelector && node.querySelector('[data-lucide]')) {
                                shouldRender = true;
                                break;
                            }
                        }
                    }
                }
                if (shouldRender) break;
            }
            if (shouldRender) {
                isRendering = true;
                try {
                    observer.disconnect();
                    renderLucide();
                } finally {
                    observer.observe(document.body, { childList: true, subtree: true });
                    isRendering = false;
                }
            }
        });

        observer.observe(document.body, { childList: true, subtree: true });
    }
});

Alpine.start();
