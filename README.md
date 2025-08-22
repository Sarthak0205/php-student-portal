# Simple PHP Student Portal (Azure deploy-ready)

## Setup (locally or on Azure)
1. Ensure PHP is supported (Azure App Service Linux with PHP runtime).
2. Upload these files to the repo root.
3. In browser, visit `https://<your-site>/db_init.php` once to create `portal.db` and seed courses.
   - After success, delete `db_init.php` for security.
4. Open `index.php` to navigate.

## Features
- Enroll students in courses (`courses.php`)
- View a student's enrollments (`mycourses.php?student_id=S123`)
- Mark/view attendance (`attendance.php`)
- View course schedules (`schedule.php`)

## Notes
- DB file `portal.db` is created in the app root — on App Service it persists across deployments unless you redeploy or remove storage. For production use, use a managed DB (MySQL/Azure Database).
- This is a demo/simple implementation for lab purposes only.
