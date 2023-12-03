<?php
function generate_before_filters_bar(string $destination) {
    echo "
<div class='mb-2'>
    <form action='$destination' method='get'>
        <div class='input-group'>";
}

function generate_filter_select(string $name, string $default_string, array $options, string $selected = null) {
    echo "<select class='form-control' name='$name'>
        <option value=''>$default_string</option>";

    foreach ($options as $option) {
        $value = $option[0];
        $text = $option[1];
        $selected_attr = $selected == $value ? 'selected' : '';
        echo "<option value='$value' $selected_attr>$text</option>";
    }

    echo "</select>";
}

function generate_after_filters_bar() {
    echo "
            <div class='input-group-append'>
                <button class='btn btn-primary' type='submit'>
                    <i class='fas fa-filter'></i>
                    Filtra
                </button>
            </div>
        </div>
    </form>
</div>";
}