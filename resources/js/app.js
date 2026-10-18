

import Alpine from 'alpinejs';
import crmPipeline from './crm-pipeline';
import initRecordViews from './record-views';
import { createIcons, icons } from 'lucide';
import { animate, inView, stagger, spring, scroll } from 'motion';

window.Alpine = Alpine;
// Existing inline scripts call `lucide.createIcons()` with no args, so bind the
// full icon set by default instead of requiring `{ icons }` at every call site.
window.lucide = { createIcons: (options) => createIcons({ icons, ...options }), icons };

// Wrapped once here so every Motion.animate() call site in the inline page scripts
// automatically respects prefers-reduced-motion, instead of every caller needing its own check.
const reducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
const reducedAnimate = (target, keyframes, options = {}) =>
    animate(target, keyframes, reducedMotionQuery.matches ? { duration: 0.01 } : options);
window.Motion = { animate: reducedAnimate, inView, stagger, spring, scroll };

Alpine.data('crmPipeline', crmPipeline);
Alpine.start();

// Shared Blade navigation and page content use the same icon renderer.
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => { initRecordViews(); window.lucide.createIcons(); }, { once: true });
} else {
    initRecordViews();
    window.lucide.createIcons();
}
