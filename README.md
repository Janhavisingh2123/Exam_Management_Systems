A web-based application to streamline the management of exams and invigilators. Built using HTML, CSS, JavaScript, PHP, and MySQL, the system allows administrators to schedule exams, assign invigilators with proper constraints, and generate detailed reports.

🚀 Features

🔐 Role-based Authentication (Admin & Invigilator login with sessions)

🖥️ Admin Dashboard to manage exams, invigilators, and assignments

📅 Exam Management – Add, edit, and delete exam schedules

👨‍🏫 Invigilator Management – Register, update, and manage invigilators with department and contact details

📝 Invigilator Assignment with constraints:

Invigilator cannot be assigned to exams of their own department

Prevents double booking of invigilators at the same date and time

🔍 Searchable Dropdowns for easy invigilator & exam selection (via Select2)

📊 Reports – Generate reports of exam schedules and invigilator assignments within a date range

📤 Export Options – Export reports to Excel, PDF, or print directly

📱 Responsive Design – Clean and user-friendly UI

🛠️ Tech Stack

Frontend: HTML, CSS, JavaScript, Select2.js

Backend: PHP

Database: MySQL (via XAMPP)

Reports Export: PHPExcel / TCPDF

📂 Database Schema

Key tables used in the project:

users – stores login details for Admin and Invigilators

exams – exam details (subject, date, time, hall, department)

invigilators – invigilator details (name, employee ID, department, contact)

assignments – mapping of exams and assigned invigilators with status

departments – list of all departments

⚙️ Installation

Install XAMPP (Apache + MySQL + PHP).

Clone this repository into your htdocs folder:

git clone https://github.com/your-username/exam-management-system.git


Import the SQL schema (database.sql) into phpMyAdmin.

Update database credentials in includes/db_connect.php.

Start Apache and MySQL from the XAMPP Control Panel.

Open in browser:

http://localhost/EXAM_Proj/NEW_PHP_proj/

🔑 Default Login Credentials

Admin:

Username: admin

Password: admin123

(Change after first login for security)

📸 Screenshots

(Add screenshots of your project here – e.g., Login Page, Dashboard, Assign Invigilators, Reports, etc.)

👥 Team Members

Person A (Backend & Database): Handled database design, backend logic, authentication, constraints, and report generation.

Person B (Frontend & UI): Worked on UI design, forms, search dropdowns, dashboards, and documentation.

📌 Future Enhancements

Email/SMS notifications for invigilators

Role-based dashboards with more analytics

Support for multiple campuses/exam centers

Automated hall allocation

📜 License

This project is for academic purposes. Free to use and modify.
