@extends('layout')
@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row container d-flex justify-content-center">
            <div class="col-md-12">
                <div class="card px-3">
                    <div class="card-body">
                        <h4 class="card-title">Task List</h4>
                        
                        <div class="d-flex mb-2">
                            <select id="project_list" class="form-control">
                            </select>
                            <a href="{{ route('project') }}" class="btn btn-primary" >Project</a>
                        </div>
                        <div class="text-right">
                        <button class="add btn btn-primary font-weight-bold todo-list-add-btn mb-2" onclick="openModal(0)">Create Task</button>
                        </div>
                        <div class="list-wrapper">
                        <ul class="d-flex flex-column todo-list" id="task_list">
                        </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="exampleModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Task Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="selectedId" id="selected_id" value="0">
        <div class="form-group">
            <label for="exampleInputEmail1">name</label>
            <input type="text" class="form-control" placeholder="name" id="new_name">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Priority</label>
            <select class="form-control" id="new_priority">
                <option value="">None</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">date</label>
            <input type="text" class="form-control" placeholder="date" id="new_due_date">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary"  onclick="saveItem()">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection('content')
@section('footer_js')
<script>

var projectList = document.querySelector("#project_list");
var taskList = document.querySelector("#task_list");

var selectedIdInput = document.querySelector("#selected_id");
var newPriorityInput = document.querySelector('#new_priority');
var newNameInput = document.querySelector('#new_name');
var newDueDateInput = document.querySelector('#new_due_date');

var requestGetTask = new XMLHttpRequest();
var projects = null;
requestGetTask.onreadystatechange = function(response) {
    if (requestGetTask.readyState === 4) {
        if (requestGetTask.status === 200) {
            var jsonRes = JSON.parse(requestGetTask.responseText);
            taskList.innerHTML = "";
            jsonRes.data.forEach(item => {
                console.log(item.id)
                taskList.innerHTML += 
                    `<li id="list_${item.id}" class="dynamic__feature mb-2 d-flex justify-content-between" draggable='true' ondragstart='dragStart()' ondragover='dragOver()'>
                        <input type="hidden" class="order-input" id="order_index_${item.id}" data-id="${item.id}" value='${item.order_index}'>
                        <div>
                            <p>Task Name: ${item.name}</p>
                            <p>Priority: ${item.priority?item.priority: "None"}</p>
                            <p>Due Date: ${item.due_date}</p>
                            <p>Project: <select onchange="changeProject('${item.id}')">${generateProjectOptions()}</select></p>
                        </div>
                        <div class="text-end">
                          
                        </div>
                        <div class="d-flex ms-5">
                           
                            <a href="javascript:void(0)"  id="remove_${item.id}" onclick="removeItem('${item.id}')"><i class="fa fa-trash"></i></a>
                            <a href="javascript:void(0)" class="mx-2" id="send_${item.id}" onclick="openModal('${item.id}')"><i class="fa fa-edit"></i></a>
                        </div>
                    </li>`
            });
            
        }
    }
}

var requestPostTask = new XMLHttpRequest();
requestPostTask.onreadystatechange = function(response) {
    if (requestPostTask.readyState === 4) {
        if (requestPostTask.status === 201) {
            var jsonRes = JSON.parse(requestPostTask.responseText);
            console.log(jsonRes);
            document.querySelector('#new_name').value = "";
            getTask(projectList.value);
            alert(jsonRes.message);
            $("#exampleModal").modal("hide");
        } else if(requestPostTask.status == 422) {
            var jsonRes = JSON.parse(requestPostTask.responseText);
            console.log(jsonRes)
            alert(`Error: ${jsonRes.message}`, ) 
        }
    }
}

var requestPutTask = new XMLHttpRequest();
requestPutTask.onreadystatechange = function(response) {
    if (requestPutTask.readyState === 4) {
        if (requestPutTask.status === 200) {
            var jsonRes = JSON.parse(requestPutTask.responseText);
            getTask(projectList.value);
            alert(jsonRes.data.message);
            $("#exampleModal").modal("hide");
        } else if(requestPostTask.status == 422) {
            var jsonRes = JSON.parse(requestPostTask.responseText);
            console.log(jsonRes)
            alert(`Error: ${jsonRes.message}`, ) 
        }
    }
}

var requestDeleteTask = new XMLHttpRequest();
requestDeleteTask.onreadystatechange = function(response) {
    if (requestDeleteTask.readyState === 4) {
        if (requestDeleteTask.status === 200) {
            var jsonRes = JSON.parse(requestDeleteTask.responseText);
            getTask(projectList.value);
            alert(jsonRes.data.message);
        }
    }
}


