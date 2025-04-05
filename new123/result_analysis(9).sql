-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2025 at 12:52 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `result_analysis`
--

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `department` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `full_name`, `email`, `username`, `password`, `department`) VALUES
(1, 'Uttam Anil Bhajipale', 'uttambhajipale@gmail.com', 'abcdefg', '$2y$10$zctiWC7gqfc9STYZpBZ8j.Fh548ObjAIPjdW8pa3K9PwVNzsMihWO', '789654'),
(2, 'swati ji', 'siti@gmail.com', 'swati123', '$2y$10$2p5sz2dnPfuSrp2WB6pP0efAcsI0lEjZuEzo3gA6hhNuAZdxy8UHm', 'swati'),
(3, 'KALYANI DINESH UTANE', 'kalyaniutane12@gmail.com', 'Kalyani', '$2y$10$51S3KKKVyHgbLcWvCD3zie0Wsc2GSOydGpoheNeCvhQ/oUnzYLTYq', 'Computer Technology'),
(4, 'Purwa', 'purvakatre0607@gmail.com', 'Purwa', '$2y$10$NOsYrmg5aEA.1M.61o0ykObrcxJFi61q/zNXHZcspcki/.3IEWtBy', 'Computer Technology'),
(5, 'KALYANI DINESH UTANE', 'kalyani9@gmail.com', 'kalyani', '$2y$10$Oopjqe1t6k6MuFrjeEfM5.3ZuIsJNYd3woT9bxnxGh.850titBlgC', 'Computer Technology'),
(7, 'uttam', 'utta@gmail.com', 'uttam', '$2y$10$KKk17jMWw3Lrjrglh13/sOZSQLc0sieUjOX/UWlYU7s1AH.r2N9dW', 'Computer Technology'),
(8, 'uttam', 'uttam@gmail.com', 'vaibhav', '$2y$10$hAkObuB2X959tSH/4x2tKu60HkOv1sUacmHbjDCxtqx2a6MDuG8Qm', 'Computer Technology'),
(9, 'swati', 'swati@gmail.com', 'swati', '$2y$10$aS/pfKPh6t3GPxmqML4duerSVv1k7Y70SuxcySickD7G3R3K3AVum', 'Computer Technology');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_results`
--

CREATE TABLE `faculty_results` (
  `id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `semester` varchar(50) NOT NULL,
  `subjects` text NOT NULL,
  `enrolled_students` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `year` int(9) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `semester` int(11) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `subject_type` varchar(100) NOT NULL,
  `max_marks` int(11) NOT NULL,
  `students_enrolled` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `year`, `branch`, `semester`, `subject_name`, `subject_type`, `max_marks`, `students_enrolled`) VALUES
