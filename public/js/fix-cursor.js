/**
 * AGGRESSIVE FIX untuk cursor typing/text yang selalu muncul
 * Script ini FORCE reset cursor ke auto/default
 */

(function() {
    'use strict';
    
    const BAD_CURSORS = ['wait', 'progress', 'text', 'help', 'context-menu'];
    
    // Force reset cursor untuk semua elemen
    function forceResetCursor() {
        // Reset html dan body
        document.documentElement.style.setProperty('cursor', 'auto', 'important');
        document.body.style.setProperty('cursor', 'auto', 'important');
        
        // Hapus style cursor dari semua elemen
        const allElements = document.querySelectorAll('[style*="cursor"]');
        allElements.forEach(el => {
            const style = el.getAttribute('style');
            if (style && (style.includes('cursor: text') || 
                         style.includes('cursor:text') ||
                         style.includes('cursor: wait') ||
                         style.includes('cursor:wait') ||
                         style.includes('cursor: progress') ||
                         style.includes('cursor:progress'))) {
                el.style.cursor = 'auto';
            }
        });
    }
    
    // Cek dan reset cursor yang salah
    function checkAndResetCursor(element) {
        try {
            const computedStyle = window.getComputedStyle(element);
            const currentCursor = computedStyle.cursor;
            
            // Jika cursor adalah salah satu yang tidak diinginkan
            if (BAD_CURSORS.includes(currentCursor)) {
                element.style.setProperty('cursor', 'auto', 'important');
                return true;
            }
        } catch (e) {
            // Ignore errors
        }
        return false;
    }
    
    // Reset cursor secara berkala
    function continuousReset() {
        forceResetCursor();
        
        // Periksa body dan html
        checkAndResetCursor(document.documentElement);
        checkAndResetCursor(document.body);
    }
    
    // Jalankan saat load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', continuousReset);
    } else {
        continuousReset();
    }
    
    // Monitor perubahan DOM
    const observer = new MutationObserver(function(mutations) {
        let needReset = false;
        
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                if (checkAndResetCursor(mutation.target)) {
                    needReset = true;
                }
            } else if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === 1) { // Element node
                        checkAndResetCursor(node);
                    }
                });
            }
        });
        
        if (needReset) {
            forceResetCursor();
        }
    });
    
    // Observe body dan html
    observer.observe(document.body, {
        attributes: true,
        attributeFilter: ['style', 'class'],
        childList: true,
        subtree: true
    });
    
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['style', 'class']
    });
    
    // Reset pada setiap event
    const events = ['click', 'mousedown', 'mouseup', 'mousemove', 'touchstart', 'touchend'];
    events.forEach(eventType => {
        document.addEventListener(eventType, function(e) {
            // Reset target element
            if (e.target && e.target.nodeType === 1) {
                checkAndResetCursor(e.target);
            }
            
            // Reset body dan html jika perlu
            setTimeout(() => {
                checkAndResetCursor(document.documentElement);
                checkAndResetCursor(document.body);
            }, 0);
        }, true);
    });
    
    // Reset setiap 500ms untuk memastikan
    setInterval(continuousReset, 500);
    
    // Override CSS cursor property secara global
    try {
        const style = document.createElement('style');
        style.id = 'force-cursor-fix';
        style.innerHTML = `
            html, html *, body, body * {
                cursor: auto !important;
            }
            a, a *, button, button *, [onclick], [role="button"], 
            input[type="button"], input[type="submit"], input[type="reset"], select {
                cursor: pointer !important;
            }
            input[type="text"], input[type="email"], input[type="password"],
            input[type="search"], input[type="tel"], input[type="url"],
            input[type="number"], textarea {
                cursor: text !important;
            }
        `;
        document.head.appendChild(style);
    } catch (e) {
        console.error('Could not inject cursor fix styles:', e);
    }
    
    console.log('✅ AGGRESSIVE Cursor fix loaded and running!');
    console.log('🔄 Monitoring and resetting cursor every 500ms');
})();
