/**
 * BikeSeat ERP - Frontend utilities
 */

document.addEventListener('DOMContentLoaded', function () {
    initToasts();
    initDataTables();
    initSweetAlertForms();
});

/**
 * Display Laravel flash messages as Toastr notifications.
 */
function initToasts() {
    if (typeof toastr === 'undefined') {
        return;
    }

    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 5000,
        extendedTimeOut: 2000,
    };

    const flash = window.erpFlash || {};

    if (flash.success) {
        toastr.success(flash.success);
    }

    if (flash.error) {
        toastr.error(flash.error);
    }

    if (flash.warning) {
        toastr.warning(flash.warning);
    }

    if (flash.info) {
        toastr.info(flash.info);
    }

    if (flash.status) {
        toastr.success(flash.status);
    }
}

/**
 * Initialize responsive DataTables on tables with .erp-datatable class.
 */
function initDataTables() {
    if (typeof $ === 'undefined' || !$.fn.DataTable) {
        return;
    }

    $('.erp-datatable').each(function () {
        if ($.fn.DataTable.isDataTable(this)) {
            return;
        }

        $(this).DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            language: {
                search: '_INPUT_',
                searchPlaceholder: 'Search records...',
            },
        });
    });
}

/**
 * Attach SweetAlert2 confirmation to elements with data-confirm attribute.
 */
function initSweetAlertForms() {
    if (typeof Swal === 'undefined') {
        return;
    }

    document.querySelectorAll('[data-confirm]').forEach(function (element) {
        element.addEventListener('click', function (event) {
            event.preventDefault();

            const message = element.getAttribute('data-confirm') || 'Are you sure?';
            const form = element.closest('form');

            Swal.fire({
                title: 'Confirm Action',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1e3a5f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, proceed',
                cancelButtonText: 'Cancel',
            }).then(function (result) {
                if (result.isConfirmed) {
                    if (form) {
                        form.submit();
                    } else if (element.href) {
                        window.location.href = element.href;
                    }
                }
            });
        });
    });
}

/**
 * Global helper to show SweetAlert toast-style notifications.
 */
window.erpNotify = function (type, message, title) {
    if (typeof Swal === 'undefined') {
        return;
    }

    Swal.fire({
        icon: type,
        title: title || (type === 'success' ? 'Success' : 'Notice'),
        text: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
    });
};
