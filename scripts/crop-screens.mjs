// Removes the browser bar (tabs + address bar) at the top and the Windows taskbar at the bottom
// from full-screen screenshots, so only the application itself is left.
//
// Detection: rows that are identical in every screenshot and end at a full-width line are
// browser/taskbar UI (page content differs between screenshots). Override with --top / --bottom.
//
// Usage:
//   node scripts/crop-screens.mjs <folder> [--dry] [--top=87] [--bottom=48] [--left=2] [--right=2] [--preview=preview.jpg]
//   Sizes are in pixels of the images in <folder>. Parts already cropped in an earlier run are skipped.
import { existsSync } from 'node:fs';
import { readdir, readFile, rename, writeFile } from 'node:fs/promises';
import { join } from 'node:path';
import sharp from 'sharp';

const args = process.argv.slice(2);
const dir = args.find((a) => !a.startsWith('--'));
const opt = (k) => args.find((a) => a.startsWith(`--${k}=`))?.split('=')[1];
const dry = args.includes('--dry');
if (!dir) {
    console.error('Usage: node scripts/crop-screens.mjs <folder> [--dry] [--top=N] [--bottom=N] [--preview=file.jpg]');
    process.exit(1);
}

const files = (await readdir(dir)).filter((f) => !f.startsWith('.') && /\.(jpe?g|png|webp)$/i.test(f)).sort();
if (!files.length) {
    console.error('No images found.');
    process.exit(1);
}

// Only images with the most common size are compared (screenshots of the same screen)
const metas = await Promise.all(files.map((f) => sharp(join(dir, f)).metadata()));
const sizeKey = (m) => `${m.width}x${m.height}`;
const counts = {};
metas.forEach((m) => (counts[sizeKey(m)] = (counts[sizeKey(m)] ?? 0) + 1));
const common = Object.entries(counts).sort((a, b) => b[1] - a[1])[0][0];
const [W, H] = common.split('x').map(Number);
const group = files.filter((_, i) => sizeKey(metas[i]) === common);

const raws = await Promise.all(group.map((f) => sharp(join(dir, f)).removeAlpha().raw().toBuffer()));
const px = (buf, x, y, c) => buf[(y * W + x) * 3 + c];
const step = Math.max(1, Math.floor(W / 400)); // sample ~400 columns per row

// S(y): share of columns where every screenshot has (almost) the same pixel
function same(y) {
    if (raws.length < 2) return 1;
    let ok = 0, n = 0;
    for (let x = 0; x < W; x += step, n++) {
        let match = true;
        for (let i = 1; i < raws.length && match; i++) {
            for (let c = 0; c < 3; c++) if (Math.abs(px(raws[i], x, y, c) - px(raws[0], x, y, c)) > 14) { match = false; break; }
        }
        if (match) ok++;
    }
    return ok / n;
}
// E(y): share of columns with a clear change between row y-1 and row y (a full-width line)
function edge(y) {
    let hit = 0, n = 0;
    for (let x = 0; x < W; x += step, n++) {
        let d = 0;
        for (const buf of raws) for (let c = 0; c < 3; c++) d += Math.abs(px(buf, x, y, c) - px(buf, x, y - 1, c));
        if (d / raws.length > 24) hit++;
    }
    return hit / n;
}
const meanSame = (a, b) => {
    let s = 0;
    for (let y = a; y < b; y++) s += same(y);
    return b > a ? s / (b - a) : 1;
};

const u = H / 1080; // 1 "1080p pixel"
function detectTop() {
    if (raws.length < 2) return Math.round(87 * u); // single screenshot: typical Chrome/Edge bar height
    const lo = Math.round(30 * u), hi = Math.round(H * 0.16);
    // Grow over every full-width line whose strip above is identical in all screenshots
    // (tab strip, address bar, bookmarks bar). Page content differs, so growth stops there.
    let cut = 0;
    for (let y = lo; y < hi; y++) {
        if (edge(y) < 0.6) continue;
        if (!cut) {
            if (meanSame(0, y) >= 0.8) cut = y;
        } else if (y - cut <= Math.round(45 * u) && meanSame(cut, y) >= 0.95) {
            cut = y;
        } else if (y - cut > Math.round(45 * u)) {
            break;
        }
    }
    if (cut >= Math.round(50 * u)) return cut;

    // Fallback (no clear line, e.g. dark browser theme): the browser strip is the band at the top
    // that is identical in every screenshot. Find where the rows start to differ, then snap to the
    // closest visible line nearby.
    const win = Math.max(3, Math.round(6 * u));
    let start = 0;
    for (let y = Math.round(40 * u); y < hi; y++) {
        if (meanSame(y, y + win) < 0.9) { start = y; break; }
    }
    if (start) {
        let best = start, bestE = 0;
        for (let y = Math.max(1, start - 2 * win); y <= start + win; y++) {
            const e = edge(y);
            if (e > bestE) { bestE = e; best = y; }
        }
        return bestE >= 0.3 ? best : start;
    }
    return Math.round(87 * u); // typical Chrome/Edge bar height
}
function detectBottom() {
    if (raws.length < 2) return Math.round(48 * u); // single screenshot: typical Windows 11 taskbar
    let best = 0, bestY = 0;
    for (let y = H - Math.round(H * 0.08); y <= H - Math.round(30 * u); y++) {
        const e = edge(y);
        if (e >= 0.6 && meanSame(y, H) >= 0.5 && e >= best) { best = e; bestY = y; }
    }
    return bestY ? H - bestY : 0;
}

