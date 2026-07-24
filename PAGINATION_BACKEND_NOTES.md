# PHP Backend Pagination Notes

Tags: #php #backend #mysql #pagination #security #web-development

## Big Picture

This implementation is an admin backend page that displays scholars from a database.

The backend is responsible for:

- Checking if the user is allowed to access the page.
- Reading search, filter, and page values from the URL.
- Building a safe SQL query.
- Counting how many records match the filters.
- Fetching only the records needed for the current page.
- Rendering the results into an HTML table.
- Creating pagination links that preserve the current filters.

The important idea:

> Pagination is not just a frontend button. The backend decides which records belong to the current page.

---

## Request Lifecycle

When an admin opens the page, PHP runs from top to bottom.

Example URL:

```txt
index.php?user=Juan&status=active&page=2
```

The backend reads:

```php
$search = $_GET['user'] ?? '';
$status = $_GET['status'] ?? '';
$page_setup = $_GET['page'] ?? 1;
```

This means:

- `user` controls the search keyword.
- `status` controls the scholar status filter.
- `page` controls the current pagination page.

Then the backend:

1. Builds SQL conditions.
2. Counts matching rows.
3. Calculates total pages.
4. Calculates the offset.
5. Fetches the current page of data.
6. Displays the result.

---

## Core Pagination Variables

```php
$page_shown = 5;
$page_setup = isset($_GET['page']) ? trim($_GET['page']) : 1;
```

Meaning:

| Variable | Meaning |
| --- | --- |
| `$page_shown` | Number of records shown per page |
| `$page_setup` | Current page number from the URL |
| `$total_rows` | Total records matching the search/filter |
| `$total_page` | Total number of pages needed |
| `$offset` | Number of rows MySQL should skip |

Example:

```txt
total rows = 23
records per page = 5
total pages = ceil(23 / 5) = 5
```

So the pages become:

| Page | Rows Shown |
| --- | --- |
| 1 | 1-5 |
| 2 | 6-10 |
| 3 | 11-15 |
| 4 | 16-20 |
| 5 | 21-23 |

---

## Pagination Formula

The most important formula:

```php
$offset = ($page_setup - 1) * $page_shown;
```

This tells MySQL how many records to skip before returning results.

Examples:

| Current Page | Formula | Offset |
| --- | --- | --- |
| 1 | `(1 - 1) * 5` | 0 |
| 2 | `(2 - 1) * 5` | 5 |
| 3 | `(3 - 1) * 5` | 10 |
| 4 | `(4 - 1) * 5` | 15 |

Then the SQL uses:

```sql
LIMIT ? OFFSET ?
```

Example for page 3:

```sql
SELECT * FROM scholars
ORDER BY created_at DESC
LIMIT 5 OFFSET 10
```

Meaning:

> Skip the first 10 records, then return the next 5 records.

---

## Why There Are Two Queries

Pagination usually needs two database queries.

### Query 1: Count Total Rows

```sql
SELECT COUNT(*) AS total_scholars FROM scholars
```

This answers:

> How many scholars match the current filters?

The answer is used to calculate the total number of pages.

### Query 2: Fetch Current Page Rows

```sql
SELECT * FROM scholars
ORDER BY created_at DESC
LIMIT ? OFFSET ?
```

This answers:

> Which scholars should be shown on this exact page?

Remember:

> Count query decides the pagination size. Data query displays the current page.

---

## Search And Filter Logic

The backend creates flexible SQL conditions.

```php
$conditions = [];
$params = [];
$types = "";
```

If the user searches:

```php
$conditions[] = "(fullname LIKE ? OR course LIKE ? OR scholarship_type LIKE ?)";
```

If the user filters by status:

```php
$conditions[] = "status=?";
```

Then all conditions are joined:

```php
$where_clause = !empty($conditions)
    ? " WHERE " . implode(" AND ", $conditions)
    : "";
```

If search and status are both used, the SQL becomes conceptually:

