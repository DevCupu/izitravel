

import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';
import { animate, inView, stagger, spring, scroll } from 'motion';

window.Alpine = Alpine;
// Existing inline scripts call `lucide.createIcons()` with no args, so bind the
// full icon set by default instead of requiring `{ icons }` at every call site.
window.lucide = { createIcons: (options) => createIcons({ icons, ...options }), icons };
window.Motion = { animate, inView, stagger, spring, scroll };

Alpine.start();
