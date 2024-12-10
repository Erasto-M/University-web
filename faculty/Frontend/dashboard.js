// Function to show and hide pages
function showPage(pageId) {
    const sections = document.querySelectorAll('.info-section');
    sections.forEach(section => {
        section.style.display = 'none'; // Hide all sections
    });

    const page = document.getElementById(pageId);
    if (page) {
        page.style.display = 'block'; // Show selected section
    }

    // Fetch data for the required pages
    if (pageId === 'faculty-info') {
        fetchFacultyInfo();
    } else if (pageId === 'course-roster') {
        fetchCourseRoster();
    } else if (pageId === 'attendance') {
        fetchAttendance();
    } else if (pageId === 'assign-grades') {
        fetchAssignGrades();
    } else if (pageId === 'view-gradebook') {
        fetchGradebook();
    } else if (pageId === 'manage-schedule') {
        fetchMasterSchedule();
    } else if (pageId === 'view-degree-audit') {
        fetchDegreeAudit();
    } else if (pageId === 'update-faculty-info') {
        fetchFacultyUpdateInfo();
    }
}

// Function to fetch faculty personal information
function fetchFacultyInfo() {
    fetch('http://84.247.174.84/university/student/login.php')  // Adjusting for login.php based on logic
        .then(response => response.json())
        .then(data => {
            document.getElementById('faculty-name').innerText = data.name;
            document.getElementById('faculty-email').innerText = data.email;
            document.getElementById('faculty-department').innerText = data.department;
            document.getElementById('faculty-office-hours').innerText = data.office_hours;
        })
        .catch(error => {
            alert(error.message);
            console.log('Error fetching faculty info:', error);
        });
}

// Function to fetch course roster for the faculty
function fetchCourseRoster() {
    fetch('http://84.247.174.84/university/faculty/view_course_roster.php') // Adjusted for view_course_roster.php
        .then(response => response.json())
        .then(data => {
            const rosterTableBody = document.getElementById('course-roster-info');
            rosterTableBody.innerHTML = ''; // Clear previous content

            if (data.length > 0) {
                data.forEach(course => {
                    const row = `
                        <tr>
                            <td>${course.CourseId}</td>
                            <td>${course.CourseName}</td>
                            <td>${course.SectionId}</td>
                            <td>${course.StudentCount}</td>
                        </tr>`;
                    rosterTableBody.innerHTML += row;
                });
            } else {
                // Display message if there are no courses
                rosterTableBody.innerHTML = '<tr><td colspan="4">No courses available for this faculty.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching course roster:', error);
        });
}

// Function to fetch attendance data
function fetchAttendance() {
    fetch('manage_attendance.php') // Adjusted for manage_attendance.php
        .then(response => response.json())
        .then(data => {
            const attendanceTableBody = document.getElementById('attendance-info');
            attendanceTableBody.innerHTML = ''; // Clear previous content

            if (data.length > 0) {
                data.forEach(attendance => {
                    const row = `
                        <tr>
                            <td>${attendance.CourseName}</td>
                            <td>${attendance.StudentName}</td>
                            <td>${attendance.Status}</td>
                            <td>${attendance.Date}</td>
                        </tr>`;
                    attendanceTableBody.innerHTML += row;
                });
            } else {
                // Display message if there are no attendance records
                attendanceTableBody.innerHTML = '<tr><td colspan="4">No attendance records found.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching attendance:', error);
        });
}

// Function to fetch grade assignment data
function fetchAssignGrades() {
    fetch('http://84.247.174.84/university/faculty/assign_grades.php') // Adjusted for assign_grades.php
        .then(response => response.json())
        .then(data => {
            const gradebookTableBody = document.getElementById('assign-grades-info');
            gradebookTableBody.innerHTML = ''; // Clear previous content

            if (data.length > 0) {
                data.forEach(grade => {
                    const row = `
                        <tr>
                            <td>${grade.CourseName}</td>
                            <td>${grade.StudentName}</td>
                            <td>${grade.Grade}</td>
                            <td>${grade.DateAssigned}</td>
                        </tr>`;
                    gradebookTableBody.innerHTML += row;
                });
            } else {
                // Display message if no grade assignments exist
                gradebookTableBody.innerHTML = '<tr><td colspan="4">No grade assignments found.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching grade assignments:', error);
        });
}

// Function to fetch and display the master schedule
function fetchMasterSchedule() {
    fetch('http://84.247.174.84/university/faculty/view_master_schedule.php')  // Adjusted for view_master_schedule.php
        .then(response => response.json())
        .then(data => {
            const scheduleTableBody = document.getElementById('master-schedule-info');
            scheduleTableBody.innerHTML = ''; // Clear previous content

            if (data.length > 0) {
                data.forEach(schedule => {
                    const row = `
                        <tr>
                            <td>${schedule.CourseName}</td>
                            <td>${schedule.SectionId}</td>
                            <td>${schedule.Semester}</td>
                            <td>${schedule.Day}</td>
                            <td>${schedule.Time}</td>
                        </tr>`;
                    scheduleTableBody.innerHTML += row;
                });
            } else {
                // Display message if no schedule records
                scheduleTableBody.innerHTML = '<tr><td colspan="5">No schedule available.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching master schedule:', error);
        });
}

// Function to fetch degree audit data
function fetchDegreeAudit() {
    fetch('http://84.247.174.84/university/faculty/view_degree_audit.php')  // Adjusted for view_degree_audit.php
        .then(response => response.json())
        .then(data => {
            document.getElementById('degree-audit-status').innerText = data.status;
            const missingCoursesList = document.getElementById('missing-courses-list');
            missingCoursesList.innerHTML = ''; // Clear previous content

            if (data.missing_courses.length > 0) {
                data.missing_courses.forEach(course => {
                    missingCoursesList.innerHTML += `<li>${course}</li>`;
                });
            } else {
                missingCoursesList.innerHTML = '<li>No missing courses.</li>';
            }
        })
        .catch(error => {
            console.error('Error fetching degree audit:', error);
        });
}

// Function to fetch and display faculty update information
function fetchFacultyUpdateInfo() {
    fetch('http://84.247.174.84/university/faculty/update_faculty_info.php')  // Adjusted for update_faculty_info.php
        .then(response => response.json())
        .then(data => {
            document.getElementById('faculty-update-name').value = data.name;
            document.getElementById('faculty-update-email').value = data.email;
            document.getElementById('faculty-update-department').value = data.department;
            document.getElementById('faculty-update-office-hours').value = data.office_hours;
        })
        .catch(error => {
            console.error('Error fetching faculty update information:', error);
        });
}