```sql
WHERE (fullname LIKE ? OR course LIKE ? OR scholarship_type LIKE ?)
AND status = ?
```

This is called dynamic query building.

The backend only adds SQL filters when the user actually uses them.

---

## Prepared Statements

The code uses prepared statements:

```php
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
```

Prepared statements separate SQL structure from user input.

Bad unsafe style:

```php
$sql = "SELECT * FROM scholars WHERE fullname LIKE '%$search%'";
```

Safer style:

```php
$sql = "SELECT * FROM scholars WHERE fullname LIKE ?";
$search_param = "%" . $search . "%";
$stmt->bind_param("s", $search_param);
```

Why this matters:

> User input should be treated as data, not executable SQL.

---

## `bind_param()` Types

MySQLi uses type letters:

| Type | Meaning |
| --- | --- |
| `s` | string |
| `i` | integer |
| `d` | double / decimal |
| `b` | blob |

In the pagination code:

```php
$data_types = $types . "ii";
```

The extra `"ii"` is for:

```php
LIMIT ?
OFFSET ?
```

Both are integers.

---

## Keeping Filters During Pagination

The helper function:

```php
function paginationLink($page_num) {
    $query = $_GET;
    $query['page'] = $page_num;
    return 'index.php?' . http_build_query($query);
}
```

This keeps the existing search/filter values when changing pages.

Example current URL:

```txt
index.php?user=Juan&status=active&page=1
```

When clicking page 2, the helper creates:

```txt
index.php?user=Juan&status=active&page=2
```

Without this helper, the search or status filter might disappear when changing pages.

Concept:

> Pagination links should preserve the current query state.

---

## Output Escaping

The page uses:

```php
htmlspecialchars($row['fullname'])
```

This protects the HTML page from stored XSS.

Example dangerous database value:

```html
<script>alert('hacked')</script>
```

If printed directly, the browser may execute it.

If escaped with `htmlspecialchars()`, it is displayed as text instead.

Rule:

> Escape output when displaying database values in HTML.

---

## Security Strengths In This Backend

Good practices used:

- Admin authentication is loaded before the page content.
- SQL values use prepared statements.
- Search values are bound instead of directly inserted into SQL.
- Pagination values for `LIMIT` and `OFFSET` are bound as integers.
- Most table values are escaped with `htmlspecialchars()`.
- Database errors shown to users do not expose raw SQL details.

---

## Security Risks To Remember

### 1. Page Number Should Be Strictly Validated

Current logic relies partly on PHP type conversion.

Better mental model:

```php
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
if (!$page || $page < 1) {
    $page = 1;
}
```

Concept:

> Validate input before using it in calculations.

### 2. Status Should Be Whitelisted

Even if SQL injection is prevented, the app should only accept valid statuses.

Example whitelist:

```php
$allowed_statuses = ['active', 'inactive', 'pending'];

if (!in_array($status, $allowed_statuses, true)) {
    $status = '';
}
```

Concept:

> Prepared statements protect SQL. Whitelists protect business rules.

### 3. Delete Should Not Use A Simple GET Link

Risky pattern:

```html
<a href="delete.php?id=5">DELETE</a>
```

Problem:

- A link can be clicked accidentally.
- A browser can preload it.
- A malicious site might trick an admin into visiting it.
- It is vulnerable to CSRF if delete happens immediately.

Safer pattern:

- Use a POST form.
- Add a CSRF token.
- Confirm authorization on the server.

Concept:

> GET should read data. POST should change data.

### 4. IDs Should Be Cast Or Escaped

The code prints IDs into links.

Safer style:

```php
$id = (int) $row['id'];
```

Concept:

> Even trusted database values should be handled carefully when printed into HTML.

### 5. Avoid `SELECT *`

Current query:

```sql
SELECT * FROM scholars
```

Better style:

```sql
SELECT id, student_id, fullname, course, year_level, scholarship_type, status
FROM scholars
```

Why:

