/**
 * SUPER AGGRESSIVE cursor fix
 * Override SEMUA yang mencoba mengubah cursor
 */

// Override CSS.supports jika digunakan untuk cek cursor
(function() {
    'use strict';
    
    // Intercept style changes
    const originalSetAttribute = Element.prototype.setAttribute;
    Element.prototype.setAttribute = function(name, value) {
        if (name === 'style' && typeof value === 'string') {
            // Hapus cursor dari style string
            if (value.includes('cursor')) {
                value = value.replace(/cursor\s*:\s*[^;]+;?/gi, '');
            }
        }
        return originalSetAttribute.call(this, name, value);
    };
    
    // Intercept inline style changes
    if (window.CSSStyleDeclaration) {
        const originalCSSText = Object.getOwnPropertyDescriptor(CSSStyleDeclaration.prototype, 'cssText');
        if (originalCSSText && originalCSSText.set) {
            Object.defineProperty(CSSStyleDeclaration.prototype, 'cssText', {
                set: function(value) {
                    if (typeof value === 'string' && value.includes('cursor')) {
                        value = value.replace(/cursor\s*:\s*[^;]+;?/gi, '');
                    }
                    return originalCSSText.set.call(this, value);
                },
                get: originalCSSText.get
            });
        }
    }
    
    // Override document.body.style.cursor setter
    function overrideCursorProperty(obj, prop) {
        try {
            const descriptor = Object.getOwnPropertyDescriptor(obj, prop);
            if (descriptor && descriptor.configurable) {
                Object.defineProperty(obj, prop, {
                    get: function() {
                        return 'auto';
                    },
                    set: function(value) {
                        // Ignore any attempts to set cursor
                        return;
                    },
                    configurable: true
                });
            }
        } catch (e) {
            // Silently fail
        }
    }
    
    // Wait for body to exist
    function setupOverrides() {
        if (document.body) {
            overrideCursorProperty(document.documentElement.style, 'cursor');
            overrideCursorProperty(document.body.style, 'cursor');
        }
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupOverrides);
    } else {
        setupOverrides();
    }
    
    // Monitor and remove cursor classes
    const cursorClassPattern = /cursor-(?:wait|progress|text|pointer|help|move|grab|grabbing|not-allowed|no-drop|zoom-in|zoom-out|alias|copy|cell|crosshair|e-resize|n-resize|ne-resize|nw-resize|s-resize|se-resize|sw-resize|w-resize|ew-resize|ns-resize|nesw-resize|nwse-resize|col-resize|row-resize|all-scroll|vertical-text)/;
    
    function cleanupClasses(element) {
        if (element.className && typeof element.className === 'string') {
            const classes = element.className.split(' ');
            const filtered = classes.filter(cls => !cursorClassPattern.test(cls));
            if (filtered.length !== classes.length) {
                element.className = filtered.join(' ');
            }
        }
    }
    
    // Cleanup all elements periodically
    setInterval(function() {
        const all = document.querySelectorAll('*');
        all.forEach(cleanupClasses);
    }, 1000);
    
    console.log('🛡️ Super aggressive cursor protection active!');
})();
