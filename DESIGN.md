> Act as a **senior UI/UX designer and frontend engineer** building a **professional, minimal POS system dashboard** for real-world retail users.
> The dashboard must be **simple, clean, fast to understand, and role-based**.

---

### 1️⃣ General Design Requirements

-   Use a **modern flat design**
-   Prioritize **clarity over decoration**
-   Avoid visual clutter
-   Ensure **accessibility and high contrast**
-   Design must be **production-ready**, not conceptual

---

### 2️⃣ Color Palette (MANDATORY – Option 2)

Use the following exact colors consistently:

**Primary (Brand / Actions):**

-   Deep Green: `#166534`

**Secondary (Highlights / Positive info):**

-   Soft Green: `#22C55E`

**Backgrounds:**

-   Main background (off-white): `#F9FAFB`
-   Card background: `#FFFFFF`

**Text:**

-   Primary text (charcoal): `#111827`
-   Secondary text: `#374151`

**Status Colors:**

-   Success: `#22C55E`
-   Warning: `#EAB308`
-   Danger: `#DC2626`
-   Info: `#3B82F6`

🚫 Do NOT introduce new colors unless absolutely necessary.

---

### 3️⃣ Layout Structure (STRICT)

#### A. Top Navigation Bar

-   Height: compact (60–70px)
-   Left: system name or logo
-   Center: page title (“Dashboard”)
-   Right: user profile dropdown (name + role + logout)

#### B. Left Sidebar

-   Fixed position
-   Collapsible (icons-only mode)
-   Menu items with icon + label:

    -   Dashboard
    -   Sales
    -   Products
    -   Inventory
    -   Reports
    -   Settings (admin only)

-   Active menu item:

    -   Deep Green background
    -   White text
    -   Left indicator bar

#### C. Main Content Area

-   Responsive grid layout
-   Proper spacing between sections
-   Cards must have:

    -   Rounded corners
    -   Light border
    -   No heavy shadows

---

### 4️⃣ Dashboard Widgets to Implement

#### 1. KPI Cards (Top Section)

Create **4 KPI cards**:

-   Today’s Sales
-   Total Transactions
-   Low Stock Items
-   Profit Today

Each card must include:

-   Large numeric value
-   Small label
-   Subtle icon
-   Status color logic:

    -   Sales/Profit → Green
    -   Low stock → Yellow/Red

---

#### 2. Sales Overview Chart

-   Simple **line or bar chart**
-   Data examples:

    -   Sales by day (week)
    -   Sales by hour (today)

-   Use only:

    -   Deep Green
    -   Light grid lines

-   No unnecessary legends

---

#### 3. Quick Actions Panel

Include buttons for:

-   ➕ New Sale
-   ➕ Add Product
-   📦 Update Stock
-   🧾 View Receipts

Rules:

-   Use primary green for main action
-   Large click area
-   Icons + text
-   Consistent button height

---

#### 4. Alerts & Notifications

Display alerts such as:

-   “5 items below reorder level”
-   “Stock update required”

Design:

-   Compact alert cards
-   Icon + short text
-   Warning/Danger colors only when needed

---

#### 5. Recent Activity Table

Table showing:

-   Date
-   Action
-   Amount
-   User

Requirements:

-   Clean borders
-   Zebra rows optional
-   Responsive (horizontal scroll on mobile)

---

### 5️⃣ Role-Based Visibility Rules

#### Cashier Dashboard

-   Show:

    -   KPI cards (sales & transactions only)
    -   “Start New Sale” button
    -   Recent receipts

-   Hide:

    -   Inventory analytics
    -   Reports
    -   Settings

#### Manager Dashboard

-   Show:

    -   All KPI cards
    -   Inventory alerts
    -   Sales charts

-   No system configuration

#### Admin Dashboard

-   Full access
-   User activity metrics
-   System overview widgets

---

### 6️⃣ Typography Rules

-   Use a clean sans-serif font
-   Font hierarchy:

    -   KPIs: large & bold
    -   Headings: medium weight
    -   Labels: small & muted

-   Avoid decorative fonts

---

### 7️⃣ Responsiveness Requirements

-   Sidebar collapses on mobile
-   KPI cards stack vertically
-   Tables become scrollable
-   Buttons remain touch-friendly

---

### 8️⃣ Output Expectations

The final output should include:

-   Complete dashboard layout
-   Clean, readable UI code
-   Reusable components
-   Scalable structure for future features

🚫 Do NOT include mock data explanations
🚫 Do NOT over-style
✅ Focus on usability and professionalism
