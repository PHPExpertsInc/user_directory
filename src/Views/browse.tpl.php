<?php
/**
* User Directory
*   Copyright (c) 2008, 2019 Theodore R. Smith <theodore@phpexperts.pro>.
*
* The following code is licensed under a modified BSD License.
* All of the terms and conditions of the BSD License apply with one
* exception:
*
* 1. Every one who has not been a registered student of the "PHPExperts
*    From Beginner To Pro" course (http://www.phpexperts.pro/) is forbidden
*    from modifing this code or using in an another project, either as a
*    deritvative work or stand-alone.
*
* BSD License: http://www.opensource.org/licenses/bsd-license.php
**/
$page              = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 0;
$items_per_page    = isset($_GET['page']) && $_GET['page'] == 'all' ? 10000000 : 10;
$searchQueryString = isset($searchQueryString) ? '&amp;' . $searchQueryString : '';
$users_count       = count($users);

$nav_div = '';
$start   = $page * $items_per_page;

if ($items_per_page < $users_count) {
    $nav_div = '<div class="page_nav">';
    if ($page != 0) {
        $nav_div .= '<a href="?view=' . $action . '&amp;page=' . ($page - 1) . $searchQueryString . '">prev</a> - ';
    } else {
        $nav_div .= 'prev - ';
    }

    if ($start + $items_per_page < $users_count) {
        $nav_div .= '<a href="?view=' . $action . '&amp;page=' . ($page + 1) . $searchQueryString . '">next</a>';
    } else {
        $nav_div .= 'next';
    }
    $nav_div .= ' - <a href="?view=' . $action . $searchQueryString . '&amp;page=all">show all</a></div>';
}

$nav_div .= "\n";
?>
        <script type="text/javascript" src="js/sorttable.js"></script>

        <div id="browse">
            <?php echo $nav_div; ?>
            <table class="sortable">
                <tr>
                    <th>Username</th>
                    <th>First</th>
                    <th>Last</th>
                    <th>Email</th>
                </tr>
<?php
    $count = 0;
    for ($a = $start; ($a < ($start + $items_per_page) && $a < $users_count); ++$a) {
        ++$count;
        $user = $users[$a]; ?>
                <tr>
                    <td><?php echo $user->username; ?></td>
                    <td><?php echo $user->firstName; ?></td>
                    <td><?php echo $user->lastName; ?></td>
                    <td><?php echo $user->email; ?></td>
                </tr>
<?php
    }
?>
            </table>
            <?php echo $nav_div; ?>
            Page <?php echo $page + 1; ?> of <?php echo ceil($users_count / $items_per_page); ?>
        </div>
