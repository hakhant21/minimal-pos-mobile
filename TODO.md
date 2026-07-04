# TODO: Refactor Reusable Blade Components

Extract repeated UI patterns into Blade components under `resources/views/components/`.

## Priority Order

### P1 — High Impact, Low Complexity

- [ ] **`<x-card>`** — Card wrapper (`rounded-xl border border-gray-200/80 bg-white p-5 shadow-sm` + dark mode). Used 20+ times across all pages. Slot for content.

- [ ] **`<x-section-header>`** — Section header with gradient icon box + heading (`flex items-center gap-3 > h-10 w-10 rounded-xl bg-linear-to-br from-{color}-500 to-{color}-600` + `h2`). Props: `color` (emerald/indigo/amber/blue/violet), `icon` (SVG path), `title`. Used 7 times.

- [ ] **`<x-btn-primary>`** — Primary gradient button (`rounded-xl bg-linear-to-r from-{color}-600 to-{color}-700 px-3.5 py-2.5 text-sm font-semibold text-white shadow-lg`). Props: `color` (default emerald), `size` (sm/md/lg), `href` or `wire:click`. Used 8+ times.

- [ ] **`<x-btn-secondary>`** — Secondary outline button (`rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700`). Used 6+ times.

- [ ] **`<x-alert>`** — Success/error notification banner. Props: `type` (success/error), slot for message. Used 8+ times.

### P2 — Moderate Impact

- [ ] **`<x-input>`** — Text input with label, error display. Props: `label`, `name`, `model` (wire:model), `error`, `placeholder`, `color` (focus ring color). Used 18+ times.

- [ ] **`<x-input-search>`** — Search input with search icon. Extends `<x-input>` with search icon prepended. Used 3 times.

- [ ] **`<x-badge>`** — Badge pill. Props: `color` (gray/red/blue/emerald/amber), `size` (sm/md). Used 6+ times.

- [ ] **`<x-empty-state>`** — Empty state with icon, message, optional CTA. Props: `icon` (SVG path), `message`, `action` (optional button). Used 5 times.

- [ ] **`<x-icon-btn>`** — Icon button (edit/delete). Props: `type` (edit/delete), `color` (accent color), `wire:click`. Used 4 times.

### P3 — Nice to Have

- [ ] **`<x-table>`** — Table wrapper with header + scrollable table. Props: `title`, slot for table content. Used 3 times.

- [ ] **`<x-modal-delete>`** — Delete confirmation modal. Props: `title`, `message`, `wire:click` for confirm, `wire:click` for cancel. Used 4 times.

- [ ] **`<x-stat-card>`** — Dashboard stat card with icon, label, value, decorative circle. Props: `label`, `value`, `icon`, `color`, `condition` (for conditional styling). Used 4 times on dashboard.

- [ ] **`<x-form-actions>`** — Inline form action buttons (primary + cancel). Props: `submit` (wire:click), `cancel` (wire:click), `submitText`, `cancelText`. Used 4 times.

## Convention

- All components go in `resources/views/components/`
- Use `{color}` prop for accent colors where applicable
- Dark mode variants included by default
- Follow existing `<x-load-more>` and `<x-app>` component patterns
