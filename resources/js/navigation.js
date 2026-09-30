// Main menu. Change labels or order here.
export const navLinks = [
    { href: '/', label: 'Home' },
    { href: '/about', label: 'About' },
    { href: '/projects', label: 'Projects' },
    { href: '/experience', label: 'Experience' },
    { href: '/certificates', label: 'Certificates' },
];

export function isActive(currentUrl, href) {
    const path = currentUrl.split('?')[0];
    return href === '/' ? path === '/' : path === href || path.startsWith(`${href}/`);
}
