# TVAM Scholarship Roadmap

This roadmap organizes the next work into milestones. Treat it like a living checklist: update it whenever a feature is completed, changed, or postponed.

## Milestone 1: Stabilize The Current System

Goal: make the project easy to run, test, and continue.

- [ ] Update `database/scholarshipDB.sql` with every table used by the current code.
- [ ] Confirm all pages load without missing-table errors.
- [ ] Make sure `documents`, `notifications`, `activity_logs`, password reset, and settings tables are represented if the code depends on them.
- [ ] Remove old sample plaintext passwords from seed data.
- [ ] Add a short test account note for admin and student users.
- [ ] Review all includes so admin pages use `config/admin-auth.php` and student pages use `config/student-auth.php`.

## Milestone 2: Scalability

Goal: make list pages work well when the system has thousands of records.

- [ ] Add pagination to `admin/scholars/index.php`.
- [ ] Add pagination to application lists.
- [ ] Add pagination to document review lists.
- [ ] Add pagination to reports and activity logs.
- [ ] Keep search and filters working together with pagination.
- [ ] Use `LIMIT` and `OFFSET`.
- [ ] Preserve query parameters across page links.

Concepts to learn:

- SQL pagination
- Total row counts
- Page numbers
- Query-string state
- Performance-friendly list screens

## Milestone 3: Sorting And Filtering

Goal: make admin screens easier to scan and manage.

- [ ] Sort scholars by newest, oldest, name, course, scholarship type, and status.
- [ ] Sort applications by newest, oldest, status, course, and scholarship type.
- [ ] Sort documents by newest, oldest, status, student, and document type.
- [ ] Whitelist allowed sort columns before building SQL.
- [ ] Avoid passing raw query-string values directly into `ORDER BY`.

Concepts to learn:

- Dynamic SQL
- Column whitelisting
- SQL injection prevention for sorting
- UX-friendly admin tables

## Milestone 4: Analytics Dashboard

Goal: move from simple counters to useful decision-making data.

- [ ] Add Chart.js to the admin dashboard.
- [ ] Show application totals by month.
- [ ] Show document status distribution.
- [ ] Show scholar totals by course.
- [ ] Show active, inactive, and pending scholarship counts.
- [ ] Add date filters if needed.

Concepts to learn:

- `COUNT()`
- `GROUP BY`
- Aggregation queries
- KPI cards
- Data visualization

## Milestone 5: Reusable Helpers

Goal: reduce repeated code and make behavior consistent.

- [ ] Expand `includes/functions.php`.
- [ ] Add `showAlert()`.
- [ ] Add `redirectSuccess()`.
- [ ] Add `redirectError()`.
- [ ] Add `sanitize()`.
- [ ] Add `generateStudentID()`.
- [ ] Improve `activityLogs()`.
- [ ] Improve `notificationAlert()`.
- [ ] Keep helper paths reliable from admin and student folders.

Concepts to learn:

- DRY principle
- Reusable functions
- Centralized behavior
- Maintainability

## Milestone 6: Notifications 2.0

Goal: make notifications behave like a real product feature.

- [ ] Add unread/read state if not already present in the database.
- [ ] Show unread notification count in the student UI.
- [ ] Mark notifications as read after opening.
- [ ] Add notification timestamps.
- [ ] Add notification categories such as application, document, and system.

Concepts to learn:

- Notification queues
- Read state
- User-specific records
- Small product workflows

## Milestone 7: Forgot Password

Goal: complete a secure account recovery flow.

- [ ] Create password reset token table.
- [ ] Generate random reset tokens.
- [ ] Store only hashed reset tokens.
- [ ] Add token expiration.
- [ ] Send reset link by email or local mail sandbox.
- [ ] Allow password reset only while token is valid.
- [ ] Invalidate used tokens.

Concepts to learn:

- Token generation
- Expiration windows
- Email workflow
- Secure password recovery

## Milestone 8: API Layer

Goal: expose selected system features as JSON.

- [ ] Implement `GET /api/scholars.php`.
- [ ] Implement `GET /api/application.php`.
- [ ] Implement `POST /api/login.php`.
- [ ] Return JSON responses.
- [ ] Use correct HTTP status codes.
- [ ] Validate request methods.
- [ ] Decide whether API authentication uses sessions first or tokens later.

Concepts to learn:

- REST
- JSON
- HTTP methods
- Status codes
- API authentication

## Milestone 9: AJAX Improvements

Goal: reduce full-page reloads for common actions.

- [ ] Use `fetch()` for approving applications.
- [ ] Use `fetch()` for rejecting applications.
- [ ] Use `fetch()` for verifying documents.
- [ ] Show loading states.
- [ ] Show success/error messages without page reload.
- [ ] Keep server-side validation as the source of truth.

Concepts to learn:

- AJAX
- `fetch()`
- JSON responses
- Progressive enhancement

## Milestone 10: Object-Oriented PHP

Goal: begin moving business logic out of individual page files.

- [ ] Create a `classes/` folder.
- [ ] Create `Scholar.php`.
- [ ] Create `Application.php`.
- [ ] Create `Document.php`.
- [ ] Create `Notification.php`.
- [ ] Move related database operations into methods.

Example methods:

- `create()`
- `update()`
- `delete()`
- `find()`
- `approve()`
- `reject()`

Concepts to learn:

- Classes
- Methods
- Encapsulation
- Service-style organization

## Milestone 11: MVC Architecture

Goal: prepare your brain for Laravel-style architecture without jumping too early.

Possible future structure:

```txt
app/
  Controllers/
  Models/
  Views/
  Core/
  Routes/
```

Do this only after the current procedural PHP version is stable.

Concepts to learn:

- Controllers
- Models
- Views
- Routing
- Request lifecycle
- Separation of concerns

## Milestone 12: Deployment

Goal: make the system accessible outside your local machine.

- [ ] Prepare production database export.
- [ ] Remove development-only data.
- [ ] Configure environment-specific database settings.
- [ ] Deploy to a hosting provider.
- [ ] Configure domain and SSL.
- [ ] Test file uploads on the server.
- [ ] Test login, registration, applications, documents, and reports.

Possible hosting targets:

- Hostinger
- InfinityFree
- Railway
- Render
- DigitalOcean VPS
- Traditional cPanel hosting

