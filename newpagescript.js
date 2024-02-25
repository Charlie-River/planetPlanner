function openForm() {
  var addFolderForm = document.getElementById("addFolderForm");
  addFolderForm.style.display = "block";
}

function openTaskForm() {
  var addTaskForm = document.getElementById("addTaskForm");
  addTaskForm.style.display = "block";
}

function loadTasks(folderId) {
    $.ajax({
        type: 'POST',
        url: 'fetch_tasks.php',
        data: { folderId: folderId },
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
