<?php
/* Template Name: Will Testament Service  */

//Terminate if the Wills Helper plugin is not activated

add_action('wp_head', function () {
    $html = <<<'HTML'
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.tailwindcss.com"></script>
    HTML;

    echo $html;
});

get_header();

if (is_user_logged_in()) {
?>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <!-- Code here -->
    <div x-data="data">
        <div x-cloak>
            <div>
                <button class="bg-green-300 hover:bg-green-500 rounded border px-8" x-show="child < 5" @click="child < 5 ? child++ : null">Add Child</button>
                <button class="bg-red-300 hover:bg-red-500 rounded border px-8" x-show="child" @click="child > 0 ? child-- : null">Remove Child</button>
            </div>

            <template x-for="item in child">
                <div>

                    <div>
                        <label for="">Child Name</label>
                        <input type="text" name="" id="">
                    </div>

                    <div>
                        <label for="">Child Age</label>
                        <input type="text">
                    </div>
                </div>

            </template>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('data', () => ({
                child: 0,
            }))
        })
    </script>
<?php
} else {
    echo '<h2>This content is restricted</h2>';
}
get_footer();
?>