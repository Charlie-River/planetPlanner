function openForm() {
  var addFolderForm = document.getElementById("addFolderForm");
  addFolderForm.style.display = "block";
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
                var taskHtml = 
                `<div class="individual-task-container">
                    <div class="info-container">
                    <div class="split-row">
                        <p id="taskName">${task.taskName}</p>
                        <p id="taskDesc">${task.taskDescription}</p>
                    <div class="split-column task-bottom-container">
                        <div>
                        <form method="post">
                            <button class="task-button">
                                <input type="checkbox" id="completed" name="taskcompleted" value="${task.task_id}">
                                <label for="completed" class="label">Complete!</label>
                            </button>
                        </form>
                        </div>
                        <div>
                        <form method="post">
                            <button type="submit" name="deleteTask" class="task-button">
                                <input type="hidden" name="deleteid" id="deleteid" value="${task.task_id}">
                                <img src="styles/trash.png" class="trash"/>
                                <label for="delete" class="label">Delete?</label>
                            </button>
                        </form>
                        </div>
                    </div>
                    </div>
                </div>`;

                // Append the task container to the main task container
                taskContainer.append(taskHtml);
            }
        },
        error: function(error) {
            console.error('Error fetching tasks:', error);
        }
    });
}

// Function to open the task form
function openTaskForm() {
    console.log("Current Folder ID:", currentFolderId);
    document.getElementById('taskid').value = currentFolderId;

    // Add your code to open the task form here
    var addTaskForm = document.getElementById("addTaskForm");
    addTaskForm.style.display = "block";
}
