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
    if (pageId === 'basic-info') {
        fetchStudentInfo();
    } else if (pageId === 'holds') {
        fetchHolds();
    } else if (pageId === 'schedule') {
        fetchSchedule();
    } else if (pageId === 'grades') {
        fetchGrades();
    } else if (pageId === 'advisor-info') {
        fetchAdvisorInfo();
    } else if (pageId === 'degree-audit') {
        fetchDegreeAudit(); 
    } else if (pageId === 'unofficial-transcript') {
        fetchTranscript(); 
    } else if (pageId === 'view-registered-courses') {
        fetchRegisteredCourses();
    } else if (pageId === 'majors-minors') {
        fetchMajorsAndMinors();
    } else if (pageId === 'major-minor-requirements') {
        fetchProgramRequirements();
    }
}

function fetchAdvisorInfo() {
    fetch('http://localhost/student/get_student_advisor.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to load advisor information.');
            }
            return response.json();
        })
        .then(advisorData => {
            if (advisorData.error) {
                alert(advisorData.error);
            } else {
                document.getElementById('advisor-name').textContent = `${advisorData.firstName} ${advisorData.lastName}`;
                document.getElementById('advisor-email').textContent = advisorData.email;
                document.getElementById('advisor-appointment-date').textContent = advisorData.dateOfAppointment;
            }
        })
        .catch(error => {
            console.error('Error fetching advisor info:', error);
        });
}


// Function to hide the advisor information section
function hideAdvisorInfo() {
    document.getElementById('advisor-info').style.display = 'none';
}

// Fetch student details
function fetchStudentInfo() {
    fetch('http://localhost/student/get_student_info.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('reg-number').innerText = data.registrationNumber;
            document.getElementById('name').innerText = data.name;
            document.getElementById('email').innerText = data.email;
            document.getElementById('id-number').innerText = data.nationalIdNumber;
            document.getElementById('gender').innerText = data.gender;
            document.getElementById('dob').innerText = data.dateOfBirth;
        })
        .catch(error => console.log('Error fetching student info:', error));
}

// Fetch holds data and display in table format
function fetchHolds() {
    fetch('http://localhost/student/get_holds.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const holdsTableBody = document.getElementById('holds-info');
            holdsTableBody.innerHTML = ''; // Clear any previous content

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(hold => {
                    const row = 
                        `<tr>
                            <td>${hold.holdType}</td>
                            <td>${hold.description}</td>
                        </tr>`;
                    holdsTableBody.innerHTML += row;
                });
            } else {
                // Display message if there are no holds
                holdsTableBody.innerHTML = '<tr><td colspan="2">No holds on your account.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching holds:', error);
            alert('Failed to load holds data. Please try again later.');
        });
}


function fetchSchedule() {
    fetch('http://localhost/student/get_student_schedule.php')
        .then(response => response.json())
        .then(data => {
            const scheduleTbody = document.getElementById('schedule-info');
            scheduleTbody.innerHTML = '';  // Clear previous content

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(course => {
                    const row =` 
                        <tr>
                            <td>${course.courseName}</td>
                            <td>${course.crnNo}</td>
                            <td>${course.semesterName}</td>
                            <td>${course.facultyFirstName} ${course.facultyLastName}</td>
                        </tr>`;
                    scheduleTbody.innerHTML += row;
                });
            } else {
                scheduleTbody.innerHTML = '<tr><td colspan="4">No courses available for the selected schedule.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching schedule:', error);
            alert('Failed to load the schedule. Please try again later.');
        });
}





function fetchGrades() {
    fetch('http://localhost/student/get_student_grades.php')
        .then(response => response.json())
        .then(data => {
            const gradesTbody = document.getElementById('grades-info'); // Ensure the table body has the correct ID
            gradesTbody.innerHTML = ''; // Clear any previous content

            if (Array.isArray(data) && data.length > 0) {
                // Loop through the grades data and populate the table
                data.forEach(gradeInfo => {
                    const row = document.createElement('tr');
                    row.innerHTML =` 
                        <td>${gradeInfo.courseName}</td>
                        <td>${gradeInfo.grade}</td>`
                    ;
                    gradesTbody.appendChild(row); // Append each row to the table body
                });
            } else {
                // If no grades are found, display a message
                gradesTbody.innerHTML = '<tr><td colspan="2">No grades available.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching grades:', error);
            alert('Failed to load grades. Please try again later.');
        });
}


