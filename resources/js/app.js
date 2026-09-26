import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';

window.Alpine = Alpine;
window.lucide = { createIcons, icons };

const renderLucide = () => {
    try {
        createIcons({ icons });
    } catch (e) {
        console.error('Lucide createIcons error:', e);
    }
};

window.renderLucide = renderLucide;

document.addEventListener('DOMContentLoaded', () => {
    renderLucide();

    if (document.body) {
        const observer = new MutationObserver((mutations) => {
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
                renderLucide();
            }
        });

        observer.observe(document.body, { childList: true, subtree: true });
    }
});

Alpine.start();
