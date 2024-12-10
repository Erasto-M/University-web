-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2024 at 10:12 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `universitydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminID` int(11) NOT NULL,
  `securityLevel` enum('Low','Medium','High') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminID`, `securityLevel`) VALUES
(3, 'Medium'),
(6, 'Low'),
(7, 'Medium'),
(8, 'High');

-- --------------------------------------------------------

--
-- Table structure for table `advisor`
--

CREATE TABLE `advisor` (
  `facultyID` int(11) DEFAULT NULL,
  `studentID` int(11) NOT NULL,
  `dateOfAppointment` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advisor`
--

INSERT INTO `advisor` (`facultyID`, `studentID`, `dateOfAppointment`) VALUES
(6, 1, '2024-09-01'),
(2, 2, '2023-09-01'),
(2, 3, '2024-06-01'),
(6, 4, '2024-01-15'),
(2, 5, '2023-09-01'),
(6, 6, '2024-09-01'),
(6, 7, '2024-01-15'),
(2, 8, '2024-06-01');

-- --------------------------------------------------------

--
-- Table structure for table `appuser`
--

CREATE TABLE `appuser` (
  `userID` int(11) NOT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `middleName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `gender` enum('M','F','Other') DEFAULT NULL,
  `DOB` date DEFAULT NULL,
  `houseNo` varchar(20) DEFAULT NULL,
  `streetName` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zipcode` varchar(10) DEFAULT NULL,
  `phoneNo` varchar(15) DEFAULT NULL,
  `userType` enum('Visitor','Student','Faculty','Admin','Researcher') DEFAULT NULL,
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appuser`
--

INSERT INTO `appuser` (`userID`, `firstName`, `middleName`, `lastName`, `gender`, `DOB`, `houseNo`, `streetName`, `city`, `state`, `zipcode`, `phoneNo`, `userType`, `email`, `password`) VALUES
(1, 'Alice', 'J.', 'Smith', 'F', '1998-03-15', '12A', 'Oak Street', 'Metropolis', 'NY', '10001', '1234567890', 'Student', 'alice.smith.1@student.com', 'Password123'),
(2, 'Bob', 'K.', 'Johnson', 'M', '1985-06-22', '23B', 'Maple Avenue', 'Gotham', 'CA', '90002', '0987654321', 'Faculty', 'bob.johnson.2@faculty.com', 'Password456'),
(3, 'Mary', 'L.', 'Williams', 'F', '1990-01-30', '45C', 'Pine Drive', 'Central City', 'TX', '33004', '2233445566', 'Admin', 'cathy.williams.3@admin.com', 'Password789'),
(4, 'David', 'M.', 'Brown', 'M', '1990-01-05', '67D', 'Birch Boulevard', 'Star City', 'FL', '33004', '2233445566', 'Visitor', 'david.brown.4@university.com', 'Password321'),
(5, 'Eve', 'N.', 'Davis', 'F', '2000-08-30', '89E', 'Cedar Lane', 'Coast City', 'WA', '98005', '3344556677', 'Student', 'eve.davis.5@student.com', 'Password654'),
(6, 'Frank', 'O.', 'Miller', 'M', '1982-12-12', '101F', 'Spruce Court', 'Keystone City', 'PA', '19006', '4455667788', 'Faculty', 'frank.miller.6@faculty.com', 'Password001'),
(8, 'Hank', 'Q.', 'Moore', 'M', '1975-10-25', '141H', 'Willow Way', 'Bludhaven', 'NJ', '07008', '6677889900', 'Researcher', 'hank.moore.8@research.com', 'Password777');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `studentID` int(11) NOT NULL,
  `crnNo` int(11) NOT NULL,
  `courseID` int(11) DEFAULT NULL,
  `classDate` date NOT NULL,
  `present` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`studentID`, `crnNo`, `courseID`, `classDate`, `present`) VALUES
(1, 11, 1, '2023-09-01', 1),
(2, 12, 2, '2023-09-01', 1),
(3, 13, 3, '2023-09-01', 0),
(4, 14, 4, '2023-09-01', 1),
(5, 15, 5, '2023-09-01', 1),
(6, 6, 6, '2023-09-01', 1),
(7, 7, 7, '2023-09-01', 0),
(8, 8, 8, '2023-09-01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `building`
--

CREATE TABLE `building` (
  `buildingID` int(11) NOT NULL,
  `buildingName` varchar(100) NOT NULL,
  `used` enum('Academic','Administrative','Residential') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `building`
--

INSERT INTO `building` (`buildingID`, `buildingName`, `used`) VALUES
(1, 'Science Block', 'Academic'),
(2, 'Library', 'Administrative'),
(3, 'Hostel A', 'Residential'),
(4, 'Hostel B', 'Residential'),
(5, 'Engineering Block', 'Academic'),
(6, 'Sports Complex', 'Administrative'),
(7, 'Hostel C', 'Residential'),
(8, 'Arts Block', 'Academic');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `courseID` int(11) NOT NULL,
  `courseName` varchar(100) NOT NULL,
  `deptID` int(11) NOT NULL,
  `numOfCredits` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `courseLevel` enum('100','200','300','400','500','600') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`courseID`, `courseName`, `deptID`, `numOfCredits`, `description`, `courseLevel`) VALUES
(1, 'Introduction to Programming', 1, 3, 'Learn the basics of programming.', '100'),
(2, 'Data Structures', 1, 4, 'In-depth study of data organization.', '200'),
(3, 'Thermodynamics', 2, 3, 'Introduction to heat and work transfer.', '200'),
(4, 'Mechanics', 2, 4, 'Study of motion and forces.', '300'),
(5, 'Quantum Physics', 3, 4, 'Fundamentals of quantum mechanics.', '300'),
(6, 'Linear Algebra', 4, 3, 'Matrix theory and linear systems.', '200'),
(7, 'Shakespeare Studies', 5, 3, 'In-depth study of Shakespeare’s works.', '300'),
(8, 'Cell Biology', 8, 3, 'Understanding cellular functions.', '100');

-- --------------------------------------------------------

--
-- Table structure for table `courseprerequisites`
--

CREATE TABLE `courseprerequisites` (
  `courseID` int(11) NOT NULL,
  `prerequisiteCourseID` int(11) NOT NULL,
  `minimumGrade` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courseprerequisites`
--

INSERT INTO `courseprerequisites` (`courseID`, `prerequisiteCourseID`, `minimumGrade`) VALUES
(1, 8, 'A'),
(2, 1, 'C'),
(3, 2, 'B'),
(4, 3, 'C'),
(5, 4, 'B'),
(6, 5, 'A'),
(7, 6, 'B'),
(8, 7, 'C');

-- --------------------------------------------------------

--
-- Table structure for table `coursesection`
--

CREATE TABLE `coursesection` (
  `crnNo` int(11) NOT NULL,
  `courseID` int(11) NOT NULL,
  `sectionNo` int(11) NOT NULL,
  `facultyID` int(11) DEFAULT NULL,
  `timeSlot` int(11) DEFAULT NULL,
  `roomID` int(11) DEFAULT NULL,
  `availableSeats` int(11) DEFAULT NULL,
  `semesterID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coursesection`
--

INSERT INTO `coursesection` (`crnNo`, `courseID`, `sectionNo`, `facultyID`, `timeSlot`, `roomID`, `availableSeats`, `semesterID`) VALUES
(1, 1, 1, 2, 1, 1, 30, 1),
(2, 2, 1, 6, 2, 2, 25, 1),
(3, 3, 1, 2, 3, 3, 40, 2),
(4, 4, 1, 6, 4, 4, 35, 2),
(5, 5, 1, 2, 5, 5, 50, 3),
(6, 6, 1, 6, 6, 6, 45, 3),
(7, 7, 1, 2, 7, 7, 20, 4),
(8, 8, 1, 6, 8, 8, 60, 4),
(9, 1, 1, 2, 1, 1, 30, 1),
(10, 2, 1, 6, 2, 2, 25, 1),
(11, 3, 1, 2, 3, 3, 40, 2),
(12, 4, 1, 6, 4, 4, 35, 2),
(13, 5, 1, 2, 5, 5, 50, 3),
(14, 6, 1, 6, 6, 6, 45, 3),
(15, 7, 1, 2, 7, 7, 20, 4),
(16, 8, 1, 6, 8, 8, 60, 4),
(17, 3, 1, 3, 31, 8, 60, 8),
(18, 3, 2, 6, 3, 4, 50, 8),
(19, 3, 3, 3, 33, 3, 6, 3),
(20, 2, 2, 3, 2, 4, 25, 8),
(21, 2, 3, 3, 2, 8, 60, 8),
(22, 4, 2, 5, 2, 8, 12, 8),
(26, 2, 1, 3, 2, 8, 340, 8),
(27, 3, 4, 2, 4, 8, 1, 8),
(28, 3, 2, 3, 1, 8, 18, 8);

-- --------------------------------------------------------

--
-- Table structure for table `day`
--

CREATE TABLE `day` (
  `dayID` int(11) NOT NULL,
  `weekDay` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `day`
--

INSERT INTO `day` (`dayID`, `weekDay`) VALUES
(1, 'Monday'),
(2, 'Tuesday'),
(3, 'Wednesday'),
(4, 'Thursday'),
(5, 'Friday'),
(6, 'Saturday'),
(7, 'Sunday'),
(8, '');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `deptID` int(11) NOT NULL,
  `deptName` varchar(100) NOT NULL,
  `chairID` int(11) DEFAULT NULL,
  `deptManager` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phoneNo` varchar(15) DEFAULT NULL,
  `roomID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`deptID`, `deptName`, `chairID`, `deptManager`, `email`, `phoneNo`, `roomID`) VALUES
(1, 'Computer Science', 2, 'Alice Manager', 'cs@university.edu', '1234567890', 6),
(2, 'Mechanical Engineering', 6, 'Bob Manager', 'me@university.edu', '2233445566', 7),
(3, 'Physics', 2, 'Charlie Manager', 'phy@university.edu', '3344556677', 3),
(4, 'Mathematics', 6, 'David Manager', 'math@university.edu', '4455667788', 4),
(5, 'English', 2, 'Eve Manager', 'eng@university.edu', '5566778899', 5),
(6, 'Sports', 6, 'Frank Manager', 'sports@university.edu', '6677889900', 2),
(7, 'Arts', 2, 'Grace Manager', 'arts@university.edu', '7788990011', 8),
(8, 'Biology', 6, 'Hank Manager', 'bio@university.edu', '8899001122', 6);

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE `enrollment` (
  `studentID` int(11) NOT NULL,
  `crnNo` int(11) NOT NULL,
  `grade` char(2) DEFAULT NULL,
  `dateOfEnrollment` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`studentID`, `crnNo`, `grade`, `dateOfEnrollment`) VALUES
(1, 1, 'A', '2023-09-01'),
(1, 2, 'B', '2023-09-01'),
(1, 5, 'B', '2024-06-01'),
(1, 7, 'A', '2024-09-01'),
(5, 3, 'A', '2024-01-15'),
(5, 4, 'C', '2024-01-15'),
(5, 6, 'A', '2024-06-01'),
(5, 8, 'B', '2024-09-01');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `facultyID` int(11) NOT NULL,
  `specialty` varchar(100) DEFAULT NULL,
  `rank` enum('Assistant','Associate','Professor') DEFAULT NULL,
  `facultyType` enum('Full-Time','Part-Time') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`facultyID`, `specialty`, `rank`, `facultyType`) VALUES
(1, 'English Literature', 'Associate', 'Part-Time'),
(2, 'Biology', 'Assistant', 'Full-Time'),
(3, 'Computer Science', 'Professor', 'Full-Time'),
(4, 'Sports Science', 'Professor', 'Full-Time'),
(5, 'Arts', 'Associate', 'Part-Time'),
(6, 'Mathematics', 'Professor', 'Full-Time'),
(7, 'Mechanical Engineering', 'Associate', 'Full-Time'),
(8, 'Physics', 'Assistant', 'Part-Time');

-- --------------------------------------------------------

--
-- Table structure for table `facultyfulltime`
--

CREATE TABLE `facultyfulltime` (
  `facultyID` int(11) NOT NULL,
  `minNoClasses` int(11) NOT NULL,
  `maxNoClasses` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facultyfulltime`
--

INSERT INTO `facultyfulltime` (`facultyID`, `minNoClasses`, `maxNoClasses`) VALUES
(2, 3, 5),
(6, 3, 5);

-- --------------------------------------------------------

--
-- Table structure for table `facultyhistory`
--

CREATE TABLE `facultyhistory` (
  `facultyID` int(11) NOT NULL,
  `crnNo` int(11) NOT NULL,
  `courseID` int(11) DEFAULT NULL,
  `semesterID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facultyhistory`
--

INSERT INTO `facultyhistory` (`facultyID`, `crnNo`, `courseID`, `semesterID`) VALUES
(2, 1, 1, 1),
(2, 3, 3, 2),
(2, 5, 5, 3),
(2, 7, 7, 4),
(6, 2, 2, 1),
(6, 4, 4, 2),
(6, 6, 6, 3),
(6, 8, 8, 4);

-- --------------------------------------------------------

--
-- Table structure for table `facultyparttime`
--

CREATE TABLE `facultyparttime` (
  `facultyID` int(11) NOT NULL,
  `minNoClasses` int(11) NOT NULL,
  `maxNoClasses` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facultyparttime`
--

INSERT INTO `facultyparttime` (`facultyID`, `minNoClasses`, `maxNoClasses`) VALUES
(2, 1, 2),
(6, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `graduate`
--

CREATE TABLE `graduate` (
  `studentID` int(11) NOT NULL,
  `deptID` int(11) DEFAULT NULL,
  `program` varchar(100) NOT NULL,
  `graduateStudentType` enum('Full-Time','Part-Time') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `graduate`
--

INSERT INTO `graduate` (`studentID`, `deptID`, `program`, `graduateStudentType`) VALUES
(1, 1, 'Master in Data Science', 'Full-Time'),
(2, 3, 'PhD in Physics', 'Part-Time'),
(3, 4, 'Master in Mathematics', 'Full-Time'),
(4, 2, 'PhD in Mechanical Engineering', 'Part-Time'),
(5, 5, 'Master in English Literature', 'Full-Time'),
(6, 8, 'PhD in Biology', 'Part-Time'),
(7, 6, 'Master in Sports Science', 'Full-Time'),
(8, 7, 'PhD in Arts', 'Part-Time');

-- --------------------------------------------------------

--
-- Table structure for table `graduatefulltime`
--

CREATE TABLE `graduatefulltime` (
  `studentID` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `creditsEarned` int(11) DEFAULT 0,
  `thesis` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `graduatefulltime`
--

INSERT INTO `graduatefulltime` (`studentID`, `year`, `creditsEarned`, `thesis`) VALUES
(1, 2023, 30, 0),
(2, 2024, 40, 1),
(3, 2025, 30, 1),
(4, 2026, 40, 1),
(5, 2023, 20, 0),
(6, 2024, 25, 0),
(7, 2025, 20, 1),
(8, 2026, 25, 1);

-- --------------------------------------------------------

--
-- Table structure for table `graduateparttime`
--

CREATE TABLE `graduateparttime` (
  `studentID` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `creditsEarned` int(11) DEFAULT 0,
  `thesis` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `graduateparttime`
--

INSERT INTO `graduateparttime` (`studentID`, `year`, `creditsEarned`, `thesis`) VALUES
(1, 2023, 10, 0),
(2, 2024, 15, 0),
(3, 2025, 10, 1),
(4, 2023, 15, 0),
(5, 2026, 15, 1),
(6, 2024, 20, 0),
(7, 2025, 15, 1),
(8, 2026, 20, 1);

-- --------------------------------------------------------

--
-- Table structure for table `hold`
--

CREATE TABLE `hold` (
  `holdID` int(11) NOT NULL,
  `holdType` enum('Academic','Health','Financial','Disciplinary') NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hold`
--

INSERT INTO `hold` (`holdID`, `holdType`, `description`) VALUES
(1, 'Academic', 'Library hold due to late book return'),
(2, 'Health', 'Health hold due to pending immunization'),
(3, 'Financial', 'Academic hold due to unmet prerequisites'),
(4, 'Disciplinary', 'Financial hold due to unpaid tuition');

-- --------------------------------------------------------

--
-- Table structure for table `institutionalresearcher`
--

CREATE TABLE `institutionalresearcher` (
  `institutionalResearcherID` int(11) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `institutionalresearcher`
--

INSERT INTO `institutionalresearcher` (`institutionalResearcherID`, `status`) VALUES
(1, 'Inactive'),
(2, 'Active'),
(3, 'Inactive'),
(4, 'Active'),
(5, 'Inactive'),
(6, 'Active'),
(7, 'Active'),
(8, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `lab`
--

CREATE TABLE `lab` (
  `labID` int(11) NOT NULL,
  `numOfCookStations` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab`
--

INSERT INTO `lab` (`labID`, `numOfCookStations`) VALUES
(1, 10),
(2, 12),
(3, 8),
(4, 6),
(5, 10),
(6, 14),
(7, 9),
(8, 7);

-- --------------------------------------------------------

--
-- Table structure for table `lecture`
--

CREATE TABLE `lecture` (
  `lectureID` int(11) NOT NULL,
  `numOfSeats` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lecture`
--

INSERT INTO `lecture` (`lectureID`, `numOfSeats`) VALUES
(1, 100),
(2, 80),
(3, 120),
(4, 150),
(5, 200),
(6, 90),
(7, 75),
(8, 110);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `userID` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `numOfAttempts` int(11) DEFAULT 0,
  `lockOut` tinyint(1) DEFAULT 0,
  `userType` enum('Visitor','Student','Faculty','Admin','Researcher') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`userID`, `email`, `password`, `numOfAttempts`, `lockOut`, `userType`) VALUES
(1, 'alice.smith@student.university.edu', 'password123', 0, 0, 'Student'),
(2, 'bob.johnson@faculty.university.edu', 'securepass456', 0, 0, 'Faculty'),
(3, 'cathy.williams@admin.university.edu', 'adminpass789', 1, 0, 'Admin'),
(4, 'david.brown@research.university.edu', 'researchpass', 0, 0, ''),
(5, 'eve.davis@student.university.edu', 'password456', 0, 0, 'Student'),
(6, 'frank.miller@faculty.university.edu', 'facultysecure', 0, 0, 'Faculty'),
(7, 'grace.wilson@admin.university.edu', 'adminsecure', 0, 0, 'Admin'),
(8, 'hank.moore@research.university.edu', 'researchsecure', 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `major`
--

CREATE TABLE `major` (
  `majorID` int(11) NOT NULL,
  `majorName` varchar(100) NOT NULL,
  `deptID` int(11) NOT NULL,
  `numOfCreditsRequired` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `major`
--

INSERT INTO `major` (`majorID`, `majorName`, `deptID`, `numOfCreditsRequired`) VALUES
(1, 'Computer Science', 1, 120),
(2, 'Mechanical Engineering', 2, 130),
(3, 'Physics', 3, 110),
(4, 'Mathematics', 4, 120),
(5, 'English', 5, 100),
(6, 'Sports Science', 6, 90),
(7, 'Arts', 7, 100),
(8, 'Biology', 8, 120);

-- --------------------------------------------------------

--
-- Table structure for table `majorrequirements`
--

CREATE TABLE `majorrequirements` (
  `majorID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL,
  `minimumGradeRequired` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `majorrequirements`
--

INSERT INTO `majorrequirements` (`majorID`, `courseID`, `minimumGradeRequired`) VALUES
(1, 1, 'B'),
(2, 2, 'C'),
(3, 3, 'C'),
(4, 4, 'B'),
(5, 5, 'A'),
(6, 6, 'B'),
(7, 7, 'C'),
(8, 8, 'B');

-- --------------------------------------------------------

--
-- Table structure for table `minor`
--

CREATE TABLE `minor` (
  `minorID` int(11) NOT NULL,
  `minorName` varchar(100) NOT NULL,
  `deptID` int(11) NOT NULL,
  `numOfCreditsRequired` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `minor`
--

INSERT INTO `minor` (`minorID`, `minorName`, `deptID`, `numOfCreditsRequired`) VALUES
(1, 'Data Science', 1, 20),
(2, 'Robotics', 2, 25),
(3, 'Astrophysics', 3, 18),
(4, 'Statistics', 4, 22),
(5, 'Creative Writing', 5, 15),
(6, 'Physical Education', 6, 10),
(7, 'Art History', 7, 18),
(8, 'Genetics', 8, 20);

-- --------------------------------------------------------

--
-- Table structure for table `minorrequirements`
--

CREATE TABLE `minorrequirements` (
  `minorID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL,
  `minimumGradeRequired` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `minorrequirements`
--

INSERT INTO `minorrequirements` (`minorID`, `courseID`, `minimumGradeRequired`) VALUES
(1, 1, 'C'),
(2, 2, 'C'),
(3, 3, 'B'),
(4, 4, 'A'),
(5, 5, 'B'),
(6, 6, 'B'),
(7, 7, 'C'),
(8, 8, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `office`
--

CREATE TABLE `office` (
  `officeID` int(11) NOT NULL,
  `numOfDesks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `office`
--

INSERT INTO `office` (`officeID`, `numOfDesks`) VALUES
(1, 5),
(2, 4),
(3, 6),
(4, 3),
(5, 8),
(6, 2),
(7, 7),
(8, 6);

-- --------------------------------------------------------

--
-- Table structure for table `period`
--

CREATE TABLE `period` (
  `periodID` int(11) NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `period`
--

INSERT INTO `period` (`periodID`, `startTime`, `endTime`) VALUES
(1, '08:00:00', '09:00:00'),
(2, '09:00:00', '10:00:00'),
(3, '10:00:00', '11:00:00'),
(4, '11:00:00', '12:00:00'),
(5, '12:00:00', '13:00:00'),
(6, '13:00:00', '14:00:00'),
(7, '14:00:00', '15:00:00'),
(8, '15:00:00', '16:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `roomID` int(11) NOT NULL,
  `roomNo` varchar(20) NOT NULL,
  `roomType` enum('Lecture','Lab','Office') NOT NULL,
  `buildingID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`roomID`, `roomNo`, `roomType`, `buildingID`) VALUES
(1, '101', 'Lecture', 1),
(2, '102', 'Lecture', 1),
(3, '201', 'Lab', 5),
(4, '202', 'Lab', 5),
(5, '301', 'Office', 2),
(6, '302', 'Office', 2),
(7, '401', 'Office', 8),
(8, '402', 'Lecture', 8);

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `semesterID` int(11) NOT NULL,
  `semesterName` varchar(100) NOT NULL,
  `semesterYear` int(11) NOT NULL,
  `startTime` date NOT NULL,
  `endTime` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`semesterID`, `semesterName`, `semesterYear`, `startTime`, `endTime`) VALUES
(1, 'Fall', 2023, '2023-09-01', '2023-12-15'),
(2, 'Spring', 2024, '2024-01-15', '2024-05-10'),
(3, 'Summer', 2024, '2024-06-01', '2024-08-15'),
(4, 'Fall', 2024, '2024-09-01', '2024-12-15'),
(5, 'Spring', 2025, '2025-01-15', '2025-05-10'),
(6, 'Summer', 2025, '2025-06-01', '2025-08-15'),
(7, 'Fall', 2025, '2025-09-01', '2025-12-15'),
(8, 'Spring', 2026, '2026-01-15', '2026-05-10');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `studentID` int(11) NOT NULL,
  `studentYear` enum('Freshman','Sophomore','Junior','Senior') DEFAULT NULL,
  `studentType` enum('Undergraduate','Graduate') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`studentID`, `studentYear`, `studentType`) VALUES
(1, 'Freshman', 'Undergraduate'),
(2, 'Sophomore', 'Undergraduate'),
(3, 'Junior', 'Undergraduate'),
(4, 'Senior', 'Undergraduate'),
(5, '', 'Graduate'),
(6, '', 'Graduate'),
(7, '', 'Graduate'),
(8, '', 'Graduate');

-- --------------------------------------------------------

--
-- Table structure for table `studenthistory`
--

CREATE TABLE `studenthistory` (
  `studentID` int(11) NOT NULL,
  `crnNo` int(11) NOT NULL,
  `courseID` int(11) DEFAULT NULL,
  `grade` char(2) DEFAULT NULL,
  `semesterID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studenthistory`
--

INSERT INTO `studenthistory` (`studentID`, `crnNo`, `courseID`, `grade`, `semesterID`) VALUES
(1, 1, 1, 'A', 1),
(2, 2, 2, 'B', 1),
(3, 3, 3, 'A', 2),
(4, 4, 4, 'C', 2),
(5, 5, 5, 'B', 3),
(6, 6, 6, 'A', 3),
(7, 7, 7, 'A', 4),
(8, 8, 8, 'B', 4);

-- --------------------------------------------------------

--
-- Table structure for table `studenthold`
--

CREATE TABLE `studenthold` (
  `studentID` int(11) NOT NULL,
  `holdID` int(11) NOT NULL,
  `dateOfHold` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studenthold`
--

INSERT INTO `studenthold` (`studentID`, `holdID`, `dateOfHold`) VALUES
(1, 1, '2023-09-01'),
(1, 2, '2023-10-15'),
(1, 5, '2024-01-10'),
(1, 6, '2024-02-05'),
(5, 3, '2023-11-20'),
(5, 4, '2023-12-10'),
(5, 7, '2024-03-15'),
(5, 8, '2024-04-01');

-- --------------------------------------------------------

--
-- Table structure for table `studentmajor`
--

CREATE TABLE `studentmajor` (
  `studentID` int(11) NOT NULL,
  `majorID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studentmajor`
--

INSERT INTO `studentmajor` (`studentID`, `majorID`) VALUES
(1, 1),
(1, 3),
(1, 6),
(1, 8),
(5, 1),
(5, 2),
(5, 4),
(5, 7);

-- --------------------------------------------------------

--
-- Table structure for table `studentminor`
--

CREATE TABLE `studentminor` (
  `studentID` int(11) NOT NULL,
  `minorID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studentminor`
--

INSERT INTO `studentminor` (`studentID`, `minorID`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8);

-- --------------------------------------------------------

--
-- Table structure for table `timeslot`
--

CREATE TABLE `timeslot` (
  `timeSlotID` int(11) NOT NULL,
  `days` int(11) NOT NULL,
  `periods` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timeslot`
--

INSERT INTO `timeslot` (`timeSlotID`, `days`, `periods`) VALUES
(25, 1, 1),
(26, 2, 2),
(27, 3, 3),
(28, 4, 4),
(29, 5, 5),
(30, 6, 6),
(31, 7, 7),
(32, 8, 8);

-- --------------------------------------------------------

--
-- Table structure for table `timeslotday`
--

CREATE TABLE `timeslotday` (
  `timeSlotID` int(11) NOT NULL,
  `dayID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timeslotday`
--

INSERT INTO `timeslotday` (`timeSlotID`, `dayID`) VALUES
(25, 1),
(26, 2),
(27, 3),
(28, 4),
(29, 5),
(30, 6),
(31, 7),
(32, 8);

-- --------------------------------------------------------

--
-- Table structure for table `timeslotperiod`
--

CREATE TABLE `timeslotperiod` (
  `timeSlotID` int(11) NOT NULL,
  `periodID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timeslotperiod`
--

INSERT INTO `timeslotperiod` (`timeSlotID`, `periodID`) VALUES
(25, 1),
(26, 2),
(27, 4),
(28, 5),
(29, 6),
(30, 7),
(31, 3),
(32, 8);

-- --------------------------------------------------------

--
-- Table structure for table `undergraduate`
--

CREATE TABLE `undergraduate` (
  `studentID` int(11) NOT NULL,
  `deptID` int(11) DEFAULT NULL,
  `undergraduateStudentType` enum('Full-Time','Part-Time') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `undergraduate`
--

INSERT INTO `undergraduate` (`studentID`, `deptID`, `undergraduateStudentType`) VALUES
(1, 8, 'Part-Time'),
(2, 5, 'Full-Time'),
(3, 3, 'Part-Time'),
(4, 1, 'Full-Time'),
(5, 4, 'Full-Time'),
(6, 2, 'Part-Time'),
(7, 6, 'Full-Time'),
(8, 7, 'Part-Time');

-- --------------------------------------------------------

--
-- Table structure for table `undergraduatefulltime`
--

CREATE TABLE `undergraduatefulltime` (
  `studentID` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `minCredits` int(11) NOT NULL,
  `maxCredits` int(11) NOT NULL,
  `creditsEarned` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `undergraduatefulltime`
--

INSERT INTO `undergraduatefulltime` (`studentID`, `year`, `minCredits`, `maxCredits`, `creditsEarned`) VALUES
(1, 2023, 12, 18, 15),
(2, 2024, 12, 18, 16),
(3, 2024, 12, 18, 13),
(4, 2023, 12, 18, 14),
(5, 2025, 12, 18, 18),
(6, 2026, 12, 18, 17),
(7, 2026, 12, 18, 15),
(8, 2025, 12, 18, 16);

-- --------------------------------------------------------

--
-- Table structure for table `undergraduateparttime`
--

CREATE TABLE `undergraduateparttime` (
  `studentID` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `minCredits` int(11) NOT NULL,
  `maxCredits` int(11) NOT NULL,
  `creditsEarned` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `undergraduateparttime`
--

INSERT INTO `undergraduateparttime` (`studentID`, `year`, `minCredits`, `maxCredits`, `creditsEarned`) VALUES
(1, 2023, 6, 12, 11),
(2, 2026, 6, 12, 7),
(3, 2023, 6, 12, 10),
(4, 2025, 6, 12, 10),
(5, 2024, 6, 12, 8),
(6, 2024, 6, 12, 9),
(7, 2026, 6, 12, 8),
(8, 2025, 6, 12, 9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminID`);

--
-- Indexes for table `advisor`
--
ALTER TABLE `advisor`
  ADD PRIMARY KEY (`studentID`),
  ADD KEY `facultyID` (`facultyID`);

--
-- Indexes for table `appuser`
--
ALTER TABLE `appuser`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`studentID`,`crnNo`),
  ADD KEY `crnNo` (`crnNo`),
  ADD KEY `courseID` (`courseID`);

--
-- Indexes for table `building`
--
ALTER TABLE `building`
  ADD PRIMARY KEY (`buildingID`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`courseID`),
  ADD KEY `deptID` (`deptID`);

--
-- Indexes for table `courseprerequisites`
--
ALTER TABLE `courseprerequisites`
  ADD PRIMARY KEY (`courseID`,`prerequisiteCourseID`),
  ADD KEY `prerequisiteCourseID` (`prerequisiteCourseID`);

--
-- Indexes for table `coursesection`
--
ALTER TABLE `coursesection`
  ADD PRIMARY KEY (`crnNo`),
  ADD KEY `courseID` (`courseID`),
  ADD KEY `facultyID` (`facultyID`),
  ADD KEY `roomID` (`roomID`);

--
-- Indexes for table `day`
--
ALTER TABLE `day`
  ADD PRIMARY KEY (`dayID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`deptID`),
  ADD KEY `chairID` (`chairID`),
  ADD KEY `roomID` (`roomID`);

--
-- Indexes for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD PRIMARY KEY (`studentID`,`crnNo`),
  ADD KEY `crnNo` (`crnNo`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`facultyID`);

--
-- Indexes for table `facultyfulltime`
--
ALTER TABLE `facultyfulltime`
  ADD PRIMARY KEY (`facultyID`);

--
-- Indexes for table `facultyhistory`
--
ALTER TABLE `facultyhistory`
  ADD PRIMARY KEY (`facultyID`,`crnNo`),
  ADD KEY `crnNo` (`crnNo`),
  ADD KEY `courseID` (`courseID`),
  ADD KEY `semesterID` (`semesterID`);

--
-- Indexes for table `facultyparttime`
--
ALTER TABLE `facultyparttime`
  ADD PRIMARY KEY (`facultyID`);

--
-- Indexes for table `graduate`
--
ALTER TABLE `graduate`
  ADD PRIMARY KEY (`studentID`),
  ADD KEY `deptID` (`deptID`);

--
-- Indexes for table `graduatefulltime`
--
ALTER TABLE `graduatefulltime`
  ADD PRIMARY KEY (`studentID`);

--
-- Indexes for table `graduateparttime`
--
ALTER TABLE `graduateparttime`
  ADD PRIMARY KEY (`studentID`);

--
-- Indexes for table `hold`
--
ALTER TABLE `hold`
  ADD PRIMARY KEY (`holdID`);

--
-- Indexes for table `institutionalresearcher`
--
ALTER TABLE `institutionalresearcher`
  ADD PRIMARY KEY (`institutionalResearcherID`);

--
-- Indexes for table `lab`
--
ALTER TABLE `lab`
  ADD PRIMARY KEY (`labID`);

--
-- Indexes for table `lecture`
--
ALTER TABLE `lecture`
  ADD PRIMARY KEY (`lectureID`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `major`
--
ALTER TABLE `major`
  ADD PRIMARY KEY (`majorID`),
  ADD KEY `deptID` (`deptID`);

--
-- Indexes for table `majorrequirements`
--
ALTER TABLE `majorrequirements`
  ADD PRIMARY KEY (`majorID`,`courseID`),
  ADD KEY `courseID` (`courseID`);

--
-- Indexes for table `minor`
--
ALTER TABLE `minor`
  ADD PRIMARY KEY (`minorID`),
  ADD KEY `deptID` (`deptID`);

--
-- Indexes for table `minorrequirements`
--
ALTER TABLE `minorrequirements`
  ADD PRIMARY KEY (`minorID`,`courseID`),
  ADD KEY `courseID` (`courseID`);

--
-- Indexes for table `office`
--
ALTER TABLE `office`
  ADD PRIMARY KEY (`officeID`);

--
-- Indexes for table `period`
--
ALTER TABLE `period`
  ADD PRIMARY KEY (`periodID`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`roomID`),
  ADD KEY `buildingID` (`buildingID`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`semesterID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`studentID`);

--
-- Indexes for table `studenthistory`
--
ALTER TABLE `studenthistory`
  ADD PRIMARY KEY (`studentID`,`crnNo`),
  ADD KEY `crnNo` (`crnNo`),
  ADD KEY `courseID` (`courseID`),
  ADD KEY `semesterID` (`semesterID`);

--
-- Indexes for table `studenthold`
--
ALTER TABLE `studenthold`
  ADD PRIMARY KEY (`studentID`,`holdID`),
  ADD KEY `holdID` (`holdID`);

--
-- Indexes for table `studentmajor`
--
ALTER TABLE `studentmajor`
  ADD PRIMARY KEY (`studentID`,`majorID`),
  ADD KEY `majorID` (`majorID`);

--
-- Indexes for table `studentminor`
--
ALTER TABLE `studentminor`
  ADD PRIMARY KEY (`studentID`,`minorID`),
  ADD KEY `minorID` (`minorID`);

--
-- Indexes for table `timeslot`
--
ALTER TABLE `timeslot`
  ADD PRIMARY KEY (`timeSlotID`),
  ADD KEY `days` (`days`),
  ADD KEY `periods` (`periods`);

--
-- Indexes for table `timeslotday`
--
ALTER TABLE `timeslotday`
  ADD PRIMARY KEY (`timeSlotID`,`dayID`),
  ADD KEY `dayID` (`dayID`);

--
-- Indexes for table `timeslotperiod`
--
ALTER TABLE `timeslotperiod`
  ADD PRIMARY KEY (`timeSlotID`,`periodID`),
  ADD KEY `periodID` (`periodID`);

--
-- Indexes for table `undergraduate`
--
ALTER TABLE `undergraduate`
  ADD PRIMARY KEY (`studentID`),
  ADD KEY `deptID` (`deptID`);

--
-- Indexes for table `undergraduatefulltime`
--
ALTER TABLE `undergraduatefulltime`
  ADD PRIMARY KEY (`studentID`);

--
-- Indexes for table `undergraduateparttime`
--
ALTER TABLE `undergraduateparttime`
  ADD PRIMARY KEY (`studentID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `appuser`
--
ALTER TABLE `appuser`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `building`
--
ALTER TABLE `building`
  MODIFY `buildingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `courseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `coursesection`
--
ALTER TABLE `coursesection`
  MODIFY `crnNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `day`
--
ALTER TABLE `day`
  MODIFY `dayID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `deptID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `facultyID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hold`
--
ALTER TABLE `hold`
  MODIFY `holdID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `institutionalresearcher`
--
ALTER TABLE `institutionalresearcher`
  MODIFY `institutionalResearcherID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `major`
--
ALTER TABLE `major`
  MODIFY `majorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `minor`
--
ALTER TABLE `minor`
  MODIFY `minorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `period`
--
ALTER TABLE `period`
  MODIFY `periodID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `roomID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `semester`
--
ALTER TABLE `semester`
  MODIFY `semesterID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `timeslot`
--
ALTER TABLE `timeslot`
  MODIFY `timeSlotID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`adminID`) REFERENCES `appuser` (`userID`);

--
-- Constraints for table `advisor`
--
ALTER TABLE `advisor`
  ADD CONSTRAINT `advisor_ibfk_1` FOREIGN KEY (`facultyID`) REFERENCES `faculty` (`facultyID`),
  ADD CONSTRAINT `advisor_ibfk_2` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`);

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`crnNo`) REFERENCES `coursesection` (`crnNo`),
  ADD CONSTRAINT `attendance_ibfk_3` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`);

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`deptID`) REFERENCES `department` (`deptID`);

--
-- Constraints for table `courseprerequisites`
--
ALTER TABLE `courseprerequisites`
  ADD CONSTRAINT `courseprerequisites_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`),
  ADD CONSTRAINT `courseprerequisites_ibfk_2` FOREIGN KEY (`prerequisiteCourseID`) REFERENCES `course` (`courseID`);

--
-- Constraints for table `coursesection`
--
ALTER TABLE `coursesection`
  ADD CONSTRAINT `coursesection_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`),
  ADD CONSTRAINT `coursesection_ibfk_2` FOREIGN KEY (`facultyID`) REFERENCES `appuser` (`userID`),
  ADD CONSTRAINT `coursesection_ibfk_3` FOREIGN KEY (`roomID`) REFERENCES `room` (`roomID`);

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`chairID`) REFERENCES `appuser` (`userID`),
  ADD CONSTRAINT `department_ibfk_2` FOREIGN KEY (`roomID`) REFERENCES `room` (`roomID`);

--
-- Constraints for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD CONSTRAINT `enrollment_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`),
  ADD CONSTRAINT `enrollment_ibfk_2` FOREIGN KEY (`crnNo`) REFERENCES `coursesection` (`crnNo`);

--
-- Constraints for table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `faculty_ibfk_1` FOREIGN KEY (`facultyID`) REFERENCES `appuser` (`userID`);

--
-- Constraints for table `facultyfulltime`
--
ALTER TABLE `facultyfulltime`
  ADD CONSTRAINT `facultyfulltime_ibfk_1` FOREIGN KEY (`facultyID`) REFERENCES `faculty` (`facultyID`);

--
-- Constraints for table `facultyhistory`
--
ALTER TABLE `facultyhistory`
  ADD CONSTRAINT `facultyhistory_ibfk_1` FOREIGN KEY (`facultyID`) REFERENCES `appuser` (`userID`),
  ADD CONSTRAINT `facultyhistory_ibfk_2` FOREIGN KEY (`crnNo`) REFERENCES `coursesection` (`crnNo`),
  ADD CONSTRAINT `facultyhistory_ibfk_3` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`),
  ADD CONSTRAINT `facultyhistory_ibfk_4` FOREIGN KEY (`semesterID`) REFERENCES `semester` (`semesterID`);

--
-- Constraints for table `facultyparttime`
--
ALTER TABLE `facultyparttime`
  ADD CONSTRAINT `facultyparttime_ibfk_1` FOREIGN KEY (`facultyID`) REFERENCES `faculty` (`facultyID`);

--
-- Constraints for table `graduate`
--
ALTER TABLE `graduate`
  ADD CONSTRAINT `graduate_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`),
  ADD CONSTRAINT `graduate_ibfk_2` FOREIGN KEY (`deptID`) REFERENCES `department` (`deptID`);

--
-- Constraints for table `graduatefulltime`
--
ALTER TABLE `graduatefulltime`
  ADD CONSTRAINT `graduatefulltime_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `graduate` (`studentID`);

--
-- Constraints for table `graduateparttime`
--
ALTER TABLE `graduateparttime`
  ADD CONSTRAINT `graduateparttime_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `graduate` (`studentID`);

--
-- Constraints for table `institutionalresearcher`
--
ALTER TABLE `institutionalresearcher`
  ADD CONSTRAINT `institutionalresearcher_ibfk_1` FOREIGN KEY (`institutionalResearcherID`) REFERENCES `appuser` (`userID`);

--
-- Constraints for table `lab`
--
ALTER TABLE `lab`
  ADD CONSTRAINT `lab_ibfk_1` FOREIGN KEY (`labID`) REFERENCES `room` (`roomID`);

--
-- Constraints for table `lecture`
--
ALTER TABLE `lecture`
  ADD CONSTRAINT `lecture_ibfk_1` FOREIGN KEY (`lectureID`) REFERENCES `room` (`roomID`);

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `appuser` (`userID`);

--
-- Constraints for table `major`
--
ALTER TABLE `major`
  ADD CONSTRAINT `major_ibfk_1` FOREIGN KEY (`deptID`) REFERENCES `department` (`deptID`);

--
-- Constraints for table `majorrequirements`
--
ALTER TABLE `majorrequirements`
  ADD CONSTRAINT `majorrequirements_ibfk_1` FOREIGN KEY (`majorID`) REFERENCES `major` (`majorID`),
  ADD CONSTRAINT `majorrequirements_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`);

--
-- Constraints for table `minor`
--
ALTER TABLE `minor`
  ADD CONSTRAINT `minor_ibfk_1` FOREIGN KEY (`deptID`) REFERENCES `department` (`deptID`);

--
-- Constraints for table `minorrequirements`
--
ALTER TABLE `minorrequirements`
  ADD CONSTRAINT `minorrequirements_ibfk_1` FOREIGN KEY (`minorID`) REFERENCES `minor` (`minorID`),
  ADD CONSTRAINT `minorrequirements_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`);

--
-- Constraints for table `office`
--
ALTER TABLE `office`
  ADD CONSTRAINT `office_ibfk_1` FOREIGN KEY (`officeID`) REFERENCES `room` (`roomID`);

--
-- Constraints for table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`buildingID`) REFERENCES `building` (`buildingID`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `appuser` (`userID`);

--
-- Constraints for table `studenthistory`
--
ALTER TABLE `studenthistory`
  ADD CONSTRAINT `studenthistory_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`),
  ADD CONSTRAINT `studenthistory_ibfk_2` FOREIGN KEY (`crnNo`) REFERENCES `coursesection` (`crnNo`),
  ADD CONSTRAINT `studenthistory_ibfk_3` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`),
  ADD CONSTRAINT `studenthistory_ibfk_4` FOREIGN KEY (`semesterID`) REFERENCES `semester` (`semesterID`);

--
-- Constraints for table `studenthold`
--
ALTER TABLE `studenthold`
  ADD CONSTRAINT `studenthold_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`),
  ADD CONSTRAINT `studenthold_ibfk_2` FOREIGN KEY (`holdID`) REFERENCES `hold` (`holdID`);

--
-- Constraints for table `studentmajor`
--
ALTER TABLE `studentmajor`
  ADD CONSTRAINT `studentmajor_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`),
  ADD CONSTRAINT `studentmajor_ibfk_2` FOREIGN KEY (`majorID`) REFERENCES `major` (`majorID`);

--
-- Constraints for table `studentminor`
--
ALTER TABLE `studentminor`
  ADD CONSTRAINT `studentminor_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`),
  ADD CONSTRAINT `studentminor_ibfk_2` FOREIGN KEY (`minorID`) REFERENCES `minor` (`minorID`);

--
-- Constraints for table `timeslot`
--
ALTER TABLE `timeslot`
  ADD CONSTRAINT `timeslot_ibfk_1` FOREIGN KEY (`days`) REFERENCES `day` (`dayID`),
  ADD CONSTRAINT `timeslot_ibfk_2` FOREIGN KEY (`periods`) REFERENCES `period` (`periodID`);

--
-- Constraints for table `timeslotday`
--
ALTER TABLE `timeslotday`
  ADD CONSTRAINT `timeslotday_ibfk_1` FOREIGN KEY (`timeSlotID`) REFERENCES `timeslot` (`timeSlotID`),
  ADD CONSTRAINT `timeslotday_ibfk_2` FOREIGN KEY (`dayID`) REFERENCES `day` (`dayID`);

--
-- Constraints for table `timeslotperiod`
--
ALTER TABLE `timeslotperiod`
  ADD CONSTRAINT `timeslotperiod_ibfk_1` FOREIGN KEY (`timeSlotID`) REFERENCES `timeslot` (`timeSlotID`),
  ADD CONSTRAINT `timeslotperiod_ibfk_2` FOREIGN KEY (`periodID`) REFERENCES `period` (`periodID`);

--
-- Constraints for table `undergraduate`
--
ALTER TABLE `undergraduate`
  ADD CONSTRAINT `undergraduate_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`),
  ADD CONSTRAINT `undergraduate_ibfk_2` FOREIGN KEY (`deptID`) REFERENCES `department` (`deptID`);

--
-- Constraints for table `undergraduatefulltime`
--
ALTER TABLE `undergraduatefulltime`
  ADD CONSTRAINT `undergraduatefulltime_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `undergraduate` (`studentID`);

--
-- Constraints for table `undergraduateparttime`
--
ALTER TABLE `undergraduateparttime`
  ADD CONSTRAINT `undergraduateparttime_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `undergraduate` (`studentID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
