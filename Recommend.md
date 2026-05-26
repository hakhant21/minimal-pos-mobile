# Recommendations

## Monthly Profit & Loss Analysis

### Current Limitation: Profit Cannot Be Calculated

The database has **no cost/purchase price** data anywhere:

| Table | Price Field | Purpose |
|-------|------------|---------|
| `units` | `price` | **Selling price** only |
| `sale_items` | `unit_price`, `subtotal` | **Revenue** (selling price × qty) |
| `products` | — | No price fields |

**What we can calculate:**
- **Revenue** = `SUM(sale_items.subtotal)` per month
- **Units sold** = `SUM(sale_items.quantity)` per month

**What we cannot calculate:**
- Cost of Goods Sold (COGS) — no cost data
- **Gross Profit** = Revenue − COGS
- **Net Profit** = Gross Profit − Expenses
- **Loss** = when costs exceed revenue
- **Profit margin per product** or per unit

Without a cost/purchase price, profit/loss is mathematically impossible.

### Minimum Schema Change Needed

Add `cost_price` to the `units` table:

```php
Schema::table('units', function (Blueprint $table) {
    $table->decimal('cost_price', 10, 2)->nullable()->after('price');
});
```

Then profit queries become possible:

```sql
-- Monthly gross profit
SELECT
    DATE_FORMAT(si.created_at, '%Y-%m') AS month,
    SUM(si.subtotal) AS revenue,
    SUM(si.quantity * u.cost_price) AS cogs,
    SUM(si.subtotal) - SUM(si.quantity * u.cost_price) AS gross_profit
FROM sale_items si
JOIN units u ON u.id = si.unit_id
GROUP BY month
ORDER BY month DESC;
```

---

## Recommended Features (Priority Order)

### P1 — Critical

- [ ] **Cost price on units** — add `cost_price` column; needs UI in product/unit creation forms
- [ ] **Stock-in with costs** — when adding stock (`Instock` component), record purchase cost per unit; track total inventory value
- [ ] **Monthly P&L report** — new Livewire page with month selector, revenue/cogs/profit per product, profit margin %, and export
- [ ] **Inventory valuation** — show total stock value (quantity × cost_price) on dashboard and inventory page

### P2 — Important

- [ ] **Supplier management** — `suppliers` table (name, contact, payment terms); link to stock-in records
- [ ] **Expense tracking** — `expenses` table (category, amount, date, notes); include in P&L for net profit
- [ ] **Discount & tax on sales** — `discount` and `tax` columns on `sales` table; line-item discounts
- [ ] **User/sales attribution** — `user_id` on `sales` table; track who made each sale; per-user sales reports
- [ ] **Inventory adjustments** — write-offs, damage, returns with reason tracking; adjust stock and record loss
- [ ] **Dashboard P&L summary** — current month revenue, COGS, gross profit %, comparison with prior month

### P3 — Nice to Have

- [ ] **Purchase orders** — full PO workflow: create PO, receive items, track partial receipts, cost variance reports
- [ ] **Barcode/QR scanning** — mobile-friendly product/unit lookup via camera
- [ ] **Customer management** — `customers` table; track purchase history, loyalty points
- [ ] **Low stock alerts** — email/in-app notifications when stock drops below threshold
- [ ] **Export reports** — PDF/CSV export for P&L, sales, inventory reports
- [ ] **Dashboard trends** — charts for daily/weekly revenue, top-selling products, stock movement
- [ ] **Audit log** — track who created/updated/deleted products, sales, stock adjustments

---

## Architecture Notes

- All new Livewire components should follow the existing pattern (`app/Livewire/`, full-page SFC in `resources/views/pages/`)
- Reports should use read-only DB queries (no writes) and support date range filtering
- Cost prices should be settable per-unit at creation time and adjustable via stock-in
- P&L reports should be paginated or lazy-loaded as data grows
