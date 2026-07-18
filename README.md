# TVAM Scholarship Management System

TVAM Scholarship Management System is a PHP and MySQL web application for managing scholarship applications, student records, document submissions, approvals, reports, notifications, and role-based access for admins and students.

This project has grown beyond simple CRUD practice. It now has real software workflow concepts: authentication, authorization, protected routes, student submissions, admin review, file handling, audit logs, and early API structure.

## Current Status

Estimated project completion: 80-85% for a capstone-level scholarship management system.

Core foundation completed:

- Landing page
- Registration
- Login and logout
- Session handling
- Role-based authentication for admin and student users
- Admin dashboard
- Student dashboard
- Sidebar and shared layout includes
- Scholar CRUD
- Scholarship application workflow
- Student application status
- Student profile and settings pages
- Student document upload
- Admin document review
- Secure document delivery through `admin/documents/download.php`
- Notifications
- Activity logs / audit trail
- Reports area
- Search and filtering in several admin screens
- Prepared statements for database queries
- Password hashing and verification for normal registration/login flow

Started but not yet complete:

- API layer files exist in `api/`, but the endpoints are currently empty.
- Forgot password page exists, but the reset-token workflow is not implemented yet.
- Reusable helper functions exist in `includes/functions.php`, but more repeated page logic can still be moved there.
- Admin analytics has KPI cards, but Chart.js visual analytics are not yet implemented.
- Pagination is not yet implemented on large list pages.
- The database SQL dump needs to be updated to include every table used by the current code.

## Project Structure

```txt
TVAM_SCHOLARSHIP/
  admin/              Admin dashboard, scholar management, applications, documents, reports, settings
  api/                Planned JSON API endpoints
  assets/             CSS, JavaScript, images, uploads, Bootstrap helper
  auth/               Login, registration, logout, forgot password
  config/             Database connection, sessions, admin/student route guards
  database/           SQL dump and database setup files
  includes/           Shared header, footer, sidebar, helper functions
  student/            Student dashboard, application, profile, status, documents, notifications
  index.php           Public landing page
  PROGRESS.txt        Older project notes and progress tracking
```

## Local Setup Notes

1. Place the folder inside XAMPP `htdocs`.
2. Start Apache and MySQL from XAMPP.
3. Create a MySQL database named `scholarship_db`.
4. Import `database/scholarshipDB.sql`.
5. Open the app in the browser:

```txt
http://localhost/TVAM_SCHOLARSHIP/index.php
```

Important database note: the current SQL dump appears behind the application code. Before sharing or deploying this project, update `database/scholarshipDB.sql` so it includes all active tables, including documents, notifications, activity logs, password reset tokens, and system settings if those features are used.

## Important Engineering Concepts In This Project

### Role-Based Access Control

The system separates authenticated users from authorized users.

- Authentication asks: "Who is logged in?"
- Authorization asks: "What is this user allowed to access?"

Admin pages should require `config/admin-auth.php`. Student pages should require `config/student-auth.php`. This keeps security rules centralized instead of duplicated across every page.

### Secure File Delivery

The project now uses `admin/documents/download.php?id=...` instead of exposing uploaded files directly.

This is important because direct links like this are risky:

```txt
/assets/uploads/document/student-file.pdf
```

A secure download controller can check:

- Is the user logged in?
- Is the user an admin?
- If not admin, does this student own the file?
- Does the file record exist?
- Does the physical file exist?

Only after those checks should PHP send the file to the browser.

### Workflow State

Scholarship systems are workflow systems. Records move through states such as:

- Pending
- Active / Approved
- Inactive / Rejected
- Verified
- Rejected

This is the beginning of backend state management. Every state change should be deliberate, validated, and ideally recorded in activity logs.

### Prepared Statements

The code uses prepared statements in many areas. Keep this habit. It protects the system from SQL injection when accepting user input from forms, query strings, and search filters.

### File Upload Security

The document upload flow checks file type, MIME type, and size. Continue improving it by:

- Keeping uploads outside public access when possible
- Using generated file names
- Checking MIME type, not just file extension
- Limiting file size
- Authorizing every file view/download

## Immediate Priorities

Follow this order:

1. Update the database dump so a fresh install works from `database/scholarshipDB.sql`.
2. Add pagination to Scholars, Users, Applications, Documents, Reports, and Activity Logs.
3. Build richer analytics with Chart.js for application counts, document statuses, and scholar statistics.
4. Refactor repeated alert, redirect, validation, notification, and activity-log logic into reusable helper functions.
5. Complete Forgot Password with secure reset tokens, expiration, and email delivery or a local mail sandbox.
6. Build the first real REST API endpoints in `api/scholars.php`, `api/application.php`, and `api/login.php`.

## Next Technical Milestones

See [ROADMAP.md](ROADMAP.md) for the detailed milestone plan.

See [ENGINEERING_NOTES.md](ENGINEERING_NOTES.md) for senior-engineer notes about security, architecture, and concepts to keep learning.

## Learning Focus

Your next growth stage is moving from PHP page-building into software engineering habits:

- Database relationships and normalization
- Pagination and sorting
- Reusable functions
- Secure file handling
- Reset-token security
- REST APIs
- AJAX interactions
- Object-oriented PHP
- MVC architecture
- Deployment

The strongest next backend topics for you are Object-Oriented PHP and REST API design.
