<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import axios from 'axios';

// Overall GitHub contribution graph (all years).
// Data from the /github/contributions route (Laravel, cached for 6 hours).

const data = ref(null);
const status = ref('loading'); // loading | ready | error
const range = ref('last'); // 'last' = last 12 months, or a year number
const hover = ref(null);
const root = ref(null);
const scroller = ref(null);
const visible = ref(false);

const CELL = 12;
const GAP = 3;
const STEP = CELL + GAP;
const LEFT = 30; // space for day labels
const TOP = 18; // space for month labels

const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
const dayLabels = { 1: 'Mon', 3: 'Wed', 5: 'Fri' };

const fmt = (iso) => {
    const [y, m, d] = iso.split('-').map(Number);
    return `${monthNames[m - 1]} ${d}, ${y}`;
};
const parse = (iso) => {
    const [y, m, d] = iso.split('-').map(Number);
    return new Date(Date.UTC(y, m - 1, d));
};

async function load() {
    status.value = 'loading';
    try {
        const res = await axios.get('/github/contributions');
        data.value = res.data;
        status.value = 'ready';
    } catch (e) {
        status.value = 'error';
    }
}

const tabs = computed(() => {
    if (!data.value) return [];
    return [{ key: 'last', label: 'Last 12 months' }, ...[...data.value.years].reverse().map((y) => ({ key: y.year, label: String(y.year) }))];
});

// Days in the selected range
const rangeDays = computed(() => {
    if (!data.value) return [];
    const all = data.value.days;
    if (range.value === 'last') {
        const last = parse(all[all.length - 1][0]);
        const from = new Date(last);
        from.setUTCDate(from.getUTCDate() - 364);
        const fromIso = from.toISOString().slice(0, 10);
        return all.filter((d) => d[0] >= fromIso);
    }
    return all.filter((d) => d[0].startsWith(String(range.value)));
});

const rangeTotal = computed(() => rangeDays.value.reduce((s, d) => s + d[1], 0));

// Arrange into week columns (Sunday = row 0)
const grid = computed(() => {
    const days = rangeDays.value;
    if (!days.length) return { cells: [], months: [], weeks: 0 };
    const offset = parse(days[0][0]).getUTCDay();
    const cells = days.map((d, i) => {
        const idx = i + offset;
        return { date: d[0], count: d[1], level: d[2], week: Math.floor(idx / 7), dow: idx % 7 };
    });
    const months = [];
    let lastMonth = -1;
    cells.forEach((c) => {
        const m = Number(c.date.slice(5, 7)) - 1;
        if (m !== lastMonth && c.dow <= 3) {
            months.push({ label: monthNames[m], week: c.week });
            lastMonth = m;
        }
    });
    return { cells, months, weeks: cells[cells.length - 1].week + 1 };
});

const svgWidth = computed(() => LEFT + grid.value.weeks * STEP);
const svgHeight = TOP + 7 * STEP;

const maxYear = computed(() => Math.max(1, ...(data.value?.years ?? []).map((y) => y.total)));

const levelClass = ['cg-l0', 'cg-l1', 'cg-l2', 'cg-l3', 'cg-l4'];

function showTip(c, e) {
    const box = root.value.getBoundingClientRect();
    const r = e.target.getBoundingClientRect();
    hover.value = { ...c, x: r.left - box.left + r.width / 2, y: r.top - box.top };
}

// Scroll the heatmap to the right edge (latest data) on narrow screens
watch(range, async () => {
    await nextTick();
    if (scroller.value) scroller.value.scrollLeft = scroller.value.scrollWidth;
});

let observer;
onMounted(() => {
    observer = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting) {
                visible.value = true;
                if (!data.value) load().then(() => nextTick(() => scroller.value && (scroller.value.scrollLeft = scroller.value.scrollWidth)));
                observer.disconnect();
            }
        },
        { rootMargin: '200px' },
    );
    observer.observe(root.value);
});
onBeforeUnmount(() => observer?.disconnect());
</script>

