// Function to show and hide pages
function showPage(pageId) {
    const sections = document.querySelectorAll('.info-section'); // Select all sections
    sections.forEach(section => {
        if (section.id === 'basic-info') {
            // Clear dynamic content in "basic-info" section
            section.innerHTML = '';
        }
        section.style.display = 'none'; // Hide all sections
    });

    const page = document.getElementById(pageId);
    if (page) {
        page.style.display = 'block'; // Show the selected section
    }

    // Fetch data only when required
    switch (pageId) {
        case 'basic-info':
            fetchStudentInfo(); 
            break;
        case 'view-advisement':
            fetchAdvisementData();
            break;
        case 'view-all-advisors':
            fetchAllAdvisors();
            break;
        case 'view-all-advisors':
            fetchAllAdvisors(); 
            break;
        case 'faculty-schedule':
            fetchFacultyCourseSchedule();
            break;
        case 'course-roster':
            fetchCourseRoster();
            break;
        case 'attendance':
            fetchAttendance();
            break;
        case 'semester-schedule':
            fetchFacultyCourseSections(); 
            break;
        case 'faculty-console':
            console.log('Faculty console loaded.');
            break;
        case 'assign-grades':
            fetchAssignGrades();
            break;
        case 'update-faculty-info':
            fetchFacultyUpdateInfo();
            break;
        default:
            console.log(`No fetch operation for pageId: ${pageId}`);
    }
}



// Function to fetch and display student personal information dynamically
function fetchStudentInfo() {
    fetch('http://84.247.174.84/university/student/get_student_info.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('API Response:', data);

            // Dynamically create the HTML for personal information
            const personalInfoHTML = `
                <h3>Personal Information</h3>
                <table>
                    <tr>
                        <td><strong>ID</strong></td>
                        <td>${data.registrationNumber || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td><strong>Name</strong></td>
                        <td>${data.name || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td><strong>Student Email</strong></td>
                        <td>${data.email || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td><strong>Gender</strong></td>
                        <td>${data.gender || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td><strong>Date of Birth</strong></td>
                        <td>${data.dateOfBirth || 'N/A'}</td>
                    </tr>
                    ${data.address ? `
                    <tr>
                        <td><strong>Address</strong></td>
                        <td>${data.address}</td>
                    </tr>` : ''}
                    ${data.streetName ? `
                    <tr>
                        <td><strong>Street Name</strong></td>
                        <td>${data.streetName}</td>
                    </tr>` : ''}
                    ${data.city ? `
                    <tr>
                        <td><strong>City</strong></td>
                        <td>${data.city}</td>
                    </tr>` : ''}
                    ${data.zipcode ? `
                    <tr>
                        <td><strong>Zip Code</strong></td>
                        <td>${data.zipcode}</td>
                    </tr>` : ''}
                </table>`;

            // Insert the dynamically created HTML into the 'basic-info' section
            const basicInfoSection = document.getElementById('basic-info');
            if (basicInfoSection) {
                basicInfoSection.innerHTML = personalInfoHTML;
                console.log('Personal Information successfully loaded.');
            } else {
                console.warn('Section with ID "basic-info" not found.');
            }
        })
        .catch(error => {
            console.error('Error fetching student info:', error);
            alert('Unable to load student information. Please try again later.');
        });
}


// Utility function to update fields safely
function updateFieldSafely(fieldId, value) {
    const element = document.getElementById(fieldId);
    if (element) {
        element.innerText = value || 'N/A';
    } else {
        console.warn(`Element with ID "${fieldId}" not found.`);
    }
}

// Function to fetch and display advisement data
function fetchAdvisementData() {
    fetch('http://84.247.174.84/university/faculty/view_advisement.php')
        .then(response => response.json())
        .then(data => {
            // Populate "Your Advisees" table
            const adviseesTableBody = document.getElementById('advisees-table-body');
            adviseesTableBody.innerHTML = ''; // Clear existing content

            data.advisees.forEach(advisee => {
                const row = `
                    <tr>
                        <td>${advisee.firstName}</td>
                        <td>${advisee.lastName}</td>
                        <td>${advisee.email}</td>
                        <td>${advisee.dateOfAppointment}</td>
                        <td>
                            <button class="btn student-details-btn" data-student-id="${advisee.studentId || 'N/A'}">
                                Details
                            </button>
                        </td>
                    </tr>`;
                adviseesTableBody.innerHTML += row;
            });

            // Populate "All Advisees" table
            const allAdviseesTableBody = document.getElementById('all-advisees-table-body');
            allAdviseesTableBody.innerHTML = ''; // Clear existing content

            data.allAdvisees.forEach(advisee => {
                const row = `
                    <tr>
                        <td>${advisee.firstName}</td>
                        <td>${advisee.lastName}</td>
                        <td>${advisee.email}</td>
                        <td>
                            <button class="btn student-details-btn" data-student-id="${advisee.studentId || 'N/A'}">
                                Details
                            </button>
                        </td>
                    </tr>`;
                allAdviseesTableBody.innerHTML += row;
            });

            // Attach event listeners for buttons
            addDetailsButtonListeners();
            console.log('Advisement data successfully loaded.');
        })
        .catch(error => {
            console.error('Error fetching advisement data:', error);
            alert('Unable to load advisement data. Please try again later.');
        });
}