// Function to fetch degree audit info from the backend
function fetchDegreeAudit() {
    fetch('http://localhost/student/get_degree_audit.php')
        .then(response => response.json())
        .then(degreeAudit => {
            const statusElement = document.getElementById('audit-status');
            const missingCoursesList = document.getElementById('missing-courses-list');

            // Clear previous content
            missingCoursesList.innerHTML = '';

            // Initialize variables to determine status and missing courses
            let hasMissingCourses = false;

            degreeAudit.forEach(course => {
                if (course.status === 'Not Completed') {
                    hasMissingCourses = true;

                    // Add course to the missing courses list
                    const li = document.createElement('li');
                    li.textContent = `${course.courseName} (Minimum Requirement is: ${course.minimumGradeRequired})`;
                    missingCoursesList.appendChild(li);
                }
            });

            // Update the status based on the presence of missing courses
            if (hasMissingCourses) {
                statusElement.textContent = 'Failed';
            } else {
                statusElement.textContent = 'Completed';
            }
        })
        .catch(error => {
            console.error('Error fetching degree audit:', error);
            alert('Failed to load degree audit data.');
        });
}

// Function to fetch unofficial transcript info from the backend
function fetchTranscript() {
    fetch('http://localhost/student/get_transcript.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to load transcript data.');
            }
            return response.json();
        })
        .then(transcriptData => {
            const transcriptTable = document.getElementById('transcript-info');
            transcriptTable.innerHTML = ''; // Clear any previous content

            if (Array.isArray(transcriptData) && transcriptData.length > 0) {
                // Populate table rows with transcript data
                transcriptData.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML =` 
                        <td>${item.courseName}</td>
                        <td>${item.grade}</td>
                        <td>${item.semesterName}</td>`
                    ;
                    transcriptTable.appendChild(row);
                });
            } else {
                // Display a message if no transcript data is found
                transcriptTable.innerHTML = '<tr><td colspan="3">No transcript data available.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching transcript:', error);
            alert('Failed to load transcript data.');
        });
}


    function fetchRegisteredCourses() {
        fetch('http://localhost/student/get_registered_courses.php')
            .then(response => response.json())
            .then(data => {
                const registeredCoursesTbody = document.getElementById('registered-courses-info');
                registeredCoursesTbody.innerHTML = ''; // Clear any previous content
    
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(course => {
                        const row = 
                            `<tr>
                                <td>${course.courseID}</td>
                                <td>${course.courseName}</td>
                                <td>${course.availableSeats}</td>
                            </tr>;
                        registeredCoursesTbody.innerHTML += row;
                    `});
                } else {
                    // Display message if there are no registered courses
                    registeredCoursesTbody.innerHTML = '<tr><td colspan="3">No registered courses found.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error fetching registered courses:', error);
                alert('Failed to load registered courses. Please try again later.');
            });
    }
    

    function fetchMajorsAndMinors() {
        fetch("http://localhost/student/get_available_majors_minors.php")
            .then(response => response.json())
            .then(data => {
                const majorsList = document.getElementById("majors-list");
                const minorsList = document.getElementById("minors-list");
                majorsList.innerHTML = ""; // Clear previous content
                minorsList.innerHTML = ""; // Clear previous content
    
                // Populate majors
                if (data.majors && data.majors.length > 0) {
                    data.majors.forEach(major => {
                        const dropdown = createDropdown(major.majorName, major.numOfCreditsRequired, major.majorID, "majors");
                        majorsList.appendChild(dropdown);
                    });
                } else {
                    majorsList.innerHTML = "<li>No majors available at the moment.</li>";
                }
    
                // Populate minors
                if (data.minors && data.minors.length > 0) {
                    data.minors.forEach(minor => {
                        const dropdown = createDropdown(minor.minorName, minor.numOfCreditsRequired, minor.minorID, "minors");
                        minorsList.appendChild(dropdown);
                    });
                } else {
                    minorsList.innerHTML = "<li>No minors available at the moment.</li>";
                }
            })
            .catch(error => {
                console.error("Error fetching majors and minors:", error);
            });
    }
    
    /* Helper function to create dropdown */
    function createDropdown(name, credits, id, section) {
        // Create container
        const container = document.createElement("div");
    
        // Create dropdown button
        const button = document.createElement("button");
        button.classList.add("btn-dropdown");
        button.innerHTML = `
            ${name} (Credits Required: ${credits})
            <span class="arrow">&#9660;</span>`
        ;
    
        // Create dropdown content
        const dropdownContent = document.createElement("div");
        dropdownContent.classList.add("dropdown-content");
    
        // Add event listener to toggle dropdown
        button.addEventListener("click", () => {
            const isActive = dropdownContent.style.display === "block";
            document.querySelectorAll(".dropdown-content").forEach(content => (content.style.display = "none")); // Hide other dropdowns
            dropdownContent.style.display = isActive ? "none" : "block"; // Toggle current dropdown
            button.classList.toggle("active", !isActive); // Toggle arrow rotation
        });
    
        // Fetch program requirements
        fetchProgramRequirementsForDropdown(id, dropdownContent);
    
        // Append button and content to container
        container.appendChild(button);
        container.appendChild(dropdownContent);
    
        return container;
    }
    
    
    
    
    // Fetch and display the requirements for a specific major or minor
    function fetchProgramRequirements(courseID) {
        fetch(`http://localhost/student/get_course_requirements.php?courseID=${courseID}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    console.error('Backend error:', data.error);
                    alert(data.error);
                } else {
                    const requirementsList = document.getElementById("requirements-list");
                    requirementsList.innerHTML = ""; // Clear previous content
    
                    if (data.length > 0) {
                        data.forEach(prerequisite => {
                            const listItem = document.createElement("li");
                            listItem.textContent = `Prerequisite: ${prerequisite.prerequisiteDescription}, Minimum Grade: ${prerequisite.minimumGrade};
                            requirementsList.appendChild(listItem)`;
                        });
                    } else {
                        requirementsList.innerHTML = "<li>No prerequisites found for this course.</li>";
                    }
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('An error occurred while fetching program requirements.');
            });
    }
    

    function fetchCourses() {
        fetch("http://localhost/student/get_available_courses.php")
            .then(response => response.json())
            .then(data => {
                const coursesList = document.getElementById("courses-list");
                coursesList.innerHTML = ""; // Clear previous content
    
                if (data.length > 0) {
                    data.forEach(course => {
                        const listItem = document.createElement("li");
                        listItem.innerHTML = 
                            `${course.courseName} (Course ID: ${course.courseID})
                            <button class="btn" onclick="fetchCoursePrerequisites(${course.courseID})">View</button>
                        `;
                        coursesList.appendChild(listItem);
                    });
                } else {
                    coursesList.innerHTML = "<li>No courses available at the moment.</li>";
                }
            })
            .catch(error => {
                console.error("Error fetching courses:", error);
            });
    }
    
    function fetchCoursePrerequisites(courseID) {
        fetch(`http://localhost/student/get_course_prerequisites.php?courseID=${courseID}`)
            .then(response => response.json())
            .then(data => {
                const requirementsList = document.getElementById("requirements-list");
                requirementsList.innerHTML = ""; // Clear previous content
    
                if (data.length > 0) {
                    data.forEach(prerequisite => {
                        const listItem = document.createElement("li");
                        listItem.textContent = `Prerequisite: ${prerequisite.prerequisiteCourseID}, Minimum Grade: ${prerequisite.minimumGrade};
                        requirementsList.appendChild(listItem)`;
                    });
                } else {
                    requirementsList.innerHTML = "No prerequisites found for this course.";
                }
            })
            .catch(error => {
                console.error("Error fetching course prerequisites:", error);
            });
    }

    function fetchProgramRequirementsForDropdown(courseID, dropdownContent) {
        fetch(`http://localhost/student/get_course_requirements.php?courseID=${courseID}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                dropdownContent.innerHTML = ""; // Clear previous content
    
                if (data.length > 0) {
                    const requirementsList = document.createElement("ul");
                    data.forEach(prerequisite => {
                        const listItem = document.createElement("li");
                        listItem.textContent = `Prerequisite: ${prerequisite.prerequisiteDescription}, Minimum Grade: ${prerequisite.minimumGrade};
                        requirementsList.appendChild(listItem)`;
                    });
                    dropdownContent.appendChild(requirementsList);
                } else {
                    dropdownContent.innerHTML = "<p>No prerequisites found for this course.</p>";
                }
            })
            .catch(error => {
                console.error("Fetch error:", error);
                alert("An error occurred while fetching program requirements.");
            });
    }