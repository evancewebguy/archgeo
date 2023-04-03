<h1>Manage <?= $entity_name_plural ?></h1>
<p><button onclick="openCategoryModal(0)"><i class="fa fa-pencil"></i> Create New <?= $entity_name_singular ?></button></p>

<div class="modal" id="create-category-modal" style="display: none;">
    <div class="modal-heading">
        <i class="fa fa-pencil"></i> <span class="modal-title">Create New <?= $entity_name_singular ?></span>
    </div>
    <div class="modal-body">
        <p id="error-msg"></p>
        <?php
        echo form_label($entity_name_singular.' Title');
        $input_attr['id'] = 'new-category';
        $input_attr['placeholder'] = 'Enter '.strtolower($entity_name_singular).' title here';
        $input_attr['autocomplete'] = 'off';
        echo form_input('new_category', '', $input_attr);
        echo '<div class="category-builder-btns">';

        echo '<div></div>'; //delete button to go here

        echo '<div>';
        $cancel_btn_attr['class'] = 'alt';
        $cancel_btn_attr['onclick'] = 'initCancel()';
        echo form_button('cancel', 'Cancel', $cancel_btn_attr);
        $submit_btn_attr['class'] = 'modal-title';
        $submit_btn_attr['onclick'] = 'submitCategory()';
        echo form_button('submit', 'Create New '.$entity_name_singular, $submit_btn_attr);
        echo '</div>';

        echo '</div>';
        ?>
    </div>
</div>

<div id="dragzone"><?= Modules::run('categories/_draw_dragzone_content', $all_categories) ?></div>