(31, 0, 'ECE', 3, 'marathi', 'Theory, Practical', 150, 40),
(32, 0, 'ECE', 3, 'science', 'Theory, Practical', 150, 40),
(33, 0, 'ECE', 3, 'hindi', 'Practical', 150, 40),
(34, 2025, '0', 1, 'marathi', 'Theory, Practical, SLA', 150, 50),
(35, 2025, '0', 1, 'sceince', 'Practical', 50, 50),
(36, 2025, '0', 2, 'marathi', 'Theory, Practical, SLA', 150, 50),
(37, 2025, '0', 2, 'sceince', 'Practical', 50, 50),
(38, 2025, '0', 3, 'abcd', 'Theory, Practical, SLA', 150, 50),
(39, 2025, '0', 3, 'efg', 'Practical', 50, 50),
(40, 0, 'ME', 2, 'asss', 'Theory, Practical, SLA', 150, 50),
(41, 0, 'ME', 2, 'fdsfsfse', 'Theory, SLA', 100, 50),
(42, 0, 'CE', 1, 'math', 'Theory, Practical, SLA', 150, 50),
(43, 0, 'CE', 1, 'chemistry', 'Theory, Practical, SLA', 150, 50),
(44, 0, 'CE', 1, 'physics', 'Theory, Practical, SLA', 150, 50),
(45, 2025, 'CSE', 1, 'swati', 'Theory, Practical, SLA', 150, 22),
(46, 2025, 'CSE', 1, 'python', 'Theory, Practical', 125, 50),
(47, 2025, 'CSE', 1, 'mad', 'Theory, Practical, SLA', 150, 50),
(48, 2025, 'CE', 2, 'php', 'Theory, Practical, SLA', 150, 50),
(49, 2025, 'CE', 2, 'mad', 'Practical', 50, 50),
(50, 2025, 'EJ', 1, 'math', 'Theory(70), Practical(50), SLA(30)', 150, 50),
(51, 2025, 'EJ', 1, 'efg', '', 0, 50),
(52, 2025, 'CE', 2, 'mad', 'Theory(70), Practical(50), SLA(30)', 150, 50),
(53, 2025, 'CE', 2, 'efg', 'Practical(50)', 50, 50),
(54, 2025, 'EJ', 4, 'swati', 'Theory(50), Practical(40), SLA(40)', 130, 50),
(55, 2025, 'EJ', 4, 'kalyani', 'Theory(49), Practical(50), SLA(33)', 132, 50);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `enrollment_number` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `username`, `enrollment_number`, `password`) VALUES
(1, 'uttam', '2200910003', '$2y$10$bBPDAzvOLBW8UGBjMb9DGeImPXt2ubYvVJVL6f//I.oYZDOZCwTau'),
(3, 'swati', '1234', '$2y$10$uQAiHXBZawPY/0FxlO/5s.a.paAshbw/YZydyITvw83KSmmHh3uHS'),
(4, 'swati', '1234', '$2y$10$kZ7SamnZXlu39T.n3BK/cue.UjI.xWaLIR2i2zMh.YKdDmAqgYJ0y'),
(5, 'uttam', '2200910003', '$2y$10$Ttf7UiFt.EWp7mJb48RSeOp0Z0F7b2RaO8GysaL4pYNjDRAq9M3na'),
(6, 'uttam', '456', '$2y$10$7nXwf57PqTopS.Wm9MkEwuMxRpXpsCHDX3zxsZfh.8u1TArfGy8yS'),
(7, 'uttam', '456', '$2y$10$1aJRfrznv78Azwfv2xjvKeOD79XrvfxrfWqTiNZFnXlqQX/SXhTGu'),
(8, 'sub', '143', '$2y$10$XhHk2dfThbXf6WNdi74LoewYaspckhTYGz5Y9GEmBmEXR2mWfndwa'),
(9, 'sub', '143', '$2y$10$6uh.IHxmCp5/awv6mbDb8OCaiE/HZFIyc8DPYBfuRie88e/rbON3y'),
(10, 'uab', '22009', '$2y$10$eH8w0yr18PYrYKuKfiJGYu2KbrkIl.LrifulqSNdSy0WkX5Wcut7q'),
(11, 'swatshajare', '456', '$2y$10$LeMsUZkonQGLqrekbrdQe.efvnor.TAlC.eQe52gGXroG3fO8JEYu'),
(12, 'Purwa', '23310220181', '$2y$10$aIUaZ5QXGHDDoBcc.nEb5eGeZQrtqVhLaOGUM9LSuMIymGw41SqSG'),
(13, 'Purwa', '23310220181', '$2y$10$hC.3xvaC5GVMI1Ed130vYOc8i.ScgMrPcIJI3n9AvUctg0YerDH9O'),
(14, 'abcd', '456', '$2y$10$zydwlkaBX6TLHLTZ8UkXfO2Xnhn7gAIxeCAslREN11bZ2qCrgCVrW'),
(15, 'kalyani', '2331220184', '$2y$10$9HzacVvhLlxwOIHCnBjr4O.Xbp7HdZRFdPprlrZclBr7jefWILVSK'),
(16, 'ahi', '2331220183', '$2y$10$SuGqJoNEIEN/g94Ln9Jsi.uBXwhvrm2LIljMuPw7nHE7UprPe6FUa'),
(17, 'ahi', '2331220183', '$2y$10$1QsL4nVFG.KLNzJWGmW8P.CWCHud64CUVy9kEPPtIr/GZ2lnOOasC'),
(18, 'ahi', '2331220183', '$2y$10$mVYlS4OLkDFMwLd.xf1Y2e6zyCBCHfDWhK3YUvVR5.qWpF9VMpCNq'),
(19, 'ahi', '2331220183', '$2y$10$by4V/XJ7N4sCvBm5OyWihuIm.MRgA8LNi7NX3/twH7hLVRAqaCyR.'),
(20, 'amit', '23331845', '$2y$10$9LQ7nxGuTbrj3DIeYljxKuah048sDlTsyz8riq637tDAZYCoaQEpi'),
(21, 'riya', '12344321', '$2y$10$V1x91b8olN/wMYAeloHnWOYO1H20rui/w/kf4IQePcU4PcaJ5.9uC');

-- --------------------------------------------------------

--
-- Table structure for table `student_results`
--

