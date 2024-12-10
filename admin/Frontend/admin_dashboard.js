
// Show the Profile section and fetch profile details
function showPage(pageId) {
    document.querySelectorAll('.info-section').forEach(section => section.style.display = 'none');
    document.getElementById(pageId).style.display = 'block';

    // Fetch profile details when navigating to the Profile section
    if (pageId === 'profile-section') {
        fetchProfile();
    }
}

// Fetch and display the Profile for the logged-in user
function fetchProfile() {
    fetch('http://localhost/admin/profile.php')  // Correct endpoint for fetching profile
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);  // If there's an error message, alert it
                return;
            }
            // Populate the profile data
            document.getElementById('profileName').textContent = `${data.firstName} ${data.lastName}`;
            document.getElementById('profileGender').textContent = data.gender;
            document.getElementById('profileDOB').textContent = data.dob;
            document.getElementById('profileZipcode').textContent = data.zipcode;
            document.getElementById('profilePhoneNo').textContent = data.phoneNo;
            document.getElementById('profileEmail').textContent = data.email;
        })
        .catch(error => console.error('Error fetching profile:', error));
}

// Show the edit profile form with pre-filled data
function showEditProfileForm() {
    fetch('http://localhost/admin/profile.php')
        .then(response => response.json())
        .then(data => {
            // Prefill the edit form with existing profile data
            document.getElementById('editName').value = `${data.firstName} ${data.lastName}`;
            document.getElementById('editGender').value = data.gender;
            document.getElementById('editDOB').value = data.dob;
            document.getElementById('editZipcode').value = data.zipcode;
            document.getElementById('editPhoneNo').value = data.phoneNo;
            document.getElementById('editEmail').value = data.email;

            // Show the edit profile form and hide the view profile section
            document.getElementById('view-profile').style.display = 'none';
            document.getElementById('editProfileForm').style.display = 'block';
        })
        .catch(error => console.error('Error fetching profile for editing:', error));
}

// Handle profile update submission
document.getElementById('editProfileForm').addEventListener('submit', (e) => {
    e.preventDefault();

    // Collect form data as FormData (this is automatically in the right format for PHP)
    const formData = new FormData(e.target);

    // Log the formData to the console for debugging purposes
    console.log('Form Data:', formData);

    // Send POST request to update the profile
    fetch('http://localhost/admin/profile.php?action=update', {
        method: 'POST',
        body: formData,  // Directly sending the FormData
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(result => {
            if (result.status === 'success') {
                alert('Profile updated successfully!');
                fetchProfile(); // Refresh the profile data after updating
                document.getElementById('view-profile').style.display = 'block';
                document.getElementById('editProfileForm').style.display = 'none';
            } else {
                alert(`Error: ${result.message}`);
            }
        })
        .catch(error => {
            console.error('Error updating profile:', error);
            alert('An unexpected error occurred while updating the profile.');
        });
});


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

    console.log('Sending update data:', data); // Log the data to check

    fetch('http://localhost/admin/course_catalog.php?action=update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json', // Ensure content type is application/json
        },
        body: JSON.stringify(data), // Send data as a JSON string
    })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                alert('Course updated successfully!');
                fetchCourses(); // Reload the courses after the update
                document.getElementById('view-courses').style.display = 'block';
                document.getElementById('update-course').style.display = 'none';
            } else {
                alert(`Error: ${result.message}`);
            }
        })
        .catch(error => {
            console.error('Error updating course:', error);
            alert('An unexpected error occurred while updating the course.');
        });
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
    fetch('http://localhost/admin/master_schedule.php')
        .then(response => response.json())
        .then(data => {
            showPage('view-master-schedule');
            const tableBody = document.getElementById('masterScheduleTableBody');
            tableBody.innerHTML = ''; // Clear existing rows

            data.forEach(section => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${section.crnNo}</td>
                    <td>${section.courseName}</td>
                    <td>${section.sectionNo}</td>
                    <td>${section.firstName} ${section.lastName}</td>
                    <td>${section.timeSlot}</td>
                    <td>${section.roomNo}</td>
                    <td>${section.availableSeats}</td>
                    <td>${section.semesterName}</td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching master schedule:', error));
}

// Create Master Schedule
document.getElementById('createMasterScheduleForm').addEventListener('submit', (e) => {
    e.preventDefault();

    // Prepare JSON data
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());

    fetch('http://localhost/admin/master_schedule.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(result => {
            if (result.message) {
                alert(result.message);
            } else {
                alert('Master schedule created successfully!');
            }
        })
        .catch(error => {
            console.error('Error creating master schedule:', error);
            alert('An unexpected error occurred while creating the master schedule.');
        });
});



