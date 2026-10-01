// Project category labels & colors. Add new categories here.
export const projectCategories = {
    web: { label: 'Web', cls: 'text-laravel bg-laravel/10' },
    security: { label: 'Security', cls: 'text-cyan bg-cyan/10' },
    ai: { label: 'AI & Data', cls: 'text-accent-2 bg-accent-2/10' },
    mobile: { label: 'Mobile', cls: 'text-emerald-500 bg-emerald-500/10' },
    robotics: { label: 'Robotics', cls: 'text-amber-500 bg-amber-500/10' },
    community: { label: 'Community', cls: 'text-rose-500 bg-rose-500/10' },
};

export function categoryOf(key) {
    return projectCategories[key] ?? { label: key, cls: 'text-muted bg-surface-2' };
}