function getTask(projectId) {
    requestGetTask.open('GET', `/api/task/${projectId}`, true);
    requestGetTask.send();
}

function generateProjectOptions() {
    return projects.map((project) => `<option value='${project.id}' ${projectList.value==project.id?'selected':''}>${project.name}</option>`);
}

function saveItem() {
    var projectId = projectList.value;
    var id = selectedIdInput.value;
    params = {
        "name": document.querySelector('#new_name').value,
        'project_id': projectId,
        'due_date': newDueDateInput.value,
        'priority': newPriorityInput.value,
    };
    if (id == 0) {
        requestPostTask.open('POST', '/api/task/');
        requestPostTask.setRequestHeader('Content-type', 'application/json');
        requestPostTask.send(JSON.stringify(params));
    } else {
        requestPutTask.open('PUT', `/api/task/${id}`);
        requestPutTask.setRequestHeader('Content-type', 'application/json');
        requestPutTask.send(JSON.stringify(params));
    }
}

function removeItem(id) {
    if(confirm(`Confirm to delete this task?`)) {
        requestDeleteTask.open('DELETE', `/api/task/${id}/`);
        requestDeleteTask.send();
    }
}

projectList.addEventListener("change", function () {
    getTask(projectList.value);
});

var requestGetProject = new XMLHttpRequest();
requestGetProject.onreadystatechange = function(response) {
    if (requestGetProject.readyState === 4) {
        if (requestGetProject.status === 200) {
            var jsonRes = JSON.parse(requestGetProject.responseText);
            projects = jsonRes.data;
            projectList.innerHTML = "";
            jsonRes.data.forEach(item => {
                projectList.innerHTML += `<option value="${item.id}">${item.name}</option>`
            });
            getTask(projectList.value);
        }
    }
}
requestGetProject.open('GET', '/api/project', true);
requestGetProject.send();


var row;
function dragStart() {
    row = event.target;
}

function dragOver() {
    event.preventDefault();
    let hoverRow = event.target;
    while (hoverRow.getAttribute('draggable') == null) {
        hoverRow = hoverRow.parentNode;
    }
    let parentHoverRow = hoverRow.parentNode;
    let childrens = Array.from(parentHoverRow.children);
    if(childrens.indexOf(hoverRow)>childrens.indexOf(row))
        hoverRow.after(row);
    else
        hoverRow.before(row);
}

jQuery('#new_due_date').datetimepicker({
    format:'Y-m-d H:i:s',
});

$("#task_list").delegate( ".dynamic__feature", "dragend", function() {
    var numberIndex = 0;
    var orderInputs = document.querySelectorAll(".order-input");
    var formData = new FormData();
    var param = ""
    Array.from(orderInputs).map(orderInput => {
        orderInput.value = numberIndex;
        param += `&id[]=${orderInput.getAttribute("data-id")}&order_index[]=${numberIndex}`
        numberIndex++;
    });
    var requestSort = new XMLHttpRequest();
    requestSort.open("POST", `/api/task/sort?${param}`, true);
    requestSort.send(requestSort);
});

var requestGetTaskInfo = new XMLHttpRequest();
requestGetTaskInfo.onreadystatechange = function(response) {
    if (requestGetTaskInfo.readyState === 4) {
        if (requestGetTaskInfo.status === 200) {
            var jsonRes = JSON.parse(requestGetTaskInfo.responseText);
            console.log(jsonRes)
            newPriorityInput.value = jsonRes.data.priority;
            newNameInput.value = jsonRes.data.name;
            newDueDateInput.value = jsonRes.data.due_date;
            $("#exampleModal").modal("show");
        }
    }
}

function openModal(id) {
    selectedIdInput.value = id;
    if (id != 0) {
        requestGetTaskInfo.open('GET', `/api/task/edit/${id}`, true);
        requestGetTaskInfo.send();
    } else {
        $("#exampleModal").modal("show");
    }
}

function changeProject(id) {
    params = {
        'project_id': event.target.value
    };
    if (confirm("Are you sure to change project?")) {
        requestPutTask.open('PUT', `/api/task/${id}`);
        requestPutTask.setRequestHeader('Content-type', 'application/json');
        requestPutTask.send(JSON.stringify(params));
    } else {
        event.target.value = projectList.value
    }
}

</script>
@endsection('footer__js')