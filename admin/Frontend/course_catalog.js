// Show the correct page based on the clicked button
function showPage(pageId) {
    document.querySelectorAll('.info-section').forEach(section => section.style.display = 'none');
    document.getElementById(pageId).style.display = 'block';

    // Fetch data when navigating to a specific page
    if (pageId === 'view-courses') {
        fetchCourses();
    }
}

// Fetch and display the courses
function fetchCourses() {
    fetch('http://localhost/admin/course_catalog.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('courseTableBody');
            tableBody.innerHTML = ''; // Clear any existing rows

            data.forEach(course => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${course.courseName}</td>
                    <td>${course.numOfCredits}</td>
                    <td>${course.courseLevel}</td>
                    <td>${course.description}</td>
                    <td>${course.deptName}</td>
                    <td><button onclick="editCourse(${course.courseID})">Edit</button></td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching courses:', error));
}

// Show the course data in the form for editing
function editCourse(courseID) {
    fetch(`http://localhost/admin/course_catalog.php?action=update&courseID=${courseID}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('updateCourseID').value = data.courseID;
            document.getElementById('courseName').value = data.courseName;
            document.getElementById('numOfCredits').value = data.numOfCredits;
            document.getElementById('courseLevel').value = data.courseLevel;
            document.getElementById('description').value = data.description;
            document.getElementById('view-courses').style.display = 'none';
            document.getElementById('update-course').style.display = 'block';
        })
        .catch(error => console.error('Error fetching course data for editing:', error));
}

// Handle the update of course details
document.getElementById('updateCourseForm').addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());

    fetch('http://localhost/admin/course_catalog.php?action=update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
        .then(response => response.json())
        .then(result => {
            alert(result.message);
            fetchCourses(); // Reload the courses after the update
            document.getElementById('view-courses').style.display = 'block';
            document.getElementById('update-course').style.display = 'none';
        })
        .catch(error => console.error('Error updating course:', error));
});

// Handle the creation of a new course section
document.getElementById('createCourseForm').addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());

    fetch('http://localhost/admin/course_catalog.php?action=create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
        .then(response => response.json())
        .then(result => {
            alert(result.message);
            document.getElementById('create-course').style.display = 'none';
            document.getElementById('view-courses').style.display = 'block';
        })
        .catch(error => console.error('Error creating course section:', error));
});


// Fetch and display the Master Schedule
function fetchMasterSchedule() {
    fetch('http://localhost/admin/master_schedule.php')  // Correct endpoint for fetching course sections
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('masterScheduleTableBody');
            tableBody.innerHTML = '';  // Clear existing rows

            data.forEach(section => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${section.crnNo}</td>
                    <td>${section.courseName}</td>
                    <td>${section.sectionNo}</td>
                    <td>${section.firstName} ${section.lastName}</td>
                    <td>${section.availableSeats}</td>
                    <td><button class="btn-delete" onclick="deleteCourseSection(${section.crnNo})">Delete</button></td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching master schedule:', error));
}

// Delete Course Section
function deleteCourseSection(crnNo) {
    const confirmDelete = confirm("Are you sure you want to delete this course section?");

    if (confirmDelete) {
        const data = { crnNo: crnNo };

        fetch('http://localhost/admin/delete_course_section.php?action=delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                alert(result.message);
                fetchMasterSchedule();  // Refresh the master schedule after deletion
            } else {
                alert(`Error: ${result.message}`);
            }
        })
        .catch(error => {
            console.error('Error deleting course section:', error);
            alert('An unexpected error occurred while deleting the course section.');
        });
    }
}

// Call this function when the page loads to show the list of course sections
fetchMasterSchedule();
