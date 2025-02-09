<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (isset($_GET['status']) && !empty($_GET['status'])): ?>
    <div class="sellpress_wrap sellpress_status_messages">
        <ul class="sellpress_status_messages_inner">
            <?php $statuses = explode(';', $_GET['status']);

            foreach ($statuses as $status): ?>
                <li class="sellpress_status_message"><?php echo sellpress_get_status_message($status); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
