# Engineering Notes

These notes capture the most important concepts to remember while improving the TVAM Scholarship Management System.

## Senior Engineer Assessment

Current estimated skill/project level:

| Area | Estimate | Notes |
| --- | ---: | --- |
| PHP fundamentals | 8.5/10 | You are comfortable building real pages and workflows. |
| MySQL | 8/10 | You are using relational data and prepared statements. |
| Authentication and authorization | 8.5/10 | Admin/student access control is in place. |
| Transactions | 8/10 | Used in file upload flow and important write operations. |
| File uploads | 8.5/10 | Good start with size, MIME, and generated file names. |
| Security awareness | 7.5/10 | You are now thinking about protected routes and secure file delivery. |
| Software architecture | 7/10 | Structure is improving; next step is reducing duplication. |
| Object-Oriented PHP | 4/10 | This should become a major learning focus soon. |
| REST APIs | 2/10 | API files exist, but real JSON endpoints still need to be built. |

## Security Notes

### Route Protection

Admin-only pages should require:

```php
require_once "../../config/admin-auth.php";
```

Student-only pages should require:

```php
require_once "../config/student-auth.php";
```

The exact path may change depending on folder depth, but the principle is the same: protect routes at the top of the file before doing sensitive work.

### File Access

Do not trust public upload paths as the main access method.

Preferred pattern:

```txt
admin/documents/download.php?id=15
```

Avoid relying on direct paths:

```txt
assets/uploads/document/file.pdf
```

The download controller should verify login, ownership, admin permissions, database record existence, and physical file existence.

### Passwords

Use:

```php
password_hash()
password_verify()
```

Never store plaintext passwords in real seed data. If old sample records have plaintext passwords, replace them.

### SQL Injection

Prepared statements protect values in `WHERE`, `INSERT`, and `UPDATE`.

Sorting needs extra care because column names cannot be bound like normal values. Use a whitelist:

```php
$allowedSorts = [
    "name" => "fullname",
    "newest" => "created_at",
    "status" => "status"
];
```

Then only use a column if it exists in the whitelist.

### CSRF

The next security layer should be CSRF protection for forms that change data:

- Add scholar
- Edit scholar
- Delete scholar
- Approve/reject application
- Verify/reject document
- Change password
- Update settings

## Database Concepts To Strengthen

Focus on:

- Primary keys
- Foreign keys
- One-to-many relationships
- Indexes
- Transactions
- Views
- Triggers
- Stored procedures
- Composite keys
- Normalization

Important relationship example:

```txt
users.id -> applications.user_id
users.id -> documents.user_id
users.id -> notifications.user_id
users.id -> activity_logs.user_id
```

If a table references another table, define a foreign key when it makes sense.

## Backend Concepts To Learn Next

High priority:

- REST API
- JSON responses
- HTTP status codes
- Middleware-style route protection
- Service layer
- Repository pattern
- Dependency injection
- Session fixation protection
- Rate limiting

Later:

- JWT
- Composer
- Autoloading
- Namespaces
- Interfaces
- Traits

## Architecture Principles

### DRY

Do not repeat yourself. If the same alert, redirect, validation, notification, or activity-log code appears across multiple pages, move it into a reusable helper.

### KISS

Keep the current version simple while you are still mastering the request lifecycle. Do not jump to Laravel or React until the PHP/MySQL foundation is stable.

### YAGNI

Do not build future complexity until it solves a real current problem.

### Separation Of Concerns

Page files should not become too crowded. Over time, separate:

- Security checks
- Validation
- Database operations
- HTML display
- Business decisions

## Recommended Learning Order

1. Pagination and sorting
2. Database foreign keys and indexes
3. Reusable helper functions
4. Chart.js analytics
5. Forgot password tokens
6. REST API endpoints
7. AJAX with `fetch()`
8. Object-Oriented PHP
9. MVC architecture
10. Deployment

## What Not To Rush Yet

Avoid jumping too early into:

- Laravel
- React
- JWT-heavy authentication
- Full MVC rewrites

Those are valuable, but the best next move is strengthening fundamentals inside this project first.

## Professional Mindset

You are no longer building isolated CRUD pages. You are building a workflow system with roles, permissions, documents, application states, reports, and auditability.

That means every new feature should answer:

- Who is allowed to do this?
- What data changes?
- Should the change be logged?
- Should someone be notified?
- What happens if the action fails?
- Will this still work with 10,000 records?