CREATE TABLE `student_results` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `enrollment` varchar(20) NOT NULL,
  `academic_year` int(9) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `semester` varchar(50) NOT NULL,
  `subject_name` varchar(500) NOT NULL,
  `total_marks` int(11) NOT NULL,
  `result` varchar(10) NOT NULL,
  `percentage` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_results`
--

INSERT INTO `student_results` (`id`, `student_id`, `enrollment`, `academic_year`, `branch`, `semester`, `subject_name`, `total_marks`, `result`, `percentage`) VALUES
(20, 14, '123456789', 0, 'CSE', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"50\",\"sla\":\"\"}]', 200, 'Pass', 100),
(21, 14, '123456789', 0, 'CSE', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"50\",\"sla\":\"\"}]', 200, 'Pass', 100),
(22, 14, '2147483647', 0, 'CSE', '6', '[{\"id\":\"9\",\"name\":\"MAD\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"10\",\"name\":\"WBP\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"11\",\"name\":\"PHP\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"12\",\"name\":\"management\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"13\",\"name\":\"ETI\",\"theory\":\"70\",\"practical\":\"\",\"sla\":\"30\"}]', 700, 'Pass', 100),
(23, 14, '2147483647', 0, 'CSE', '6', '[{\"id\":\"9\",\"name\":\"MAD\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"10\",\"name\":\"WBP\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"11\",\"name\":\"PHP\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"12\",\"name\":\"management\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"13\",\"name\":\"ETI\",\"theory\":\"70\",\"practical\":\"\",\"sla\":\"30\"}]', 700, 'Pass', 100),
(24, 14, '2147483647', 0, 'CSE', '6', '[{\"id\":\"9\",\"name\":\"MAD\",\"theory\":\"69\",\"practical\":\"49\",\"sla\":\"29\"},{\"id\":\"10\",\"name\":\"WBP\",\"theory\":\"69\",\"practical\":\"49\",\"sla\":\"29\"},{\"id\":\"11\",\"name\":\"PHP\",\"theory\":\"69\",\"practical\":\"49\",\"sla\":\"29\"},{\"id\":\"12\",\"name\":\"management\",\"theory\":\"69\",\"practical\":\"49\",\"sla\":\"29\"},{\"id\":\"13\",\"name\":\"ETI\",\"theory\":\"69\",\"practical\":\"\",\"sla\":\"29\"}]', 686, 'Pass', 98),
(25, 14, '2147483647', 0, 'CSE', '6', '[{\"id\":\"9\",\"name\":\"MAD\",\"theory\":\"69\",\"practical\":\"49\",\"sla\":\"29\"},{\"id\":\"10\",\"name\":\"WBP\",\"theory\":\"69\",\"practical\":\"49\",\"sla\":\"29\"},{\"id\":\"11\",\"name\":\"PHP\",\"theory\":\"69\",\"practical\":\"49\",\"sla\":\"29\"},{\"id\":\"12\",\"name\":\"management\",\"theory\":\"69\",\"practical\":\"49\",\"sla\":\"29\"},{\"id\":\"13\",\"name\":\"ETI\",\"theory\":\"69\",\"practical\":\"\",\"sla\":\"29\"}]', 686, 'Pass', 98),
(26, 14, '2147483647', 0, 'CSE', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"67\",\"practical\":\"45\",\"sla\":\"22\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"45\",\"sla\":\"\"}]', 179, 'Pass', 89.5),
(27, 14, '2147483647', 0, 'CSE', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"67\",\"practical\":\"45\",\"sla\":\"22\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"45\",\"sla\":\"\"}]', 179, 'Pass', 89.5),
(28, 14, '2147483647', 0, 'CSE', '6', '[{\"id\":\"9\",\"name\":\"MAD\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"10\",\"name\":\"WBP\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"11\",\"name\":\"PHP\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"12\",\"name\":\"management\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"13\",\"name\":\"ETI\",\"theory\":\"70\",\"practical\":\"\",\"sla\":\"30\"}]', 700, 'Pass', 100),
(29, 14, '2147483647', 0, 'CSE', '6', '[{\"id\":\"9\",\"name\":\"MAD\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"10\",\"name\":\"WBP\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"11\",\"name\":\"PHP\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"12\",\"name\":\"management\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"13\",\"name\":\"ETI\",\"theory\":\"70\",\"practical\":\"\",\"sla\":\"30\"}]', 700, 'Pass', 100),
(30, 14, '2147483647', 0, 'CSE', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"68\",\"practical\":\"45\",\"sla\":\"25\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"45\",\"sla\":\"\"}]', 183, 'Pass', 91.5),
(31, 14, '1112233', 0, 'CSE', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"50\",\"sla\":\"\"}]', 200, 'Pass', 100),
(32, 14, '1112233', 0, 'CSE', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"50\",\"sla\":\"\"}]', 200, 'Pass', 100),
(33, 14, '1112233', 0, 'CSE', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"50\",\"sla\":\"\"}]', 200, 'Pass', 100),
(34, 14, '1111111', 0, 'CSE', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"50\",\"sla\":\"\"}]', 200, 'Pass', 100),
(35, 14, '22222', 0, 'ECE', '2', '[{\"id\":\"7\",\"name\":\"wire\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"8\",\"name\":\"motar\",\"theory\":\"\",\"practical\":\"50\",\"sla\":\"\"}]', 200, 'Pass', 100),
(36, 14, '22222', 0, 'ECE', '2', '[{\"id\":\"7\",\"name\":\"wire\",\"theory\":\"70\",\"practical\":\"50\",\"sla\":\"25\"},{\"id\":\"8\",\"name\":\"motar\",\"theory\":\"\",\"practical\":\"45\",\"sla\":\"\"}]', 190, 'Pass', 95),
(37, 16, '2147483647', 0, 'CSE', '6', '[{\"id\":\"9\",\"name\":\"MAD\",\"theory\":\"\",\"practical\":\"\",\"sla\":\"\"},{\"id\":\"10\",\"name\":\"WBP\",\"theory\":\"\",\"practical\":\"\",\"sla\":\"\"},{\"id\":\"11\",\"name\":\"PHP\",\"theory\":\"\",\"practical\":\"\",\"sla\":\"\"},{\"id\":\"12\",\"name\":\"management\",\"theory\":\"\",\"practical\":\"\",\"sla\":\"\"},{\"id\":\"13\",\"name\":\"ETI\",\"theory\":\"\",\"practical\":\"\",\"sla\":\"\"},{\"id\":\"14\",\"name\":\"MAD\",\"theory\":\"\",\"practical\":\"\",\"sla\":\"\"},{\"id\":\"15\",\"name\":\"WBP\",\"theory\":\"\",\"practical\":\"\",\"sla\":\"\"},{\"id\":\"16\",\"name\":\"PHP\",\"theory\":\"\",\"practical\":\"\",\"sla\":\"\"},{\"id\"', 0, 'Fail', 0),
(38, 21, '23310220185', 2024, '0', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"20\",\"practical\":\"10\",\"sla\":\"5\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"5\",\"sla\":\"\"}]', 40, 'Fail', 20),
(39, 21, '23310220185', 2024, '0', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"55\",\"practical\":\"45\",\"sla\":\"25\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"45\",\"sla\":\"\"}]', 170, 'Pass', 85),
(40, 21, '258852', 2024, '0', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"55\",\"practical\":\"40\",\"sla\":\"29\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"49\",\"sla\":\"\"}]', 173, 'Pass', 86.5),
(41, 21, '258852', 2024, 'CSE', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"55\",\"practical\":\"29\",\"sla\":\"19\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"49\",\"sla\":\"\"}]', 152, 'Pass', 76),
(42, 21, '258852', 2024, 'CSE', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"60\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"50\",\"sla\":\"\"}]', 190, 'Pass', 95),
(43, 21, '258852', 2024, 'CSE', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"60\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"50\",\"sla\":\"\"}]', 190, 'Pass', 95),
(44, 21, '258852', 2024, 'CSE', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"60\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"50\",\"sla\":\"\"}]', 190, 'Pass', 95),
(45, 21, '258852', 2024, 'CSE', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"60\",\"practical\":\"50\",\"sla\":\"30\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"50\",\"sla\":\"\"}]', 190, 'Pass', 95),
(46, 21, '258852', 2024, 'CSE', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"55\",\"practical\":\"29\",\"sla\":\"19\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"49\",\"sla\":\"\"}]', 152, 'Pass', 76),
(47, 21, '222222', 2024, 'CSE', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"22\",\"practical\":\"22\",\"sla\":\"19\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"44\",\"sla\":\"\"}]', 107, 'Pass', 53.5),
(48, 21, '1111111', 2022, 'CSE', '5', '[{\"id\":\"5\",\"name\":\"marathi\",\"theory\":\"44\",\"practical\":\"44\",\"sla\":\"19\"},{\"id\":\"6\",\"name\":\"math\",\"theory\":\"\",\"practical\":\"49\",\"sla\":\"\"}]', 156, 'Pass', 78);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty_results`
--
ALTER TABLE `faculty_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faculty_id` (`faculty_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_results`
--
ALTER TABLE `student_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `faculty_results`
--
ALTER TABLE `faculty_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `student_results`
--
ALTER TABLE `student_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `faculty_results`
--
ALTER TABLE `faculty_results`
  ADD CONSTRAINT `faculty_results_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`);

--
-- Constraints for table `student_results`
--
ALTER TABLE `student_results`
  ADD CONSTRAINT `student_results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