// Vertical line between column x-1 and x along most of the height (window border / scrollbar edge)
function edgeCol(x) {
    let hit = 0, n = 0;
    const rs = Math.max(1, Math.floor(H / 300));
    for (let y = 0; y < H; y += rs, n++) {
        let d = 0;
        for (const buf of raws) for (let c = 0; c < 3; c++) d += Math.abs(px(buf, x, y, c) - px(buf, x - 1, y, c));
        if (d / raws.length > 14) hit++;
    }
    return hit / n;
}
// Thin window border / scrollbar at the left or right edge (max ~24px)
function detectSide(fromLeft) {
    const max = Math.round(16 * u);
    let cut = 0;
    for (let i = 1; i <= max; i++) {
        const x = fromLeft ? i : W - i;
        if (edgeCol(x) >= 0.6) cut = i;
    }
    return cut;
}

// Previous run (.cropped = "top,bottom[,left,right]"): parts already removed are not detected again
const marker = join(dir, '.cropped');
const done = existsSync(marker) ? (await readFile(marker, 'utf8')).trim().split(',').map(Number) : null;
const pick = (k, i, detect) => (opt(k) !== undefined ? Number(opt(k)) : done && done[i] > 0 ? 0 : detect());
const top = pick('top', 0, detectTop);
const bottom = pick('bottom', 1, detectBottom);
const left = pick('left', 2, () => detectSide(true));
const right = pick('right', 3, () => detectSide(false));
console.log(`Image size ${W}x${H} (${group.length} screenshots compared)`);
console.log(`Crop: top ${top}px (browser bar), bottom ${bottom}px (taskbar), left ${left}px, right ${right}px (window border)`);

// Preview: first screenshot with the parts that will be removed shaded red
const preview = opt('preview');
if (preview) {
    const shade = (w, h) => ({ input: { create: { width: Math.max(1, w), height: Math.max(1, h), channels: 4, background: { r: 239, g: 68, b: 68, alpha: 0.6 } } } });
    const layers = [];
    if (top) layers.push({ ...shade(W, top), top: 0, left: 0 });
    if (bottom) layers.push({ ...shade(W, bottom), top: H - bottom, left: 0 });
    if (left) layers.push({ ...shade(left, H), top: 0, left: 0 });
    if (right) layers.push({ ...shade(right, H), top: 0, left: W - right });
    const marked = await sharp(join(dir, group[0])).composite(layers).png().toBuffer();
    await sharp(marked).resize({ width: Math.min(W, 1400) }).jpeg({ quality: 80 }).toFile(preview);
    console.log(`Preview: ${preview}`);
}

if (dry) process.exit(0);
if (!top && !bottom && !left && !right) {
    console.log('Nothing to crop.');
    process.exit(0);
}

const outW = W - left - right, outH = H - top - bottom;
const save = (pipeline, f, tmp) => (/\.png$/i.test(f) ? pipeline.png() : /\.webp$/i.test(f) ? pipeline.webp({ quality: 85 }) : pipeline.jpeg({ quality: 85, mozjpeg: true })).toFile(tmp);
const skipped = [];
for (const [i, f] of files.entries()) {
    const src = join(dir, f);
    const tmp = join(dir, `.tmp-${f}`);
    const m = metas[i];
    // Other sizes of the same screen shape (e.g. scaled down in Word) get the same crop, scaled
    const k = m.width / W;
    if (Math.abs(m.height / m.width - H / W) > 0.02) {
        skipped.push(f);
        continue;
    }
    const box = {
        left: Math.round(left * k),
        top: Math.round(top * k),
        width: Math.max(1, Math.round(m.width - (left + right) * k)),
        height: Math.max(1, Math.round(m.height - (top + bottom) * k)),
    };
    box.width = Math.min(box.width, m.width - box.left);
    box.height = Math.min(box.height, m.height - box.top);
    await save(sharp(src).extract(box), f, tmp);
    await rename(tmp, src);
    console.log(`OK ${f} -> ${box.width}x${box.height}`);
}
if (skipped.length) console.log(`Skipped (different screen shape, crop manually): ${skipped.join(', ')}`);
const prev = done ?? [0, 0, 0, 0];
await writeFile(marker, [top, bottom, left, right].map((v, i) => v + (prev[i] || 0)).join(',') + '\n');