<style>
    #dragzone {
        padding: 0;
        min-height: 100vh;
        font-size: 1.4em;
    }

    .node {
        background-color: cornsilk;
        margin: 0.5em 0;
        padding: 0.4em;
        border: 3px skyblue solid;
    }

    #error-msg {
        color: red;
        text-align: left;
    }

    .category-builder-btns {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
</style>

<script>
var maxAllowedLevels = <?= $max_allowed_levels ?>;
var token = '<?= $token ?>';
var dragzone = document.getElementById("dragzone");
var nodes = document.getElementsByClassName("node");
var selectedNode = '';
var selectedNodePos = 0;
var selectedNodeStartPos = 0;
var dropAllowed = false;
var parentNode = dragzone;
var siblings = [];
var updateId = 0;
var dragParentStart = 'dragzone';
var dragParentEnd = 'dragzone';

function rememberPositions(parentNode) {

    setTimeout(() => {

        var children = parentNode.children;
        var childNodes = [];
     
        for (var i = 0; i < children.length; i++) {
            
            var childNode = {
                id: children[i]['id'],
                priority: i+1,
                parent_category_id: parentNode.id
            }

            childNodes.push(childNode);

        }

        //send the childNodes information to the API
        var http = new XMLHttpRequest();
        http.open('POST', '../categories/remember_positions');
        http.setRequestHeader('trongatetoken', token);
        http.send(JSON.stringify(childNodes));

    }, 500);

}

function addListenersToNode(node) {

    node.addEventListener("mousedown", (ev) => {

        //update the selectedNodeStartPos
        updateStartPos(ev.target);

        dropAllowed = false;

        for (var i = nodes.length - 1; i >= 0; i--) {
            document.getElementById(nodes[i]['id']).style.backgroundColor = 'cornsilk';
        }

        document.getElementById(ev.target.id).style.backgroundColor = 'tomato';
        document.getElementById(ev.target.id).style.transition = '0s';
    });

    node.addEventListener("dragstart", (ev) => {
        ev.dataTransfer.setData('text', ev.target.id); //Firefox fix
        selectedNode = document.getElementById(ev.target.id);
        dragParentStart = selectedNode.parentNode;

        setTimeout(() => {

            try {
                parentNode.removeChild(selectedNode);
            }
            catch(err) {
                return;
            }
            
        }, 0);
    });

    node.addEventListener("dragend", (ev) => {

        dragzone.style.paddingTop = 0;
        dragParentEnd = selectedNode.parentNode;

        rememberPositions(dragParentStart);

        if (dragParentEnd !== dragParentStart) {
            rememberPositions(dragParentEnd);
        }
        
        if (dropAllowed == false) {
            cancelDrop();
            returnNodesToOriginalPos();
        }

    });

    node.addEventListener("dragover", (ev) => {
        ev.preventDefault(); //allow elements to be dropped on top
        parentNode = document.getElementById(ev.target.id);
        parentNode.style.paddingBottom = '3em';
        parentNode.style.transition = '3s';
        whereAmI(ev.clientY);
    });

    node.addEventListener("dragleave", (ev) => {
        parentNode = dragzone;
        var belowEl = document.getElementById(ev.target.id);
        belowEl.style.paddingBottom = '0.4em';
        whereAmI(ev.clientY);
    });

    node.addEventListener("dblclick", (ev) => {
        document.getElementById(ev.target.id).style.backgroundColor = 'cornsilk';
        openCategoryModal(ev.target.id);
    });

}

for (var i = 0; i < nodes.length; i++) {
    addListenersToNode(nodes[i]);
}

dragzone.addEventListener("dragover", (ev) => {
    ev.preventDefault(); //allow elements to be dropped on top
});

dragzone.addEventListener("drop", (ev) => {
    ev.preventDefault(); //prevent older versions of Firefox from redirecting

    if (parentNode.id !== 'dragzone') {
        //get the depth of the parent element
        var depth = getDepth();

        if (depth<=maxAllowedLevels) {
            dropAllowed = true;
        } else {
            alert("Not allowed!");
        }

    } else {
        dropAllowed = true;
    }

    parentNode.insertBefore(selectedNode, parentNode.children[selectedNodePos]);
    parentNode.style.paddingBottom = '0.4em';

    setTimeout(() => {
        selectedNode.style.backgroundColor = 'cornsilk';
        selectedNode.style.transition = '2s';
        returnNodesToOriginalPos();
    }, 200);

});

function getDepth() {
    var depth = 2;

    var el = document.getElementById(parentNode.id);
    //get the parent of the element (the parent of the parent)
    var parentOfParent = el.parentNode;
    var parentOfParentId  = parentOfParent.id;

    while(parentOfParentId !== 'dragzone') {
        depth++;
        parentOfParent = parentOfParent.parentNode;
        parentOfParentId = parentOfParent.id
    }

    return depth;
}

function updateStartPos(child) {

    var parent = child.parentNode;
    var children = parent.children;

    for (var i = 0; i < children.length; i++) {
        
        if (child == children[i]) {
            selectedNodeStartPos = i;
        }

    }

}

function cancelDrop() {
    dragzone.insertBefore(selectedNode, dragzone.children[selectedNodeStartPos]);

    setTimeout(() => {
        selectedNode.style.backgroundColor = 'cornsilk';
        selectedNode.style.transition = '2s';
    }, 200);

}

function returnNodesToOriginalPos() {

    for (var i = 0; i < nodes.length; i++) {
        document.getElementById(nodes[i]['id']).style.marginTop = '0.5em';
    }

}

function establishSiblingPositions() {

    siblings = parentNode.children;

    for (var i = 0; i < siblings.length; i++) {
        var element = document.getElementById(siblings[i]['id']);
        var position = element.getBoundingClientRect();
        var yTop = position.top; //the top of the element (y-axis)
        var yBtm = position.bottom; //the btm of the element (y-axis)
        //calculate where the middle of the element is (with respect to y-axis)
        siblings[i]['yPos'] = yTop + ((yBtm - yTop)/2);
    }

}

function whereAmI(currentYPos) {
    //identify the node that is directly ABOVE the selectedNode
    establishSiblingPositions();

    for (var i = 0; i < siblings.length; i++) {
        if (siblings[i]['yPos']<currentYPos) {
            //this node MUST be above the selectedNode
            var nodeAbove = document.getElementById(siblings[i]['id']);
            selectedNodePos = i+1;
        } else {
            //set the nodeBelow to the node that's immediately below the selectedNode
            if (!nodeBelow) {
                var nodeBelow = document.getElementById(siblings[i]['id']);
            }
        }
    }

    if (typeof nodeAbove == 'undefined') {
        dragzone.style.paddingTop = '1em';
        dragzone.style.transition = '1s';
        selectedNodePos = 0;
    }

    returnNodesToOriginalPos();

    if (typeof nodeBelow == 'object') {
        nodeBelow.style.marginTop = '3em';
        nodeBelow.style.transition = '3s';
    }

}

function extractCategoryTitle(elId) {
    var categoryTitle = document.getElementById(elId).innerHTML;
    var n = categoryTitle.indexOf("<div");

    if (n>-1) {
        //must contain HTML code
        categoryTitle = categoryTitle.substring(0, n);
    }

    return categoryTitle;
}

function openCategoryModal(elId) {

    if (elId == 0) {
        openTheModal('create-category-modal');
        updateId = 0;
        var mdlTitle = 'CREATE NEW <?= strtoupper($entity_name_singular) ?>';
        destroyDeleteBtn();

    } else {
        openTheModal('create-category-modal');
        updateId = elId.replace('record-id-', '');
        var mdlTitle = 'UPDATE <?= strtoupper($entity_name_singular) ?> TITLE';

        //get the category title from the clicked element
        var categoryTitle = extractCategoryTitle(elId);
        document.getElementById('new-category').value = categoryTitle;
        buildDeleteBtn();
    }

    document.getElementById('error-msg').innerHTML = '';
    var mdlTitles = document.getElementsByClassName('modal-title');
    for (var i = 0; i < mdlTitles.length; i++) {
        mdlTitles[i].innerHTML = mdlTitle;
    }

    document.getElementById('new-category').focus();
}

function openTheModal(modalId) {
    var pageOverlay = document.getElementById("overlay");

    if(typeof(pageOverlay) == 'undefined' || pageOverlay == null) {
        var modalContainer = document.createElement("div");
        modalContainer.setAttribute("id", "modal-container");
        modalContainer.setAttribute("style", "z-index: 3;");
        body.prepend(modalContainer);

        var overlay = document.createElement("div");
        overlay.setAttribute("id", "overlay");
        overlay.setAttribute("style", "z-index: 2");
        
        body.prepend(overlay);

        var targetModal = _(modalId);
        targetModalContent = targetModal.innerHTML;
        targetModal.remove();

        //create a new model
        var newModal = document.createElement("div");
        newModal.setAttribute("class", "modal");
        newModal.setAttribute("id", modalId);

        newModal.style.zIndex = 4;
        newModal.innerHTML = targetModalContent;
        modalContainer.appendChild(newModal);

        setTimeout(() => {
            newModal.style.opacity = 1;
            newModal.style.marginTop = '12vh';
        }, 0);
    }
}

function deleteCategory() {

    var http = new XMLHttpRequest();
    http.open('POST', '<?=  BASE_URL ?>api/delete/categories/' + updateId);
    http.setRequestHeader('trongatetoken', token);
    http.send();

    http.onload = function() {

        if (http.status !== 200) {
            //something went wrong
            document.getElementById("error-msg").innerHTML = http.responseText;
        } else {
            //remove the node
            var nodeToRemove = document.getElementById('record-id-' + updateId);
            var nodeToRemoveParent = nodeToRemove.parentNode;
            nodeToRemoveParent.removeChild(nodeToRemove);
            nodes = document.getElementsByClassName("node");
            rememberPositions(nodeToRemoveParent);
            closeModal();
        }        

    }    

}

function initCancel() {
    closeModal();
}

function submitCategory() {

    if (updateId == 0) {
        var targetUrl = '<?= BASE_URL ?>api/create/categories';
    } else {
        var targetUrl = '<?= BASE_URL ?>api/update/categories/' + updateId;
    }

    var newCategoryTitle = document.getElementById("new-category").value;

    //send a POST request to API
    var params = {
        category_title: newCategoryTitle
    }

    var http = new XMLHttpRequest();
    http.open('POST', targetUrl);
    http.setRequestHeader('trongatetoken', token);
    http.send(JSON.stringify(params));

    http.onload = function() {

        if (http.status !== 200) {
            //something went wrong
            document.getElementById("error-msg").innerHTML = http.responseText;
        } else {

            //make the modal disappear
            closeModal();

            //turn the response into an object
            var newCategoryObj = JSON.parse(http.responseText);
            var newCategoryTitle = newCategoryObj.category_title;
            var newCategoryId = newCategoryObj.id;

            if (updateId == 0) {
                //create a new node to represent the new category
                // create a new div element 
                var newDiv = document.createElement("div"); 
                // and give it some content 
                var newContent = document.createTextNode(newCategoryTitle); 
                // add the text node to the newly created div
                newDiv.appendChild(newContent); 

                newDiv.setAttribute("id", "record-id-" + newCategoryId);
                newDiv.setAttribute("class", "node");
                newDiv.setAttribute("draggable", "true");

                //add the new div onto the dragzone
                dragzone.appendChild(newDiv);
                addListenersToNode(newDiv);
                nodes = document.getElementsByClassName("node");
                rememberPositions(dragzone);

            } else {

                var targetEl = document.getElementById('record-id-' + updateId);
                var targetElInnerHTML = targetEl.innerHTML;
                var oldCategoryTitle = extractCategoryTitle('record-id-' + updateId);
                var newInnerHTML = targetElInnerHTML.replace(oldCategoryTitle, newCategoryTitle);
                targetEl.innerHTML = newInnerHTML;
            }

        }

    }

}

var deleteBtnExists = 0;

function buildDeleteBtn() {

    if (deleteBtnExists == 0) {
        var deleteBtn = document.createElement("button");
        deleteBtn.setAttribute("class", "danger");

        <?php
        $long_btn_text = 'DELETE '.strtoupper($entity_name_singular);
        $delete_btn_text = ((strlen($entity_name_singular)>8) ? 'DELETE' : $long_btn_text);   
        ?>

        deleteBtn.innerHTML = '<i class=\'fa fa-trash\'></i> <?= $delete_btn_text ?>';
        var deleteBtnParent = document.querySelector("#create-category-modal > div.modal-body > div > div:nth-child(1)");
        deleteBtn.setAttribute("onclick", "deleteCategory()");
        deleteBtnParent.appendChild(deleteBtn);
        deleteBtnExists = 1
    }
}

function destroyDeleteBtn() { 
    var deleteBtn = document.querySelector("#create-category-modal > div.modal-body > div > div:nth-child(1) > button");

    if (deleteBtnExists == 1) {
        deleteBtn.remove();
        deleteBtnExists = 0
    }

}
</script>

<h1><?= $delete_btn_text ?></h1>