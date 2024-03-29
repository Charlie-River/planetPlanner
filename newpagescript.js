
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
            if (tasks.length > 0) {
                folderHeading.html(`Your ${tasks[0].folderName} Tasks!`);
            } else {
                // Handle the case when no tasks are retrieved
                folderHeading.html('Add some tasks!');
            }

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
                openFolder(folderId);
                loadLastOpenedFolderTasks();
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

function openEditForm(folderId) {
    console.log("Folder ID:", folderId);
    console.log("Edit Form CLICKED");

    var newName = document.getElementById("editFolder").value; // Get the new folder name

    // Send folderId and new folder name to PHP using AJAX
    $.ajax({
        type: 'POST',
        url: 'newpage.php',
        data: { folderId: folderId, editFolder: newName },
        success: function(response) {
            console.log('Folder ID and new name sent to PHP successfully');
            // Optionally handle response from PHP if needed
        },
        error: function(error) {
            console.error('Error sending folder ID and new name to PHP:', error);
        }
    });

    var addTaskForm = document.getElementById("addTaskForm");
    var addFolderForm = document.getElementById("addFolderForm");
    var editFolderForm = document.getElementById("editFolderForm");

    // Display the edit folder form
    if (addFolderForm.style.display == "block") {
        addFolderForm.style.display = "none";
        editFolderForm.style.display = "block";
    } else if (addTaskForm.style.display == "block") {
        addTaskForm.style.display = "none";
        editFolderForm.style.display = "block";
    } else {
        editFolderForm.style.display = "block";
    }
    
}

// Function to open a folder and store its ID in local storage
function openFolder(folderId) {
    localStorage.setItem('lastOpenedFolderId', folderId);
    //loadTasks(folderId); // Load tasks of the opened folder
}

// Function to load tasks of the last opened folder
function loadLastOpenedFolderTasks() {
    var lastOpenedFolderId = localStorage.getItem('lastOpenedFolderId');
    if (lastOpenedFolderId) {
        //loadTasks(lastOpenedFolderId);
        console.log("folder stored:", lastOpenedFolderId)
    } else {
        console.log("nO FOLDER STORED")
    }
}