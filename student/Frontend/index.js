document.addEventListener("DOMContentLoaded", () => {
    const btnMasterSchedule = document.getElementById("btnMasterSchedule");
    const btnCourseCatalog = document.getElementById("btnCourseCatalog");
    const contentArea = document.getElementById("contentArea");

    // Display Master Schedule
    btnMasterSchedule.addEventListener("click", async () => {
        try {
            const response = await fetch("http://84.247.174.84/university/admin/master_schedule.php");
            const data = await response.json();

            const tableHtml = `
                <table>
                    <thead>
                        <tr>
                            <th>CRN</th>
                            <th>Course Name</th>
                            <th>Section Number</th>
                            <th>Days</th>
                            <th>Time Slot</th>
                            <th>Room Number</th>
                            <th>Building Name</th>
                            <th>Professor</th>
                            <th>Available Seats</th>
                            <th>Semester</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.map(course => `
                            <tr>
                                <td>${course.crnNo}</td>
                                <td>${course.courseName}</td>
                                <td>${course.sectionNo}</td>
                                <td>${course.days}</td>
                                <td>${course.startTime} - ${course.endTime}</td>
                                <td>${course.roomNo}</td>
                                <td>${course.buildingName}</td>
                                <td>${course.firstName} ${course.lastName}</td>
                                <td>${course.availableSeats}</td>
                                <td>${course.SemesterName}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;

            contentArea.innerHTML = tableHtml;
        } catch (error) {
            console.error("Error fetching master schedule:", error);
            contentArea.innerHTML = "<p>Error loading master schedule. Please try again later.</p>";
        }
    });

    // Display Course Catalog with Search
    btnCourseCatalog.addEventListener("click", () => {
        contentArea.innerHTML = `
            <div class="course-catalog-container">
                <div class="search-form">
                    <form id="searchForm">
                        <h2>Search Courses</h2>
                        <label for="semester">Semester:</label>
                        <select id="semester" name="semester">
                            <option value="">-- Loading Semesters --</option>
                        </select>
                                        <label for="courseID">Course ID:</label>
                        <input type="text" id="courseID" name="courseID">

                        <label for="crn">CRN:</label>
                        <input type="text" id="crn" name="crn">

                        <label for="department">Department:</label>
                        <select id="department" name="department">
                            <option value="">Select Department</option>
                        </select>

                        <label for="professor">Professor:</label>
                        <select id="professor" name="professor">
                            <option value="">Select Professor</option>
                        </select>

                        <label>Day:</label>
                        <div>
                            <label><input type="checkbox" name="day[]" value="Monday"> Monday</label>
                            <label><input type="checkbox" name="day[]" value="Tuesday"> Tuesday</label>
                            <label><input type="checkbox" name="day[]" value="Wednesday"> Wednesday</label>
                            <label><input type="checkbox" name="day[]" value="Thursday"> Thursday</label>
                            <label><input type="checkbox" name="day[]" value="Friday"> Friday</label>
                            <label><input type="checkbox" name="day[]" value="Saturday"> Friday</label>
                            <label><input type="checkbox" name="day[]" value="Sunday"> Friday</label>
                        </div>

                        <label for="time">Time:</label>
                        <select id="time" name="time">
                            <option value="">Select Time</option>
                        </select>

                        <button type="button" id="filterBtn">Search</button>
                    </form>
                </div>
                <div id="courseResults" class="course-results">
                    <p>Search results will be displayed here.</p>
                </div>
            </div>
        `;

        const filterBtn = document.getElementById("filterBtn");
        const courseResults = document.getElementById("courseResults");

        filterBtn.addEventListener("click", async () => {
            const formData = new FormData(document.getElementById("searchForm"));
            const searchParams = new URLSearchParams(formData).toString();
        
            try {
                const response = await fetch(`http://84.247.174.84/university/admin/course_catalog.php?${searchParams}`);
                const data = await response.json();
        
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
                                    <td>${course.SemesterName}</td>
                                    <td>${course.TimeSlot}</td>
                                    <td>${course.RoomNumber}</td>
                                    <td>${course.BuildingName}</td>
                                    <td>${course.Professor || "N/A"}</td>
                                    <td>${course.AvailableSeats}</td>
                                    
                                </tr>
                            `).join("")}
                        </tbody>
                    </table>
                `;
        
                courseResults.innerHTML = tableHtml;
            } catch (error) {
                console.error("Error fetching courses:", error);
                courseResults.innerHTML = "<p>Error loading courses. Please try again later.</p>";
            }
        });
        
    });
});


document.addEventListener("DOMContentLoaded", async () => {
    const btnCourseCatalog = document.getElementById("btnCourseCatalog");
    const contentArea = document.getElementById("contentArea");

    // Fetch semesters for the dropdown
async function fetchSemesters() {
    try {
        const response = await fetch("http://84.247.174.84/university/admin/get_semesters.php");

        // Check if response is OK
        if (!response.ok) {
            throw new Error("Network response was not OK");
        }

        const data = await response.json();

        // Populate the semester dropdown
        const semesterDropdown = document.getElementById("semester");
        semesterDropdown.innerHTML = `
            <option value="">-- Select Semester --</option>
            ${data.map(sem => `<option value="${sem.semesterID}">${sem.semesterName}</option>`).join("")}
        `;
    } catch (error) {
        console.error("Error fetching semesters:", error);
    }
}


    // Event listener for Course Catalog
    btnCourseCatalog.addEventListener("click", () => {
        contentArea.innerHTML = `
            <div class="course-catalog-container">
                <div class="search-form">
                    <form id="searchForm">
                        <h2>Search Courses</h2>
                        <label for="semester">Semester:</label>
                        <select id="semester" name="semester">
                            <option value="">-- Loading Semesters --</option>
                        </select>

                        <label for="courseID">Course ID:</label>
                        <input type="text" id="courseID" name="courseID">

                        <label for="crn">CRN:</label>
                        <input type="text" id="crn" name="crn">

                        <label for="department">Department:</label>
                        <input type="text" id="department" name="department">

                        <label for="professor">Professor:</label>
                        <input type="text" id="professor" name="professor">

                        <label for="days">Days:</label>
                        <select id="days" name="days">
                            <option value="">-- Select Day --</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                        </select>

                        <button type="button" id="filterBtn">Search</button>
                    </form>
                </div>
                <div id="courseResults" class="course-results">
                    <p>Search results will be displayed here.</p>
                </div>
            </div>
        `;

        // Fetch and populate semester dropdown
        fetchSemesters();

        // Add filter functionality
        const filterBtn = document.getElementById("filterBtn");
        const courseResults = document.getElementById("courseResults");

        filterBtn.addEventListener("click", async () => {
            const formData = new FormData(document.getElementById("searchForm"));
            const searchParams = new URLSearchParams(formData).toString();

            try {
                const response = await fetch(`http://84.247.174.84/university/admin/course_catalog.php?${searchParams}`);
                const data = await response.json();

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
                                <th>Department</th>
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
                                    <td>${course.Department}</td>
                                </tr>
                            `).join("")}
                        </tbody>
                    </table>
                `;

                courseResults.innerHTML = tableHtml;
            } catch (error) {
                console.error("Error fetching courses:", error);
                courseResults.innerHTML = "<p>Error loading courses. Please try again later.</p>";
            }
        });
    });
});



