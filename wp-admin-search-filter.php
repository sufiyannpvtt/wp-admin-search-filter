<?php
/*
Plugin Name: WP Admin Search Filter
Description: Admin-side search and pagination for contact messages using $wpdb.
Version: 1.0
Author: Mohammad Shadullah
*/

if (!defined('ABSPATH')) {
    exit;
}

/*--------------------------------------------------------------
# Admin Menu
--------------------------------------------------------------*/
add_action('admin_menu', 'wpasf_admin_menu');

function wpasf_admin_menu() {
    add_menu_page(
        'Admin Search',
        'Admin Search',
        'manage_options',
        'admin-search',
        'wpasf_admin_page',
        'dashicons-search'
    );
}

/*--------------------------------------------------------------
# Admin Page with Search + Pagination
--------------------------------------------------------------*/
function wpasf_admin_page() {

    if (!current_user_can('manage_options')) {
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'wpsc_messages';

    // Search
    $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

    // Pagination
    $per_page = 5;
    $page_num = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset   = ($page_num - 1) * $per_page;

    // WHERE clause
    $where = '';
    $params = [];

    if ($search) {
        $where = "WHERE name LIKE %s OR email LIKE %s OR message LIKE %s";
        $like = '%' . $wpdb->esc_like($search) . '%';
        $params = [$like, $like, $like];
    }

    // Total count
    if ($params) {
        $total = $wpdb->get_var(
            $wpdb->prepare("SELECT COUNT(*) FROM $table_name $where", ...$params)
        );
    } else {
        $total = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    }

    // Main query
    $query = "SELECT * FROM $table_name $where ORDER BY created_at DESC LIMIT %d OFFSET %d";
    $params[] = $per_page;
    $params[] = $offset;

    $messages = $wpdb->get_results(
        $wpdb->prepare($query, ...$params)
    );

    $total_pages = ceil($total / $per_page);
    ?>

    <div class="wrap">
        <h1>Admin Search & Pagination</h1>

        <!-- Search Form -->
        <form method="get" style="margin-bottom:15px;">
            <input type="hidden" name="page" value="admin-search">
            <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search messages">
            <input type="submit" class="button" value="Search">
        </form>

        <!-- Table -->
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($messages): foreach ($messages as $row): ?>
                <tr>
                    <td><?php echo esc_html($row->name); ?></td>
                    <td><?php echo esc_html($row->email); ?></td>
                    <td><?php echo esc_html($row->message); ?></td>
                    <td><?php echo esc_html($row->created_at); ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr>
                    <td colspan="4">No records found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php
        if ($total_pages > 1) {
            echo '<div class="tablenav"><div class="tablenav-pages">';
            echo paginate_links([
                'base'    => add_query_arg('paged', '%#%'),
                'format'  => '',
                'current' => $page_num,
                'total'   => $total_pages,
            ]);
            echo '</div></div>';
        }
        ?>
    </div>

    <?php
}
