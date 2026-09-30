// Daftar menu utama. Ubah label atau urutan di sini.
export const navLinks = [
    { href: '/', label: 'Beranda' },
    { href: '/tentang', label: 'Tentang' },
    { href: '/project', label: 'Project' },
    { href: '/pengalaman', label: 'Pengalaman' },
    { href: '/sertifikat', label: 'Sertifikat' },
];

export function isActive(currentUrl, href) {
    const path = currentUrl.split('?')[0];
    return href === '/' ? path === '/' : path === href || path.startsWith(`${href}/`);
}
