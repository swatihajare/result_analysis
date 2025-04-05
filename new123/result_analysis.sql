CREATE DATABASE result_analysis;

USE result_analysis;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    enrollment_number VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    department VARCHAR(50) NOT NULL
);

CREATE TABLE student_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    branch VARCHAR(50) NOT NULL,
    semester VARCHAR(50) NOT NULL,
    total_marks INT NOT NULL,
    result VARCHAR(10) NOT NULL,
    percentage FLOAT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id)
);

CREATE TABLE faculty_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faculty_id INT NOT NULL,
    branch VARCHAR(50) NOT NULL,
    semester VARCHAR(50) NOT NULL,
    subjects TEXT NOT NULL,
    enrolled_students INT NOT NULL,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id)
);