<template>
    <div ref="root" class="relative">
        <!-- Loading -->
        <div v-if="status === 'loading'" class="card card-static grid gap-4 p-6 sm:p-8">
            <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                <div v-for="i in 4" :key="i" class="h-20 animate-pulse rounded-xl bg-surface-2" />
            </div>
            <div class="h-40 animate-pulse rounded-xl bg-surface-2" />
        </div>

        <!-- Error -->
        <div v-else-if="status === 'error'" class="card flex flex-col items-center gap-3 p-10 text-center">
            <p class="font-semibold text-ink">The contribution graph could not be loaded.</p>
            <p class="text-sm text-muted">Please try again shortly, or view it directly on GitHub.</p>
            <div class="mt-2 flex gap-2">
                <button type="button" class="btn-ghost" @click="load">Try again</button>
                <a href="https://github.com/Bahtiarrifaistudent" target="_blank" rel="noopener" class="btn-primary">Open GitHub</a>
            </div>
        </div>

        <template v-else-if="data">
            <!-- Summary -->
            <dl class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                <div class="card p-5">
                    <dt class="text-xs text-muted">Total contributions</dt>
                    <dd class="mt-1 font-display text-3xl font-bold text-ink">{{ data.total.toLocaleString('en-US') }}</dd>
                    <dd class="mt-0.5 font-mono text-[11px] text-muted">since {{ data.stats.since ? fmt(data.stats.since) : '-' }}</dd>
                </div>
                <div class="card p-5">
                    <dt class="text-xs text-muted">Active days</dt>
                    <dd class="mt-1 font-display text-3xl font-bold text-ink">{{ data.stats.active_days }}</dd>
                    <dd class="mt-0.5 font-mono text-[11px] text-muted">days with commits</dd>
                </div>
                <div class="card p-5">
                    <dt class="text-xs text-muted">Longest streak</dt>
                    <dd class="mt-1 font-display text-3xl font-bold text-ink">{{ data.stats.longest_streak }} <span class="text-base font-medium text-muted">days</span></dd>
                    <dd class="mt-0.5 font-mono text-[11px] text-muted">current streak: {{ data.stats.current_streak }} days</dd>
                </div>
                <div class="card p-5">
                    <dt class="text-xs text-muted">Best day</dt>
                    <dd class="mt-1 font-display text-3xl font-bold text-ink">{{ data.stats.best_day?.count ?? 0 }}</dd>
                    <dd class="mt-0.5 font-mono text-[11px] text-muted">{{ data.stats.best_day ? fmt(data.stats.best_day.date) : '-' }}</dd>
                </div>
            </dl>

            <div class="mt-4 grid gap-4 lg:grid-cols-[1fr_16rem]">
                <!-- Heatmap -->
                <div class="card card-static min-w-0 p-5 sm:p-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <p class="text-sm text-ink">
                            <span class="font-display text-lg font-bold">{{ rangeTotal.toLocaleString('en-US') }}</span>
                            contributions {{ range === 'last' ? 'in the last 12 months' : `in ${range}` }}
                        </p>
                        <div class="-mx-1 flex gap-1 overflow-x-auto px-1" role="tablist" aria-label="Time range">
                            <button
                                v-for="t in tabs"
                                :key="t.key"
                                type="button"
                                role="tab"
                                :aria-selected="range === t.key"
                                class="shrink-0 rounded-lg px-3 py-1.5 text-xs font-semibold transition"
                                :class="range === t.key ? 'bg-ink text-bg' : 'text-muted hover:bg-surface-2 hover:text-ink'"
                                @click="range = t.key"
                            >
                                {{ t.label }}
                            </button>
                        </div>
                    </div>

                    <div ref="scroller" class="mt-5 overflow-x-auto pb-2">
                        <svg
                            :key="range"
                            :width="svgWidth"
                            :height="svgHeight"
                            :viewBox="`0 0 ${svgWidth} ${svgHeight}`"
                            class="block"
                            role="img"
                            :aria-label="`Contribution graph: ${rangeTotal} contributions`"
                            @mouseleave="hover = null"
                        >
                            <text v-for="m in grid.months" :key="m.label + m.week" :x="LEFT + m.week * STEP" y="11" class="fill-muted text-[10px]">{{ m.label }}</text>
                            <text v-for="(label, dow) in dayLabels" :key="dow" x="0" :y="TOP + dow * STEP + CELL - 2" class="fill-muted text-[10px]">{{ label }}</text>
                            <rect
                                v-for="c in grid.cells"
                                :key="c.date"
                                :x="LEFT + c.week * STEP"
                                :y="TOP + c.dow * STEP"
                                :width="CELL"
                                :height="CELL"
                                rx="3"
                                class="cg-cell"
                                :class="[levelClass[c.level], visible ? 'cg-in' : 'opacity-0']"
                                :style="{ animationDelay: `${c.week * 14}ms` }"
                                @mouseenter="showTip(c, $event)"
                            />
                        </svg>
                    </div>

                    <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-[11px] text-muted">
                        <a :href="data.url" target="_blank" rel="noopener" class="font-mono hover:text-accent">@{{ data.username }} on GitHub</a>
                        <span class="flex items-center gap-1">
                            Less
                            <svg v-for="l in 5" :key="l" width="12" height="12"><rect width="12" height="12" rx="3" :class="levelClass[l - 1]" /></svg>
                            More
                        </span>
                    </div>
                </div>

                <!-- Totals per year -->
                <div class="card p-5 sm:p-6">
                    <p class="text-sm font-semibold text-ink">Per year</p>
                    <ul class="mt-4 space-y-3">
                        <li v-for="y in [...data.years].reverse()" :key="y.year">
                            <button type="button" class="group w-full text-left" @click="range = y.year">
                                <span class="flex items-baseline justify-between text-sm">
                                    <span class="font-mono" :class="range === y.year ? 'font-bold text-accent' : 'text-ink'">{{ y.year }}</span>
                                    <span class="text-muted">{{ y.total }}</span>
                                </span>
                                <span class="mt-1.5 block h-2 overflow-hidden rounded-full bg-surface-2">
                                    <span
                                        class="block h-full rounded-full bg-accent transition-[width] duration-1000 ease-out group-hover:opacity-80"
                                        :style="{ width: visible ? `${Math.max(2, (y.total / maxYear) * 100)}%` : '0%' }"
                                    />
                                </span>
                            </button>
                        </li>
                    </ul>
                    <p class="mt-5 font-mono text-[10px] text-muted">Click a year to see its graph.</p>
                </div>
            </div>

            <!-- Tooltip -->
            <div
                v-if="hover"
                class="pointer-events-none absolute z-10 -translate-x-1/2 -translate-y-full rounded-lg bg-ink px-2.5 py-1.5 text-xs whitespace-nowrap text-bg shadow-lg"
                :style="{ left: hover.x + 'px', top: hover.y - 6 + 'px' }"
            >
                <span class="font-semibold">{{ hover.count ? `${hover.count} ${hover.count === 1 ? 'contribution' : 'contributions'}` : 'No contributions' }}</span>
                on {{ fmt(hover.date) }}
            </div>
        </template>
    </div>
</template>
