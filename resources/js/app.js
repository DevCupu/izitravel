

import Alpine from 'alpinejs';
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

Alpine.start();
