@extends('layout')
@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row container d-flex justify-content-center">
            <div class="col-md-12">
                <div class="card px-3">
                    <div class="card-body">
                        <h4 class="card-title">Project List</h4>
                        <div class="add-items text-right"> <a href="{{ route('task') }}" class="btn btn-primary" >Task</a></div>
                        <div class="add-items d-flex"> <input type="text" class="form-control todo-list-input" placeholder="Enter project name..." id="new_name"> <button class="add btn btn-primary font-weight-bold todo-list-add-btn" onclick="saveItem()">Add</button> </div>
                        <div class="list-wrapper">
                        <ul class="d-flex flex-column-reverse todo-list" id="project_list">
                            <li>
                                <input type="text" class="form-control mr-1" >
                                <div class="d-flex mx-2">
                                    <a href="javascript:void(0)"><i class="fa fa-times"></i></a>
                                    <a href="javascript:void(0)" class="mx-2" ><i class="fa fa-paper-plane"></i></a>
                                </div>
                            </li>
                        </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection('content')
@section('footer_js')
<script>
var projectList = document.querySelector("#project_list");
var requestGetProject = new XMLHttpRequest();
requestGetProject.onreadystatechange = function(response) {
    if (requestGetProject.readyState === 4) {
        if (requestGetProject.status === 200) {
            var jsonRes = JSON.parse(requestGetProject.responseText);
            projectList.innerHTML = "";
            jsonRes.data.forEach(item => {
                projectList.innerHTML += 
                    `<li id="list_${item.id}" class="border-0">
                        <input type="text" class="form-control mr-1" id="name_${item.id}" value="${item.name}">
                        <div class="d-flex mx-2">
                            <a href="javascript:void(0)"  id="remove_${item.id}" onclick="removeItem('${item.id}')"><i class="fa fa-trash"></i></a>
                            <a href="javascript:void(0)" class="mx-2" id="send_${item.id}" onclick="saveItem('${item.id}')"><i class="fa fa-paper-plane"></i></a>
                        </div>
                    </li>`
            });
            
        }
    }
}

var requestPostProject = new XMLHttpRequest();
requestPostProject.onreadystatechange = function(response) {
    if (requestPostProject.readyState === 4) {
        if (requestPostProject.status === 201) {
            var jsonRes = JSON.parse(requestPostProject.responseText);
            console.log(jsonRes);
            document.querySelector('#new_name').value = "";
            getProject();
            alert(jsonRes.message);
        } else if(requestPostTask.status == 422) {
            var jsonRes = JSON.parse(requestPostTask.responseText);
            console.log(jsonRes)
            alert(`Error: ${jsonRes.message}`, ) 
        }
    }
}

var requestPutProject = new XMLHttpRequest();
requestPutProject.onreadystatechange = function(response) {
    if (requestPutProject.readyState === 4) {
        if (requestPutProject.status === 200) {
            var jsonRes = JSON.parse(requestPutProject.responseText);
            alert(jsonRes.data.message);
        } else if(requestPostTask.status == 422) {
            var jsonRes = JSON.parse(requestPostTask.responseText);
            console.log(jsonRes)
            alert(`Error: ${jsonRes.message}`, ) 
        }
    }
}

var requestDeleteProject = new XMLHttpRequest();
requestDeleteProject.onreadystatechange = function(response) {
    if (requestDeleteProject.readyState === 4) {
        if (requestDeleteProject.status === 200) {
            var jsonRes = JSON.parse(requestDeleteProject.responseText);
            getProject();
            alert(jsonRes.data.message);
        }
    }
}


function getProject() {
    requestGetProject.open('GET', '/api/project', true);
    requestGetProject.send();
}

function saveItem(id=0) {
    if (id == 0) {
        params = {
            "name": document.querySelector('#new_name').value
        }
        requestPostProject.open('POST', '/api/project/');
        requestPostProject.setRequestHeader('Content-type', 'application/json');
        requestPostProject.send(JSON.stringify(params));
    } else {
        params = {
            "name": document.querySelector(`#name_${id}`).value
        }
        requestPutProject.open('PUT', `/api/project/${id}`);
        requestPutProject.setRequestHeader('Content-type', 'application/json');
        requestPutProject.send(JSON.stringify(params));
    }
}

function removeItem(id) {
    if(confirm(`Confirm to delete this project?`)) {
        requestDeleteProject.open('DELETE', `/api/project/${id}/`);
        requestDeleteProject.send();
    }
}
getProject();
</script>
@endsection('footer__js')