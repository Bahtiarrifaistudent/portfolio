// Converts a folder (or list) of photos (HEIC/JPG/PNG/WEBP) into web-ready gallery images.
// Keeps the full photo (no cropping), max 1600px wide, JPEG.
//
// Usage: node scripts/convert-gallery.mjs <input folder or files...> --out=public/images/experience/company-name
import { readdir, readFile, mkdir, stat } from 'node:fs/promises';
import { basename, extname, join } from 'node:path';
import convert from 'heic-convert';
import sharp from 'sharp';

const args = process.argv.slice(2);
const out = (args.find((a) => a.startsWith('--out=')) ?? '--out=public/images/gallery').slice(6).replace(/\\/g, '/');
const inputs = args.filter((a) => !a.startsWith('--'));
const exts = /\.(heic|heif|jpe?g|png|webp)$/i;

let files = [];
for (const input of inputs) {
    if ((await stat(input)).isDirectory()) {
        files.push(...(await readdir(input)).filter((f) => exts.test(f)).sort().map((f) => join(input, f)));
    } else if (exts.test(input)) {
        files.push(input);
    }
}
if (!files.length) {
    console.error('No photos found.');
    process.exit(1);
}

await mkdir(out, { recursive: true });
const urls = [];
for (const [i, file] of files.entries()) {
    let buffer = await readFile(file);
    if (/\.(heic|heif)$/i.test(file)) buffer = Buffer.from(await convert({ buffer, format: 'JPEG', quality: 0.92 }));
    const name = `${String(i + 1).padStart(2, '0')}-${basename(file, extname(file)).toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '')}.jpg`;
    const dest = `${out}/${name}`;
    const info = await sharp(buffer).rotate().resize({ width: 1600, withoutEnlargement: true }).jpeg({ quality: 82, mozjpeg: true }).toFile(dest);
    urls.push('/' + dest.replace(/^public\//, ''));
    console.log(`OK ${dest} (${info.width}x${info.height}, ${Math.round(info.size / 1024)} KB)`);
}

console.log("\nPaste into config/portfolio.php:\n'photos' => [");
for (const u of urls) console.log(`    '${u}',`);
console.log('],');
