<?php 
function draw_child_nodes($parent_category_id, $all_categories) {

    $child_nodes = [];
    foreach ($all_categories as $category) {
        $node_data['id'] = $category->id;
        $node_data['category_title'] = $category->category_title;
        $priority = $category->priority;
        $node_parent_id = $category->parent_category_id;

        if ($node_parent_id == $parent_category_id) {
            //this node MUST be a child of the parent_category_id
            $child_nodes[$priority] = $node_data;
        }
    }

    ksort($child_nodes); //order by priority asc

    //now that we've gather our child nodes, let's display them
    foreach ($child_nodes as $child_node) {
        echo '<div id="record-id-'.$child_node['id'].'" class="node" draggable="true">';
        echo $child_node['category_title'];
        draw_child_nodes($child_node['id'], $all_categories); //draw child nodes for THIS node
        echo '</div>';
    }

}

draw_child_nodes(0, $all_categories);