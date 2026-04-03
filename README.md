# Gym Management System

A comprehensive web-based Gym Management System built with PHP and MySQL. This system provides a complete solution for gym administrators to manage members, trainers, equipment, attendance, and subscriptions, while offering members a personalized dashboard to track their workouts, diet, health metrics, and payments.

## Features

### For Members
- **Personalized Dashboard:** Overview of membership status, upcoming payments, and quick health stats.
- **Health & Fitness Tracking:**
  - Water Tracker
  - Sleep Monitor
  - Diet & Nutrition Plans (including Premium options)
  - Workout Routines
- **Health Declaration:** Initial health assessment for personalized recommendations.
- **AI Coach:** Interact with an AI assistant for fitness advice.
- **IoT Integration:** Track metrics from connected devices.
- **Subscription Management:** View and manage membership plans and payments.

### For Administrators
- **Admin Dashboard:** Centralized overview of gym operations.
- **Member Management:** Add, edit, remove, and view member details.
- **Trainer Management:** Assign and manage personal trainers.
- **Equipment Management:** Track gym inventory and maintenance status.
- **Attendance Tracking:** Monitor member check-ins and check-outs.
- **Reports & Analytics:** Generate financial and attendance reports.
- **System Announcements:** Broadcast messages to all members.

## Technologies Used
- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Styling:** Custom CSS (glassmorphism design, responsive layouts)

## Setup Instructions

### Prerequisites
- Install a local web server (e.g., [XAMPP](https://www.apachefriends.org/index.html), WAMP, or MAMP).

### Installation
1. **Clone or Download the Repository**
   Download the project files and place them in your web server's root directory:
   - For XAMPP: Place the `gym-management` folder inside `C:\xampp\htdocs\`.

2. **Database Configuration**
   - Open your web browser and go to `http://localhost/phpmyadmin`.
   - Create a new database named `gym_management`.
   - Import the provided SQL file (`database_setup.sql`) to set up the necessary tables.
   - Alternatively, you can run the setup script by visiting `http://localhost/gym-management/setup.php` (if available in the project).

3. **Running the Application**
   - Open your web browser and navigate to:
     `http://localhost/gym-management/`
   - You will see the login page where members and admins can sign in.

## Project Structure
- `/assets`: Contains images, icons, and other static files.
- `/backend`: Core PHP scripts for authentication, database connection, etc.
- `/includes`: Reusable components like headers and navigation bars.
- `index.php`: The main landing/login page.
- `dashboard.php` & `member_dashboard.php`: The primary interfaces for admins and members, respectively.
- `style.css`: Contains all the custom styling for the application.

## Screenshots

![Login Page]<img width="1295" height="879" alt="login_screenshot" src="https://github.com/user-attachments/assets/58c8ff0e-e352-48af-975c-978eccc97984" />


![Registration Page](assets/signup_screenshot.png)

![Member Dashboard])<img width="1295" height="878" alt="signup_screenshot" src="https://github.com/user-attachments/assets/6021f412-50fa-468b-b3fa-ef62fc7111c4" />


![Admin Dashboard]<img width="1295" height="1499" alt="dashboard_screenshot" src="https://github.com/user-attachments/assets/4a7b5675-dfa6-4bbc-a1f9-79d354326c03" />


![Diet Planner]<img width="1295" height="879" alt="diet_page_screenshot" src="https://github.com/user-attachments/assets/98d04eb8-cf88-4a38-b0d4-a037d4a7ca8e" />


![AI Coach]<img width="1295" height="879" alt="ai_coach_screenshot" src="https://github.com/user-attachments/assets/c9ebf33c-6366-4d94-b01f-d91f93fc8dbc" />


![Sleep Monitor]<img width="1295" height="879" alt="sleep_monitor_screenshot" src="https://github.com/user-attachments/assets/273d368e-3af0-4973-a214-cb81000cfb01" />


![IoT Dashboard]<img width="1295" height="879" alt="iot_dashboard_screenshot" src="https://github.com/user-attachments/assets/ddf7e271-2513-4b73-9bb3-b684315f2dcc" />


---
*Developed as part of a comprehensive Gym Management solution.*
