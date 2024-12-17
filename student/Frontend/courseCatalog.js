document.addEventListener("DOMContentLoaded", () => {
    async function fetchSemesters() {
        try {
            const response = await fetch("http://84.247.174.84/university/admin/get_semesters.php");
            if (!response.ok) throw new Error("Failed to fetch semesters");
            const data = await response.json();
            const semesterDropdown = document.getElementById("semester");
            semesterDropdown.innerHTML = `
                <option value="">-- Select Semester --</option>
                ${data.map(sem => `<option value="${sem.semesterID}">${sem.semesterName}</option>`).join('')}
            `;
        } catch (error) {
            console.error("Error fetching semesters:", error);
        }
    }

    async function fetchCourses(searchParams) {
        try {
            const response = await fetch(`http://84.247.174.84/university/admin/course_catalog.php?${searchParams}`);
            const data = await response.json();
            const courseResults = document.getElementById("courseResults");

            if (data.length === 0) {
                courseResults.innerHTML = "<p>No courses found matching your criteria.</p>";
                return;
            }

            const tableHtml = `
                <table>
                    <thead>
                        <tr>
                            <th>CRN</th>
                            <th>Course Name</th>
                            <th>Course ID</th>
                            <th>Section Number</th>
                            <th>Days</th>
                            <th>Time Slot</th>
                            <th>Room Number</th>
                            <th>Building Name</th>
                            <th>Professor</th>
                            <th>Available Seats</th>
                            <th>Semester</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.map(course => `
                            <tr>
                                <td>${course.CRN}</td>
                                <td>${course.CourseName}</td>
                                <td>${course.CourseID}</td>
                                <td>${course.SectionNumber}</td>
                                <td>${course.Days}</td>
                                <td>${course.TimeSlot}</td>
                                <td>${course.RoomNumber}</td>
                                <td>${course.BuildingName}</td>
                                <td>${course.Professor || 'N/A'}</td>
                                <td>${course.AvailableSeats}</td>
                                <td>${course.SemesterName}</td>
                                <td><button class="add-btn" onclick="addCourse(${course.CRN})">Add</button></td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
            courseResults.innerHTML = tableHtml;
        } catch (error) {
            console.error("Error fetching courses:", error);
            document.getElementById("courseResults").innerHTML = "<p>Error loading courses. Please try again later.</p>";
        }
    }

   // Attach addCourse to the global window object
window.addCourse = async function (crn) {
    try {
        const response = await fetch("http://84.247.174.84/university/student/register_course.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ crn }),
        });

        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

        const result = await response.json();

        if (result.success) {
            alert("Course successfully registered!");
        } else if (result.errorCode) {
            switch (result.errorCode) {
                case "ALREADY_REGISTERED":
                    alert("You are already registered for this course.");
                    break;
                case "COURSE_NOT_FOUND":
                    alert("The selected course does not exist.");
                    break;
                case "NO_SEATS_AVAILABLE":
                    alert("No seats are available for this course.");
                    break;
                case "PREREQUISITES_NOT_MET":
                    alert("You do not meet the prerequisites for this course.");
                    break;
                case "STUDENT_HAS_HOLD":
                    alert("You have a hold on your account. Please resolve it before registering.");
                    break;
                default:
                    alert("Failed to register course. Please try again.");
            }
        } else {
            alert("An unknown error occurred.");
        }
    } catch (error) {
        console.error("Error registering course:", error);
        alert("An error occurred. Please try again.");
    }
};

    

    document.getElementById("filterBtn").addEventListener("click", () => {
        const formData = new FormData(document.getElementById("searchForm"));
        const searchParams = new URLSearchParams(formData).toString();
        fetchCourses(searchParams);
    });

    fetchSemesters();
});
