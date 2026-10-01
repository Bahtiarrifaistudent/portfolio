// Converts the first page of a certificate PDF (or an image) into a web-ready image.
// Output: max 1600px wide JPEG.
//
// Usage: node scripts/convert-certificate.mjs <input.pdf|jpg|png|heic> <output.jpg>
import { readFile, mkdir } from 'node:fs/promises';
import { dirname } from 'node:path';
import sharp from 'sharp';

const [input, output] = process.argv.slice(2);
if (!input || !output) {
    console.error('Usage: node scripts/convert-certificate.mjs <input.pdf|jpg|png|heic> <output.jpg>');
    process.exit(1);
}

let buffer = await readFile(input);

if (/\.pdf$/i.test(input)) {
    // Render page 1 with pdf.js + a native canvas (no Ghostscript/Poppler needed)
    const { createCanvas } = await import('@napi-rs/canvas');
    const pdfjs = await import('pdfjs-dist/legacy/build/pdf.mjs');
    const doc = await pdfjs.getDocument({ data: new Uint8Array(buffer), verbosity: 0 }).promise;
    const page = await doc.getPage(1);
    const base = page.getViewport({ scale: 1 });
    const viewport = page.getViewport({ scale: Math.min(4, 2000 / Math.max(base.width, base.height)) });
    const canvas = createCanvas(Math.round(viewport.width), Math.round(viewport.height));
    const ctx = canvas.getContext('2d');
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    await page.render({ canvasContext: ctx, canvas, viewport }).promise;
    buffer = canvas.toBuffer('image/png');
    await doc.destroy();
} else if (/\.(heic|heif)$/i.test(input)) {
    const { default: convert } = await import('heic-convert');
    buffer = Buffer.from(await convert({ buffer, format: 'JPEG', quality: 0.92 }));
}

await mkdir(dirname(output), { recursive: true });
const info = await sharp(buffer)
    .rotate()
    .flatten({ background: '#ffffff' })
    .resize({ width: 1600, withoutEnlargement: true })
    .jpeg({ quality: 85, mozjpeg: true })
    .toFile(output);
console.log(`OK ${output} (${info.width}x${info.height}, ${Math.round(info.size / 1024)} KB)`);
