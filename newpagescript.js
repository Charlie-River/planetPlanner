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
        data: { folderId: folderId, currentFolderId: currentFolderId },
        success: function(response) {
            var tasks = JSON.parse(response);
            var taskContainer = $('.task-container');

            // Clear previous content
            taskContainer.empty();

            // Iterate over tasks and append individual task containers
            for (var i = 0; i < tasks.length; i++) {
                var task = tasks[i];
                var taskHtml = '<div class="individual-task-container">' +

                  '<div class="task-left-side>' +

                      '<div class="split-column">' +
                          '<p id="taskName">' + task.taskName + '</p>' +
                          '<p id="taskDesc">' + task.taskDescription + '</p>' +
                      '</div>' +

                      '<div class="task-right-side">' +
                          '<input type="checkbox" id="completed" name="taskcompleted" value="Completed">' +
                      '</div>' +

                  '</div>' +
              '</div>';

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
