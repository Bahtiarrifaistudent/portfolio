// Converts a photo (HEIC/HEIF/JPG/PNG/WEBP) into a 4:5 web-ready profile photo.
//
// Usage:
//   node scripts/convert-photo.mjs <photo> [output.jpg] [--x=50] [--y=50] [--zoom=1]
//
//   --x     horizontal focus point in % (0 = left, 50 = center, 100 = right)
//   --y     vertical focus point in %   (0 = top,  50 = center, 100 = bottom)
//   --zoom  1 = largest possible crop, 1.3 = zoom in 30%, etc.
import { readFile, mkdir } from 'node:fs/promises';
import { dirname } from 'node:path';
import convert from 'heic-convert';
import sharp from 'sharp';

const args = process.argv.slice(2);
const opts = Object.fromEntries(args.filter((a) => a.startsWith('--')).map((a) => a.slice(2).split('=')));
const [input, output = 'public/images/profile.jpg'] = args.filter((a) => !a.startsWith('--'));

if (!input) {
    console.error('Usage: node scripts/convert-photo.mjs <photo> [output.jpg] [--x=50] [--y=50] [--zoom=1]');
    process.exit(1);
}

const clamp = (v, min, max) => Math.min(max, Math.max(min, v));
const fx = clamp(Number(opts.x ?? 50), 0, 100) / 100;
const fy = clamp(Number(opts.y ?? 50), 0, 100) / 100;
const zoom = clamp(Number(opts.zoom ?? 1), 1, 4);

let buffer = await readFile(input);

// Browsers cannot display HEIC, so decode it to JPEG first
if (/\.(heic|heif)$/i.test(input)) {
    buffer = Buffer.from(await convert({ buffer, format: 'JPEG', quality: 0.95 }));
}

// Apply EXIF orientation first so width/height are the real, upright size
const upright = await sharp(buffer).rotate().toBuffer();
const { width: W, height: H } = await sharp(upright).metadata();

// Largest 4:5 box that fits, shrunk by zoom, centered on the focus point
const RATIO = 4 / 5;
let cw = W / H > RATIO ? Math.round(H * RATIO) : W;
let ch = W / H > RATIO ? H : Math.round(W / RATIO);
cw = Math.round(cw / zoom);
ch = Math.round(ch / zoom);
const left = clamp(Math.round(fx * W - cw / 2), 0, W - cw);
const top = clamp(Math.round(fy * H - ch / 2), 0, H - ch);

await mkdir(dirname(output), { recursive: true });

const info = await sharp(upright)
    .extract({ left, top, width: cw, height: ch })
    .resize(1000, 1250)
    .jpeg({ quality: 84, mozjpeg: true })
    .toFile(output);

console.log(`Original ${W}x${H} -> crop ${cw}x${ch} at (${left},${top}) [x=${opts.x ?? 50}% y=${opts.y ?? 50}% zoom=${zoom}]`);
console.log(`OK ${output} (${info.width}x${info.height}, ${Math.round(info.size / 1024)} KB)`);
