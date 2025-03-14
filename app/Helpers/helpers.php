<?php
function showGroupsRecursive($groups)
{
    foreach ($groups as $group) {
        echo '<p>' . $group->name . '</p>';
        if ($group->descendants->isNotEmpty()) {
            showGroupsRecursive($group->descendants);
        }
    }
}