// Function to attach event listeners to details buttons
function addDetailsButtonListeners() {
    // Select all dynamically generated "Details" buttons
    document.querySelectorAll('.student-details-btn').forEach(button => {
        button.addEventListener('click', event => {
            const studentId = event.currentTarget.getAttribute('data-student-id');
            if (!studentId || studentId === 'N/A') {
                alert("Student ID is missing.");
                return;
            }

            // Redirect to the details page
            window.location.href = `http://84.247.174.84/university/faculty/view_student_details.php?studentId=${studentId}`;
        });
    });
}


// Function to fetch and display all advisors
function fetchAllAdvisors() {
    fetch('http://84.247.174.84/university/faculty/view_all_advisors.php') // Endpoint for fetching advisor data
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json(); // Parse response as JSON
        })
        .then(data => {
            console.log('API Response:', data);

            const allAdvisorsTableBody = document.getElementById('all-advisors-table-body');
            if (!allAdvisorsTableBody) {
                console.warn('Table body for all advisors not found.');
                return;
            }

            allAdvisorsTableBody.innerHTML = ''; // Clear existing rows

            // Use a Map to eliminate duplicate entries
            const uniqueAdvisors = new Map();

            data.data.forEach(advisor => {
                if (!uniqueAdvisors.has(advisor.facultyID)) {
                    uniqueAdvisors.set(advisor.facultyID, advisor);

                    const row = `
                        <tr>
                            <td>${advisor.firstName}</td>
                            <td>${advisor.lastName}</td>
                            <td>${advisor.email}</td>
                            <td>${advisor.specialty || 'N/A'}</td>
                            <td>${advisor.rank}</td>
                            <td>${advisor.facultyType}</td>
                        </tr>`;
                    allAdvisorsTableBody.innerHTML += row;
                }
            });

            console.log('All advisors loaded successfully.');
        })
        .catch(error => {
            console.error('Error fetching all advisors:', error);
            alert('Unable to load advisors. Please try again later.');
        });
}


// Function to fetch course roster
function fetchCourseRoster() {
    fetch('http://84.247.174.84/university/faculty/view_course_roster.php')
        .then(response => response.json())
        .then(data => {
            const rosterTableBody = document.getElementById('course-roster-info');
            if (!rosterTableBody) return;

            rosterTableBody.innerHTML = '';
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
        })
        .catch(error => {
            console.error('Error fetching course roster:', error);
            alert('Unable to load course roster.');
        });
}