// Update Master Schedule
document.getElementById('updateMasterScheduleForm').addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());

    fetch('http://localhost/admin/master_schedule.php?action=update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(result => {
            if (result.message) {
                alert(result.message);
            } else {
                alert('Master schedule updated successfully!');
            }
        })
        .catch(error => {
            console.error('Error updating master schedule:', error);
            alert('An unexpected error occurred while updating the master schedule.');
        });
});

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




// Update Personal Account Password
document.getElementById('updatePasswordForm').addEventListener('submit', (e) => {
    e.preventDefault();

    // Validate password confirmation
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (newPassword !== confirmPassword) {
        alert('New password and confirmation do not match.');
        return;
    }

    // Collect data and convert to JSON format
    const data = {
        currentPassword: document.getElementById('currentPassword').value,
        newPassword: newPassword
    };

    // Send POST request to update the password
    fetch('http://localhost/admin/update_password.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'  // Ensure JSON is set as content type
        },
        body: JSON.stringify(data)  // Send JSON data in the body
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(result => {
            if (result.status === 'success') {
                alert('Password updated successfully!');
                document.getElementById('updatePasswordForm').reset();
            } else {
                alert(`Error: ${result.message}`);
            }
        })
        .catch(error => {
            console.error('Error updating password:', error);
            alert('An unexpected error occurred while updating the password.');
        });
});



// Show Search Course Catalog Form
function showSearchCourseCatalog() {
    document.getElementById('search-course-catalog').style.display = 'block';
    document.getElementById('search-department').style.display = 'none';
}

// Show Search by Department Form
function showSearchByDepartment() {
    document.getElementById('search-department').style.display = 'block';
    document.getElementById('search-course-catalog').style.display = 'none';
}

// Fetch and display course catalog data based on search parameters
function searchCourseCatalog() {
    const formData = new FormData(document.getElementById('searchForm'));
    const data = Object.fromEntries(formData.entries());

    fetch('http://localhost/admin/course_catalog.php?action=search', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            return;
        }
        displayCourseCatalog(data);
    })
    .catch(error => console.error('Error fetching course catalog:', error));
}

// Fetch and display course catalog data based on department
function searchByDepartment() {
    const formData = new FormData(document.getElementById('departmentForm'));
    const data = Object.fromEntries(formData.entries());

    fetch('http://localhost/admin/course_catalog.php?action=searchByDepartment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            return;
        }
        displayCourseCatalog(data);
    })
    .catch(error => console.error('Error fetching courses by department:', error));
}

// Display the course catalog data
function displayCourseCatalog(data) {
    const tableBody = document.getElementById('courseCatalogTableBody');
    tableBody.innerHTML = '';  // Clear existing rows

    data.forEach(course => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${course.courseName}</td>
            <td>${course.deptName}</td>
            <td>${course.numOfCredits}</td>
            <td>${course.courseLevel}</td>
            <td>${course.description}</td>
        `;
        tableBody.appendChild(row);
    });
}

// Event listener for the search form submission
document.getElementById('searchForm').addEventListener('submit', (e) => {
    e.preventDefault();
    searchCourseCatalog();  // Call function to search the course catalog
});

// Event listener for the department search form submission
document.getElementById('departmentForm').addEventListener('submit', (e) => {
    e.preventDefault();
    searchByDepartment();  // Call function to search the catalog by department
});

// Fetch and display departments (for the department search dropdown)
function fetchDepartments() {
    fetch('http://localhost/admin/get_departments.php')
    .then(response => response.json())
    .then(data => {
        const departmentSelect = document.getElementById('department');
        data.forEach(department => {
            const option = document.createElement('option');
            option.value = department.deptID;
            option.textContent = department.deptName;
            departmentSelect.appendChild(option);
        });
    })
    .catch(error => console.error('Error fetching departments:', error));
}

// Call this function to populate department dropdown when the page loads
fetchDepartments();


