<script type = "text/javascript">
    $(document).ready(function() {
        // Select input value
        $('.numberInput').on('click', function() {
            if ($(this).val() == 0) {
                $(this).select();
            }
        });

        // Format input number separator with commas
        $('.numberInput').on('input', function() {
            // Remove non-numeric characters from input value
            let inputVal = $(this).val().replace(/\D/g, '');

            // Format input value with commas
            let formattedVal = inputVal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Set formatted value as input value
            $(this).val(formattedVal);
        });
    });

    function setLoadingButton(btnName, btnText) {
        $(btnName).attr('disabled', true);
        $(btnName).html(
            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> `+btnText
        );
    }

    function resetLoadingButton(btnName, btnText) {
        $(btnName).attr('disabled', false);
        $(btnName).html(btnText);
    }
</script>