- Less data is transferred.
- Sensitive future columns are not accidentally exposed.
- The page clearly documents what fields it needs.

Concept:

> Fetch only the data needed for the current feature.

---

## Performance Concepts

Offset pagination is simple and good for small to medium data.

But with very large tables, this can become slower:

```sql
LIMIT 5 OFFSET 50000
```

Why?

MySQL still has to skip many rows before returning the result.

For large systems, consider:

- Adding indexes.
- Using keyset pagination.
- Filtering by indexed columns.

Useful indexes for this page may include:

```sql
created_at
status
fullname
course
scholarship_type
```

Important note:

`LIKE '%keyword%'` is harder to optimize because the wildcard at the beginning prevents normal index usage.

---

## Offset Pagination Vs Keyset Pagination

### Offset Pagination

Example:

```sql
LIMIT 5 OFFSET 10
```

Pros:

- Easy to understand.
- Easy to jump to page 1, 2, 3, etc.
- Good for admin tables.

Cons:

- Can become slow for very high page numbers.
- Results can shift if new rows are inserted while browsing.

### Keyset Pagination

Example:

```sql
WHERE created_at < ?
ORDER BY created_at DESC
LIMIT 5
```

Pros:

- Faster for huge tables.
- Better for infinite scroll.

Cons:

- Harder to jump to exact page numbers.
- More complex to implement.

For this project, offset pagination is a reasonable choice.

---

## Common Pagination Bugs

### Bug: Page Less Than 1

Example:

```txt
index.php?page=-5
```

Fix:

```php
if ($page_setup < 1) {
    $page_setup = 1;
}
```

### Bug: Page Greater Than Total Pages

Example:

```txt
index.php?page=999
```

Fix:

```php
if ($page_setup > $total_page) {
    $page_setup = $total_page;
}
```

### Bug: Losing Search Filters

Example:

User searches for Juan, then clicks page 2, but search disappears.

Fix:

```php
http_build_query($_GET)
```

This preserves query parameters.

### Bug: Active Page CSS Not Applied Correctly

The page item should place `active` inside the `class` attribute.

Conceptual correct pattern:

```php
<li class="page-item <?php echo ($i == $page_setup) ? 'active' : ''; ?>">
```

---

## Mental Model

Think of pagination like a bookshelf.

If each shelf can hold 5 books:

- Page 1 starts at book 0.
- Page 2 skips 5 books.
- Page 3 skips 10 books.
- Page 4 skips 15 books.

That is exactly what `OFFSET` does.

```txt
offset = (current page - 1) * records per page
```

---

## Checklist For Building Pagination In PHP

- Protect the page with authentication.
- Read `page` from `$_GET`.
- Validate page as a positive integer.
- Decide how many records to show per page.
- Read search/filter values.
- Build SQL conditions.
- Use prepared statements.
- Run a count query.
- Calculate total pages.
- Clamp page number to a valid range.
- Calculate offset.
- Run the data query with `LIMIT` and `OFFSET`.
- Escape output using `htmlspecialchars()`.
- Generate pagination links.
- Preserve search/filter query parameters.

---

## Things To Practice Next

- Add a page size dropdown like 5, 10, 25, 50.
- Add sorting by fullname, status, and newest.
- Add a whitelist for status values.
- Convert delete links into POST forms with CSRF tokens.
- Replace `SELECT *` with explicit column names.
- Add indexes for common filters.
- Create a reusable pagination helper function.
- Learn the difference between offset pagination and keyset pagination.

---

## Quick Memory Summary

Pagination has three core steps:

```txt
1. Count matching rows.
2. Calculate offset.
3. Fetch rows using LIMIT and OFFSET.
```

The formula:

```txt
offset = (current_page - 1) * records_per_page
```

The security rule:

```txt
Validate input. Bind SQL values. Escape output.
```

The backend mindset:

```txt
The browser asks for a page.
PHP validates the request.
MySQL returns only the needed slice.
PHP renders safe HTML.
```