// Function to fetch and display the faculty course schedule
function fetchFacultyCourseSchedule() {
    fetch('http://84.247.174.84/university/admin/course_catalog.php') // Replace with actual API endpoint
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const tableBody = document.querySelector('#course-schedule-table tbody');
            tableBody.innerHTML = ''; // Clear existing table data

            // Loop through each course and append a table row
            data.forEach(course => {
                const row = `
                    <tr>
                        <td>${course.CRN}</td>
                        <td>${course.CourseName}</td>
                        <td>${course.CourseID}</td>
                        <td>${course.SectionNumber}</td>
                        <td>${course.Days}</td>
                        <td>${course.TimeSlot}</td>
                        <td>${course.RoomNumber}</td>
                        <td>${course.BuildingName}</td>
                        <td>${course.Professor}</td>
                        <td>${course.AvailableSeats}</td>
                        <td>${course.SemesterName}</td>
                        <td>${course.Department}</td>
                    </tr>`;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => {
            console.error('Error fetching course schedule:', error);
            alert('Unable to load course schedule. Please try again later.');
        });
}

// Function to fetch and display faculty course sections
function fetchFacultyCourseSections() {
    fetch('http://84.247.174.84/university/faculty/view_course_sections.php') // Adjust URL as needed
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json(); // Parse the JSON response
        })
        .then(data => {
            console.log("Course Section Data:", data); // Debugging log

            // Reference the table body
            const tableBody = document.getElementById('course-section-body');

            // Clear the existing table content
            tableBody.innerHTML = '';

            // Loop through the course sections and add rows
            data.forEach(section => {
                const row = `
                    <tr>
                        <td>${section.semesterName}</td>
                        <td>${section.semesterYear}</td>
                        <td>${section.duration} days</td>
                        <td>${section.startTime}</td>
                        <td>${section.endTime}</td>
                        <td>
                            <button class="btn view-roster-btn" data-crn="${section.crnNo}">
                                View Roster
                            </button>
                            <button class="btn view-attendance-btn" data-crn="${section.crnNo}">
                                View Attendance
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row; // Append row to table
            });

            // Attach event listeners for "View Roster" and "View Attendance"
            attachViewButtons();
        })
        .catch(error => {
            console.error("Error fetching course sections:", error);
            alert("Unable to load course sections. Please try again later.");
        });
}


// Function to fetch and display Course Section Roster
function fetchRoster(crnNo) {
    fetch(`http://84.247.174.84/university/faculty/view_roster.php?crnNo=${crnNo}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                let tableHTML = `
                    <h3>Course Roster</h3>
                    <table border="1">
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Course Name</th>
                            <th>Grade</th>
                        </tr>
                `;
                data.roster.forEach(student => {
                    tableHTML += `
                        <tr>
                            <td>${student.userID}</td>
                            <td>${student.studentName}</td>
                            <td>${student.courseName}</td>
                            <td>${student.grade}</td>
                        </tr>
                    `;
                });
                tableHTML += `</table>`;
                document.getElementById('section-details').innerHTML = tableHTML;
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error("Error fetching roster:", error));
}

// Function to fetch and display Course Section Attendance
function fetchAttendance(crnNo) {
    fetch(`http://84.247.174.84/university/faculty/view_attendance.php?crnNo=${crnNo}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                let tableHTML = `
                    <h3>Course Attendance</h3>
                    <table border="1">
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Attended Classes</th>
                        </tr>
                `;
                data.attendance.forEach(student => {
                    tableHTML += `
                        <tr>
                            <td>${student.userID}</td>
                            <td>${student.studentName}</td>
                            <td>${student.attendedClasses}</td>
                        </tr>
                    `;
                });
                tableHTML += `</table>`;
                document.getElementById('section-details').innerHTML = tableHTML;
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error("Error fetching attendance:", error));
}

// Attach event listeners to View Roster and View Attendance buttons
function attachViewButtons() {
    document.querySelectorAll('.view-roster-btn').forEach(button => {
        button.addEventListener('click', event => {
            const crnNo = event.target.getAttribute('data-crn');
            console.log(`View Roster for CRN: ${crnNo}`);
            fetchRoster(crnNo); // Correct function call
        });
    });

    document.querySelectorAll('.view-attendance-btn').forEach(button => {
        button.addEventListener('click', event => {
            const crnNo = event.target.getAttribute('data-crn');
            console.log(`View Attendance for CRN: ${crnNo}`);
            fetchAttendance(crnNo); // Correct function call
        });
    });
}


// Call fetchFacultyCourseSections when DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    fetchFacultyCourseSections();
});


function fetchAssignGrades(crnNo) {
    fetch(`http://84.247.174.84/university/faculty/assign_grades.php?crnNo=${crnNo}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                let courseName = data.courseName;

                let tableHTML = `
                    <h3>Assign Grades for ${courseName}</h3>
                    <form id="grades-form">
                        <table border="1" style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Grade</th>
                            </tr>
                `;

                data.roster.forEach(student => {
                    tableHTML += `
                        <tr>
                            <td>${student.studentID}</td>
                            <td>${student.studentName}</td>
                            <td>
                                <input type="text" name="grade[${student.studentID}]" value="${student.grade || ''}" />
                            </td>
                        </tr>
                    `;
                });

                tableHTML += `
                        </table>
                        <button type="submit">Submit Grades</button>
                    </form>
                `;

                document.getElementById('section-details').innerHTML = tableHTML;

                // Add event listener for form submission
                document.getElementById('grades-form').addEventListener('submit', event => {
                    event.preventDefault();
                    submitGrades(crnNo);
                });
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error("Error fetching roster:", error));
}

function submitGrades(crnNo) {
    const formData = new FormData(document.getElementById('grades-form'));
    const grades = {};

    for (const [key, value] of formData.entries()) {
        const studentID = key.match(/\[(\d+)\]/)[1];
        grades[studentID] = value;
    }

    fetch('http://84.247.174.84/university/faculty/assign_grades.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ crnNo: crnNo, grades: grades })
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Grades updated successfully!');
            } else {
                alert(`Error: ${data.message}`);
            }
        })
        .catch(error => console.error("Error submitting grades:", error));
}

// Add event listener for "Assign Grades" button
document.querySelectorAll('.view-roster-btn').forEach(button => {
    button.addEventListener('click', () => {
        const crnNo = button.getAttribute('data-crn');
        fetchAssignGrades(crnNo);
    });
});
