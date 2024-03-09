function openForm() {
    var addTaskForm = document.getElementById("addTaskForm");
    var addFolderForm = document.getElementById("addFolderForm");

    //if task form is displaying instead, change it
    if (addTaskForm.style.display == "block") {
        addTaskForm.style.display = "none";
        addFolderForm.style.display = "block";
    } else {
        addFolderForm.style.display = "block";
    }
}

// Function to open the task form
function openTaskForm() {
    document.getElementById('taskid').value = currentFolderId;
    console.log("Current Folder ID:", currentFolderId);

    var addTaskForm = document.getElementById("addTaskForm");
    var addFolderForm = document.getElementById("addFolderForm");

    //if folder form is displaying instead, change it
    if (addFolderForm.style.display == "block") {
        addFolderForm.style.display = "none";
        addTaskForm.style.display = "block";
    } else {
        addTaskForm.style.display = "block";
    }
}

// Declare a global variable to store the current folderId
var currentFolderId;

function loadTasks(folderId) {
    // Set the currentFolderId
    currentFolderId = folderId;

    $.ajax({
        type: 'POST',
        url: 'fetch_tasks.php',
        data: { folderId: folderId},
        success: function(response) {
            var tasks = JSON.parse(response);
            var taskContainer = $('.task-container');
            var folderHeading = $('.folderHeading');

            // Clear previous content
            taskContainer.empty();

            // Display folder information in the heading
            folderHeading.html(`Your ${tasks[0].folderName} Tasks!`);

            // Iterate over tasks and append individual task containers
            for (var i = 0; i < tasks.length; i++) {
                var task = tasks[i];
                if (task.completed == 1) {
                    var taskHtml = 
                `<div class="individual-task-container container-glow">
                    <div class="info-container">
                    <div class="split-row">
                        <p id="taskName">${task.taskName} - Completed!</p>
                        <p id="taskDesc">${task.taskDescription}</p>
                        <p id="taskCompleted"> You completed this task: ${task.completed_at} </p>

                    <div class="split-column justify-center">
                        <div>
                            <form method="post">
                                <button type="submit" name="deleteTask" class="task-button">
                                <p> Would you like to </p>
                                    <input type="hidden" name="deleteid" id="deleteid" value="${task.task_id}">
                                    <img src="styles/trash.png" class="trash"/>
                                    <p>Delete?</p>
                                </button>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>`;

                } else {
                    var taskHtml = 
                `<div class="individual-task-container">
                    <div class="info-container">
                    <div class="split-row">
                        <p id="taskName">${task.taskName}</p>
                        <p id="taskDesc">${task.taskDescription}</p>
                    <div class="split-column task-bottom-container">
                        <div>
                        <form method="post">
                            <button type="submit" name="completeTask" class="task-button">
                                <input type="hidden" name="completeid" id="completeid" value="${task.task_id}">
                                <img src="styles/tick.png" class="trash"/>
                                <p>Complete!</p>
                            </button>
                        </form>
                        </div>
                        <div>
                        <form method="post">
                            <button type="submit" name="deleteTask" class="task-button">
                                <input type="hidden" name="deleteid" id="deleteid" value="${task.task_id}">
                                <img src="styles/trash.png" class="trash"/>
                                <p>Delete?</p>
                            </button>
                        </form>
                        </div>
                    </div>
                    </div>
                </div>`;
                }

                // Append the task container to the main task container
                taskContainer.append(taskHtml);
            }
        },
        error: function(error) {
            console.error('Error fetching tasks:', error);
        }
    });
}

function toggleDropdown(button) {
    var dropdown = button.querySelector('.dropdown-content');
    dropdown.classList.toggle('show');
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.optionIcon')) {
        var dropdowns = document.querySelectorAll('.dropdown-content');
        dropdowns.forEach(function(dropdown) {
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        });
    }
};
